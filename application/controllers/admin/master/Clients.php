<?php defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends Admin_Controller
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
            'clients',
        ));

        // load model
        $this->load->model(array(
            'clients_model',
            'contacts_model',
            'client_contacts_model' => 'cc_model',
        ));

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/master/clients'));
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
        $this->add_js_theme("clients_i18n.js");
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
        foreach ($this->clients_model->get_fields() as $field) {
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
                foreach ($this->clients_model->get_fields() as $field) {
                    if ($this->input->post($field)) {
                        $filter .= "&{$field}=" . $this->input->post($field, true);
                    }
                };

                // redirect using new filter(s)
                redirect(THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");
            }
        }

        // get list
        $dt_rows = $this->clients_model->get_list($limit, $offset, $filters, $sort, $dir);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $dt_rows['total'],
            'per_page'   => $limit,
        ));

        // setup page header data
        $this->set_title(lang('clients title list'));

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
        $data['content'] = $this->load->view('admin/master/clients/list', $content_data, true);
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

            // save
            $saved = $this->clients_model->do_save($this->input->post(), $active_user['id']);

            if ($saved) {
                $this->cc_model->delete_by(array('client_id' => $saved));
                foreach (array_unique($this->input->post('contacts')) as $contact_id) {
                    if ($contact_id) {
                        $this->cc_model->insert(array(
                            'client_id'  => $saved,
                            'contact_id' => $contact_id,
                        ));
                    }
                }

                $this->session->set_flashdata('message', sprintf(lang('global msg add_success'), $this->input->post('nama')));
            } else {
                $this->session->set_flashdata('error', sprintf(lang('global error add_failed'), $this->input->post('nama')));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->set_title(lang('clients title add'));

        $data = $this->includes;

        $contacts = $this->ref_contacts();
        if (!empty($this->input->post())) {
            $contacts = $this->input->post('contacts');
        }

        // set content data
        $content_data = array(
            'this_url'    => THIS_URL,
            'title'       => $this->includes['page_header'],
            'cancel_url'  => $this->_redirect_url,
            'dt'          => empty($this->input->post()) ? null : $this->input->post(),
            'dt_id'       => null,
            'dt_kategori' => $this->ref_kategori(),
            'dt_status'   => $this->ref_status(),
            'dt_status'   => $this->ref_status(),
            'dt_contacts' => array(
                'ref'  => array('' => 'None') + $this->contacts_model->dropdown('id', 'nama'),
                'data' => $contacts,
            ),
        );

        // load views
        $data['content'] = $this->load->view('admin/master/clients/form', $content_data, true);
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
        $dt = $this->clients_model->as_array()->get($id);

        // if empty results, return to list
        if (!$dt) {
            redirect($this->_redirect_url);
        }

        $contacts = $this->ref_contacts($dt['id']);
        if (!empty($this->input->post())) {
            $contacts = $this->input->post('contacts');
        }

        // validators
        $this->form_validators($dt, false);

        if ($this->form_validation->run() == true) {
            $active_user = $this->session->userdata('logged_in');

            // save the changes
            $saved = $this->clients_model->do_save($this->input->post(), $active_user['id']);

            if ($saved) {
                $this->cc_model->delete_by(array('client_id' => $id));
                foreach (array_unique($this->input->post('contacts')) as $contact_id) {
                    if ($contact_id) {
                        $this->cc_model->insert(array(
                            'client_id'  => $id,
                            'contact_id' => $contact_id,
                        ));
                    }
                }

                $this->session->set_flashdata('message', sprintf(lang('global msg edit_success'), $this->input->post('nama')));
            } else {
                $this->session->set_flashdata('error', sprintf(lang('global error edit_failed'), $this->input->post('nama')));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->set_title(lang('clients title edit'));

        $data = $this->includes;

        // set content data
        $content_data = array(
            'this_url'    => THIS_URL,
            'title'       => $this->includes['page_header'],
            'cancel_url'  => $this->_redirect_url,
            'dt'          => empty($this->input->post()) ? $dt : $this->input->post(),
            'dt_id'       => $id,
            'dt_kategori' => $this->ref_kategori(),
            'dt_status'   => $this->ref_status(),
            'dt_contacts' => array(
                'ref'  => array('' => 'None') + $this->contacts_model->dropdown('id', 'nama'),
                'data' => $contacts,
            ),
        );

        // load views
        $data['content'] = $this->load->view('admin/master/clients/form', $content_data, true);
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

                $dt = $this->clients_model->as_array()->get($id);
                if ($dt) {
                    $deleted = $this->clients_model->do_delete($dt, $active_user['id']);
                    if ($deleted) {
                        $this->cc_model->delete_by(array('client_id' => $id));

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
        foreach ($this->clients_model->get_fields() as $field) {
            if ($this->input->get($field)) {
                $filters[$field] = $this->input->get($field, true);
            }
        };

        // get list
        $dt_rows = $this->clients_model->get_list(0, 0, $filters, $sort, $dir);

        if ($dt_rows['total'] > 0) {
            // manipulate the output array
            foreach ($dt_rows['results'] as $key => $row) {
                //
            }

            // export the file
            array_to_csv($dt_rows['results'], lang('clients list title'));
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
            $this->form_validation->set_rules('nama', lang('clients input nama'), 'required|trim|min_length[3]|max_length[32]|callback__check_nama[]');
        } else {
            $this->form_validation->set_rules('nama', lang('clients input nama'), 'required|trim|min_length[3]|max_length[32]|callback__check_nama[' . $dt['nama'] . ']');
        }
        $this->form_validation->set_rules('alamat', lang('clients input alamat'), 'required|trim|max_length[255]');
        $this->form_validation->set_rules('kategori', lang('clients input kategori'), 'required|trim');
        $this->form_validation->set_rules('status', lang('clients input status'), 'required|trim');
        // $this->form_validation->set_rules('keterangan', lang('clients input keterangan'), 'required|trim');
    }

    /**
     * Enum pada field clients.kategori
     */
    private function ref_kategori()
    {
        return array(
            'Pemerintah' => 'Pemerintah',
            'Swasta'     => 'Swasta',
        );
    }

    /**
     * Enum pada field clients.status
     */
    private function ref_status()
    {
        return array(
            'NA'          => 'NA',
            'Supported'   => 'Supported',
            'Unsupported' => 'Unsupported',
        );
    }

    /**
     * Get contacts of client
     */
    public function ref_contacts($client_id = null)
    {
        $key_values = array();
        $key_fields = array('id', 'nama', 'email', 'hp1');
        if ($client_id) {
            $cc = $this->cc_model->with('contacts')->get_many_by(array('client_id' => $client_id));
            foreach ($cc as $index => $row) {
                foreach ($key_fields as $key) {
                    $contacts                 = $row->contacts;
                    $key_values[$index][$key] = isset($contacts->$key) ? $contacts->$key : null;
                }
            }
        }

        if (!count($key_values)) {
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
        if (trim($nama) != trim($current) && $this->clients_model->nama_exists($nama)) {
            $this->form_validation->set_message('_check_nama', sprintf(lang('clients error nama_exists'), $nama));
            return false;
        } else {
            return $nama;
        }
    }
}
