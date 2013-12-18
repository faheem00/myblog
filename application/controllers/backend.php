<?php

class Backend extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('blogdb');
        $this->load->helper('date');
    }
    
    public function index(){
        if($this->session->userdata('username')){ //If username is set in session data
            $posts = $this->blogdb->getpostlist(); //get post list from blogdb
            $this->load->view('backend',$posts); //Load backend view and send post list
        }
    }
    
    public function postsubmit($pid=""){ //“pid” parameter with default null
        if($this->input->post('psubmit') && $this->input->post('content',FALSE) && $this->input->post('ptitle')){ //If backend sends post title and post content 
            $data['post_content'] = $this->input->post('content',FALSE);
            $data['post_title'] = $this->input->post('ptitle');
            $data['tag_names'] = explode(",", $this->input->post('ptags')); //Value will be exploded with delimiter “,” and sent as an array
            $data['post_time'] = now();
            $data['users_user_id'] = $this->session->userdata('userid');
            if(!empty($pid)) $data['post_id'] = $pid; //If post id is not empty
            $this->blogdb->publish_post($data);
            $this->session->set_userdata('postsubmitted',TRUE);
            redirect('/backend');
        }
        else redirect('/backend');
    }
    public function editpost(){
        if($this->input->post('pid')){ //AJAX request requesting for post title and post content
        $result = $this->blogdb->getpostcontent($this->input->post('pid')); //post id sent to getpostcontent, returns post row
        $tags_array = $this->blogdb->gettags($this->input->post('pid'));
        $tags = array();
        foreach ($tags_array as $tags_row) {
            $tags[] = $tags_row->tag_name;
        }
        $tags_name = implode(",", $tags);
        echo json_encode(array('edit_title' => $result->post_title, 'edit_content' => $result->post_content, 'edit_tags' => $tags_name));
        }
        else if ($this->input->post('post_id') && $this->input->post('post_title') && $this->input->post('post_content',FALSE) && $this->input->post('tags_name')) {
            //the post is to be updated in the database
            $data['post_id'] = $this->input->post('post_id');
            $data['post_content'] = $this->input->post('post_content',FALSE);
            $data['post_title'] = $this->input->post('post_title');
            $data['tag_names']  = explode(",", $this->input->post('tags_name'));
            $data['users_user_id'] = $this->session->userdata('userid');
            $data['post_time'] = now();
            $this->blogdb->publish_post($data); //Publishing updated post, returning last update time
            //echo new row of post list
            $echo = "<tr>";
            $echo .= "<td>"; $echo .= $data['post_id']; $echo .= "</td>";
            $echo .= "<td>"; $echo .= $data['post_title']; $echo .= "</td>";
            $echo .= "<td>"; $echo .= unix_to_human($data['post_time']); $echo .= "</td>";
            $echo .= "<td>"; $echo .= $this->blogdb->getlikecount($data['post_id']); $echo .= "</td>";
            $echo .= "<td>"; $echo .= "<a data-toggle='modal' href='#editModal'><i class='fa fa-edit'></i></a>"; $echo .= "</td>";
            $echo .= "<td>"; $echo .= "<a data-toggle='modal' href='#deleteModal'><i class='fa fa-trash-o'></i></a>"; $echo .= "</td>";
            $echo .= "</tr>";
            echo $echo; //return new row
        }
    }
    public function deletepost(){
        if($this->input->post('pid')){
        if($this->blogdb->delete_post($this->input->post('pid')))
        echo "success";
        }
    }
    public function backendc(){
        $this->load->view('backendc');
    }
    
    public function logout(){
        $this->session->sess_destroy();
    }
}

?>
