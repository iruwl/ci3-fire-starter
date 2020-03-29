<?php defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{

    /**
     * @vars
     */
    private $_db;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // define primary table
        $this->_db = 'users';
    }

    /**
     * Get list of non-deleted users
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $filters
     * @param  string $sort
     * @param  string $dir
     * @return array|boolean
     */
    public function get_all($limit = 0, $offset = 0, $filters = array(), $sort = 'name', $dir = 'ASC')
    {
        $sql = "
            SELECT SQL_CALC_FOUND_ROWS *
            FROM {$this->_db}
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
     * Get specific user
     *
     * @param  int $id
     * @return array|boolean
     */
    public function get_user($id = null)
    {
        if ($id) {
            $sql = "
                SELECT *
                FROM {$this->_db}
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
     * Add a new user
     *
     * @param  array $data
     * @return mixed|boolean
     */
    public function add_user($data = array())
    {
        if ($data) {
            // secure password
            $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
            $password = hash('sha512', $data['password'] . $salt);

            $sql = "
                INSERT INTO {$this->_db} (
                    username,
                    password,
                    salt,
                    name,
                    email,
                    language,
                    is_admin,
                    status,
                    deleted,
                    created_at,
                    updated_at
                ) VALUES (
                    " . $this->db->escape($data['username']) . ",
                    " . $this->db->escape($password) . ",
                    " . $this->db->escape($salt) . ",
                    " . $this->db->escape($data['name']) . ",
                    " . $this->db->escape($data['email']) . ",
                    " . $this->db->escape($this->config->item('language')) . ",
                    " . $this->db->escape($data['is_admin']) . ",
                    " . $this->db->escape($data['status']) . ",
                    '0',
                    '" . date('Y-m-d H:i:s') . "',
                    '" . date('Y-m-d H:i:s') . "'
                )
            ";

            $this->db->query($sql);

            if ($id = $this->db->insert_id()) {
                return $id;
            }
        }

        return false;
    }

    /**
     * User creates their own profile
     *
     * @param  array $data
     * @return mixed|boolean
     */
    public function create_profile($data = array())
    {
        if ($data) {
            // secure password and create validation code
            $salt            = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
            $password        = hash('sha512', $data['password'] . $salt);
            $validation_code = sha1(microtime(true) . mt_rand(10000, 90000));

            $sql = "
                INSERT INTO {$this->_db} (
                    username,
                    password,
                    salt,
                    name,
                    email,
                    language,
                    is_admin,
                    status,
                    deleted,
                    validation_code,
                    created_at,
                    updated_at
                ) VALUES (
                    " . $this->db->escape($data['username']) . ",
                    " . $this->db->escape($password) . ",
                    " . $this->db->escape($salt) . ",
                    " . $this->db->escape($data['name']) . ",
                    " . $this->db->escape($data['email']) . ",
                    " . $this->db->escape($data['language']) . ",
                    '0',
                    '0',
                    '0',
                    " . $this->db->escape($validation_code) . ",
                    '" . date('Y-m-d H:i:s') . "',
                    '" . date('Y-m-d H:i:s') . "'
                )
            ";

            $this->db->query($sql);

            if ($this->db->insert_id()) {
                return $validation_code;
            }
        }

        return false;
    }

    /**
     * Edit an existing user
     *
     * @param  array $data
     * @return boolean
     */
    public function edit_user($data = array())
    {
        if ($data) {
            $sql = "
                UPDATE {$this->_db}
                SET
                    username = " . $this->db->escape($data['username']) . ",
            ";

            if ($data['password'] != '') {
                // secure password
                $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
                $password = hash('sha512', $data['password'] . $salt);

                $sql .= "
                    password = " . $this->db->escape($password) . ",
                    salt = " . $this->db->escape($salt) . ",
                ";
            }

            $sql .= "
                    name = " . $this->db->escape($data['name']) . ",
                    email = " . $this->db->escape($data['email']) . ",
                    language = " . $this->db->escape($data['language']) . ",
                    is_admin = " . $this->db->escape($data['is_admin']) . ",
                    status = " . $this->db->escape($data['status']) . ",
                    updated_at = '" . date('Y-m-d H:i:s') . "'
                WHERE id = " . $this->db->escape($data['id']) . "
                    AND deleted = '0'
            ";

            $this->db->query($sql);

            if ($this->db->affected_rows()) {
                return true;
            }
        }

        return false;
    }

    /**
     * User edits their own profile
     *
     * @param  array $data
     * @param  int $user_id
     * @return boolean
     */
    public function edit_profile($data = array(), $user_id = null)
    {
        if ($data && $user_id) {
            $sql = "
                UPDATE {$this->_db}
                SET
                    username = " . $this->db->escape($data['username']) . ",
            ";

            if ($data['password'] != '') {
                // secure password
                $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
                $password = hash('sha512', $data['password'] . $salt);

                $sql .= "
                    password = " . $this->db->escape($password) . ",
                    salt = " . $this->db->escape($salt) . ",
                ";
            }

            $sql .= "
                    name = " . $this->db->escape($data['name']) . ",
                    email = " . $this->db->escape($data['email']) . ",
                    language = " . $this->db->escape($data['language']) . ",
                    updated_at = '" . date('Y-m-d H:i:s') . "'
                WHERE id = " . $this->db->escape($user_id) . "
                    AND deleted = '0'
            ";

            $this->db->query($sql);

            if ($this->db->affected_rows()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Soft delete an existing user
     *
     * @param  int $id
     * @return boolean
     */
    public function delete_user($id = null)
    {
        if ($id) {
            $sql = "
                UPDATE {$this->_db}
                SET
                    email = '',
                    is_admin = '0',
                    status = '0',
                    deleted = '1',
                    updated_at = '" . date('Y-m-d H:i:s') . "'
                WHERE id = " . $this->db->escape($id) . "
                    AND id > 1
            ";

            $this->db->query($sql);

            if ($this->db->affected_rows()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for valid login credentials
     *
     * @param  string $username
     * @param  string $password
     * @return array|boolean
     */
    public function login($username = null, $password = null)
    {
        if ($username && $password) {
            $sql = "
                SELECT
                    id,
                    username,
                    password,
                    salt,
                    name,
                    email,
                    language,
                    is_admin,
                    status,
                    created_at,
                    updated_at
                FROM {$this->_db}
                WHERE (username = " . $this->db->escape($username) . "
                        OR email = " . $this->db->escape($username) . ")
                    AND status = '1'
                    AND deleted = '0'
                LIMIT 1
            ";

            $query = $this->db->query($sql);

            if ($query->num_rows()) {
                $results         = $query->row_array();
                $salted_password = hash('sha512', $password . $results['salt']);

                if ($results['password'] == $salted_password) {
                    unset($results['password']);
                    unset($results['salt']);

                    return $results;
                }
            }
        }

        return false;
    }

    /**
     * Handle user login attempts
     *
     * @return boolean
     */
    public function login_attempts()
    {
        // delete older attempts
        $older_time = date('Y-m-d H:i:s', strtotime('-' . $this->config->item('login_max_time') . ' seconds'));

        $sql = "
            DELETE FROM login_attempts
            WHERE attempt < '{$older_time}'
        ";

        $query = $this->db->query($sql);

        // insert the new attempt
        $sql = "
            INSERT INTO login_attempts (
                ip,
                attempt
            ) VALUES (
                " . $this->db->escape($_SERVER['REMOTE_ADDR']) . ",
                '" . date("Y-m-d H:i:s") . "'
            )
        ";

        $query = $this->db->query($sql);

        // get count of attempts from this IP
        $sql = "
            SELECT
                COUNT(*) AS attempts
            FROM login_attempts
            WHERE ip = " . $this->db->escape($_SERVER['REMOTE_ADDR'])
        ;

        $query = $this->db->query($sql);

        if ($query->num_rows()) {
            $results        = $query->row_array();
            $login_attempts = $results['attempts'];
            if ($login_attempts > $this->config->item('login_max_attempts')) {
                // too many attempts
                return false;
            }
        }

        return true;
    }

    /**
     * Validate a user-created account
     *
     * @param  string $encrypted_email
     * @param  string $validation_code
     * @return boolean
     */
    public function validate_account($encrypted_email = null, $validation_code = null)
    {
        if ($encrypted_email && $validation_code) {
            $sql = "
                SELECT id
                FROM {$this->_db}
                WHERE SHA1(email) = " . $this->db->escape($encrypted_email) . "
                    AND validation_code = " . $this->db->escape($validation_code) . "
                    AND status = '0'
                    AND deleted = '0'
                LIMIT 1
            ";

            $query = $this->db->query($sql);

            if ($query->num_rows()) {
                $results = $query->row_array();

                $sql = "
                    UPDATE {$this->_db}
                    SET status = '1',
                        validation_code = NULL
                    WHERE id = '" . $results['id'] . "'
                ";

                $this->db->query($sql);

                if ($this->db->affected_rows()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Reset password
     *
     * @param  array $data
     * @return mixed|boolean
     */
    public function reset_password($data = array())
    {
        if ($data) {
            $sql = "
                SELECT
                    id,
                    name
                FROM {$this->_db}
                WHERE email = " . $this->db->escape($data['email']) . "
                    AND status = '1'
                    AND deleted = '0'
                LIMIT 1
            ";

            $query = $this->db->query($sql);

            if ($query->num_rows()) {
                // get user info
                $user = $query->row_array();

                // create new random password
                $user_data['new_password'] = generate_random_password();
                $user_data['name']         = $user['name'];

                // create new salt and stored password
                $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
                $password = hash('sha512', $user_data['new_password'] . $salt);

                $sql = "
                    UPDATE {$this->_db} SET
                        password = " . $this->db->escape($password) . ",
                        salt = " . $this->db->escape($salt) . "
                    WHERE id = " . $this->db->escape($user['id']) . "
                ";

                $this->db->query($sql);

                if ($this->db->affected_rows()) {
                    return $user_data;
                }
            }
        }

        return false;
    }

    /**
     * Check to see if a username already exists
     *
     * @param  string $username
     * @return boolean
     */
    public function username_exists($username)
    {
        $sql = "
            SELECT id
            FROM {$this->_db}
            WHERE username = " . $this->db->escape($username) . "
            LIMIT 1
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows()) {
            return true;
        }

        return false;
    }

    /**
     * Check to see if an email already exists
     *
     * @param  string $email
     * @return boolean
     */
    public function email_exists($email)
    {
        $sql = "
            SELECT id
            FROM {$this->_db}
            WHERE email = " . $this->db->escape($email) . "
            LIMIT 1
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows()) {
            return true;
        }

        return false;
    }

}
