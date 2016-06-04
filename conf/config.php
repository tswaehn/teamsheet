<?php

  $dbname="timesheet";
  $user="root";
  $pass="";

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
?>
