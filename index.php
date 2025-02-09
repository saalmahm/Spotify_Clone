<?php
session_start();
include_once "vendor/autoload.php";

$db = (new Database)->connect();
$router = new Router($db);
$router->run();
