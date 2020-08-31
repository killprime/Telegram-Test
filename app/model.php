<?php

function getRowsByPaginate($table, $page, $rows = 5, $sort = null, $search = null, $where = null){
  global $db;
  // Сколько записей выводим $rows
  // Текущая страница
  $currentPage = $page;

  $stringSql = '';

  // Сколько всего записей в бд
  if($search)
  {
    $columns = $db->query("SHOW COLUMNS FROM `$table`")->fetch_assoc_array();
    $stringSql = "WHERE ";
    foreach ($columns as $key => $column) {
      if($key == 0 && $where != null)
      {
        $stringSql .= " $where  OR ";
      }
      if($column['Type'] != 'date')
      {
        $stringSql .= " `$column[Field]` LIKE '%$search%' ";
        if($columns[$key] != end($columns))
        {
          $stringSql .= " OR ";
        }
      }

    }
    $posts = $db->query("SELECT COUNT(*) FROM `$table` $stringSql")->fetch_row()[0];
  }else{
    if($where)
    {
      $stringSql .= "WHERE $where ";
    }
    $posts = $db->query("SELECT COUNT(*) FROM `$table` $stringSql")->fetch_row()[0];
  }
  // Сколько получится страниц
  $total = intval(($posts - 1) / $rows) + 1;

  if(empty($currentPage) or $currentPage < 0) $currentPage = 1;
  if($currentPage > $total) $currentPage = $total;
  // С какой записи выводим данные
  $start = $currentPage * $rows - $rows;
  // Делаем выборку записей
  if($sort && $search == null)
  {
    $result = $db->query("SELECT * FROM `$table` $stringSql ORDER BY `$sort[0]` $sort[1] LIMIT $start, $rows")->fetch_assoc_array();
  }elseif ($search && $sort == null) {
    $result = $db->query("SELECT * FROM `$table` $stringSql LIMIT $start, $rows")->fetch_assoc_array();
  }elseif ($search && $sort) {
    $result = $db->query("SELECT * FROM `$table` $stringSql ORDER BY `$sort[0]` $sort[1] LIMIT $start, $rows")->fetch_assoc_array();
  }elseif ($search == null && $sort == null) {
    $result = $db->query("SELECT * FROM `$table` $stringSql LIMIT $start, $rows")->fetch_assoc_array();
  }
  return ['total' => $total, 'result' => $result];
}

function getRow($table, $where){
  global $db;
  return $db->query("SELECT * FROM `$table` WHERE `array_keys($where)[0]`= '$where[0]'")->fetch_assoc();
}

function getRowsByWhere($table, $where, $operator = 'AND'){
  global $db;

  $stringSql = '';
  $i = 0;
  foreach ($where as $key => $value) {
    $stringSql .= " `$key` = '$value' ";
    if($i != (count($where)-1))
    {
      $stringSql .= " $operator ";
    }
    $i++;
  }

  return $db->query("SELECT * FROM `$table` WHERE $stringSql")->fetch_assoc_array();
}

function addRow($table, $array)
{
  global $db;
  return array('result' => $db->query('INSERT INTO `$table` SET ?As', $array), 'id' => $db->getLastInsertId());
}

function deleteRow($table, $id){
  global $db;
  $result = $db->query("DELETE FROM `$table` WHERE `id` = ?i", $id);
  return $result;
}

function updateRow($table, $where, $array)
{
  global $db;
  return $db->query("UPDATE `$table` SET ?As WHERE `array_keys($where)[0]`= '$where[0]'", $event);
}

function getSetting($name)
{
  global $db;
  $result = $db->query("SELECT * FROM `settings` WHERE `name`= '?s'", $name);
  return $result->fetch_assoc_array();
}
