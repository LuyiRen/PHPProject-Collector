#!/usr/bin/php
 <?php

 include '/app/etc/db.conf';
try{
  $conn = new mysqli(ENTER_SERVER, ENTER_USER, ENTER_PASSWORD, ENTER_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  //create queries
  $sql= "show status";
  $result = $conn->query($sql);
  $sql2 = "show variables";
  $result2 = $conn->query($sql2);

  //create array
  $bigarray = array();

  //from first sql selection, status
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $var = (string)$row["SQL_VARIABLE_NAME"];
      $newvar = clean_it_up($var);
      $num = (string)$row["SQL_VARIABLE_VALUE"];
      $bigarray[$newvar] = $num;
    }
  }
  // from second sql selection,
  if($result2->num_rows > 0){
    while($row = $result2->fetch_assoc()){
      $var = (string)$row["SQL_VARIABLE_NAME"];
      $newvar = clean_it_up($var);
      $num = (string)$row["SQL_VARIABLE_VALUE"];
      $bigarray[$newvar] = $num;
    }
  }

  //confirm it works
  echo json_encode($bigarray, JSON_PRETTY_PRINT);
}
catch(Exception $e){
  echo json_encode('Caught error: ', $e->getMessage(), '\n');
}
// function to make variables lowercase and underscore
function clean_it_up($a){
  $temp = explode("_", $a);
  $word ="";
  $count=0;
  foreach($temp as $element){
    $temp1 = strtolower($element[0]);
    $element[0] = $temp1;
    if($word==""){
      $word=$word.$element;
    }
    else{
    $word=$word."_".$element;
    }
  }
  return $word;
}
