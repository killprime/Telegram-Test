<?php
use Telegram\Bot\Api;

$token = "1177246455:AAGraAdQUOBQm0n7LBgSyrvdXidz0jW-G6Y";

$telegram = new Api($token);

$updates = $telegram->getWebhookUpdates()['message'];

$swapCharacters = swapCharacters($updates['text']);
$textResponse = ($swapCharacters) ? $swapCharacters : $updates['text'];

$response = $telegram->sendMessage([
  'chat_id' => $updates['chat']['id'],
  'text' => $textResponse
]);

function swapCharacters($text){
  if(empty($text) || strlen($text) < 1)
  {
    return false;
  }
  $i = 0;
  $arrayCharacters = preg_split('//u',$text,-1,PREG_SPLIT_NO_EMPTY);
  $result = '';
  while($i < count($arrayCharacters))
  {
    $result .= (isset($arrayCharacters[$i+1])) ? $arrayCharacters[$i+1].$arrayCharacters[$i] : $arrayCharacters[$i];
    $i += 2;
  }
  return $result;
}
