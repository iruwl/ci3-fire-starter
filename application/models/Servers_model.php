<?php defined('BASEPATH') or exit('No direct script access allowed');

class Servers_model extends MY_Model
{
    public $_table = 'servers';

    public $has_many = array(
        'server_applications' => array('primary_key' => 'server_id', 'model' => 'server_applications_model'),
    );

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

    /**
     * Check to see if a nama already exists
     *
     * @param  string $nama
     * @return boolean
     */
    public function nama_exists($nama)
    {
        $query = $this->_database->get_where($this->_table, array(
            'nama'    => $nama,
            'deleted' => '0',
        ));

        return $query->num_rows() > 0;
    }
}
