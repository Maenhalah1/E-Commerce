<?php 
include "connect.php";
$tpl = 'includes/templetes/';
$css = 'layout/css/';
$js = 'layout/js/';

// Include the files
include "includes/languages/english.php";
include "includes/functions/functions.php";
include $tpl ."head.php";
if (!isset($noNavbar)) {
	include $tpl . "navbar.php";
}
include "lists.php";
?>