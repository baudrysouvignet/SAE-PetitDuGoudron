<?php
require_once '../config/DatabaseManager.php';

$database = new DatabaseManager();
$event = $database->select(request: "SELECT * FROM Event where id = :id");

var_dump ($event);
?>