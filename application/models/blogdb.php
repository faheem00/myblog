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
    public function getpostlist($limit,$offset){ //Get the list of posts for backend
        $this->db->select('post_id,post_title,post_time')->from('posts')->where('users_user_id',$this->session->userdata('userid'))->limit($limit,$offset);
        $query = $this->db->get();
        return $query->result();
    }
    public function getpostnames($id=''){ //post id having default value NULL
        if(empty($id)){
        $this->db->select('post_id,post_title');
        $query = $this->db->get('posts');
        return $query->result();
        }
        else{ //post id not null
            $this->db->select('post_title');
            $query = $this->db->get_where('posts',array('post_id' => $id));
            return $query->row();
        }
    }
 
    public function getpostcontent($id){
        $query = $this->db->get_where('posts',array('post_id' => $id));
        return $query->row();
    }
    public function gettags($id){
        $this->db->select('tags.tag_name')->from('tags,posts_has_tags')->where('posts_has_tags.posts_post_id',$id)
                ->where('posts_has_tags.tags_tags_id = tags.tags_id');
        $query = $this->db->get();
        return $query->result();
    }
    public function getlikecount($id){
        $this->db->select('like_id')->from('likes')->where('posts_post_id',$id);
        return $this->db->count_all_results(); //Return like count
    }
    public function isliked($ipaddress,$id){
        $query = $this->db->get_where('likes',array('like_ip_address' => $ipaddress,'posts_post_id' => $id));
        if($query->num_rows() > 0) return true;
        else return false;
    }
    public function insertlike($post_id,$ipaddress){
        $this->db->set(array('posts_post_id' => $post_id, 'like_ip_address' => $ipaddress));
        $this->db->insert('likes');
        return $this->getlikecount($post_id); //Return like count
    }
    public function getpostrowcount(){
        return $this->db->count_all('posts');
    }
}
?>
