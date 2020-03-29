<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function show_404($page = '', $log_error = true)
    {
        $CI = &get_instance();
        $CI->output->set_status_header('404');
        $CI->load->view("errors/error_404");
        echo $CI->output->get_output();
        exit;
    }
}
