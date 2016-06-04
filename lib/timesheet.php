<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Timesheet {
  var $customers= array();
  var $projects= array();
  var $tasks= array();

  function __construct(){
    global $timesheetTable;
    
    // update all lists
    $this->customers= $timesheetTable->getCustomers();
    $this->projects= $timesheetTable->getProjects();
    $this->tasks= $timesheetTable->getTasks();

    $this->script();
    
  }
  
  function script(){
    
    echo' <SCRIPT language="javascript">
      
        function reloadPage( value ){

          document.tableForm.actionType.value = value;
          document.getElementById("myForm").submit();
          //document.tableForm.submit();
        }
        function editMode(  ){

          document.tableForm.mode.value = "edit";
          document.getElementById("myForm").submit();
          
        }
        function resetPage(  ){

          document.tableForm.mode.value = "view";
          document.getElementById("myForm").submit();
          
        }

        function jumpToDay( day ){

          document.tableForm.mode.value = "view";
          document.tableForm.day.value = day;
          document.getElementById("myForm").submit();
          
        }

  $(function() {
    $( document ).tooltip();
  });
  
$( document ).tooltip({
    content: function() {
        return $(this).attr(\'title\')+\'hello\';
    }
});
  
        function addRow(tableID) {

            var table = document.getElementById(tableID);

            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);

            var cell1 = row.insertCell(0);
            var element1 = document.createElement("input");
            element1.type = "checkbox";
            element1.name="chkbox[]";
            cell1.appendChild(element1);

            var cell2 = row.insertCell(1);
            cell2.innerHTML = rowCount + 1;

            var cell3 = row.insertCell(2);
            var element2 = document.createElement("input");
            element2.type = "text";
            element2.name = "txtbox[]";
            cell3.appendChild(element2);


        }
        
        function deleteRow(tableID) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;

            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }


            }
            }catch(e) {
                alert(e);
            }
        }


        </script>' ;
    
  }
  
  function renderCalenderView( $day, $mode ){
    global $timesheetTable;
    
    if ($mode== "edit"){
      return "";
    }
    
    $days= MyTime::getDaysOfMonth($day);

    
    // --- prepare the line for weekdays
    $dateRow="<tr>";
    foreach ($days as $timestamp=>$item){
      $class= "date";
      switch( date("N", $timestamp)){
        case 1:
        case 2:
        case 3:
        case 4:
        case 5: $class.=" weekday"; break;
        case 6:
        case 7: $class.=" weekend"; break;
      }
      if ($timestamp == $day){
        $class.= " today-cal";
      }
      $dateRow.= '<td> <a href="?day='.$timestamp.'" class="'.$class.'"> '.date("D",$timestamp).'<br>'.date("j",$timestamp).'</a> </td>';
    }
    $dateRow.="</tr>";

    // --- prepare the row for duration related data
    $statusRow="<tr>";
    foreach ($days as $timestamp=>$item){
      
      $duration= $timesheetTable->getSumDurationsForDay( $timestamp );
      $class= "duration";
      if ($timestamp == $day){
        $class.= " today-dur";
      }      
      if ($duration <= 0){
        $class.= " missing";
      } else if ($duration < 8) {
        $class.= " incomplete";
      } else if ($duration <= 10){
        $class.= " correct";
      } else {
        $class.= " wow";
      }
      $tooltip="first line<br>second line";
      $statusRow.= '<td ><a href="?day='.$timestamp.'" class="'.$class.'" >'.$duration.'</a> </td>';
    }
    $statusRow.="</tr>";
        
    // --- finally output 
    $out= "";
    $out.= '<table class="calendar">';
    $out.= $dateRow;
    $out.= $statusRow;
    $out.= '</table>';
    
    return $out;
  }
  
  
  function renderTableNavi($day, $mode ){

    if ($mode== "edit"){
      return "";
    }

    // --- prev / next calc
    $prev= MyTime::timeToArray($day);
    $prev["m"]= $prev["m"]-1;
    $prev= MyTime::arrayToTime($prev);
    $next= MyTime::timeToArray($day);
    $next["m"]= $next["m"]+1;
    $next= MyTime::arrayToTime($next);
    
    
    $out='';
    //$out.= '<h3>';
    $out.= '<input type="button" value="&lt;Prev" onclick="jumpToDay('.$prev.')" /> ';
    $out.= '<span id="table-navi">&nbsp;&nbsp;&nbsp;'.date("F Y", $day).'&nbsp;&nbsp;&nbsp;</span>';
    $out.= '<input type="button" value="Next&gt;" onclick="jumpToDay('.$next.')" />';
    //$out.= '</h3>';
    
    return $out;
  }
  
  /*
   * creates a drop down list with property called "name"
   * multiple selection options "list"
   * and on selected value "value"
   */
  function renderDropDown( $list, $name, $value ){
    
    $text='';
    $text.= '<select name="'.$name.'[]" size="1" style="width:130px;">';
    
    array_unshift( $list, "");
    foreach($list as $item ){
      if (strcasecmp($value, $item)==0){
        $selected= "selected";
      } else {
        $selected= "";
      }
      $text.= '<option '.$selected.' >'.$item.'</option>';
    }
    $text.= '</select>';
    
    return $text;
  }
  
  function renderNumber( $name, $value ){
    
    $text= '<input type="text" name="'.$name.'[]" value="'.$value.'" style="width:130px;text-align:left;">';
    return $text;
  }

  function renderText( $name, $value ){
    
    $text= '<input type="text" name="'.$name.'[]" value="'.$value.'" style="width:500px;text-align:left;">';
    return $text;
  }
  
  function renderWorkLine( $data, $mode ){
    
    $fields= array( "customer", "project", "task", "duration", "itemAction" );
    
    $line= "<tr>";
    foreach ($fields as $field){
      $line.= "<td>";
      if (!isset($data[$field])){
        $data[$field]= "";
      }
      if ($field=="itemAction"){
         $width="500px";
       } else {
         $width="130px";
       }
      if ($mode=="view"){
        $line.= '<input disabled type="edit" name="'.$field.'[]" value="'.$data[$field].'" style="width:'.$width.';" />'; // note: disabled fields will not be posted!!
        $line.= '<input type="hidden" name="'.$field.'[]" value="'.$data[$field].'" />';
      } else {
         switch ($field){
          case "customer": $line.= $this->renderDropDown( $this->customers, "customer", $data[$field] );break;
          case "project": $line.= $this->renderDropDown( $this->projects, "project", $data[$field] );break;
          case "task": $line.= $this->renderDropDown( $this->tasks, "task", $data[$field] );break;
          case "duration": $line.= $this->renderNumber( $field, $data[$field] ); break;
          case "itemAction": $line.= $this->renderText( $field, $data[$field] ); break;

          default:
              $line.= $data[$field];
        }
      }
      $line.= "</td>";
    }
    $line.= "</tr>";
    
    return $line;
  }
  
  
  
  function renderTable( $day, $data, $mode= "view" ){
    global $timesheetTable;
    
    // start the form
    echo '<form name="tableForm" id="myForm" method="post" action="./" >';
    echo '<input type="hidden" name="actionType" value="unknown">';
    echo '<input type="hidden" name="mode" value="'.$mode.'">';
    echo '<input type="hidden" name="day" value="'.$day.'" >';
    echo '<table id="timesheet-table">';

    // --- navi
    echo '<tr><td colspan="5">';
    echo $this->renderTableNavi($day, $mode);
    echo '</td></tr>';
    
    // --- top calender view
    echo '<tr><td colspan="5">';
    echo $this->renderCalenderView( $day, $mode );
    echo '</td></tr>';
    
    
    // --- date
    if ($mode=="edit"){
    echo '<tr><td colspan="5" id="tablehead">';
      echo 'Timesheet for '.date("D, d M Y", $day).'';
      echo '</td></tr>';
    }
    
    // --- table head
    echo '<tr> <th>Customer</th> <th>Project</th> <th>Task</th> <th>Duration [h]</th> <th>Action</th></tr>';
    
    // --- table rows
    foreach( $data as $line ){
      echo $this->renderWorkLine( $line, $mode );
    }
    if (getUrlParam("actionType")=="add_row_work"){
      echo $this->renderWorkLine( array(), $mode );
    }
    if ($mode == "edit"){
      echo '<tr><td colspan="5" id="work-button"><input type="button" value="Add Row" onclick="reloadPage(\'add_row_work\')"/> </td></tr>';
    }
        
    
    echo '</table>';
    if ($mode== "edit"){
      echo '<input type="submit" name="doSomething" value="save">';
      echo '<input type="submit" name="doSomething" value="cancel" onclick="resetPage()">';
    } else {
      echo '<input type="button" value="edit" onclick="editMode()">';
    }
    
    echo '</form>';
    
  }
  
  function importDataFromPost(){
    
    $tasks= getUrlParam( "task" );
    $customers= getUrlParam( "customer" );
    $projects= getUrlParam( "project" );
    
    $durations= getUrlParam("duration");
    $texts= getUrlParam("itemAction");

    
    echo "<p>";
    print_r($tasks);
    
    $work= array();
    if (!empty($tasks)){
      for ($i=0;$i<count($tasks);$i++){
        $line= array( "customer"=>$customers[$i], 
                      "project"=>$projects[$i], 
                      "task"=>$tasks[$i], 
                      "duration"=>$durations[$i],
                      "itemAction"=>$texts[$i]
                    );
        $work[]= $line;
      }
    }
    $data= $work;
    
    
    
    return $data;
  }
  
  function saveTableToDB( $data ){
    global $timesheetTable;
    global $user;
    
    echo "saving now!";
    
    $day= getUrlParam("day");
    $data= $this->importDataFromPost();
    
    $dbData= array();
    foreach( $data as $item ){
      $dbItem= array();
      print_r($item["customer"]);
      print_r($this->customers);
      
      $key= array_search( $item["customer"], $this->customers );
      $dbItem[]= $key;
      
      $key= array_search( $item["project"], $this->projects );
      $dbItem[]= $key;
      
      $key= array_search( $item["task"], $this->tasks );
      $dbItem[]= $key;
      
      $dbItem[]= $user->uid;
      
      $dbItem[]= MyTime::timestampToMySQL( $day );
      
      $dbItem[]= $item["duration"];
      
      $dbItem[]= $item["itemAction"];

      $dbData[]= $dbItem;
    }
    
    $timesheetTable->removeTimesheetItems( $user->uid, MyTime::timestampToMySQL($day) );
    $timesheetTable->saveTimesheetItem( $dbData ); 
  }
  
  function loadTableFromDB($day){
    global $timesheetTable;
    global $user;

    $data= $timesheetTable->getTimesheetItems( $user->uid, MyTime::timestampToMySQL($day) );
    
    if (empty($data)){
      $data[]= array();
    }
    
    return $data;
  }
  
  
}

