<?php 
if (!defined('ABSPATH')) {
    exit;
}

class jobs_board_email_nottification_setting{

     public function __construct() {

      add_action('admin_init', 'add_options_setting_email_nottification');  
  

}

public function add_options_setting_email_nottification(){

 return 'dfsfgsdfdshfsdgh';

}

}

new jobs_board_email_nottification_setting();




 ?>
