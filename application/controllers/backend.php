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
        $config['total_rows'] = $this->blogdb->getpostrowcount($this->session->userdata('userid'));//Number of total rows of posts
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
        $data['posts'] = $this->blogdb->getpostlist($this->pagination->per_page,$page,$this->session->userdata('userid')); //Get post list
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
    
    //Function for displaying other people's blogs
    public function otherblog(){
        $data['usernames'] = $this->blogdb->getusernames($this->session->userdata('userid'));
        $this->load->view('backendc',$data);
    }
    
    
    public function editprofile(){
        $data = $this->getprofiledata();
        $this->load->view('backendc',$data);
    }
    
    //Function for getting profile data
    public function getprofiledata(){
        $data = $this->blogdb->getprofiledata();
        return $data;
    }
    
    public function logout(){
        $this->session->sess_destroy();
    }
    
    //Function for returning typeahead objects
    public function typeahead(){
        $tags_array = $this->blogdb->gettags(0);
        $tags = array();
        foreach ($tags_array as $tags_row) {
            $tags[] = $tags_row->tag_name;
        }
        echo json_encode($tags);
    }
    
    //Function for setting profile data
    public function setprofiledata(){
        if($this->input->post('fieldname')){
            switch($this->input->post('fieldname')){
                case 'username':
                    echo $this->blogdb->setprofiledata('username',$this->input->post('value'));
                    break;
                case 'fullname':
                    echo $this->blogdb->setprofiledata('full_name',$this->input->post('value'));
                    break;
                case 'email':
                    echo $this->blogdb->setprofiledata('email',$this->input->post('value'));
                    break;
                case 'gender':
                    echo $this->blogdb->setprofiledata('gender',$this->input->post('value'));
                    break;
                case 'dob':
                    $date = date('Y-m-d',strtotime($this->input->post('value')));
                    echo $this->blogdb->setprofiledata('dob',$date);
                    break;
                case 'bio':
                    echo $this->blogdb->setprofiledata('description',$this->input->post('value'));
                    break;
                case 'profileview':
                    if($this->input->post('value') == "false")
                    $value = 0;
                    else $value = 1;
                    echo $this->blogdb->setprofiledata('enable_profile_view',$value);
            }
        }
    }
    
    //Function for checking validity of edited username and email
    public function checkexist(){
        if($this->input->get('field') == 'editusername'){
            echo json_encode(
                    array(
                        "value" => $this->input->get('value'),
                        "valid" => $this->blogdb->checkuserexist($this->input->get('value')),
                        "message" => "User already exists"
            ));
        }
        else if($this->input->get('field') == 'editemail'){
            echo json_encode(
                array(
                "value" => $this->input->get('value'),
                "valid" => $this->blogdb->checkemailexist($this->input->get('value')),
                "message" => "Email already exists"
                ));
        }
        }
        
     //Function for uploading file
     function uploadpic() {  
        $this->load->helper('string');
        $config['upload_path'] = './images/' . $this->blogdb->getuserid($this->session->userdata('username')) . '/';
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1024';
        $config['file_name'] = $this->blogdb->getuserid($this->session->userdata('username')) . "_" . time();
        $config['file_string'] = random_string('unique');

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('profilepic')) {
            $error = json_encode(array('success' => false, 'msg' => $this->upload->display_errors()));
            echo $error;
        } else {
            $config['upload_path'] = $config['upload_path'];
            $config['file_name'] = $this->upload->data()['file_name'];
            $this->blogdb->setprofilepic($config);
            $data = json_encode(array('success' => true, 'file_link' => '/backend/image?id=' .  $config['file_string']));
            echo $data;
        }
    }
    
    //Function for loading a pic anonymously
    function image(){
        if($this->input->get('id') == 'placeholder'){
            $this->load->helper('file');
            $image_path = './images/placeholder.jpg';
            $this->output->set_content_type(get_mime_by_extension($image_path));
            $this->output->set_output(file_get_contents($image_path));
        }
        else if($this->input->get('id')){
            $this->load->helper('file');
            $image_path = $this->blogdb->getprofilepic($this->input->get('id'));
            $this->output->set_content_type(get_mime_by_extension($image_path));
            $this->output->set_output(file_get_contents($image_path));
        }
    }
}

?>
