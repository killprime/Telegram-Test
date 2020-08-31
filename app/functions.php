<?php

function render($tmp,$vars = array(),$custom = false) {
  $path = ($custom) ? 'templates/'.$tmp : 'templates/pages/'.$tmp.'.blade.php';
  // var_dump($path);
  if(file_exists($path)) {
    ob_start();
    extract($vars);
    require $path;
    return ob_get_clean();
  }
}

function view($page, $vars = array())
{
  $body = render($page, $vars);
  $scripts = getScripts();
  $headerScripts = render('assets/header.blade.php', array('data'=>$scripts, 'page'=>$page), true);
  $footerScripts = render('assets/footer.blade.php', array('data'=>$scripts, 'page'=>$page), true);
  $title = ($vars['title']) ? $vars['title'] : 'Моё новое приложение';
  echo render('layouts/app.blade.php', array('body'=>$body, 'headerScripts'=>$headerScripts, 'footerScripts'=>$footerScripts, 'title'=>$title), true);
}

function saveFile($upload_path, $files)
{
  $return_name = basename($files["name"]);
  $target_file = $upload_path . $return_name;
  // Check if file already exists
  if (file_exists($target_file)) {
    while (file_exists($target_file)) {
      $extension = pathinfo($target_file, PATHINFO_EXTENSION);
      $return_name = $return_name . rand(0,9999999) . '.' . $extension;
      $target_file = $upload_path . $return_name;
    }
  }
  // Check file size
  if ($files["size"] > 500000) {
    echo "Файл слишком большой.";
    return false;
  }
  //$_FILES["fileToUpload"]
  if (move_uploaded_file($files["tmp_name"], $target_file)) {
    return $return_name;
  } else {
    // echo "Произошла ошибка загрузки файла";
    return false;
  }
}

function getScripts(){
  $scripts['assets_styles'][]  = array('url'=>'/assets/css/bootstrap.css','version'=>'1.0.0','header'=>true, 'page'=>false);
  $scripts['assets_styles'][]  = array('url'=>'/assets/css/main.css','version'=>'1.0.0','header'=>true, 'page'=>false);

  $scripts['assets_scripts'][] = array('url'=>'/assets/js/jquery.js','version'=>'1.0.0','header'=>false, 'page'=>false);
  $scripts['assets_scripts'][] = array('url'=>'/assets/js/bootstrap.min.js','version'=>'1.0.0','header'=>false, 'page'=>false);
  $scripts['assets_scripts'][] = array('url'=>'/assets/js/main.js','version'=>'1.0.0','header'=>false, 'page'=>false);

  return $scripts;
}

function navPaginate($page, $total, $link){
  $disabledPrev = "disabled";
  $disabledNext = "disabled";
  // Проверяем нужны ли стрелки назад
  if ($page != 1){
    $disabledPrev = "";
  }
  // Проверяем нужны ли стрелки вперед
  if ($page != $total){
    $disabledNext = "";
  }
  $pervpage = '<li class="page-item '.$disabledPrev.'"><a class="page-link" href='.$link.'?page=1><<</a></li>
                                <li class="page-item '.$disabledPrev.'"><a class="page-link" href='.$link.'?page='. ($page - 1) .'><</a></li>';
  $nextpage = '<li class="page-item '.$disabledNext.'"><a class="page-link" href='.$link.'?page='. ($page + 1) .'>></a></li>
                                     <li class="page-item '.$disabledNext.'"><a class="page-link" href='.$link.'?page=' .$total. '>>></a></li>';
  // Находим две ближайшие станицы с обоих краев, если они есть
  if($page - 2 > 0) $page2left = '<li class="page-item"><a class="page-link" href='.$link.'?page='. ($page - 2) .'>'. ($page - 2) .'</a></li>';
  if($page - 1 > 0) $page1left = '<li class="page-item"><a class="page-link" href='.$link.'?page='. ($page - 1) .'>'. ($page - 1) .'</a></li>';
  if($page + 2 <= $total) $page2right = '<li class="page-item"><a class="page-link" href='.$link.'?page='. ($page + 2) .'>'. ($page + 2) .'</a></li>';
  if($page + 1 <= $total) $page1right = '<li class="page-item"><a class="page-link" href='.$link.'?page='. ($page + 1) .'>'. ($page + 1) .'</a></li>';
  $curLinkPage = '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
  // Вывод меню
  return $pervpage.$page2left.$page1left.$curLinkPage.$page1right.$page2right.$nextpage;
}

function runController($name){
  require_once(PATH_APP.'/app/Controllers/'.$name.'.php');
}

function redirect($link){
  return header("Location: $link", true, 301);
}

function getSiteUrl(){
  return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
}
