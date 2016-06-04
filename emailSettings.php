<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailSettings {
  
  var $user;
  var $pass;
  var $from;
  var $host;
  var $port;
  
  function __construct( $user_, $pass_, $from_, $smtpHost_, $smtpPort_  ){
    $this->user= $user_;
    $this->pass= $pass_;
    $this->from= $from_;
    $this->host= $smtpHost_;
    $this->port= $smtpPort_;
  }
  
  
};

$emailSettings = new EmailSettings( 
            "", // user
            "",  // pass
            "", // reply email address
            "", // smptHost
            587   // smtpPort
            );
