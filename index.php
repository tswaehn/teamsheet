<?php
/**
 * PHPTimeSheet - an online web based time tracking application.
 * 
 * @copyright Copyright (C) 2000 Darren McClelland <darren@seattleserver.com>, <dmuser1@aa.net>
 * @copyright Copyright (C) 2004 Sebastian Mendel <phptimesheet at sebastianmendel dot de>
 * @copyright Copyright (C) 2016 Sven Ginka <sven.ginka@gmail.com>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU General Public License  - GPL
 * @package phpTeamSheet
 * @author Darren McClelland <darren@seattleserver.com>, <dmuser1@aa.net> 
 * @author Sebastian Mendel <phptimesheet@sebastianmendel.de> 
 * @author Sven Ginka <sven.ginka@gmail.com>
 * @version $Id: index.php,v 2.0 2016/06/04 $
 * 
 * 
 */

session_set_cookie_params( 3600*24*14, "/teamsheet", "", false, true );
session_start();

//print_r( $_SESSION );

if (file_exists( "./conf/dev_config.php")){
  // use dev_config for local development; we usually dont want to publish dev used user/pass/server/... settings 
  include("./conf/dev_config.php");
} else {
  include("./conf/config.php");
}
include("./lib/errorCodes.php");
include("./lib/checkSetup.php");
include("./lib/diverse.php");
include("./lib/logging.php");
include("./lib/dbConnection.php");
include("./lib/dbUser.php");
include("./lib/dbTimesheetTable.php");
include("./lib/timesheet.php");
include("./lib/drawCalendar.php");
include("./lib/statistics.php");
include("./lib/myTime.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);
/* 
require_once 'config.inc.php';
require_once 'extras.lib.php';
require_once 'worker.class.php';
require_once 'week.class.php';
require_once 'workday.class.php';
*/
/* open Database */
//require_once 'db.lib.php';

connectToDb();


if (isset( $_SESSION["uid"]) && isset($_SESSION["uname"]) && isset( $_SESSION["ukey"])){
  debug("uid from session");
  $session_uid= $_SESSION["uid"];
  $session_uname= $_SESSION["uname"];
  $session_ukey= $_SESSION["ukey"];
} else {
  $session_uid= -1;
  $session_uname= "";
  $session_ukey= 0;
}

$user= new User($session_uname, $session_ukey);

if (!$user->isValid()){
  debug("request for login");
  
  $PHP_AUTH_USER= $_SERVER["PHP_AUTH_USER"];
  $PHP_AUTH_PW= $_SERVER["PHP_AUTH_PW"];

  if ( !isset( $PHP_AUTH_USER ) || !isset( $PHP_AUTH_PW ) )
  {
      $realm = 'TimeSheet Login ' . strftime( '%c', time() );
      Header( 'WWW-authenticate:  Basic  realm="' . $realm . '"' );
      Header( 'HTTP/1.0  401  Unauthorized' );
      include( template( UNAUTH_TEMPLATE ) );
      exit;
  }

  $user = new User( $PHP_AUTH_USER, md5($PHP_AUTH_PW) );

  if ( !$user->isValid() )
  {
      $realm = 'TimeSheet Login ' . strftime( '%c', time() );
      Header( 'WWW-authenticate:  Basic  realm="' . $realm . '"' );
      Header( 'HTTP/1.0  401  Unauthorized' );
      include( template( UNAUTH_TEMPLATE ) );
      exit;
  }

  $_SESSION["uid"]= $user->uid;
  $_SESSION["uname"]= $user->uname;
  $_SESSION["ukey"]=  md5($PHP_AUTH_PW);
  session_write_close();
}


if ( isset( $_REQUEST['logout'] ) )
{
    $myworker->logout( TEMPLATES_DIR . '/' . LANG . '/' . LOGOUT_TEMPLATE );
    exit;
}


/**
 * If update password
 */
if ( isset( $_REQUEST['updatepasswd'] ) )
{
    // Ensure they really know their password
    if ( empty($_REQUEST['oldpasswd']) || !$myworker->authenticate( $_REQUEST['oldpasswd'] ) )
    {
        $error = 'You must authenticate with your current password before changeing it';
        include( TEMPLATES_DIR . '/' . LANG . '/' . PASSWD_TEMPLATE );
        exit;
    }

    if ( empty($_REQUEST['newpassword']) || $_REQUEST['newpassword'] != $_REQUEST['newpassword1'] )
    {
        $error = 'The new passwords must match and can not be empty';
        include( TEMPLATES_DIR . '/' . LANG . '/' . PASSWD_TEMPLATE );
        exit;
    }
    $myworker->reset_password( $_REQUEST['newpassword'] ); 
    $message = sprintf( 'Updated user password for %s', $myworker->get_name() );
} // if(isset($updatepasswd)

lg( "user ".$user->uname." ".$user->uid );
$timesheetTable= new dbTimesheetTable();

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="format.css">
<link rel="stylesheet" type="text/css" href="calendar.css">

  <!-- jQuery -->
  <script src="./jQuery/external/jquery/jquery.js"></script>
  <script src="./jQuery/jquery-ui.js"></script>
  <link rel="stylesheet" href="./jQuery/jquery-ui.css">
  
  
<title>
..::TeamSheet::..
</title>
</head>

<body>
  <!-- this is the header !-->
  <?php include("./lib/header.php") ?> 
  
  
  <?php 
    include( template( TABLE_TEMPLATE ));
    ?>
  
  
  <?php
    include("./lib/footer.php");
  ?>
</body>

</html>

