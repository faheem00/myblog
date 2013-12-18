<?php
class Login extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }
    function index(){
        $this->load->model('blogdb');
        //form rules
        $this->form_validation->set_rules('username','Username','required|min_length[6]|xss_clean');
        $this->form_validation->set_rules('password','Password','required|min_length[6]|md5');

	if ($this->form_validation->run() == FALSE)
	{
		$this->load->view('verifico'); //If not valid, load view again
	}
        else{
            //Get username password from view
            $userdata['username'] = $this->input->post('username');
            $userdata['password'] = $this->input->post('password');
            //Send user data to verifylogin function in blogdb
            if($this->blogdb->verifylogin($userdata) > 0){ //If user exists
                $userdata['userid'] = $this->blogdb->verifylogin($userdata); //get user id using verifylogin function in blogdb
                unset($userdata['password']); //unset password
                $this->session->set_userdata($userdata); //set user data in session 
                redirect('backend'); //redirect to backend.
            }
            else{
                $this->load->view('verifico');
            }
        }
    }
}
?>
