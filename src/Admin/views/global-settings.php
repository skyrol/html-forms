<?php defined( 'ABSPATH' ) or exit; ?>

<div class="wrap hf">

    <p class="breadcrumbs">
        <span class="prefix"><?php echo __( 'You are here: ', 'html-forms' ); ?></span>
        <a href="<?php echo admin_url( 'admin.php?page=html-forms' ); ?>">HTML Forms</a> &rsaquo;
        <span class="current-crumb"><strong><?php _e( 'Settings', 'html-forms' ); ?></strong></span>
    </p>

    <h1 class="page-title"><?php _e( 'Settings', 'html-forms' ); ?></h1>

    <form method="post" action="<?php echo admin_url( 'options.php' ); ?>">
        <?php settings_fields( 'hf_settings' ); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e( 'Load stylesheet?', 'html-forms' ); ?></th>
                    <td>
                        <label><input type="radio" name="hf_settings[load_stylesheet]" value="1" <?php checked( $settings['load_stylesheet'], 1 ); ?>> <?php _e( 'Yes' ); ?></label> &nbsp;
                        <label><input type="radio"  name="hf_settings[load_stylesheet]" value="0"  <?php checked( $settings['load_stylesheet'], 0 ); ?>> <?php _e( 'No' ); ?></label>

                        <p class="help"><?php _e( 'Select "yes" to apply some basic form styles to all HTML Forms.', 'html-forms' ); ?></p>
                    </td>
                </tr>
            </table>
        <?php submit_button(); ?>
    </form>

    <?php require __DIR__ . '/admin-footer.php'; ?>
</div>
