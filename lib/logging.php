<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define("LOG_FILE", "./logging/standard.log");
define("ERROR_FILE", "./logging/errors.log");
define("REPORT_FILE", "./logging/report.log");
define("DEBUG_FILE", "./logging/debug.log");

  // global storage for back trace
  $errorBackTrace= "";
  

function initLogging(){
  
  logInit();
  errorInit();
  reportInit();
  debugInit();
  
}
  
function logInit(){
  file_put_contents( LOG_FILE, "\n---".date("r")."\n" );
}
function errorInit(){
  file_put_contents( ERROR_FILE, "\n---".date("r")."\n" );
}

function reportInit(){
  file_put_contents( REPORT_FILE, date("r")."\n" );
}

function debugInit(){
  file_put_contents( DEBUG_FILE, "\n---".date("r")."\n" );
}


/*
 * this function describes the current step that is beeing
 * executed.
 * in case of an error this backtrace will be logged to file
 */
function backTrace( $text ){
  global $errorBackTrace;

  $errorBackTrace= $text;  
  lg($text);
}

function lg( $text ){
  $text .= "\n";
  
  echo $text;
  
  file_put_contents( LOG_FILE, $text, FILE_APPEND );
} 

function error( $function, $message ){
  global $errorBackTrace;
  
  $line= $errorBackTrace."\n";
  $line.= $function.">".$message."\n";
  
  lg($line);
  file_put_contents( ERROR_FILE, $line, FILE_APPEND );
  
}

function report( $text ){
  
  $text.= "\n";
  lg($text);
  file_put_contents( REPORT_FILE, $text, FILE_APPEND );
}

function debug( $text ){
  
  $text.= "\n";
  file_put_contents( DEBUG_FILE, $text, FILE_APPEND );
}
