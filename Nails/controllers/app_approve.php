<?php
	// Проверка авторизации
	include "admin_check.php";

	// Подключение подключения к базе
	include "../connect.php";

	// Получение данных
	$app_id = $_POST["app_id"];

	// Изменени заявки
	$sql = sprintf("UPDATE `order` SET `status`='Одобрена' WHERE `order_id`='%s'", $app_id);

	// В случае ошибки исполнения запроса
	if(!$connect->query($sql)) die("Error: ". $connect->error);

	// В случае успеха
	return header("Location:../admin.php?message=Заявка одобрена");