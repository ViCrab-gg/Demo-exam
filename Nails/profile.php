<?php
// Проверка авторизации пользователя
include "controllers/check.php";

// Подключение подключения к базе
include "connect.php";

// Получение id пользователя
$user_id = $_SESSION["user_id"];

// Запрос на получение запросов пользователя
$sql = sprintf("SELECT * FROM `order` WHERE `user_id`='%s' ORDER BY `created_at` DESC", $user_id);
// Отправка запроса в базу
$result = $connect->query($sql);
// Проверка на наличие ошибок в исполнении запроса
if(!$result) die("Error: ". $connect->error);
// Получение данных из результата запроса
$app = "";
while($row = $result->fetch_assoc()) {
    $do = ($row["status"] == "Новая") ?
        sprintf('<p class="small"><a onclick="return window.confirm(\'Вы действительно хотите удалить заявку?\')" href="controllers/app_delete.php?app_id=%s">Удалить заявку</a></p>', $row["order_id"])
        : "";
    $refusal = ($row["status"] == "Отклонена") ? sprintf('<p class="center"><b>Причина отклонения:</b></p><p>%s</p>', $row["rejection_reason"]) : "";
    $app .= sprintf('
        <div class="col">
            <p class="center">Статус заявки: <b>%s</b></p>
            <p>Категория заявки: <b>%s</b></p>

            %s%s
            <p class="small">%s</p>
        </div>
    ',  $row["status"], $row["category"],  $do, $refusal, $row["created_at"]);
}

// Запрос на получение категорий
$sql = "SELECT master FROM `master`";
$result = $connect->query($sql);
$masters = "";
while($row = $result->fetch_assoc())
    $masters .= sprintf('<option value="%s">%s</option>', $row["master"], $row["master"]);
// Подключение хедера
include "header.php";
?>

<main>
    <div class="content">

        <div class="head">Ваши записи</div>
        <p class="small">Все | Новые | Решённые | Отклонённые</p>
        <!-- Вывод данных запросов -->
        <div class="row"><?= $app ?></div>

        <div class="head">Записаться</div>
        <form action="controllers/app_add.php" method="POST">
            <input type="datetime-local" name="appointment_time" required>
            <select required name="category">
                <option value selected disabled>Мастер</option>
                <!-- Вывод категорий -->
                <?= $masters  ?>
            </select>
            <button>Записаться</button>
        </form>

    </div>
</main>

</body>
</html>