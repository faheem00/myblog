<?php

class Viewblog extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('blogdb'); //load model
    }
    function index($username){
        $userid = $this->blogdb->getuserid($username);
        $postlist['postdata'] = $this->blogdb->getpostnames($userid); //Get post list
        $postlist['username'] = $username;
        $this->load->view('blog',$postlist); //Load blog view, send post list
    }
}
?>
