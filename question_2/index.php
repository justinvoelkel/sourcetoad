<?php
include '../data_structure.php';

/*
   Question 2

   Given the above example data structure again. Write a PHP function/method to sort the data structure based on a key OR keys regardless of what level it or they occurs with in the data structure ( i.e. sort by last_name **AND** sort by account_id ). **HINT**: Recursion is your friend.
*/


function get_nested_value($arr, $key)
{
    foreach($arr as $k => $v){
        // if the value is an array traverse the branch
        // NOTE: order matters here - we should always traverse a
        // subarray instead of returning. This is an opinionated decision
        // based on the use case.
        if(is_array($v)){
            // swallow recursion exceptions to continue traversal
            try {
                return get_nested_value($v, $key);
            }
            catch( Exception $e) {
                continue;
            }
        }

        if($k == $key){
            return $v;
        }
    }
    // if the key was not found either because it is not part
    // of the data structure or becuase the key's value was an array
    throw new Exception('Invalid nested key requested');
}

function deep_sort($arr, $keys = [], $direction = 'asc')
{
    //assure a passed sort direction is valid
    $accepted_directions = ['asc','desc'];
    if(!in_array($direction, $accepted_directions)){
        throw new Exception('Invalid sorting direction');
    }

    usort($arr, function($a, $b) use ($keys, $direction) {
        $a_vals = array_map(fn($k) => get_nested_value($a, $k), $keys);
        $b_vals = array_map(fn($k) => get_nested_value($b, $k), $keys);
        // set sort direction
        $vals = $direction == 'asc' ? [$a_vals, $b_vals] : [$b_vals, $a_vals];
        return $vals[0] <=> $vals[1];
    });
    return $arr;
}

$sorted = deep_sort($guests, ['last_name', 'account_id'], 'desc');
var_dump($sorted);
?>
