<?php
  include '../data_structure.php';

/*
   Question 1

   Given the following example data structure. Write a single function to print out its nested key value pairs at any level for easy display to the user.
*/

  function print_nested($arr, $indent = "")
  {
      foreach($arr as $key => $val) {
          echo $indent;
          // skip the numeric index of the top level array
          if(!is_int($key)){
              echo "$key: ";
          }
          // if the value is array call self and further indent
          if(is_array($val)){
              echo "\r\n";
              print_nested($val, $indent . "\t");
          } else {
              echo "$val\r\n";
          }
      }
  }

  print_nested($guests);
?>
