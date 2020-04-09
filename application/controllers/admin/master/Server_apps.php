<?php defined('BASEPATH') or exit('No direct script access allowed');

class Server_apps extends Admin_Controller
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
            'server_apps',
        ));

        // load models
        $this->load->model(array(
            'server_applications_model' => 'sa_model',
            'applications_model',
            'servers_model',
        ));

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/master/server_apps'));
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
        $this->add_js_theme("server_apps_i18n.js");
    }

    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/

    /**
     * List page
     */
    public function Xindex()
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
        $this->set_title(lang('server_apps title list'));

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
        $data['content'] = $this->load->view('admin/master/server_apps/list', $content_data, true);
        $this->load->view($this->template, $data);
    }

    /**
     * Add new
     */
    public function add($server_id = null)
    {
        // make sure we have a numeric server_id
        if (is_null($server_id) or !is_numeric($server_id)) {
            $this->redirect();
        }

        // validators
        $this->form_validators();

        if ($this->form_validation->run() == true) {
            $active_user = $this->session->userdata('logged_in');

            $post = $this->input->post();

            $post['server_id']  = $server_id;
            $post['db_profile'] = count($this->input->post('db_profile')) ? json_encode($this->input->post('db_profile')) : null;

            // save
            $saved = $this->sa_model->do_save($post, $active_user['id']);

            if ($saved) {
                $this->session->set_flashdata('message', sprintf(lang('global msg add_success'), $this->applications_model->get($post['application_id'])->nama));
            } else {
                $this->session->set_flashdata('error', sprintf(lang('global error add_failed'), $this->applications_model->get($post['application_id'])->nama));
            }

            // return to list and display message
            $this->redirect();
        }

        // setup page header data
        $this->set_title(lang('server_apps title add'));

        $data = $this->includes;

        $db_profile = $this->ref_db_profile();
        if (!empty($this->input->post())) {
            $db_profile = $this->input->post('db_profile');
        }

        // set content data
        $content_data = array(
            'this_url'               => THIS_URL,
            'title'                  => $this->includes['page_header'],
            'cancel_url'             => base_url('admin/master/server_apps/redirect'),
            'dt'                     => empty($this->input->post()) ? null : $this->input->post(),
            'dt_id'                  => null,
            'dt_server_id'           => empty($server_id) ? null : $server_id,
            'dt_db_profile'          => $db_profile,
            'dt_servers'             => array('' => 'None') + $this->servers_model->dropdown('id', 'nama'),
            'dt_applications'        => array('' => 'None') + $this->applications_model->dropdown('id', 'nama'),
            'dt_kategori'            => array('' => 'None') + $this->ref_kategori(),
            'dt_status'              => array('' => 'None') + $this->ref_status(),
            'dt_status_pemeliharaan' => array('' => 'None') + $this->ref_status_pemeliharaan(),
        );

        // load views
        $data['content'] = $this->load->view('admin/master/server_apps/form', $content_data, true);
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
            $this->redirect();
        }

        // get the data
        $dt = $this->sa_model->as_array()->get($id);

        // if empty results, return to list
        if (!$dt) {
            $this->redirect();
        }

        $db_profile = $this->ref_db_profile();
        if (!empty($this->input->post())) {
            $db_profile = $this->input->post('db_profile');
        } else {
            if ($dt['db_profile']) {
                $db_profile = $this->ref_db_profile(json_decode($dt['db_profile'], true));
            }
        }

        // validators
        $this->form_validators($dt, false);

        if ($this->form_validation->run() == true) {
            $active_user = $this->session->userdata('logged_in');

            $post = $this->input->post();

            $post['db_profile'] = count($this->input->post('db_profile')) ? json_encode($this->input->post('db_profile')) : null;

            // save the changes
            $saved = $this->sa_model->do_save($post, $active_user['id']);

            if ($saved) {
                $this->session->set_flashdata('message', sprintf(lang('global msg edit_success'), $this->applications_model->get($post['application_id'])->nama));
            } else {
                $this->session->set_flashdata('error', sprintf(lang('global error edit_failed'), $this->applications_model->get($post['application_id'])->nama));
            }

            // return to list and display message
            $this->redirect();
        }

        // setup page header data
        $this->set_title(lang('server_apps title edit'));

        $data = $this->includes;

        // set content data
        $content_data = array(
            'this_url'               => THIS_URL,
            'title'                  => $this->includes['page_header'],
            'cancel_url'             => base_url('admin/master/server_apps/redirect'),
            'dt'                     => empty($this->input->post()) ? $dt : $this->input->post(),
            'dt_id'                  => $id,
            'dt_server_id'           => empty($dt['server_id']) ? null : $dt['server_id'],
            'dt_db_profile'          => $db_profile,
            'dt_servers'             => $this->servers_model->dropdown('id', 'nama'),
            'dt_applications'        => $this->applications_model->dropdown('id', 'nama'),
            'dt_kategori'            => $this->ref_kategori(),
            'dt_status'              => $this->ref_status(),
            'dt_status_pemeliharaan' => $this->ref_status_pemeliharaan(),
        );

        // load views
        $data['content'] = $this->load->view('admin/master/server_apps/form', $content_data, true);
        $this->load->view($this->template, $data);
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

                $dt = $this->sa_model->as_array()->get($id);
                if ($dt) {
                    $deleted = $this->sa_model->do_delete($dt, $active_user['id']);
                    if ($deleted) {
                        $this->session->set_flashdata('message', sprintf(lang('global msg delete_success'), $this->applications_model->get($dt['application_id'])->nama));
                        $this->session->keep_flashdata('message');
                    } else {
                        $this->session->set_flashdata('error', sprintf(lang('global msg delete_fail'), $this->applications_model->get($dt['application_id'])->nama));
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

    public function redirect()
    {
        $redirect_url = $this->_redirect_url;
        if (stripos($this->_redirect_url, THIS_URL) === false) {
            $this->session->unset_userdata(REFERRER);
        }
        redirect($redirect_url);
    }

    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/

    private function form_validators($dt = array(), $new_data = true)
    {
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('server_id', lang('server_apps input server_id'), 'required|numeric');
        $this->form_validation->set_rules('application_id', lang('server_apps input application_id'), 'required|numeric');
        $this->form_validation->set_rules('kategori', lang('server_apps input kategori'), 'required|trim');
        $this->form_validation->set_rules('git_url', lang('server_apps input git_url'), 'trim|max_length[255]');
        $this->form_validation->set_rules('api_url', lang('server_apps input api_url'), 'trim|max_length[255]');
        $this->form_validation->set_rules('app_url', lang('server_apps input app_url'), 'required|trim|max_length[255]');
        $this->form_validation->set_rules('app_path', lang('server_apps input app_path'), 'required|trim|max_length[255]');
        // $this->form_validation->set_rules('app_port', lang('server_apps input app_port'), 'required|trim|max_length[255]');
        $this->form_validation->set_rules('app_service', lang('server_apps input app_service'), 'trim|max_length[255]');
        // $this->form_validation->set_rules('db_profile', lang('server_apps input db_profile'), 'trim');
        $this->form_validation->set_rules('status', lang('server_apps input status'), 'required|trim');
        $this->form_validation->set_rules('status_pemeliharaan', lang('server_apps input status_pemeliharaan'), 'required|trim');
        $this->form_validation->set_rules('keterangan', lang('server_apps input keterangan'), 'trim|max_length[255]');
    }

    /**
     * Enum pada field server_apps.kategori
     */
    private function ref_kategori()
    {
        return array(
            'Development' => 'Development',
            'Production'  => 'Production',
            'Demo'        => 'Demo',
        );
    }

    /**
     * Enum pada field server_apps.status
     */
    private function ref_status()
    {
        return array(
            'Belum dipasang' => 'Belum dipasang',
            'Terpasang'      => 'Terpasang',
            'Dihapus'        => 'Dihapus',
            'Pindah server'  => 'Pindah server',
        );
    }

    /**
     * Enum pada field server_apps.status_pemeliharaan
     */
    private function ref_status_pemeliharaan()
    {
        return array(
            'Supported'   => 'Supported',
            'Unsupported' => 'Unsupported',
        );
    }

    private function ref_db_profile($data = array())
    {
        $key_values = array();
        $key_fields = array('connection_string', 'keterangan');
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
}
