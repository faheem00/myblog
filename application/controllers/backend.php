<?php

class Backend extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('blogdb');
        $this->load->helper('date');
    }
    
    public function index(){
        if($this->session->userdata('username')){ //If username is set in session data
            $this->load->view('backend'); //Load backend view
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
    
    public function pagination($uri=''){ //Paginate backend posts
        $this->load->library('pagination'); //Load pagination library
        $config['base_url'] = base_url() . 'backend/pagination'; //Base url which is used on pagination
        $config['total_rows'] = $this->blogdb->getpostrowcount();//Number of total rows of posts
        $config['per_page'] = 5; // Row per page
        $config['uri_segment'] = 3; //URI segment
        $config['full_tag_open'] = '<ul class="pagination">'; 
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $page = ($this->uri->segment(3) >0 ) ? $this->uri->segment(3) : 0;
        $data['posts'] = $this->blogdb->getpostlist($this->pagination->per_page,$page); //Get post list
            $val = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0 ; //If there is no URI, set 0
            $echo = "";
            foreach($data['posts'] as $row): 
            $echo .= "<tr data-id = '" . $row->post_id ."'>";
            $echo .=  "<td>".++$val."</td>";
            $echo .=  "<td>".$row->post_title."</td>";
            $echo .=  "<td>".unix_to_human($row->post_time)."</td>";
            $echo .=  "<td>".$this->blogdb->getlikecount($row->post_id)."</td>";
            $echo .=  '<td><a data-toggle="modal" href="#editModal"><i class="fa fa-edit"></i></a></td>';
            $echo .=  '<td><a data-toggle="modal" href="#deleteModal"><i class="fa fa-trash-o"></i></a></td>';
            $echo .=  "</tr>";
            endforeach;
            if($uri != -1) //If not called from index functions
            echo json_encode(array('pagination' => $data['pagination'], 'echo' => $echo)); //JSON encode the result and echo
            else
            return $data;
        
    }
    
    public function backendc(){
        $data = $this->pagination(-1);
        $this->load->view('backendc',$data);
    }
    
    public function logout(){
        $this->session->sess_destroy();
    }
    
}

?>
