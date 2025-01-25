<?php
//connection to the database
$host = '127.0.0.1';
$dbname = 'db_group_y3b';
$user = 'root';
$password = '';

//mysqli connect
$db = new mysqli($host, $user, $password, $dbname);

if ($db->connect_error) {
  die('Connection failed: ' . $db->connect_error);
}
