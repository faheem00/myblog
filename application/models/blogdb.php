<?php

class Blogdb extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->load->helper('date');
    }
    public function publish_post($data){
        if(!empty($data['post_id'])){ //If the post id is not empty
            $query = $this->db->get_where('posts',array('post_id' => $data['post_id']));
            if($query->num_rows() > 0){
                $data['post_last_update_time'] = $data['post_time'];
                unset($data['post_time']);
                foreach($data['tag_names'] as $tag_name){
                    $tag_id = $this->db->get_where('tags',array('tag_name' => $tag_name));
                    if($tag_id->num_rows() == 0){
                        $this->db->set(array('tag_name' => $tag_name));
                        $this->db->insert('tags');
                        $tag_id = $this->db->get_where('tags',array('tag_name' => $tag_name));
                    }
                    $this->db->where('posts_post_id',$data['post_id']);
                    $this->db->where('tags_tags_id',$tag_id->row()->tags_id);
                    if($this->db->get('posts_has_tags')->num_rows == 0){
                    $this->db->set(array('posts_post_id' => $data['post_id'],'tags_tags_id' => $tag_id->row()->tags_id));   
                    $this->db->insert('posts_has_tags');
                    }
                }
                unset($data['tag_names']);
                $this->db->where('post_id',$data['post_id']);
                $this->db->update('posts',$data);
            }
        }
        else{ //Post id is empty
            $data['post_last_update_time'] = now();
            $tag_array = $data['tag_names'];
            unset($data['tag_names']);
            $this->db->set($data);
            $this->db->insert('posts');
            $data['tag_names'] = $tag_array;
            $data['post_id'] = $this->db->insert_id();
            foreach($data['tag_names'] as $tag_name){
                $tag_id = $this->db->get_where('tags',array('tag_name' => $tag_name));
                if($tag_id->num_rows() == 0){
                        $this->db->set(array('tag_name' => $tag_name));
                        $this->db->insert('tags');
                        $tag_id = $this->db->get_where('tags',array('tag_name' => $tag_name));
                    }
                $this->db->set(array('posts_post_id' => $data['post_id'],'tags_tags_id' => $tag_id->row()->tags_id));   
                $this->db->insert('posts_has_tags');
            }
            return $data['post_last_update_time']; //Return last post update time
        }
    }
    public function delete_post($pid){
       $this->db->delete('posts_has_tags',array('posts_post_id' => $pid));
       $this->db->delete('posts',array('post_id' => $pid)); 
       return true;
    }
    
    public function verifylogin($data){
        $query = $this->db->get_where('users',array('username' => $data['username'],'password' => $data['password']));
        if($query->num_rows() > 0){
            $row = $query->row();
            return $row->user_id;
        }
        else return 0;
    }
    public function getpostlist($limit,$offset,$id){ //Get the list of posts for backend
        $this->db->select('post_id,post_title,post_time')->from('posts')->where('users_user_id',$id)->limit($limit,$offset);
        $query = $this->db->get();
        return $query->result();
    }
    public function getpostnames($id){
        $this->db->select('post_id,post_title');
        $query = $this->db->get_where('posts',array('users_user_id' => $id));
        return $query->result();
    }
 
    public function getpostcontent($post_id){
        $query = $this->db->get_where('posts',array('post_id' => $post_id));
        return $query->row();
    }
    public function gettags($id){
        if($id == 0){
           $this->db->select('tag_name')->from('tags'); 
        }
        else{
            $this->db->select('tags.tag_name')->from('tags,posts_has_tags')->where('posts_has_tags.posts_post_id',$id)
                ->where('posts_has_tags.tags_tags_id = tags.tags_id');
        }
        $query = $this->db->get();
        return $query->result();
    }
    public function getlikecount($id){
        $this->db->select('like_id')->from('likes')->where('posts_post_id',$id);
        return $this->db->count_all_results(); //Return like count
    }
    public function isliked($user_id,$id){
        $query = $this->db->get_where('likes',array('users_user_id' => $user_id,'posts_post_id' => $id));
        if($query->num_rows() > 0) return true;
        else return false;
    }
    public function insertlike($post_id,$user_id){
        $this->db->set(array('posts_post_id' => $post_id, 'users_user_id' => $user_id));
        $this->db->insert('likes');
        return $this->getlikecount($post_id); //Return like count
    }
    public function getpostrowcount($id){
        $this->db->select("*")->from("posts")->where('users_user_id',$id);
        return $this->db->count_all_results();
    }
    public function checkuserexist($username){
        $query = $this->db->get_where('users',array('username' => $username));
        if($query->num_rows() > 0) return FALSE;
        else return TRUE;
    }
    public function checkemailexist($email){
        $query = $this->db->get_where('users',array('email' => $email));
        if($query->num_rows() > 0) return FALSE;
        else return TRUE;
    }
    public function register($data){
        $data['create_time'] = now();
        $this->db->set($data);
        $this->db->insert('users');
        return TRUE;
    }
    public function getuserid($username){
        $query = $this->db->get_where('users',array('username' => $username));
        return $query->row()->user_id;
    }
    public function getusernames($id){
        $this->db->where('user_id !=', $id);
        $this->db->where('enable_profile_view', '1');
        $this->db->where('`user_id` = `users_user_id');
        $query = $this->db->get('users,user_profiles');
        return $query->result();
    }
    public function getpermission($id){
        $query = $this->db->get_where('user_profiles',array('users_user_id' => $id, 'enable_profile_view' => '1'));
        if($query->num_rows() > 0) return true;
        else return false;
    }
    public function getprofiledata(){
        $this->db->join('users','users.user_id = user_profiles.users_user_id');
        $query = $this->db->get_where('user_profiles',array('users_user_id' => $this->session->userdata('userid')));
        if($query->num_rows() > 0){
            $data['username'] = $query->row()->username;
            $data['fullname'] = $query->row()->full_name;
            if(isset($query->row()->dob))$data['dob'] = $query->row()->dob;
            $data['gender'] = $query->row()->gender;
            $data['description'] = $query->row()->description;
            $data['address'] = $query->row()->address;
            $data['enable_profile_view'] = $query->row()->enable_profile_view ? true:false;
            $data['profile_picture_string'] = $query->row()->profile_picture_string;
            $data['email'] = $query->row()->email;
            return $data;
        }
        else return false;
    }
    public function setprofiledata($cname,$cvalue){  //Column name, Column value
        //$this->db->join('users','users.user_id = user_profiles.users_user_id');
       // $this->db->select('*')->from('user_profiles')->join('users','users.user_id = user_profiles.users_user_id')->where(array('user_profiles.users_user_id' => $this->session->userdata('userid'),$cname => $cvalue));
        //$query = $this->db->get();
        if($cname == 'username' || $cname == 'email'){
        $this->db->where('user_id',$this->session->userdata('userid'));
        $this->db->update('users',array($cname => $cvalue));
        if($cname == 'username') $this->session->set_userdata('username',$cvalue);
        }
        else{
        $this->db->where('users_user_id',$this->session->userdata('userid'));
        $this->db->update('user_profiles',array($cname => $cvalue));
        }
        $data = json_encode(array('echo' => 'true','value' => $cvalue));
        return $data;
    }
    public function setprofilepic($data){
        $query = $this->db->get_where('user_profiles',array('profile_picture_string' => $data['file_string']));
        if($query->num_rows()> 0){
            $this->load->helper('string');
            $data['file_string'] = random_string('unique');
        }
        $this->db->where('users_user_id',$this->session->userdata('userid'));
        $this->db->update('user_profiles',array('profile_picture_path' => $data['upload_path'] . $data['file_name'],'profile_picture_string' => $data['file_string']));
    }
    public function getprofilepic($id){
        $query = $this->db->get_where('user_profiles',array('profile_picture_string' => $id));
        if($query->num_rows()> 0){
            return $query->row()->profile_picture_path;
        }
        else return NULL;
    }
}
?>
