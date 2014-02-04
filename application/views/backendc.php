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

<div id="editprofilediv">
    <?php echo form_open('',array("onsubmit" => "return false;")) ?>
    <div class="row">
        <h1 class="text-center">Edit Your Profile</h1>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-2">
            <span class="text-center">Avatar</span>
            <div class="thumbnail">
                <img style="height:200px; width:200px;" class="avatar img-responsive img-rounded" src='<?php echo "/backend/image?id="; if(isset($profile_picture_string)) echo $profile_picture_string; else echo 'placeholder'?>'>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
            <input type="file" name="profilepic" id="fileupload">
            </div>
            <div class="row">
            <div class="sizeBox"></div>
            <div class="Progress"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="usernameshow">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Username:</span>
                <span class="text-right placeholder"><?=$username?></span>
                <button class="btn btn-primary pull-right">Edit</button>
            </div>
        </div>
        <div class="usernamehide hide">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Username:</span>
                <div class='form-group'>
                <input name="editusername" type="text" class="form-control profiledata altedit" <?php echo 'value=' . $username?> minlength="6" data-validation-ajax-ajax="backend/checkexist">
                <p class="help-block"></p>
                </div>
                <div class="btn-group pull-right">
                <input type="button" class="btn btn-primary" value="Save">
                <button class="btn btn-primary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="fullnameshow">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Full name:</span>
                <span class="text-right placeholder"><?php if(isset($fullname)) echo $fullname; else echo "No name set yet"; ?></span>
                <button class="btn btn-primary pull-right">Edit</button>
            </div>
        </div>
        <div class="fullnamehide hide">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Full name:</span>
                <input class="form-control profiledata" <?php if(!isset($fullname)) echo "placeholder='Set a Full Name'"; else echo 'value="' . $fullname . '"'?> >
                <div class="btn-group pull-right">
                <input type="button" class="btn btn-primary" value="Save">
                <button class="btn btn-primary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="emailshow">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Email Address:</span>
                <span class="text-right placeholder"><?php if(isset($email)) echo $email; else echo "No email set yet"; ?></span>
                <button class="btn btn-primary pull-right">Edit</button>
            </div>
        </div>
        <div class="emailhide hide">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Email Address:</span>
                <div class='form-group'>
                <input name="editemail" type="email" class="form-control profiledata altedit" <?php if(!isset($email)) echo "placeholder='Set an email'"; else echo 'value=' . $email?> data-validation-ajax-ajax="backend/checkexist">
                <p class="help-block"></p>
                </div>
                <div class="btn-group pull-right">
                <input type="button" class="btn btn-primary" value="Save">
                <button class="btn btn-primary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="gendershow">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Gender</span>
                <span class="text-right"><?php if(!isset($gender)) echo "Not yet set"; else if($gender == 'M') echo "Male" ;else echo "Female"?></span>
                <button class="btn btn-primary pull-right">Edit</button>
            </div>
        </div>
        <div class="genderhide hide">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Gender:</span>
                <div class="btn btn-default btn-group profiledata">
                    <input type="radio" name="gender" <?php if($gender == 'M') echo "checked" ?> value="M">Male
                    <input type="radio" name="gender" <?php if($gender == 'F') echo "checked" ?> value="F">Female
                </div>
                <div class="btn-group pull-right">
                <input type="button" class="btn btn-primary" value="Save">
                <button class="btn btn-primary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="dobshow">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Date of Birth</span>
                <span class="text-right placeholder"><?php if(isset($dob)) echo $dob; else echo "No date of birth set yet"; ?></span>
                <button class="btn btn-primary pull-right">Edit</button>
            </div>
        </div>
        <div class="dobhide hide">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Date of Birth:</span>
                <input class="form-control profiledata datepicker" <?php if(!isset($dob)) echo "placeholder='Set a date'"; else echo 'value=' . $dob?> >
                <div class="btn-group pull-right">
                <input type="button" class="btn btn-primary" value="Save">
                <button class="btn btn-primary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>js/bootstrap-switch.min.js"></script>
    <div class="row">
        <div class="profileview">
            <div class="col-md-6 col-md-offset-2 thumbnail">
                <span class="text-info">Let public view your profile?</span>
                <span class="pull-right">
                <input type="checkbox" <?php if($enable_profile_view) echo "checked"; ?> data-on="success" data-off="danger" data-on-label="Yes" data-off-label="No"></input>
                </span>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom: 20px;">
        <div class="bioshow">
            <div class="col-md-2 col-md-offset-1">
                <span class="text-info">About Yourself:</span>
            </div>
            <div class="col-md-6 thumbnail placeholder">
                <?php if(isset($description)) echo $description; else echo "No description has been provided yet" ?>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary pull-right">Edit</button>
            </div>
            </div>
        <div class="biohide hide">
            <div class="col-md-2 col-md-offset-1">
                <span class="text-info">About Yourself:</span>
            </div>
            <textarea class="col-md-6 profiledata" rows="8">
<?php if(!isset($description)) echo "placeholder='Set a description'"; else echo $description?>
            </textarea>
            <div class="col-md-3">
                <div class="btn-group">
                <input type="button" class="btn btn-primary" value="Save">
                <button class="btn btn-primary">Cancel</button>
                </div>
                </div>
            </div>
        </div>
    <?php echo form_close() ?>
    </div>
