<?php

require PATH_APP.'/vendor/autoload.php';
use Krugozor\Database\Mysql\Mysql as Mysql;

$db = null;
if(IS_USE_BD == true)
{
  // Соединение с СУБД и получение объекта-"обертки" над "родным" mysqli
  $db = Mysql::create(DB_HOST, DB_LOGIN, DB_PASSWORD)
  // Выбор базы данных
  ->setDatabaseName(DB_NAME)
  // Выбор кодировки
  ->setCharset(DB_CHARSET);
}
