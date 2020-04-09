<?php defined('BASEPATH') or exit('No direct script access allowed');

class Server_applications_model extends MY_Model
{
    public $_table = 'server_applications';

    // public $belongs_to = array(
    //     'servers'      => array('primary_key' => 'server_id', 'model' => 'servers_model'),
    //     'applications' => array('primary_key' => 'application_id', 'model' => 'applications_model'),
    // );

    public $protected_attributes = array('id');
    public $soft_delete          = true;
    public $skip_validation      = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function do_save($data = array(), $user_id)
    {
        $id = $data['id'];
        if ($id) {
            $data['updated_by'] = $user_id;
            return $this->update($id, $this->updated_at($data));
        }
        $data['created_by'] = $user_id;
        return $this->insert($this->created_at($data));
    }

    public function do_delete($data = array(), $user_id)
    {
        $id                 = $data['id'];
        $data['deleted_by'] = $user_id;
        $this->update($id, $this->deleted_at($data));
        return $this->delete($id);
    }
}
