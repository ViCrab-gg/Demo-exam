<?php
// Проверка авторизации
include "check.php";

// Подключение к базе данных
include "../connect.php";

// Получение id пользователя
$user_id = $_SESSION["user_id"];

// Получение данных из формы
$appointment_time = $_POST["appointment_time"];
$master = trim($_POST["category"]);

// Преобразование времени записи в формат, подходящий для сравнения
$appointment_datetime = new DateTime($appointment_time);

// Получение часов из времени записи
$hour = (int) $appointment_datetime->format('H');

// Проверка времени записи (не раньше 8 утра и не позже 6 вечера)
if ($hour < 8 || $hour >= 18) {
    return header("Location:../profile.php?message=Можно записаться только с 8:00 до 18:00");
}

// Форматирование времени для использования в SQL запросе
$formatted_time = $appointment_datetime->format('Y-m-d H:i:s');

// Проверка на существующую запись в течение 1 часа до и после указанного времени
$sql = sprintf("SELECT * FROM `order` WHERE `master`='%s' AND
                (`appointment_time` BETWEEN DATE_SUB('%s', INTERVAL 1 HOUR)
                AND DATE_ADD('%s', INTERVAL 1 HOUR))",
    $connect->real_escape_string($master),
    $connect->real_escape_string($formatted_time),
    $connect->real_escape_string($formatted_time)
);

$result = $connect->query($sql);
if ($result->num_rows > 0) {
    // Если запись уже существует
    return header("Location:../profile.php?message=Запись с выбранной датой и временем уже существует");
}

// Добавление данных в базу
$sql = sprintf("INSERT INTO `order`(`user_id`, `appointment_time`, `master`, `status`) VALUES('%s', '%s', '%s', 'Новая')",
    $user_id,
    $connect->real_escape_string($formatted_time),
    $connect->real_escape_string($master)
);

// В случае ошибки исполнения запроса
if (!$connect->query($sql)) die("Error: " . $connect->error);

// В случае успеха
return header("Location:../profile.php?message=Запись создана");
?>