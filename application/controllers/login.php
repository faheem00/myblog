<?php
class Login extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('blogdb');
    }
    function index(){
        if($this->session->userdata('userid')){
            redirect('backend');
            return;
        }
        //form rules
        $this->form_validation->set_rules('username','Username','required|min_length[6]|xss_clean');
        $this->form_validation->set_rules('password','Password','required|min_length[6]|md5');
        $this->form_validation->set_error_delimiters('<li class="text-danger">', '</li>');

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
                $data['errormessage'] = '<li class="help-block">Your username or password was wrong</li>';
                $this->load->view('verifico',$data);
            }
        }
    }
    
    //Check if registering username or email exists
    function checkexist(){
        if ($this->input->get('field') == 'regusername') {
            echo json_encode(
                    array(
                        "value" => $this->input->get('value'),
                        "valid" => $this->blogdb->checkuserexist($this->input->get('value')),
                        "message" => "User already exists"
            ));
        }
        else if($this->input->get('field') == 'regemail'){
                echo json_encode(
                array(
                "value" => $this->input->get('value'),
                "valid" => $this->blogdb->checkemailexist($this->input->get('value')),
                "message" => "Email already exists"
                ));
        }
    }
    //Register a new user
    function register(){
        if($this->input->post('regsubmit')){
        $data['username'] = $this->input->post('regusername');
        $data['email'] = $this->input->post('regemail');
        $data['password'] = md5($this->input->post('regpassword'));
        if($this->blogdb->register($data)){
            $this->session->set_userdata('newregistered',TRUE);
            $data['userid'] = $this->blogdb->verifylogin($data);
            unset($data['password']); //unset password
            $this->session->set_userdata($data); //set user data in session 
            redirect('backend'); //redirect to backend.  
        }
        }
    }
}
?>
