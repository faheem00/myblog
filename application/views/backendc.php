<div id='newpostdiv'>
            <div class='col-md-9' id='backend'>
                <h1 class='text-center'>Create a post</h1>
                <?php $this->load->helper('form'); ?>
                <?php echo form_open('backend/postsubmit'); ?>
                    <div class="form-group">
                        <input type="text" class="form-control" name="ptitle" id="ptitle" placeholder="Title">
                    </div>
                    <div class='form-group'>
                        <textarea name="content" class="form-control" name="tinymce" id="tinymce1" style="width:100%">
                        </textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control bootstrap-tagsinput" name="ptags" autocomplete="off" placeholder="Tags">
                    </div>
                    <input type="submit" class='btn btn-primary' name="psubmit" id="psubmit" value="Submit now" data-animation="true" data-placement="bottom" data-container="form" data-content="There is no content in the text field or in the title field!" >
                <?php echo form_close(); ?>
                    
                <?php if ($this->session->userdata('postsubmitted')): ?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            Your post has been successfully submitted!
                        </div>
                        <?php $this->session->unset_userdata('postsubmitted');
                    endif; ?>
                    
                 <?php if ($this->session->userdata('newregistered')): ?>
                        <div class="alert alert-info fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            Congratulations, you have been successfully registered! View "My Profile" section to edit your profile,
                            "Create a post" to create a new post, or "View old posts" to edit or delete your existing posts. Happy Blogging!
                        </div>
                        <?php $this->session->unset_userdata('newregistered');
                    endif;
                    ?>   
                    
                    </div>
            </div>
</div>

<div id='oldpostdiv'>
    <?php if(!empty($posts)): ?>
    <table class='table-responsive table-striped'>
                <thead>
                    <tr>
                        <th>Post Number</th>
                        <th>Post title</th>
                        <th>Post Date</th>
                        <th>Likes</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $val = 0; foreach($posts as $row): ?>
                    <tr data-id = "<?php echo $row->post_id;?>">
                        <td><?php echo ++$val?></td>
                        <td><?php echo $row->post_title;?></td>
                        <td><?php echo unix_to_human($row->post_time);?></td>
                        <td><?php echo $this->blogdb->getlikecount($row->post_id);?></td>
                        <td><a data-toggle="modal" href="#editModal"><i class='fa fa-edit'></i></a></td>
                        <td><a data-toggle="modal" href="#deleteModal"><i class='fa fa-trash-o'></i></a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    <ul class="page"><?php echo $pagination; ?></ul>
    <?php else: ?>
    <h1 class="text-center text-muted">You have no posts yet</h1>   
    <?php endif; ?>
    
    <div class="alert alert-success fade in" style="display: none;" id="deletesuccess">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        Your post has been successfully deleted!
    </div>
    <div class="alert alert-success fade in" style="display: none;" id="editsuccess">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        Your post has been successfully edited!
    </div>
    
    <!-- Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Delete a post</h4>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this post?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
          <input type="button" id="postdelete" class="btn btn-primary" value="Yes">
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!-- Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Edit this post</h4>
        </div>
        <div class="modal-body">
          <?php $this->load->helper('form'); ?>
            <form>
                    <div class="form-group">
                        <input type="text" class="form-control" name="etitle" id="etitle" placeholder="Title" >
                    </div>
                    <div class='form-group'>
                        <textarea name="content" class="form-control" name="tinymce" id="tinymce2"  style="width:100%;">
                        </textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control bootstrap-tagsinput" name="ptags" id="ptags" data-role="tagsinput" placeholder="Tags">
                    </div>
                    <input type="button" class='btn btn-primary' name="esubmit" id="esubmit" value="Submit now">
            </form>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  
</div>

<div id='otherblogdiv'>
    <div class='text-center'>Here are the lists of other people's blogs</div>
    <div class='text-center'>
        <ul class='list-unstyled'>
        <?php foreach($usernames as $row): ?>
            <li><a href='<?=  site_url(); ?>viewblog/<?=$row->username; ?>'><?=$row->username; ?></a></li>
        <?php endforeach; ?>
        </ul>
    </div>
</div>
