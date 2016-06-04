<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Statistics {

  
  function renderWeek( $day ){
    global $timesheetTable;
    
    $week= date("W", $day);
    $data= $timesheetTable->getDurationsByDay( $week );
    
    $today= date("N", $day);
    
    $startOfWeek= $day - ($today-1)*3600*24;
    $endOfWeek= $day + (7-$today)*3600*24;
    
    echo date("r", $startOfWeek)."<br>";
    echo date("r", $endOfWeek)."<br>";
    
    print_r($data);
  }
  
  
}

