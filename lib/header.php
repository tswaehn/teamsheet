<?php


  define("HEADER_FOLDER", "./images/headers/");
  
  function filterImages( $list ){
    
    $outList= array();
    foreach ($list as $item ){
      if (preg_match("/.*\.(png|jpg|jpeg)/i", $item)){
        $outList[]= $item;
      }
      
    }
    
    return $outList;
    
  }
  
  $files= scandir(HEADER_FOLDER);
    
  $files= filterImages( $files );
  
  $count= count($files);

  $today= MyTime::timeToArray(time());
  $i= ($today["year"]+$today["mon"]+$today["mday"]) % $count;
  //$i= rand(0, $count-1);
  
  echo '<div id="header">';
    echo '<img src="'.HEADER_FOLDER.$files[$i].'"></img>';

    echo '<div id="gradient"></div>';
    echo '<div id="title">TeamSheet</div>';
    echo '<div id="timestamp">'.date("D d/M/y").'</div>';
  echo '</div>';