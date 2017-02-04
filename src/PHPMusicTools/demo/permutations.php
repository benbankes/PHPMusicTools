<?php

// calculate all permutations of n tones,
// with all cyclic rotations removed

function pc_permute($items, $perms = array()) {
    if (empty($items)) { 
    	$return = array($perms);
    }  else {
    	$return = array();
        for ($i = count($items) - 1; $i >= 0; --$i) {
             $newitems = $items;
             $newperms = $perms;
             list($foo) = array_splice($newitems, $i, 1);
             array_unshift($newperms, $foo);
             $return = array_merge($return, pc_permute($newitems, $newperms));
         }
    }
    return $return;
}

$perms = pc_permute(array(0, 1, 2, 3, 4, 5));

// now remove any that are rotations

function remove_rotations($array) {

}

function chirality() {
	
}

function rotate_array($arr) {
	array_push($arr, array_shift($arr));
	return $arr;
}

print_r($perms);
