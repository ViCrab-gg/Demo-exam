<?php
$connect = new mysqli("localhost", "root", "", "nails");
$connect->set_charset("utf-8");
if($connect->connect_error)
	die("Connection error: ". $connect->connect_error);