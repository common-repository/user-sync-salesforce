<div class="wrap">
    <h1><?php _e("Salesforce User Sync", 'salesforce-user-sync'); ?></h1>

    <form method="post" action="options.php" class="sus-admin">

        <h2><?php _e('Users') ?></h2>

        <table id="susUserTable" class="wp-list-table widefat fixed striped users" cellspacing="0" width="100%" style="text-align: left" >
            <thead>
            <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Salesforce ID</th>
                <th>Options</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Salesforce ID</th>
                <th>Options</th>
            </tr>
            </tfoot>
            <tbody>

            </tbody>
        </table>

    </form>
</div>