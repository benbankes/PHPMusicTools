<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

echo 'example 1';

include('happy_birthday.php');



$happy_birthday->transpose(3, -1);

echo '<pre>';
print_r($happy_birthday);
