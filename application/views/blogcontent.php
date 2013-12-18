<?php $this->load->model('blogdb'); ?>
<h1 class='post-header text-center'><?php echo $post_title; ?></h1> <!-- End of post title -->
                    <br>
                    
                    <div class='postcontent'>
                        <?php echo $post_content; ?>
                    </div> <!-- End of Main Post -->
                    
                    <div class='postinfo'>
                    <div class='row'>Posted on:&nbsp;&nbsp;&nbsp;<time class="posttime" data-livestamp="<?php echo $post_time; ?>"></time></div>
                    <div class='row'>Last Update on:&nbsp;&nbsp;&nbsp;<time class="posttime" data-livestamp="<?php echo $post_last_update_time; ?>"></time></div>
                    <div class='row'>Tags:
                        <?php echo $tags; ?>
                    </div>
                    <div class='row'><span class="postlike text-success"><?php echo $likecount;?></span> people like this</div>
                    <?php if($this->blogdb->isliked($this->session->userdata('ip_address'),$post_id)){ ?>
                    <div class='row text-info'>Liked</div>
                    <?php }else{ ?>
                    <div class='row'>Like&nbsp;<a class="likebutton"><i class="fa fa-thumbs-up fa-2x"></i></a></div>
                    <?php } ?>
                </div>  <!-- End of postinfo-->                 
                
                <button class="btn col-md-12 col-xs-12 btn-primary" id="loadcomments">Load comments</button>
                
                <div class="comment-wrapper">
                    <div id="disqus_thread"></div> 
                </div>  <!-- End of comment -->
               
                        