<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$sessionUser = '';
if (isset($_SESSION['user'])) {
	$sessionUser = $_SESSION['user'];
}
include "connect.php";
$tpl = 'includes/templetes/';
$css = 'layout/css/';
$js = 'layout/js/';
$Urlimgs = 'layout/images/'; 

// Include the files
include "includes/languages/english.php";
include "includes/functions/functions.php";
include $tpl ."head.php";
if (!isset($noNavbar)) {
	include $tpl . "navbar.php";
}
include "lists.php";
?>