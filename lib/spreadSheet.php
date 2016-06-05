<?php

  function exportSpreadSheet(){
    global $user;
    global $dbTimesheet;
    global $day;
    
    define("SEP", ";");
    define("LE", "\r\n");

    // get all entries for the selected month
    $entries= $dbTimesheet->getEntriesForThisMonth($day);

    // set the export filename -- username - timesheet date - creation date
    $filename= "./cache/".$user->uname."_".date("Y-m-M",$day)."___created-".date("Y-m-d_H-i-s",time()).".csv";
    
    $content= array();

    // insert the header
    $header= implode(SEP, array("date", "week", "customer", "project", "task", "factor", "duration", "action")) . LE;
    $content[]= $header;
    
    // loop through each day
    foreach ($entries as $timestamp=>$timeSheetItems){
      $timeArr= MyTime::timeToArray($timestamp);
      $timeStr= $timeArr["mday"].".".$timeArr["mon"].".".$timeArr["year"].SEP.date("W", $timestamp);
      
      // loop through each item per day
      foreach( $timeSheetItems as $item){
        $line= $timeStr.SEP. implode(SEP, $item) .LE;
        $content[]= $line;
      }
      
    }

    // finally write the file
    file_put_contents($filename, $content);
  }
  
  