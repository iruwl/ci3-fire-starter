<?php defined('BASEPATH') or exit('No direct script access allowed');

class Wbs_model extends MY_Model
{
    public $_table = 'work_packages';

    public $protected_attributes = array('id');
    public $soft_delete          = true;
    public $skip_validation      = true;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get list (Override)
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $filters
     * @param  string $sort
     * @param  string $dir
     * @return array|boolean
     */
    public function get_list($limit = 0, $offset = 0, $filters = array(), $sort = 'id', $dir = 'ASC')
    {
        // Get data
        if ($this->soft_delete && $this->_temporary_with_deleted !== true) {
            $this->_database->where($this->soft_delete_key, ($this->_temporary_only_deleted ? '1' : '0'));
        }
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $this->_database->like($key, $value);
            }
        }
        if ($limit) {
            $this->_database->limit($limit, $offset);
        }

        // parent child order
        $order_parent_child = "case when parent_id=0 then id else parent_id end {$dir}, parent_id!=0";
        $this->_database->order_by($order_parent_child, null, false);

        // normal order
        $this->_database->order_by($sort, $dir);

        $query   = $this->_database->get($this->_table);
        $results = $query->result_array();
        $count   = $query->num_rows();

        // Count total data
        if ($this->soft_delete && $this->_temporary_with_deleted !== true) {
            $this->_database->where($this->soft_delete_key, ($this->_temporary_only_deleted ? '1' : '0'));
        }
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $this->_database->like($key, $value);
            }
        }
        $total = $this->_database->from($this->_table)->count_all_results();

        return array(
            'results' => $results,
            'count'   => $count,
            'total'   => $total,
        );
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
