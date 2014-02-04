<?php
class Test_Mail extends CI_Controller {
function __construct() {
        parent::__construct();
    }
function index()
{
// we load the email library and send a mail
$this->load->library('email');
$from = 'info@localhost.com';
$to = 'faheemabrar2003@gmail.com'; 
$to_name = "Faheem Abrar";
$this->email->from($from);
$this->email->to($to, $to_name);
$this->email->subject('Thanks for registering');
$this->email->message('<h1>You are now a registered member of Myblog!</h1>');
$this->email->send();
//to debug we can use print_debugger()
echo $this->email->print_debugger();
}
}
?>