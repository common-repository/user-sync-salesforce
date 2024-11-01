<div class="wrap">
    <h1><?php _e("Salesforce User Sync Settings", 'salesforce-user-sync'); ?></h1>

    <form method="post" action="options.php" class="sus-admin" id="sus-admin-settings">

        <h2><?php _e('Salesforce credentials') ?></h2>

        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><?php _e('Username') ?></th>
                <td>
                    <input type="input" name="sf_username"
                           id="sf_username"
                           value="<?php echo $options['sf_username']; ?>">
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Password') ?></th>
                <td>
                    <input type="password" name="sf_password"
                           id="sf_password"
                           value="<?php echo $options['sf_password']; ?>">
                </td>
            </tr>
            </tbody>
        </table>

        <input type="button" name="button" id="sus_save_options" class="button button-primary"
               value="<?php _e('Save') ?>">

    </form>
</div>