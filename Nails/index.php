<?php
session_start();
// Подключение подключения к базе
include "connect.php";

// Получение количества одобренных заявок
$count = $connect->query("SELECT COUNT(*) FROM `master`")->fetch_array()[0];

// Составление  запроса на получение одобренных заявок
$sql = "SELECT * FROM `master`";
// Отправка запроса в базу
$result = $connect->query($sql);
// Проверка на наличие ошибок в исполнении запроса
if(!$result) die("Error: ". $connect->error);
// Получение данных из результата запроса
$app = "";
while($row = $result->fetch_assoc())
	$app .= sprintf('
		<div class="col">
			<img src="%s">
			<h3>%s</h3>
		</div>
	', $row["image_path"], $row["master"]);

// Подключение хедера
include "header.php";
?>

	<main>
		<div class="content">
			
			<div class="head">Мастера</div>
			<p class="small">Количество мастеров - <?= $count ?></p>
			<!-- Вывод данных запроса -->
			<div class="row"><?= $app ?></div>

			<div class="head" id="register">Регистрация</div>
			<form action="controllers/register.php" method="POST">
				<input type="text" name="fio" placeholder="ФИО (кириллица, дефис, пробел, до 32 символов)" pattern="[а-яА-ЯёЁ\-\s]{1,32}" required>
				<input type="text" name="login" placeholder="Логин (латиница, до 16 символов)" pattern="[a-zA-Z]{1,16}" required>
				<input type="email" name="email" pattern=".{1,32}" placeholder="Email (наличие @, до 32 символов)" required>
				<input type="phone" name="phone" pattern=".{11,19}" placeholder="8(XXX)-XXX-XX-XX" required>
				<input type="carlicense" name="carlicense" pattern=".{1,100}" placeholder="Номер водительского удостоверения" required>
				<input type="password" name="password" pattern=".{1,32}" placeholder="Пароль (до 32 символов)" required>
				<input type="password" name="password_check" placeholder="Повтор пароля" required>
				<div class="line">
					<input type="checkbox" required>
					<p>Согласие на обработку персональных данных</p>
				</div>
				<button>Зарегистрироваться</button>
			</form>

			<div class="head" id="login">Войти</div>
			<form action="controllers/login.php" method="POST">
				<input type="text" required name="login" placeholder="Введите логин или почту">
				<input type="password" required name="password" placeholder="Пароль">
				<button>Войти</button>
			</form>

		</div>
	</main>

</body>
</html>