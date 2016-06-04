<?php


/*
 * this file is meant to check all pre-conditions to ensure a proper running application
 */



// check if the logging path is writeable
if (!is_writable("./logging/")){
  die("logging path is not writable. errorcode ".ERR_LOGGING_PATH_NOT_WRITEABLE);
}