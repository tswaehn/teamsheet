<?php

  $dbname="teamsheet";
  $user="root";
  $pass="password";

  define( "BUILD_NR", "v0.1.0");	

  $emailNotificationRecipients= array(
                 "darth_vader@star-8.com",
                 "luke_skywalker@kindergarden.com"
                );




  // templates
  define("LANG", "DE");
  define("TEMPLATES_DIR", "./templates/");

  // no authtentication template
  define("UNAUTH_TEMPLATE", "noAuth.php");
  
  //
  define("TABLE_TEMPLATE", "table.php");
  
  
  // ---
  define("SMTP_SERVER", "");
  define("SMTP_PORT", "");
  define("SMTP_USER", "");
  define("SMTP_PASS", "");
  define("SMTP_REPLY", "");
  
?>
