<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define("DB_VERSION", "1.0");


class dbTimesheetTable{
  
  var $customers= array();
  var $projects= array();
  var $tasks= array();
  
  function __construct() {
    $this->dbCheckTable();
   
  }
  
  function dbVersionOK(){
    $version= getConfigDb("dbVersion");
    
    $ret= false;
    
    // fresh database
    if (empty($version)){
      // write db
      setConfigDb("dbVersion", DB_VERSION);
      return true;
    }
    
    if (strcmp(DB_VERSION, $version) == 0 ){
      return true;
    } else {
      return false;
    }
  }
  
  function dbCheckTable(){

    if (!$this->dbVersionOK() ){
      die("migration of database needed - errorcode ".ERR_MIGRATION_OF_DATABASE_NEEDED);
    }
    
    // 
    if (!tableExists(DB_TIMESHEETS)){
      $fields= array( "tid", "customerID", "projectID", "taskID", "uid", "timestamp", "duration", "itemAction" );

      $fieldInfo= array();
      
      $fieldInfo["tid"]["type"]= INDEX;
      $fieldInfo["tid"]["size"]= 0;

      $fieldInfo["customerID"]["type"]= INT;
      $fieldInfo["customerID"]["size"]= 0;

      $fieldInfo["projectID"]["type"]= INT;
      $fieldInfo["projectID"]["size"]= 0;
      
      $fieldInfo["taskID"]["type"]= INT;
      $fieldInfo["taskID"]["size"]= 0;
      
      $fieldInfo["uid"]["type"]= INT;
      $fieldInfo["uid"]["size"]= 0;
      
      $fieldInfo["timestamp"]["type"]= TIMESTAMP;
      $fieldInfo["timestamp"]["size"]= 0;
      
      $fieldInfo["duration"]["type"]= FLOAT;
      $fieldInfo["duration"]["size"]= 0;
      
      $fieldInfo["itemAction"]["type"]= ASCII;
      $fieldInfo["itemAction"]["size"]= 200;
      
      
      // create a new table
      createTable( DB_TIMESHEETS, $fields, $fieldInfo );
      
    }

    // 
    if (!tableExists(DB_CUSTOMERS)){
      $fields= array( "customerID", "cname", "notes" );

      $fieldInfo= array();
      
      $fieldInfo["customerID"]["type"]= INDEX;
      $fieldInfo["customerID"]["size"]= 0;

      $fieldInfo["cname"]["type"]= ASCII;
      $fieldInfo["cname"]["size"]= 100;
      
      $fieldInfo["notes"]["type"]= ASCII;
      $fieldInfo["notes"]["size"]= 100;
      
      
      // create a new table
      createTable( DB_CUSTOMERS, $fields, $fieldInfo );

      // now add some customers
      $lines= array();
      $lines[]= array( "0", "", "none selected" );
      $lines[]= array( "", "Customer A", "" );
      $lines[]= array( "", "Customer B", "" );
      $lines[]= array( "", "Customer C", "" );
      $lines[]= array( "", "Customer D", "" );
      insertIntoTable( DB_CUSTOMERS, $fields, $lines );
      
    }

    // 
    if (!tableExists(DB_PROJECTS)){
      $fields= array( "projectID", "pname", "notes" );

      $fieldInfo= array();
      
      $fieldInfo["projectID"]["type"]= INDEX;
      $fieldInfo["projectID"]["size"]= 0;

      $fieldInfo["pname"]["type"]= ASCII;
      $fieldInfo["pname"]["size"]= 100;
      
      $fieldInfo["notes"]["type"]= ASCII;
      $fieldInfo["notes"]["size"]= 100;
      
      
      // create a new table
      createTable( DB_PROJECTS, $fields, $fieldInfo );

      // now add some projects
      $lines= array();
      $lines[]= array( "0", "", "none selected" );
      $lines[]= array( "", "Project1", "" );
      $lines[]= array( "", "Project2", "" );
      $lines[]= array( "", "Project3", "" );
      $lines[]= array( "", "Project4", "" );
      $lines[]= array( "", "Project5", "" );
      $lines[]= array( "", "Project6", "" );     
      insertIntoTable( DB_PROJECTS, $fields, $lines );
      
    }
    
    // 
    if (!tableExists(DB_TASKS)){
      $fields= array( "taskID", "tname", "factor", "notes" );

      $fieldInfo= array();
      
      $fieldInfo["taskID"]["type"]= INDEX;
      $fieldInfo["taskID"]["size"]= 0;

      $fieldInfo["tname"]["type"]= ASCII;
      $fieldInfo["tname"]["size"]= 100;
      
      $fieldInfo["factor"]["type"]= FLOAT;
      $fieldInfo["factor"]["size"]= 0;
      
      $fieldInfo["notes"]["type"]= ASCII;
      $fieldInfo["notes"]["size"]= 100;
      
      
      // create a new table
      createTable( DB_TASKS, $fields, $fieldInfo );

      // now add some tasks
      $lines= array();
      $lines[]= array( "0", "", 0, "none selected" );

      $lines[]= array( "100", "[vacation]", 1,"" );     
      $lines[]= array( "101", "[accumulated leave]", -1, "" );     
      
      $lines[]= array( "102", "[sick leave]", 1, "" );     
      $lines[]= array( "103", "[public holiday]", 1, "" );     
      $lines[]= array( "104", "[compensation]", -1, "" );     
      $lines[]= array( "105", "[sunday compensation]", -1, "" );     
      
      $lines[]= array( "1000", "onsite paid", 1, "" );
      $lines[]= array( "", "onsite goodwill", 1, "" );
      $lines[]= array( "", "onsite warranty", 1, "" );
      $lines[]= array( "", "installation", 1, "" );
      $lines[]= array( "", "telephone support", 1, "" );
      $lines[]= array( "", "email support", 1, "" );     
      
      
      insertIntoTable( DB_TASKS, $fields, $lines );

    }
  }
  
  function getCustomers(){
    
    $result= dbGetFromTable( DB_CUSTOMERS, "customerID,cname", "1", 5000 );
    
    $customers= array();
    foreach( $result as $item ){
      $customers[$item["customerID"]]= $item["cname"];
    }
    asort($customers);
    return $customers;
  }
  
  function getProjects(){
    
    $result= dbGetFromTable( DB_PROJECTS, "projectID,pname", "1", 5000 );
    
    $projects= array();
    foreach( $result as $item ){
      $projects[$item["projectID"]]= $item["pname"];
    }
    asort($projects);
    return $projects;
  }

  function getTasks(){
    
    $result= dbGetFromTable( DB_TASKS, "taskID,tname", "1", 5000 );
    
    $tasks= array();
    foreach( $result as $item ){
      $tasks[$item["taskID"]]= $item["tname"];
    }
    
    asort( $tasks );
    return $tasks;
  }
  
  /* this function stores some timesheet items 
   * 
   * input is an enumerated array where the fields are in the order
   *   "customerID", "projectID", "taskID", "uid", "timestamp", "duration", "itemAction"
   */
  function saveTimesheetItem( $lines ){
    lg( "saving" );
    lg( print_r($lines, true));
    
    $fields= array("customerID", "projectID", "taskID", "uid", "timestamp", "duration", "itemAction" );
    insertIntoTable( DB_TIMESHEETS, $fields, $lines);
  }
   
  function removeTimesheetItems( $uid, $timestamp ){
    
    $where= array();
    $where[]= array( "whereCol"=>"uid", "whereVal"=>$uid );
    $where[]= array( "whereCol"=>"timestamp", "whereVal"=>$timestamp );
    
    removeFromTable(DB_TIMESHEETS, $where );
    
  }
  
  function getTimesheetItems( $uid, $timestamp ){
    
    $customers= $this->getCustomers();
    $projects= $this->getProjects();
    $tasks= $this->getTasks();
    
    $fields= array("customerID", "projectID", "taskID", "uid", "timestamp", "duration", "itemAction" );
    $search= "`uid`='".$uid."' AND `timestamp`='".$timestamp."'";
    $result= dbGetFromTable( DB_TIMESHEETS, $fields, $search, 100 );
    
    $data= array();
    foreach ($result as $row){
      
      $line= array();
      $line["customer"]= $customers[$row["customerID"]];
      $line["project"]= $projects[$row["projectID"]];
      $line["task"]= $tasks[$row["taskID"]];
      $line["duration"]= $row["duration"];
      $line["itemAction"]= $row["itemAction"];
      
      $data[]= $line;
    }
    
    return $data;
  }
  
  
  
  function getSumDurationsForDay( $today ){
    global $user;
    
    $todayArr= MyTime::timeToArray($today);
    $dodayStr= $todayArr["y"].'-'.$todayArr["m"].'-'.$todayArr["d"];
    $sql= "SELECT sum(duration) AS durationPerDay FROM ".q(DB_TIMESHEETS)." WHERE `uid`=".$user->uid." AND timestamp BETWEEN '".$dodayStr." 00:00:00' AND '".$dodayStr." 23:59:59' ";
    $result= dbExecute($sql);
    
    if ($result->rowCount() == 1){
      $temp= $result->fetch();
      $durationPerDay= $temp["durationPerDay"];
    } else {
      $durationPerDay= 0;
    }
    
    if ($durationPerDay <= 0){
      $durationPerDay= "";
    }

    return $durationPerDay;
  }

  function getEntriesForThisMonth( $currentDay ){
    global $user;
    
    $currentDayArr= MyTime::timeToArray($currentDay);
    $daysOfMonth= MyTime::getNrOfDaysPerMonth($currentDay);
    
    $timeSheetItems= array();
    for ($i=1; $i<= $daysOfMonth; $i++){
      $day= $currentDayArr;
      $day["d"]= $i;
      
      $timestamp= MyTime::arrayToTime( $day );
      
      $array= $this->getTimesheetItems($user->uid, MyTime::timestampToMySQL($timestamp));
      
      $timeSheetItems[$timestamp]= $array;
    }
    
    
    return $timeSheetItems;
  }
  
  
  function getDurationsByDay( $week ){
    global $user;
    $data= array();
    
    $sql= "SELECT * FROM ".q(DB_TIMESHEETS)." WHERE uid=".$user->uid." AND week(timestamp)=".$week." GROUP BY day(timestamp) ORDER BY timestamp ASC";
    $result= dbExecute($sql);
    
    foreach($result as $item){
      $data[]= $item;
    }
    
    
    return $data;
  }
  
}
