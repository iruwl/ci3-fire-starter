<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * All  > PUBLIC <  AJAX functions should go in here
 *
 * CSRF protection has been disabled for this controller in the config file
 *
 * IMPORTANT: DO NOT DO ANY WRITEBACKS FROM HERE!!! For retrieving data only.
 */
class Ajax extends Public_Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Change session language - user selected
     */
    public function set_session_language()
    {
        $language                = $this->input->post('language');
        $this->session->language = $language;
        $results['success']      = true;
        echo json_encode($results);
        die();
    }

    // http://php.net/manual/en/features.file-upload.php
    public function uploadfile()
    {
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (!isset($_FILES['upfile']['error']) ||
            is_array($_FILES['upfile']['error'])
        ) {
            $this->response(1, 'Exceeded filesize limit or file unsupported.');
        }

        // https://andrewcurioso.com/blog/archive/2010/detecting-file-size-overflow-in-php.html
        $uploadMaxSize = ini_get('upload_max_filesize');
        switch (substr($uploadMaxSize, -1)) {
            case 'G':
                $uploadMaxSize = $uploadMaxSize * 1024;
            case 'M':
                $uploadMaxSize = $uploadMaxSize * 1024;
            case 'K':
                $uploadMaxSize = $uploadMaxSize * 1024;
        }

        // Check $_FILES['upfile']['error'] value.
        switch ($_FILES['upfile']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->response(1, 'No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->response(1, 'Exceeded filesize limit: ' . $this->convertToReadableSize($uploadMaxSize));
            default:
                $this->response(1, 'Unknown errors.');
        }

        // You should also check filesize here.
        // if ($_FILES['upfile']['size'] > 1000000) {
        if ($_FILES['upfile']['size'] > $uploadMaxSize) {
            $this->response(1, 'Exceeded file size limit: ' . $this->convertToReadableSize($uploadMaxSize));
        }

        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['upfile']['tmp_name']), array(
                'jpeg' => 'image/jpeg',
                'jpg'  => 'image/jpeg',
                'png'  => 'image/png',
                'gif'  => 'image/gif',
                'pdf'  => 'application/pdf',
                'doc'  => 'application/msword',
                'xls'  => 'application/vnd.ms-excel',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'odt'  => 'application/vnd.oasis.opendocument.text',
                'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
            ), true)
        ) {
            $this->response(1, 'Invalid file format.');
        }

        $time        = time();
        $upload_path = FCPATH . 'upload/';
        // $file_name   = sprintf("$upload_path%s.%s", sha1_file($_FILES['upfile']['tmp_name']), $ext);
        $file_name = sprintf("$upload_path%s.%s", sha1(sha1_file($_FILES['upfile']['tmp_name']) . $time), $ext);

        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        if (!move_uploaded_file($_FILES['upfile']['tmp_name'], $file_name)) {
            $this->response(1, 'Failed to move uploaded file.');
        }

        restore_error_handler();

        // $this->response(0, 'File is uploaded successfully.');
        $this->response(0, basename($file_name));
    }

    private function response($error, $msg, $data = null)
    {
        $response = array(
            'error'   => $error,
            'message' => $msg,
            'result'  => $data,
        );
        header('Content-type: application/json');
        echo json_encode($response) . PHP_EOL;
        die();
    }

    private function convertToReadableSize($size)
    {
        $base   = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }
}
