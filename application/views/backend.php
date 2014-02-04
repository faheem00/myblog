<!DOCTYPE html>
<html>
    <head>
        <title>Back end of <?php echo $this->session->userdata('username') ?>'s Blog</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-tagsinput.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/typeahead.js-bootstrap.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-switch.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/smoothness/jquery-ui-1.10.4.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/uploadfile.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css">
        <script src="<?php echo base_url(); ?>js/jquery-2.0.3.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>   
        <script src="<?php echo base_url(); ?>js/jquery.cookie.js"></script>
        <script src="<?php echo base_url(); ?>js/jqBootstrapValidation.js"></script>
        <script src="<?php echo base_url(); ?>js/nicEdit.js"></script>  
        <script src="<?php echo base_url(); ?>js/bootstrap-tagsinput.js"></script>
        <script src="<?php echo base_url(); ?>js/typeahead.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap-switch.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery-ui-1.10.4.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.ajaxfileupload.js"></script>
        <script src="<?php echo base_url(); ?>js/custom.js"></script>
    </head>
    <body>
        <header>
            <div class="text-center">Backend of Faheem's Blog</div>
        </header> <!-- End of Header -->
        <div class='container'>
            <div class='col-md-3'>
                <ul class='nav nav-pills nav-stacked'>
                    <li class="active"><a class='col-md-12' id='newpost'>New Post</a></li>
                    <li><a class='col-md-12' id='oldpost'>Old Posts</a></li>
                    <li><a class='col-md-12' id='logout'>Logout</a></li>
                    <li><a class='col-md-12' id='viewblog'>View Your Blog</a></li>
                    <li><a class='col-md-12' id='viewotherblog'>View other people's Blog</a></li>
                    <li><a class='col-md-12' id='editprofle'>Edit your profile</a></li>
                </ul>
            </div>
            <div class='col-md-9' id='backend'>
            
            </div>
        </div>
    </body>
</html>
