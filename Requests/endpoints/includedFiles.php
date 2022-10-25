<?php
require '../../Includes/config/Database.php';
require "../../Includes/TableClasses/User.php";
require "../../Includes/TableClasses/Shift.php";
require "../../Includes/TableClasses/Fuel.php";
require "../../Includes/TableClasses/MeterReadings.php";

include_once '../../Includes/TableFunctions/Handler.php';

$database = new Database();
$db = $database->getConnection();
