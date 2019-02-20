<?php
use App\Game;
require_once('Game.php');

$mode = php_sapi_name() == "cli" ? "cli" : "web";
$_POST = json_decode(file_get_contents("php://input"),true);

$game = new Game($_POST['current']??1);

if($mode === 'cli')
{
    $game->start($mode);
}
elseif($mode === 'web' && $_SERVER['REQUEST_METHOD'] === 'GET')
{
    include 'frontend.html';
}
elseif($mode === 'web' && $_SERVER['REQUEST_METHOD'] === 'POST')
{
    $result = $game->start($mode);
    echo json_encode($result);
}