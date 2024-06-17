<?php
// Проверка авторизации
include "controllers/admin_check.php";

// Подключение подключения к базе
include "connect.php";

// Запрос на получение категорий
$sql = "SELECT * FROM `master`";
$result = $connect->query($sql);
$categories = "";
while($row = $result->fetch_assoc())
	$categories .= sprintf('<option value="%s">%s</option>', $row["master_id"], $row["master"]);

// Запрос на получение новых заявок
$sql = "SELECT a.*, u.fio AS user_name
        FROM `order` a
        JOIN `users` u ON a.user_id = u.user_id
        WHERE a.status = 'Новая' OR a.status = 'В процессе'
        ORDER BY a.created_at DESC";
$result = $connect->query($sql);
if(!$result) die("Error: ". $connect->error);
$new = "";
while($row = $result->fetch_assoc())
	$new .= sprintf('
		<div class="col">
			<h3>%s</h3>
			<p class="center">Статус заявки: <b>%s</b></p>
			<p>Категория заявки: <b>%s</b></p>
			<h3>Одобрение заявки</h3>
			<form action="controllers/app_approve.php" enctype="multipart/form-data" method="POST">
				<button value="%s" name="app_id">Одобрить</button>
			</form>
			<h3>Отклонение заявки</h3>
			<form action="controllers/app_reject.php" method="POST">
				<textarea name="rejection_reason" placeholder="Причина отклонения (до 256 символов)" required pattern=".{1,256}"></textarea>
				<button value="%s" name="app_id">Отклонить</button>
			</form>
			<p class="small">%s</p>
		</div>
	',
	$row["user_name"],
	$row["status"],
	$row["master"],
	$row["order_id"],
	$row["order_id"],
	$row["created_at"]);

// Запрос на получение одобренных заявок
// Запрос на получение новых заявок
$sql = "SELECT a.*, u.fio AS user_name
        FROM `order` a
        JOIN `users` u ON a.user_id = u.user_id
        WHERE `status`='Одобрена'
        ORDER BY a.created_at DESC";
$result = $connect->query($sql);
if(!$result) die("Error: ". $connect->error);
$approved = "";
while($row = $result->fetch_assoc())
	$approved .= sprintf('
		<div class="col">
			<h3>%s</h3>
			<p class="center">Статус заявки: <b>%s</b></p>
			<p>Категория заявки: <b>%s</b></p>
			<p class="small">%s</p>
		</div>
	', $row["user_name"], $row["status"], $row["master"], $row["created_at"]);

// Запрос на получение отклоненных заявок
$sql = "SELECT a.*, u.fio AS user_name
        FROM `order` a
        JOIN `users` u ON a.user_id = u.user_id
        WHERE `status`='Отклонена'
        ORDER BY a.created_at DESC";
$result = $connect->query($sql);
if(!$result) die("Error: ". $connect->error);
$rejected = "";
while($row = $result->fetch_assoc())
	$rejected .= sprintf('
		<div class="col">
			<h3>%s</h3>
			<p class="center">Статус заявки: <b>%s</b></p>
			<p>Категория заявки: <b>%s</b></p>
			<p class="center"><b>Причина отклонения:</b></p>
			<p class="small">%s</p>
		</div>
	', $row["user_name"], $row["status"], $row["master"], $row["rejection_reason"], $row["created_at"]);

// Подключение хедера
include "header.php";
?>

	<main>
		<div class="content">

			<div class="head">Мастера</div>
            <form action="controllers/category_add.php" enctype="multipart/form-data" method="POST">
                <div class="line">
                    <p class="left">Фотография мастера</p>
                    <input type="file" required name="image">
                    <input type="text" name="master" placeholder="Название" required pattern=".{1,64}">
                    <button>Добавить</button>
                </div>
            </form>
			<form action="controllers/category_delete.php">
				<div class="line">
					<select required name="master_id">
						<option value selected disabled>Категории</option>
						<?= $categories ?>
					</select>
					<button>Удалить</button>
				</div>
			</form>
			
			<div class="head">Новые заявки/ В процессе</div>
			<!-- Вывод новых заявок -->
			<div class="row"><?= $new ?></div>

			<div class="head">Одобренные заявки</div>
			<!-- Вывод одобренных заявок -->
			<div class="row"><?= $approved ?></div>

			<div class="head">Отклоненные заявки</div>
			<!-- Вывод отклоненных заявок -->
			<div class="row"><?= $rejected ?></div>

		</div>
	</main>

</body>
</html>