<?php defined('BASEPATH') or exit('No direct script access allowed');

class Servers extends Admin_Controller
{

    /**
     * @var string
     */
    private $_redirect_url;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // load the language files
        $this->lang->load(array(
            'global',
            'servers',
        ));

        // load models
        $this->load->model(array(
            'servers_model',
            'clients_model',
            'server_applications_model' => 'sa_model',
            'applications_model',
        ));

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/master/servers'));
        define('DEFAULT_LIMIT', $this->settings->per_page_limit);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "nama");
        define('DEFAULT_DIR', "asc");

        // use the url in session (if available) to return to the previous filter/sorted/paginated list
        if ($this->session->userdata(REFERRER)) {
            $this->_redirect_url = $this->session->userdata(REFERRER);
        } else {
            $this->_redirect_url = THIS_URL;
        }

        // add js
        $this->add_js_theme("servers_i18n.js");
    }

    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/

    /**
     * List page
     */
    public function index()
    {
        // get parameters
        $limit  = $this->input->get('limit') ? $this->input->get('limit', true) : DEFAULT_LIMIT;
        $offset = $this->input->get('offset') ? $this->input->get('offset', true) : DEFAULT_OFFSET;
        $sort   = $this->input->get('sort') ? $this->input->get('sort', true) : DEFAULT_SORT;
        $dir    = $this->input->get('dir') ? $this->input->get('dir', true) : DEFAULT_DIR;

        // get filters
        $filters = array();
        foreach ($this->servers_model->get_fields() as $field) {
            if ($this->input->get($field)) {
                $filters[$field] = $this->input->get($field, true);
            }
        };

        // build filter string
        $filter = empty($filters) ? '' : '&' . http_build_query($filters);

        // save the current url to session for returning
        $this->session->set_userdata(REFERRER, THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");

        // are filters being submitted?
        if ($this->input->post()) {
            if ($this->input->post('clear')) {
                // reset button clicked
                redirect(THIS_URL);
            } else {
                // apply the filter(s)
                $filter = "";
                foreach ($this->servers_model->get_fields() as $field) {
                    if ($this->input->post($field)) {
                        $filter .= "&{$field}=" . $this->input->post($field, true);
                    }
                };

                // redirect using new filter(s)
                redirect(THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");
            }
        }

        // get list
        $dt_rows = $this->servers_model->get_list($limit, $offset, $filters, $sort, $dir);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $dt_rows['total'],
            'per_page'   => $limit,
        ));

        // setup page header data
        $this->set_title(lang('servers title list'));

        $data = $this->includes;

        // set content data
        $content_data = array(
            'this_url'   => THIS_URL,
            'title'      => $this->includes['page_header'],
            'dt_rows'    => $dt_rows['results'],
            'dt_count'   => $dt_rows['count'],
            'dt_total'   => $dt_rows['total'],
            'filters'    => $filters,
            'filter'     => $filter,
            'pagination' => $this->pagination->create_links(),
            'limit'      => $limit,
            'offset'     => $offset,
            'sort'       => $sort,
            'dir'        => $dir,
        );

        // load views
        $data['content'] = $this->load->view('admin/master/servers/list', $content_data, true);
        $this->load->view($this->template, $data);
    }

    /**
     * Add new
     */
    public function add()
    {
        // validators
        $this->form_validators();

        if ($this->form_validation->run() == true) {
            $active_user = $this->session->userdata('logged_in');

            $post = $this->input->post();

            $post['ports']    = count($this->input->post('ports')) ? json_encode($this->input->post('ports')) : null;
            $post['networks'] = count($this->input->post('networks')) ? json_encode($this->input->post('networks')) : null;
            $post['users']    = count($this->input->post('users')) ? json_encode($this->input->post('users')) : null;

            // save
            $saved = $this->servers_model->do_save($post, $active_user['id']);

            if ($saved) {
                $this->session->set_flashdata('message', sprintf(lang('global msg add_success'), $this->input->post('nama')));
            } else {
                $this->session->set_flashdata('error', sprintf(lang('global error add_failed'), $this->input->post('nama')));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->set_title(lang('servers title add'));

        $data = $this->includes;

        $ports = $this->ref_ports();
        if (!empty($this->input->post())) {
            $ports = $this->input->post('ports');
        }

        $networks = $this->ref_networks();
        if (!empty($this->input->post())) {
            $networks = $this->input->post('networks');
        }

        $users = $this->ref_users();
        if (!empty($this->input->post())) {
            $users = $this->input->post('users');
        }

        // set content data
        $content_data = array(
            'this_url'           => THIS_URL,
            'title'              => $this->includes['page_header'],
            'cancel_url'         => $this->_redirect_url,
            'app_add_url'        => base_url('admin/master/servers/add_apps/'),
            'app_edit_url'       => base_url('admin/master/servers/edit_apps/'),
            'app_delete_url'     => base_url('admin/master/server_apps/delete/'),
            'redirect_param'     => '?redirect=' . urlencode(current_url()),
            'dt'                 => empty($this->input->post()) ? null : $this->input->post(),
            'dt_id'              => null,
            'dt_ports'           => $ports,
            'dt_networks'        => $networks,
            'dt_users'           => $users,
            'dt_owner'           => $this->ref_owner(),
            'dt_owned_by_client' => (array) 'N/A'+$this->clients_model->dropdown('id', 'nama'),
            'dt_applications'    => array(),
        );

        // load views
        $data['content'] = $this->load->view('admin/master/servers/form', $content_data, true);
        $this->load->view($this->template, $data);
    }

    /**
     * Edit/Update
     *
     * @param  int $id
     */
    public function edit($id = null)
    {
        // make sure we have a numeric id
        if (is_null($id) or !is_numeric($id)) {
            redirect($this->_redirect_url);
        }

        // get the data
        $dt = $this->servers_model->with('server_applications')->as_array()->get($id);

        // if empty results, return to list
        if (!$dt) {
            redirect($this->_redirect_url);
        }

        $applications = array();
        if (count($dt['server_applications'])) {
            foreach ($dt['server_applications'] as $index => $row) {
                foreach ($row as $key => $value) {
                    $applications[$index][$key] = $value;
                }

                $app_det = $this->applications_model->get($row->application_id);

                $applications[$index]['app_name']           = $app_det->nama;
                $applications[$index]['app_kategori']       = $app_det->kategori;
                $applications[$index]['app_jenis']          = $app_det->jenis;
                $applications[$index]['app_bahasa_program'] = $app_det->bahasa_program;
            }
        }

        $ports = $this->ref_ports();
        if (!empty($this->input->post())) {
            $ports = $this->input->post('ports');
        } else {
            if ($dt['ports']) {
                $ports = $this->ref_ports(json_decode($dt['ports'], true));
            }
        }

        $networks = $this->ref_networks();
        if (!empty($this->input->post())) {
            $networks = $this->input->post('networks');
        } else {
            if ($dt['networks']) {
                $networks = $this->ref_networks(json_decode($dt['networks'], true));
            }
        }

        $users = $this->ref_users();
        if (!empty($this->input->post())) {
            $users = $this->input->post('users');
        } else {
            if ($dt['users']) {
                $users = $this->ref_users(json_decode($dt['users'], true));
            }
        }

        // validators
        $this->form_validators($dt, false);

        if ($this->form_validation->run() == true) {
            $active_user = $this->session->userdata('logged_in');

            $post = $this->input->post();

            $post['ports']    = count($this->input->post('ports')) ? json_encode($this->input->post('ports')) : null;
            $post['networks'] = count($this->input->post('networks')) ? json_encode($this->input->post('networks')) : null;
            $post['users']    = count($this->input->post('users')) ? json_encode($this->input->post('users')) : null;

            // save the changes
            $saved = $this->servers_model->do_save($post, $active_user['id']);

            if ($saved) {
                $this->session->set_flashdata('message', sprintf(lang('global msg edit_success'), $this->input->post('nama')));
            } else {
                $this->session->set_flashdata('error', sprintf(lang('global error edit_failed'), $this->input->post('nama')));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->set_title(lang('servers title edit'));

        $data = $this->includes;

        // set content data
        $content_data = array(
            'this_url'           => THIS_URL,
            'title'              => $this->includes['page_header'],
            'cancel_url'         => $this->_redirect_url,
            'app_add_url'        => base_url('admin/master/servers/add_apps/' . $id),
            'app_edit_url'       => base_url('admin/master/servers/edit_apps/'),
            'app_delete_url'     => base_url('admin/master/server_apps/delete/'),
            'redirect_param'     => '?redirect=' . urlencode(current_url()),
            'dt'                 => empty($this->input->post()) ? $dt : $this->input->post(),
            'dt_id'              => $id,
            'dt_ports'           => $ports,
            'dt_networks'        => $networks,
            'dt_users'           => $users,
            'dt_owner'           => $this->ref_owner(),
            'dt_owned_by_client' => (array) 'N/A'+$this->clients_model->dropdown('id', 'nama'),
            'dt_applications'    => $applications,
        );

        // load views
        $data['content'] = $this->load->view('admin/master/servers/form', $content_data, true);
        $this->load->view($this->template, $data);
    }

    public function add_apps($server_id)
    {
        $this->session->set_userdata(array(
            REFERRER => $this->input->get('redirect'),
        ));
        redirect(base_url('admin/master/server_apps/add/' . $server_id));
    }

    public function edit_apps($server_apps_id)
    {
        $this->session->set_userdata(array(
            REFERRER => $this->input->get('redirect'),
        ));
        redirect(base_url('admin/master/server_apps/edit/' . $server_apps_id));
    }

    /**
     * Delete
     */
    public function delete()
    {
        if ($_POST && isset($_POST['id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $id   = $_POST['id'];
            $resp = array();

            if (!is_null($id) or !is_numeric($id)) {
                $active_user = $this->session->userdata('logged_in');

                $dt = $this->servers_model->as_array()->get($id);
                if ($dt) {
                    $deleted = $this->servers_model->do_delete($dt, $active_user['id']);
                    if ($deleted) {
                        $this->session->set_flashdata('message', sprintf(lang('global msg delete_success'), $dt['nama']));
                        $this->session->keep_flashdata('message');
                    } else {
                        $this->session->set_flashdata('error', sprintf(lang('global msg delete_fail'), $dt['nama']));
                    }
                } else {
                    $this->session->set_flashdata('error', lang('global error no_results'));
                }
            } else {
                $this->session->set_flashdata('error', lang('global error id_required'));
            }

            $this->session->keep_flashdata('error');

        } else {
            show_404();
        }
    }

    /**
     * Export list to CSV
     */
    public function export()
    {
        // get parameters
        $sort = $this->input->get('sort') ? $this->input->get('sort', true) : DEFAULT_SORT;
        $dir  = $this->input->get('dir') ? $this->input->get('dir', true) : DEFAULT_DIR;

        // get filters
        $filters = array();
        foreach ($this->servers_model->get_fields() as $field) {
            if ($this->input->get($field)) {
                $filters[$field] = $this->input->get($field, true);
            }
        };

        // get list
        $dt_rows = $this->servers_model->get_list(0, 0, $filters, $sort, $dir);

        if ($dt_rows['total'] > 0) {
            // manipulate the output array
            foreach ($dt_rows['results'] as $key => $row) {
                //
            }

            // export the file
            array_to_csv($dt_rows['results'], lang('servers list title'));
        } else {
            // nothing to export
            $this->session->set_flashdata('error', lang('global error no_results'));
            redirect($this->_redirect_url);
        }

        exit;
    }

    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/

    private function form_validators($dt = array(), $new_data = true)
    {
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        if ($new_data) {
            $this->form_validation->set_rules('nama', lang('servers input nama'), 'required|trim|min_length[3]|max_length[64]|callback__check_nama[]');
        } else {
            $this->form_validation->set_rules('nama', lang('servers input nama'), 'required|trim|min_length[3]|max_length[64]|callback__check_nama[' . $dt['nama'] . ']');
        }
        $this->form_validation->set_rules('os', lang('servers input os'), 'required|trim|max_length[20]');
        $this->form_validation->set_rules('processor', lang('servers input processor'), 'required|trim|max_length[32]');
        $this->form_validation->set_rules('memory', lang('servers input memory'), 'required|trim|max_length[32]');
        $this->form_validation->set_rules('storage', lang('servers input storage'), 'required|trim|max_length[32]');
        $this->form_validation->set_rules('owner', lang('servers input owner'), 'required|trim');
        // $this->form_validation->set_rules('ports', lang('servers input ports'), 'required|trim');
        // $this->form_validation->set_rules('networks', lang('servers input networks'), 'required|trim');
        // $this->form_validation->set_rules('users', lang('servers input users'), 'required|trim');
        $this->form_validation->set_rules('owned_by_client', lang('servers input owned_by_client'), 'numeric');
        $this->form_validation->set_rules('owned_by_other', lang('servers input owned_by_other'), 'trim|max_length[32]');
        $this->form_validation->set_rules('location', lang('servers input location'), 'trim');
        // $this->form_validation->set_rules('latitude', lang('servers input latitude'), 'required|trim');
        // $this->form_validation->set_rules('longitude', lang('servers input longitude'), 'required|trim');
        $this->form_validation->set_rules('keterangan', lang('servers input keterangan'), 'trim');
    }

    /**
     * Enum pada field servers.owner
     */
    private function ref_owner()
    {
        return array(
            'Milik Sendiri'       => 'Milik Sendiri',
            'Milik Klien'         => 'Milik Klien',
            'Milik Pihak Lainnya' => 'Milik Pihak Lainnya',
        );
    }

    private function ref_ports($data = array())
    {
        $key_values = array();
        $key_fields = array('port', 'keterangan');
        if (is_array($data) and count($data)) {
            foreach ($data as $index => $row) {
                foreach ($key_fields as $key) {
                    $key_values[$index][$key] = isset($row[$key]) ? $row[$key] : null;
                }
            }
        } else {
            foreach ($key_fields as $key) {
                $key_values[0][$key] = null;
            }
        }
        return $key_values;
    }

    private function ref_networks($data = array())
    {
        $key_values = array();
        $key_fields = array('interface', 'ip', 'keterangan');
        if (is_array($data) and count($data)) {
            foreach ($data as $index => $row) {
                foreach ($key_fields as $key) {
                    $key_values[$index][$key] = isset($row[$key]) ? $row[$key] : null;
                }
            }
        } else {
            foreach ($key_fields as $key) {
                $key_values[0][$key] = null;
            }
        }
        return $key_values;
    }

    private function ref_users($data = array())
    {
        $key_values = array();
        $key_fields = array('user', 'password');
        if (is_array($data) and count($data)) {
            foreach ($data as $index => $row) {
                foreach ($key_fields as $key) {
                    $key_values[$index][$key] = isset($row[$key]) ? $row[$key] : null;
                }
            }
        } else {
            foreach ($key_fields as $key) {
                $key_values[0][$key] = null;
            }
        }
        return $key_values;
    }

    /**
     * Make sure nama is available
     *
     * @param  string $nama
     * @param  string|null $current
     * @return int|boolean
     */
    public function _check_nama($nama, $current)
    {
        if (trim($nama) != trim($current) && $this->servers_model->nama_exists($nama)) {
            $this->form_validation->set_message('_check_nama', sprintf(lang('servers error nama_exists'), $nama));
            return false;
        } else {
            return $nama;
        }
    }

    /**
     * not used
     */
    public function _check_ip($data)
    {
        if (is_array($data) and count($data)) {
            foreach ($data as $row) {
                if (isset($row['ip']) && filter_var($row['ip'], FILTER_VALIDATE_IP) == false) {
                    $this->form_validation->set_message('title_validate', 'The Title field is Required.');
                    return false;
                }
            }
        }
        return true;
    }
}
