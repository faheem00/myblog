<?php

class Viewblog extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('blogdb'); //load model
    }
    function index($username){
        $userid = $this->blogdb->getuserid($username);
        if($this->blogdb->getpermission($userid)){ //If user has given permission to view his blog
        $postlist['postdata'] = $this->blogdb->getpostnames($userid); //Get post list
        $postlist['username'] = $username;
        $this->load->view('blog',$postlist); //Load blog view, send post list
        }
        else show_error ('No file here', '404');
    }
}
?>
