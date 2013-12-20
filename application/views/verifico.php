<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title>Log In to Your Blog</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css">
        <script src="<?php echo base_url(); ?>js/jquery-2.0.3.js"></script> 
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.cookie.js"></script>
        <script src="<?php echo base_url(); ?>js/jqBootstrapValidation.js"></script>
        <style>
            .row:before {
                content: '';
                display: inline-block;
                height: 100%;
                vertical-align: middle;
                margin-right: -0.25em; /* Adjusts for spacing */
            }
        </style>
    </head>
    <body>
        <div class="container">
        <div class='row'>
            <div class='col-md-6 text-center thumbnail center-block'>
                <h3>Log in to your blog</h3>
            <?php echo form_open('login') ?>
            <div class='form-group'>
            <input type="text" class='form-control' name="username" placeholder="Username" required>
            </div>
            <div class='form-group'>
            <input type="password" class='form-control' name="password" placeholder="Password" required minlength="6">
            <p class="help-block"></p>
            </div>
            <div class='form-group'>
            <input type='submit' class='btn btn-success' name="loginsubmit" value="Submit">
            <span>OR</span>
            <a data-toggle="modal" href="#regModal" class='btn btn-info' name="registersubmit">Register</a>
            </div>
        </form>
        <ul class="list-unstyled">
        <?php echo validation_errors(); //check for validation errors ?>
        <?php if(!empty($errormessage)) echo $errormessage; ?>
        </ul>
            </div>
        </div>
       </div>
        
        <script>
        //Jquery bootstrap validation plugin
        $(function () {
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); 
                    $.ajaxSetup({
                        data: {
                            csrf_test_name: $.cookie('csrf_cookie_name')
                        }
        });
        });

        </script>
        
        <!-- Registration Modal -->
        <div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="regModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title text-center">Register</h4>
                    </div>
                    <div class="modal-body">
                        <?php echo form_open('login/register',array('novalidate' => 'novalidate')) ?>
                        <div class='form-group'>
                            <input type="text" class='form-control' name="regusername" placeholder="Username" required minlength="6" data-validation-ajax-ajax="login/checkexist">
                            <p class="help-block"></p>
                        </div>
                        <div class='form-group'>
                            <input type="email" class='form-control' name="regemail" placeholder="Email" required data-validation-ajax-ajax="login/checkexist">
                            <p class="help-block"></p>
                        </div>
                        <div class='form-group'>
                            <input type="email" class='form-control' placeholder="Confirm Email" data-validation-match-match="regemail" data-validation-match-message="Email address does not match">
                            <p class="help-block"></p>
                        </div>
                        <div class='form-group'>
                            <input type="password" class='form-control' name="regpassword" placeholder="Password" required minlength="6">
                            <p class="help-block"></p>
                        </div>
                        <div class='form-group'>
                            <input type="password" class='form-control' placeholder="Confirm password" data-validation-match-match="regpassword" data-validation-match-message="Password does not match" minlength="6">
                            <p class="help-block"></p>
                        </div>
                        <div class="form-group text-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <input type="submit" name="regsubmit" class="btn btn-primary" value="Register">
                        </div>
                        <?php echo form_close() ?>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </body>
</html>
