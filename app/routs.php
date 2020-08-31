<?php

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
  $r->addRoute('GET', '/', 'home');

  $r->addRoute('GET', '/telegram-webhook', 'telegram');
  $r->addRoute('POST', '/telegram-webhook', 'telegram');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
  $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
  case FastRoute\Dispatcher::NOT_FOUND:
  var_dump('not found');
  // ... 404 Not Found
  break;
  case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
  $allowedMethods = $routeInfo[1];
  var_dump($allowedMethods);
  // ... 405 Method Not Allowed
  break;
  case FastRoute\Dispatcher::FOUND:
  $handler = $routeInfo[1];
  $vars = $routeInfo[2];
  call_user_func_array($handler, $vars);
  // ... call $handler with $vars
  break;
}

function home(){
  runController('HomeController');
}

function telegram(){
  runController('TelegramController');
}
