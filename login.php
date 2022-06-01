<!DOCTYPE html>
<html lang="ru">
  <head>
      <meta charset="utf-8"/>
      <title>Задание 5 авторизация</title>
    <style>
    .error {
	border: 2px solid red;
	}
    </style>
    </head>
  <body>
<h2>Введите логин и пароль, чтобы авторизоваться:</h2>
<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
  session_destroy();
  header('Location: ./');
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['nologin']))
    print("<div>Пользователя с таким логином не существует</div>");
  if (!empty($_GET['wrongpass']))
    print("<div>Неверный пароль!</div>");
?>
    <form action="" method="POST">
      <input type="text" name="login" placeholder="логин"/>
      <input type="text" name="pass" placeholder="пароль"/>
      <input type="submit" name="submit" id="submit" value="Войти" />
    </form>
    <?php
}
    else{
      $db = new PDO('mysql:host=localhost;dbname=u47478', 'u47478', '2559767', array(PDO::ATTR_PERSISTENT => true));
  $stmt1 = $db->prepare("SELECT id, pass FROM login_pass WHERE login = ?");
  $stmt1 -> execute([$_POST['login']]);
  $row = $stmt1->fetch(PDO::FETCH_ASSOC);
  if (!$row) {
    header('Location: ?nologin=1');
    exit();
  }
  if($row['pass'] != md5($_POST['pass'])) {
    header('Location: ?wrongpass=1');
    exit();
  }
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  // Записываем ID пользователя.
  $_SESSION['uid'] = $row["id"];

  // Делаем перенаправление.
  header('Location: ./');
}

?>

</body>
</html>