<?php
class Pages extends CI_Controller {

    public function view($page = 'home') {

        $this->load->helper(array('form', 'url'));

        // echo base_url().'public/js/test.js';die;

        /*
        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php')) {
            // Whoops, we don't have a page for that!
            show_404();
        }
        */

        $data['title'] = ucfirst($page); // Capitalize the first letter

        // $this->load->view('templates/header', $data);
        // $this->load->view('pages/'.$page, $data);
        // $this->load->view('templates/footer', $data);

        $this->load->view('test');
    }

    public function index(){

        $this->load->helper(array('form', 'url'));

        echo base_url();die;

        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required',
            array('required' => 'You must provide a %s.')
        );
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('myform');
        } else {
            $this->load->view('formsuccess');
        }
    }
}