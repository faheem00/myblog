<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css">
        <script src="<?php echo base_url(); ?>js/jquery-2.0.3.js"></script> 
        <script src="<?php echo base_url(); ?>js/custom.js"></script>
    </head>
    <body>
        <div class="container">
        <div class='row'>
            <div class='col-md-12 text-center'>
                <?php echo validation_errors(); //check for validation errors ?>
        <?php echo form_open('login') ?>
            <div class='form-group'>
            <input type="text" class='form-control' name="username" placeholder="Username">
            </div>
            <div class='form-group'>
            <input type="password" class='form-control' name="password" placeholder="Password">
            </div>
            <div class='form-group'>
            <input type='submit' class='btn btn-success' name="loginsubmit" value="Submit">
            </div>
        </form>
            </div>
        </div>
       </div>
    </body>
</html>
