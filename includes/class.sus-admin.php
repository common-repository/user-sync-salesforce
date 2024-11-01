<?php

/**
 * Class SusAdmin
 */
class SusAdmin
{
    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_enqueue_scripts', array($this, 'sus_enqueue'));
        add_action('admin_head', array($this, 'sus_stylesheet'));
    }

    /**
     * Load stylesheets
     */
    function sus_stylesheet()
    {
        // Only load style and scripts when needed
        $screen = get_current_screen();
        $pages = array(
            'sf-user-sync_page_settings',
            'toplevel_page_salesforce-user-sync'
        );
        if(in_array($screen->id, $pages)) {
            wp_enqueue_style('sus_datatables_css', '//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
            wp_enqueue_style('sus_datatables_css');

            wp_enqueue_script('sus_datatables', '//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'), false, true);
            wp_enqueue_style('sus_datatables');

            wp_enqueue_script('sus_admin', plugins_url('../js/sus-admin.js', __FILE__), array('sus_datatables'), false, true);
            wp_enqueue_style('sus_admin');
        }
    }

    /**
     * @param $hook
     */
    function sus_enqueue($hook) {
        if( 'index.php' != $hook ) {
            // Only applies to dashboard panel
            return;
        }

        wp_enqueue_script( 'ajax-script', plugins_url( '/js/sus-admin.js', __FILE__ ), array('jquery') );

        // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
        wp_localize_script( 'ajax-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_menu_page(
            'Salesforce User Sync',
            'SF User Sync',
            'manage_options',
            'salesforce-user-sync',
            array($this, 'create_admin_users_page')
        );

        add_submenu_page(
            'salesforce-user-sync',
            'Users',
            'Users',
            'manage_options',
            'salesforce-user-sync'
        );

        add_submenu_page(
            'salesforce-user-sync',
            'Settings',
            'Settings',
            'manage_options',
            'settings',
            array($this, 'create_admin_settings_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_users_page()
    {
        ob_start();
        require(plugin_dir_path(__FILE__) . '../templates/sus-admin-users.php');
        ob_end_flush();
    }

    /**
     * Options page callback
     */
    public function create_admin_settings_page()
    {
        $options = get_option('sus_options');
        ob_start();
        require(plugin_dir_path(__FILE__) . '../templates/sus-admin-settings.php');
        ob_end_flush();
    }

}

if (is_admin()) {
    $SusAdmin = new SusAdmin();
}

