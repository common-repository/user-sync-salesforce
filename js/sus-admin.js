(function ($) {

    $(document).ready(function () {
        var userTable = $('#susUserTable');
        var sus_save_options = $('#sus_save_options');

        var table = userTable.DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": ajaxurl,
                "data": {
                    'action': 'sus_get_users'
                }
            },
            "columns": [
                {data: "user_login"},
                {data: "display_name"},
                {data: "user_email"},
                {data: 'salesforce_id'},
                {
                    data: null,
                    className: "center",
                    defaultContent: '<a href="" class="editor_edit">Push to Salesforce</a>'
                }
            ]
        });

        // Edit record
        userTable.on('click', 'a.editor_edit', function (e) {
            e.preventDefault();

            var data = {
                'action': 'sus_create_salesforce_user',
                'id': $(this).closest('tr').attr('id')
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post(ajaxurl, data, function (response) {
                table.draw();
            });
        });

        sus_save_options.on('click', function(e) {
            e.preventDefault();
            var datastring = $("#sus-admin-settings").serialize();

            var data = {
                'action': 'sus_save_options',
                'data': datastring
            };

            $.post(ajaxurl, data, function(response) {
                alert('Settings saved successfully.')
            });
        });
    });

})(jQuery);