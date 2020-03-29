<?php defined('BASEPATH') or exit('No direct script access allowed');

class Work_package_types_model extends CI_Model
{

    /**
     * @vars
     */
    private $_tbl;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // define primary table
        $this->_tbl = 'work_package_types';
    }

    /**
     * Get list of non-deleted data
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $filters
     * @param  string $sort
     * @param  string $dir
     * @return array|boolean
     */
    public function get_all($limit = 0, $offset = 0, $filters = array(), $sort = 'id', $dir = 'ASC')
    {
        $sql = "
            SELECT SQL_CALC_FOUND_ROWS *
            FROM {$this->_tbl}
            WHERE deleted = '0'
        ";

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $value = $this->db->escape('%' . $value . '%');
                $sql .= " AND {$key} LIKE {$value}";
            }
        }

        $sql .= " ORDER BY {$sort} {$dir}";

        if ($limit) {
            $sql .= " LIMIT {$offset}, {$limit}";
        }

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $results['results'] = $query->result_array();
        } else {
            $results['results'] = null;
        }

        $sql              = "SELECT FOUND_ROWS() AS total";
        $query            = $this->db->query($sql);
        $results['total'] = $query->row()->total;

        return $results;
    }

    /**
     * Get specific data
     *
     * @param  int $id
     * @return array|boolean
     */
    public function get($id = null)
    {
        if ($id) {
            $sql = "
                SELECT *
                FROM {$this->_tbl}
                WHERE id = " . $this->db->escape($id) . "
                    AND deleted = '0'
            ";

            $query = $this->db->query($sql);

            if ($query->num_rows()) {
                return $query->row_array();
            }
        }

        return false;
    }

    /**
     * Add a new data
     *
     * @param  array $data
     * @return mixed|boolean
     */
    public function add($data = array())
    {
        if ($data) {
            $this->db->insert($this->_tbl, $data);
            if ($id = $this->db->insert_id()) {
                return $id;
            }
        }

        return false;
    }

    /**
     * Update an existing data
     *
     * @param  array $data
     * @return boolean
     */
    public function update($data = array())
    {
        if ($data) {
            $id = $data['id'];
            unset($data['id']);

            $this->db->where('id', $id);
            $this->db->update($this->_tbl, $data);

            if ($this->db->affected_rows()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Soft delete an existing data
     *
     * @param  int $id
     * @return boolean
     */
    public function delete($id = null)
    {
        if ($id) {
            $this->db->where('id', $id);
            $this->db->delete($this->_tbl);

            if ($this->db->affected_rows()) {
                return true;
            }
        }

        return false;
    }
}
