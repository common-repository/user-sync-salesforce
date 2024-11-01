<?php

/**
 * Class SusSalesforce
 */
class SusSalesforce
{
    /**
     * @var SforcePartnerClient
     */
    protected $sforceConnection;

    /**
     * SusSalesforce constructor.
     */
    public function __construct()
    {
        $options = get_option('sus_options');
        if(!empty($options['sf_username']) && !empty($options['sf_password'])) {
            $this->sforceConnection = $this->set_salesforce_connection($options);
        } else {
            //todo: admin bericht dat gegevens missen
        }


        add_action('wp_ajax_sus_create_salesforce_user', array($this, 'ajax_create_salesforce_user'), 10, 2);
    }

    /**
     * @param $email
     * @return mixed|SObject
     */
    function find_salesforce_user_by_email($email)
    {
        $query = "SELECT Id, Email from Contact where Email = '" . $email . "'";
        $response = $this->sforceConnection->query($query);
        // QueryResult object is only for PARTNER client
        $queryResult = new QueryResult($response);
        $records = $queryResult->current();

        return $records;
    }

    /**
     * @return SforcePartnerClient
     */
    function set_salesforce_connection($options)
    {
        try {
            $connection = new SforcePartnerClient();
            $connection->createConnection(SUS_PLUGIN_DIR . "partner.wsdl.xml");
            $connection->login($options['sf_username'], $options['sf_password']); //login credentials

            return $connection;
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * @param $record
     * @return SaveResult
     * @throws Exception
     */
    function salesforce_create_user($record)
    {
        $record = $this->wp_to_salesforce_mapping();

        $contact = new stdClass;
        $contact->type = 'Contact';
        $contact->fields = $record;

        try {
            return $this->sforceConnection->create(array($contact), 'Contact');
        } catch (Exception $e) {
            throw new Exception('Could not connect to Salesforce', 0, $e);
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function ajax_create_salesforce_user()
    {
        $user = get_user_by('id', $_POST['id']);

        $record = array(
            'FirstName' => $user->data->user_login,
            'LastName' => $user->data->user_nicename,
            'Email' => $user->data->user_email
        );

        $contact = new stdClass;
        $contact->type = 'Contact';
        $contact->fields = $record;

        try {
            $result = $this->sforceConnection->create(array($contact), 'Contact');
            add_user_meta($_POST['id'], 'salesforce_id', $result[0]->id);

            return $result[0]->id;
        } catch (Exception $e) {
            throw new Exception('Could not connect to Salesforce', 0, $e);
        }
    }

    /**
     * @param $record
     * @param $user
     * @return UpdateResult
     * @throws Exception
     */
    function salesforce_update_user($record, $user)
    {
        $contact = new stdClass;
        $contact->type = 'Contact';
        $contact->fields = $record;
        $contact->Id = $user->Id;

        try {
            return $this->sforceConnection->update(array($contact), 'Contact');
        } catch (Exception $e) {
            throw new Exception('Could not connect to Salesforce', 0, $e);
        }
    }

    /**
     * @param $ids
     * @return DeleteResult
     * @throws Exception
     */
    function salesforce_delete_user($ids)
    {
        try {
            return $this->sforceConnection->delete($ids, 'Contact');
        } catch (Exception $e) {
            throw new Exception('Could not connect to Salesforce', 0, $e);
        }
    }

    /**
     * @return array
     */
    function wp_to_salesforce_mapping()
    {
        $record = array(
            'FirstName' => $_POST['first_name'],
            'LastName' => $_POST['last_name'],
            'Email' => $_POST['email'],
            'MailingStreet' => $_POST['billing_address_1'],
            'MailingCity' => $_POST['billing_city'],
            'MailingPostalCode' => $_POST['billing_postcode'],
            'MailingCountry' => $_POST['billing_country'],
            'MailingState' => $_POST['billing_state'],
            'Phone' => $_POST['billing_phone'],
        );

        return $record;
    }
}

if (is_admin()) {
    $salesforce = new SusSalesforce();
}
