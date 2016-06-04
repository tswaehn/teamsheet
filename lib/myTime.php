<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MyTime {
  
  /*
   * converts timestamp into array
   * 
   * input: timstamp int
   * return: timstamp array
   * 
   * Array
(
    [seconds] => 40
    [minutes] => 58
    [hours]   => 21
    [mday]    => 17
    [wday]    => 2
    [mon]     => 6
    [year]    => 2003
    [yday]    => 167
    [weekday] => Tuesday
    [month]   => June
    [0]       => 1055901520
)
   */
  static function timeToArray( $now ){
    $arr= getdate( $now );
    // add addtional "useful" values
    $arr["y"]= $arr["year"];
    $arr["m"]= $arr["mon"];
    $arr["d"]= $arr["mday"];

    $arr["hrs"]= $arr["hours"];
    $arr["min"]= $arr["minutes"];
    $arr["sec"]= $arr["seconds"];

    return $arr;
  }
  
  /*
   * convert an array into timestamp
   * 
   * input: timstamp array
   * return: timstamp int
   */
  static function arrayToTime( $arr ){
    
    $time= mktime($arr["hrs"], 
                  $arr["min"],
                  $arr["sec"],
                  $arr["m"],
                  $arr["d"],
                  $arr["y"]
            );
    return $time;
  }
  
  static function timestampToMySQL( $timestamp ){
    return gmdate( "Y-m-d 0:0:0", $timestamp );
  }
  
  /*
   * input timestamp
   * 
   * return timestamp
   * 
   */
  static function getFirstDayOfMonth( $now ){
    
    // analyze today
    $arr= MyTime::timeToArray( $now );
    
    // modify day to first of month
    $arr["d"]= 1;
    
    // convert back to timestamp
    $startOfMonth= MyTime::arrayToTime( $arr );

    return $startOfMonth;
  }
  
  static function getLastDayOfMonth( $now ){
    
    // analyze today
    $obj= new DateTime( $now );
    $year= $obj["y"];
    $month= $obj["m"];
    $day= $obj["d"];
    
    $nrOfDaysPerMonth= date("t", $now );
    
    $endOfMonthObj= new DateTime();
    $endOfMonthObj->setDate( $year, $month, $nrOfDaysPerMonth);
    
    return $endOfMonthObj;
  }
  
  
  static function getNrOfDaysPerMonth( $now ){
    $nrOfDaysPerMonth= date("t", $now );
    return $nrOfDaysPerMonth;
  }
  
  static function getDatesOfWeek( $week ){
    $startOfWeek= strtotime( "this monday" );
    $endOfWeek= strtotime("this sunday");
    
    $weekDayToday= date("N", $week);
    
  }
  
  /*
   * input: timestamp
   * 
   * return:
   */
  static function getDaysOfMonth( $now ){
    
    $startOfMonth= MyTime::getFirstDayOfMonth($now);
    $nrOfDaysPerMonth= MyTime::getNrOfDaysPerMonth($now);
    
    // prepare day
    $day= MyTime::timeToArray( $startOfMonth );
    
    $arr= array();
    for( $i=1;$i<=$nrOfDaysPerMonth;$i++){
      $day["d"]= $i;
      $dayTimestamp= MyTime::arrayToTime( $day );
      $arr[$dayTimestamp]= $i;
    }
    
    return $arr;
  }
  
  
}