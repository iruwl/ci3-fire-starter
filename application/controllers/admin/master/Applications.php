<?php defined('BASEPATH') or exit('No direct script access allowed');

class Applications extends Admin_Controller
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
            'applications',
        ));

        // load model
        $this->load->model(array(
            'applications_model',
        ));

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/master/applications'));
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
        $this->add_js_theme("applications_i18n.js");
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
        foreach ($this->applications_model->get_fields() as $field) {
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
                foreach ($this->applications_model->get_fields() as $field) {
                    if ($this->input->post($field)) {
                        $filter .= "&{$field}=" . $this->input->post($field, true);
                    }
                };

                // redirect using new filter(s)
                redirect(THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");
            }
        }

        // get list
        $dt_rows = $this->applications_model->get_list($limit, $offset, $filters, $sort, $dir);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $dt_rows['total'],
            'per_page'   => $limit,
        ));

        // setup page header data
        $this->set_title(lang('applications title list'));

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
        $data['content'] = $this->load->view('admin/master/applications/list', $content_data, true);
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
            $post        = $this->input->post();
            $active_user = $this->session->userdata('logged_in');

            $post['dokumen'] = count($this->input->post('dokumen')) ? json_encode($this->input->post('dokumen')) : null;

            // save
            $saved = $this->applications_model->do_save($post, $active_user['id']);

            if ($saved) {
                $this->session->set_flashdata('message', sprintf(lang('global msg add_success'), $this->input->post('nama')));
            } else {
                $this->session->set_flashdata('error', sprintf(lang('global error add_failed'), $this->input->post('nama')));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->set_title(lang('applications title add'));

        $data = $this->includes;

        $dokumen = $this->ref_dokumen();
        if (!empty($this->input->post())) {
            $dokumen = $this->input->post('dokumen');
        }

        // set content data
        $content_data = array(
            'this_url'          => THIS_URL,
            'title'             => $this->includes['page_header'],
            'cancel_url'        => $this->_redirect_url,
            'dt'                => empty($this->input->post()) ? null : $this->input->post(),
            'dt_id'             => null,
            'dt_kategori'       => $this->ref_kategori(),
            'dt_jenis'          => $this->ref_jenis(),
            'dt_bahasa_program' => $this->ref_bahasa_program(),
            'dt_dokumen'        => $dokumen,
        );

        // load views
        $data['content'] = $this->load->view('admin/master/applications/form', $content_data, true);
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
        $dt = $this->applications_model->as_array()->get($id);

        // if empty results, return to list
        if (!$dt) {
            redirect($this->_redirect_url);
        }

        $dokumen = $this->ref_dokumen();
        if (!empty($this->input->post())) {
            $dokumen = $this->input->post('dokumen');
        } else {
            if ($dt['dokumen']) {
                $dokumen = $this->ref_dokumen(json_decode($dt['dokumen'], true));
            }
        }

        // validators
        $this->form_validators($dt, false);

        if ($this->form_validation->run() == true) {
            $post        = $this->input->post();
            $active_user = $this->session->userdata('logged_in');

            $post['dokumen'] = count($this->input->post('dokumen')) ? json_encode($this->input->post('dokumen')) : null;

            // save
            $saved = $this->applications_model->do_save($post, $active_user['id']);

            if ($saved) {
                $this->session->set_flashdata('message', sprintf(lang('global msg edit_success'), $this->input->post('nama')));
            } else {
                $this->session->set_flashdata('error', sprintf(lang('global error edit_failed'), $this->input->post('nama')));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->set_title(lang('applications title edit'));

        $data = $this->includes;

        // set content data
        $content_data = array(
            'this_url'          => THIS_URL,
            'title'             => $this->includes['page_header'],
            'cancel_url'        => $this->_redirect_url,
            'dt'                => empty($this->input->post()) ? $dt : $this->input->post(),
            'dt_id'             => $id,
            'dt_kategori'       => $this->ref_kategori(),
            'dt_jenis'          => $this->ref_jenis(),
            'dt_bahasa_program' => $this->ref_bahasa_program(),
            'dt_dokumen'        => $dokumen,
        );

        // load views
        $data['content'] = $this->load->view('admin/master/applications/form', $content_data, true);
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

                $dt = $this->applications_model->as_array()->get($id);
                if ($dt) {
                    $deleted = $this->applications_model->do_delete($dt, $active_user['id']);
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
        foreach ($this->customers_model->get_fields() as $field) {
            if ($this->input->get($field)) {
                $filters[$field] = $this->input->get($field, true);
            }
        };

        // get list
        $dt_rows = $this->applications_model->get_list(0, 0, $filters, $sort, $dir);

        if ($dt_rows['total'] > 0) {
            // manipulate the output array
            foreach ($dt_rows['results'] as $key => $row) {
                // unset($dt_rows['results'][$key]['password']);
                // unset($dt_rows['results'][$key]['deleted']);

                // if ($row['status'] == 0) {
                //     $dt_rows['results'][$key]['status'] = lang('admin input inactive');
                // } else {
                //     $dt_rows['results'][$key]['status'] = lang('admin input active');
                // }
            }

            // export the file
            array_to_csv($dt_rows['results'], lang('applications list title'));
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
            $this->form_validation->set_rules('nama', lang('applications input nama'), 'required|trim|min_length[3]|max_length[32]|callback__check_nama[]');
        } else {
            $this->form_validation->set_rules('nama', lang('applications input nama'), 'required|trim|min_length[3]|max_length[32]|callback__check_nama[' . $dt['nama'] . ']');
        }
        $this->form_validation->set_rules('deskripsi', lang('applications input deskripsi'), 'required|trim|max_length[255]');
        $this->form_validation->set_rules('kategori', lang('applications input kategori'), 'required');
        $this->form_validation->set_rules('jenis', lang('applications input jenis'), 'required');
        $this->form_validation->set_rules('bahasa_program', lang('applications input bahasa_program'), 'required');
        // $this->form_validation->set_rules('dokumen', lang('applications input dokumen'), 'required');
        // $this->form_validation->set_rules('keterangan', lang('applications input keterangan'), 'required');
    }

    /**
     * Enum pada field applications.kategori
     */
    private function ref_kategori()
    {
        return array(
            'Undefined' => 'Undefined',
            'Core'      => 'Core',
            'Non-Core'  => 'Non-Core',
            'Pelengkap' => 'Pelengkap',
            'Pendukung' => 'Pendukung',
        );
    }

    /**
     * Enum pada field applications.jenis
     */
    private function ref_jenis()
    {
        return array(
            'Undefined'         => 'Undefined',
            'Web App'           => 'Web App',
            'Desktop App'       => 'Desktop App',
            'Android/Ios App'   => 'Android/Ios App',
            'Web Service/API'   => 'Web Service/API',
            'Scripting/Service' => 'Scripting/Service',
        );
    }

    /**
     * Enum pada field applications.bahasa_program
     */
    private function ref_bahasa_program()
    {
        return array(
            'Undefined'       => 'Undefined',
            'PHP'             => 'PHP',
            'Python'          => 'Python',
            'Visual Basic'    => 'Visual Basic',
            'Delphi'          => 'Delphi',
            'Shell Scripting' => 'Shell Scripting',
        );
    }

    private function ref_dokumen($data = array())
    {
        $key_values = array();
        $key_fields = array('tanggal', 'filename', 'filepath', 'keterangan');
        if (is_array($data) and count($data)) {
            foreach ($data as $index => $rows) {
                foreach ($key_fields as $key) {
                    $key_values[$index][$key] = isset($rows[$key]) ? $rows[$key] : null;
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
        if (trim($nama) != trim($current) && $this->applications_model->nama_exists($nama)) {
            $this->form_validation->set_message('_check_nama', sprintf(lang('applications error nama_exists'), $nama));
            return false;
        } else {
            return $nama;
        }
    }
}
