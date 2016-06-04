<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User {
  
  var $uid;
  var $uname;
  
  function __construct( $uname_, $pass_ ) {
    
    $this->uid=-1;
    $this->uname= "unknown";
    
    $this->dbCheckTable();
    
    $result= dbGetFromTable( DB_USER, "uid,uname", "uname=".dbQuote($uname_)." AND md5pass=".dbQuote($pass_) );
    
    if ($result->rowCount()==0){
      return;
    }
    
    $obj= $result->fetch(PDO::FETCH_OBJ);
    
    $this->uid= $obj->uid;
    $this->uname= $obj->uname;
    
  }
  
  function isValid(){
    if ($this->uid==-1){
      return false;
    } else {
      return true;
    }
  }
  
  function dbCheckTable(){
    
    // 
    if (!tableExists(DB_USER)){
      $fields= array( "uid", "uname", "md5pass", "admin", "email" );

      $fieldInfo= array();
      
      $fieldInfo["uid"]["type"]= INDEX;
      $fieldInfo["uid"]["size"]= 0;
      
      $fieldInfo["uname"]["type"]= ASCII;
      $fieldInfo["uname"]["size"]= 30;

      $fieldInfo["md5pass"]["type"]= ASCII;
      $fieldInfo["md5pass"]["size"]= 64;

      $fieldInfo["admin"]["type"]= INT;
      $fieldInfo["admin"]["size"]= 0;
      
      $fieldInfo["email"]["type"]= ASCII;
      $fieldInfo["email"]["size"]= 100;
      
      // create a new table
      createTable( DB_USER, $fields, $fieldInfo );
      
      // now add admin user
      $lines= array();
      $lines[]= array( "", "admin", md5("password"), 1, "admin@superuser.com" );
      insertIntoTable( DB_USER, $fields, $lines );
    }
    
  }
  
}