<?php

use HTML_Forms\Form;

/**
 * @param $form_id_or_slug int|string
 * @return Form
 * @throws Exception
 */
function hf_get_form( $form_id_or_slug ) {

    if( is_numeric( $form_id_or_slug ) ) {
        $post = get_post( $form_id_or_slug );

        if( ! $post || $post->post_type !== 'html-form' ) {
            throw new Exception( "Invalid form ID" );
        }
    } else {
        $posts = get_posts(
            array(
                'post_type' => 'html-form',
                'post_name' => $form_id_or_slug,
                'numberposts' => 1,
            )
        );

        if( empty( $posts ) ) {
            throw new Exception( 'Invalid form slug' );
        }
        $post = $posts[0];
    }

    static $default_messages;
    if( $default_messages === null ) {
        $default_messages = array(
            'success' => __('Thank you! We will be in touch soon.', 'html-forms'),
            'invalid_email' => __( 'Sorry, that email address looks invalid.', 'html-forms' ),
            'required_field_missing' => __( "Please fill in the required fields.", "html-forms" ),
            'error' => __( 'Oops. An error occurred.', 'html-forms' ),
        );
    }

    static $default_settings = array(
        'hide_after_success' => 0,
        'redirect_url' => '',
        'required_fields' =>'',
        'email_fields' => '',
    );

    $post_meta = get_post_meta( $post->ID );

    $settings = array();
    if( ! empty( $post_meta['_hf_settings'][0] ) ) {
        $settings = (array) maybe_unserialize( $post_meta['_hf_settings'][0] );
    }

    $messages = array();
    foreach( $post_meta as $meta_key => $meta_values ) {
        if( strpos( $meta_key, 'hf_message_' ) === 0 ) {
            $message_key = substr( $meta_key, strlen( 'hf_message_' ) );
            $messages[$message_key] = (string) $meta_values[0];
        }
    }

    $form = new Form( $post->ID );
    $form->title = $post->post_title;
    $form->slug = $post->post_name;
    $form->markup = $post->post_content;
    $form->settings = array_merge( $default_settings, $settings );
    $form->messages = array_merge( $default_messages, $messages );
    return $form;
}

function hf_array_get( $array, $key, $default = null ) {
    if ( is_null( $key ) ) {
        return $array;
    }

    if ( isset( $array[$key] ) ) {
        return $array[$key];
    }

    foreach (explode( '.', $key ) as $segment) {
        if ( ! is_array( $array ) || ! array_key_exists( $segment, $array ) ) {
            return $default;
        }

        $array = $array[$segment];
    }

    return $array;
}

/**
 * @param string $template
 * @param array $data
 *
 * @return string
 */
function hf_template( $template, array $data = array() ) {
    $template = preg_replace_callback( '/\[([ -~]+)\]/', function( $matches ) use ( $data ) {
        $key = $matches[1];
        $replacement = hf_array_get( $data, $key, '' );
        return $replacement;
    }, $template );
    return $template;
}