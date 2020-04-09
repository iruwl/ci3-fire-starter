<?php defined('BASEPATH') or exit('No direct script access allowed');

class Client_contacts_model extends MY_Model
{
    public $_table = 'client_contacts';

    public $belongs_to = array(
        'clients'  => array('primary_key' => 'client_id', 'model' => 'clients_model'),
        'contacts' => array('primary_key' => 'contact_id', 'model' => 'contacts_model'),
    );

    public $protected_attributes = array('id');
    public $skip_validation      = true;

    public function __construct()
    {
        parent::__construct();
    }
}
