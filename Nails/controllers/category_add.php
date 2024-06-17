<?php
// Проверка авторизации
include "admin_check.php";

// Подключение к базе данных
include "../connect.php";

// Получение данных из формы
$master = trim($_POST["master"]);

// Проверка на наличие файла изображения
if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != UPLOAD_ERR_OK) {
    return header("Location:../admin.php?message=Ошибка загрузки изображения");
}

// Получение данных изображения
$size = getimagesize($_FILES["image"]["tmp_name"]);
$end = "";

// Проверка на расширение изображения
if ($size["mime"] == "image/png") {
    $end = ".png";
} elseif ($size["mime"] == "image/jpeg") {
    $end = ".jpg";
} elseif ($size["mime"] == "image/bmp") {
    $end = ".bmp";
} else {
    return header("Location:../admin.php?message=Файл не является изображением");
}

// Получение размера изображения
$filesize = filesize($_FILES["image"]["tmp_name"]);
// Перевод размера в МБ
$filesize = $filesize / (1024 * 1024);

// Проверка на размер изображения
if ($filesize > 2) {
    return header("Location:../admin.php?message=Изображение не должно превышать 2мб");
}

// Имя изображения
$image_name = "1_" . time() . "_" . rand() . $end;
// Путь до изображения
$path = "../images/After/" . $image_name;

// Перемещение изображения в папку
if (!move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
    return header("Location:../admin.php?message=Ошибка сохранения изображения");
}

// Путь для добавления в базу данных
$path_in_db = "images/After/" . $image_name;

// Добавление данных в базу
$sql = sprintf(
    "INSERT INTO `master` (`master`, `image_path`) VALUES ('%s', '%s')",
    $connect->real_escape_string($master),
    $connect->real_escape_string($path_in_db)
);

// В случае ошибки исполнения запроса
if (!$connect->query($sql)) {
    return header("Location:../admin.php?message=Ошибка добавления категории");
}

// В случае успеха
return header("Location:../admin.php?message=Категория добавлена");
?>