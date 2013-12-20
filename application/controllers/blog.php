<?php

class Blog extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('blogdb'); //load model
    }
    function index(){
        $postlist['postdata'] = $this->blogdb->getpostnames($this->session->userdata('userid')); //Get post list
        $postlist['username'] = $this->session->userdata('username');
        $this->load->view('blog',$postlist); //Load blog view, send post list
    }
    function getid(){ //Get post id
        if($this->input->post('post_id')){
            $this->session->set_flashdata('current_id',$this->input->post('post_id'));//export id via flash cookie
        }
    }
    function insertlike(){
        if($this->input->post('post_id')){
            $count = $this->blogdb->insertlike($this->input->post('post_id'),$this->session->userdata('userid'));
            echo $count; //Count of likes
        }
    }
    function showblogpost(){
        if($this->session->flashdata('current_id')){
            $data = (array) $this->blogdb->getpostcontent($this->session->flashdata('current_id'));
            $tags_array = $this->blogdb->gettags($this->session->flashdata('current_id'));
            $tags = array();
            foreach ($tags_array as $tags_row) {
                $tags[] = $tags_row->tag_name;
            }
            $tags_name = implode(",", $tags);
            $data['likecount'] = $this->blogdb->getlikecount($this->session->flashdata('current_id'));
            $data['tags'] = $tags_name;
            $this->load->view('blogcontent',$data);
        }
        else echo "<h1 class='text-center text-muted col-md-offset-4'>You have no posts yet</h1>";
    }
}
?>
