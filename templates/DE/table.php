<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!isset($user)){
  die();
}

$timesheet= new Timesheet();

// get the day that needs to be checked
$day= getUrlParam("day");
if (empty($day)){
  $day= time();
}

// prepare the correct mode
$mode= getUrlParam("mode");
if (!($mode == "edit")){
  $mode= "view";
}

// if anything has to be done
$action= getUrlParam("doSomething");
switch( $action ){
  
  case "save": 
              $data= $timesheet->importDataFromPost();
              $timesheet->saveTableToDB($data);
              // switch mode back to view
              $mode= "view";
              break; 
            
  case "cancel":
    
              break;
            
  case "spreadSheet":
              exportSpreadSheet();
              break;
  default:
    
}

if ($mode=="view"){
  $data= $timesheet->loadTableFromDB($day);
  
} else {

  $data= $timesheet->importDataFromPost();
  
}

//print_r($data);
$timesheet->renderTable($day, $data, $mode);

//$timesheet->render( $day, $mode );

//print_r( $_POST );

//$calendar = new Calendar();
//echo $calendar->show();

echo '<div id="statusbar-month">';

echo '</div>';

echo '<div id="statusbar-total">';

echo '</div>';

echo '<div id="menu-bar">';
  echo '<div id="links">';
  echo '<a href="./?day='.$day.'&doSomething=spreadSheet" >[download spreadsheet]</a>';
  echo '</div>';
echo '</div>';

echo "<p>";
echo '<pre>';
$statistics= new Statistics();

$statistics->renderWeek( $day );

echo '</pre>';
  

