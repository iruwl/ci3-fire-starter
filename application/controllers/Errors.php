<?php defined('BASEPATH') or exit('No direct script access allowed');

class Errors extends CI_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // disable the profiler
        $this->output->enable_profiler(false);
    }

    /**
     * Custom 404 page
     */
    public function error404()
    {
        // load views
        $this->load->view("errors/error_404");
    }

}
