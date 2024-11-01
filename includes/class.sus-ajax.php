<?php

/**
 * Class SusAjax
 */
class SusAjax
{
    /**
     * SusAjax constructor.
     */
    public function __construct()
    {
        add_action('wp_ajax_sus_get_users', array($this, 'sus_get_users'));
        add_action('wp_ajax_sus_save_options', array($this, 'sus_save_options'));
    }

    /**
     *
     */
    function sus_get_users()
    {
        $args = array(
            'fields' => array(
                'ID',
                'user_login',
                'display_name',
                'user_email',
            )
        );

        $data = array();
        $users = get_users($args);
        foreach ($users as $user) {
            $salesforce_id = get_user_meta($user->ID, 'salesforce_id', true);
            $user->salesforce_id = $salesforce_id;
            $user->DT_RowId = $user->ID;
            unset($user->ID);
            $data['data'][] = $user;
        }

        echo json_encode($data, JSON_PRETTY_PRINT);

        wp_die();
    }

    function sus_save_options()
    {
        $params = array();
        parse_str($_POST['data'], $params);

        update_option('sus_options', $params); //todo: validatie

        echo print_r($params); //todo: remove on release

        wp_die();
    }
}

if (is_admin()) {
    $SusAjax = new SusAjax();
}