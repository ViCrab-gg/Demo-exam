<?php
	// Проверка авторизации
	include "admin_check.php";

	// Подключение подключения к базе
	include "../connect.php";

	// Проверка на наличие id категории
	if(!isset($_GET["master_id"]))
		return header("Location:../admin.php?message=Передайте id категории");

	// Получение id категории
	$master_id = $_GET["master_id"];

	// Получение категории
	$sql = sprintf("SELECT * FROM `master` WHERE `master_id`='%s'", $master_id);
	$result = $connect->query($sql);
	if(!$result) die("Error: ". $connect->error);
	if(!$master = $result->fetch_assoc())
		return header("Location:../admin.php?message=Категории не существует");

	// Удаление всех заявок, связанных с категорией
	$sql = sprintf("DELETE FROM `master` WHERE `master`='%s'", $master["master"]);
	// В случае ошибки исполнения запроса
	if(!$connect->query($sql))
		return header("Location:../admin.php?message=Ошибка удаления связанных заявок");

	// Запрос на удаление категории
	$sql = sprintf("DELETE FROM `master` WHERE `master_id`='%s'", $master["master_id"]);
	
	// В случае ошибки исполнения запроса
	if(!$connect->query($sql)) die("Error: ". $connect->error);

	// В случае успеха
	return header("Location:../admin.php?message=Категория вместе со связанными заявками была удалена");
