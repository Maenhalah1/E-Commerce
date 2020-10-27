<?php
session_start(); 
if (isset($_SESSION['Username'])){
	$pageTitle = "Profile";
	
	include "init.php";

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
?>

	<?php if ($do == 'Manage' || $do == 'Delete' || $do == 'Activate'): ?>


	<?php elseif ($do == "Add" || $do == "Insert"): ?>


	<?php elseif($do == 'Edit' || $do == 'Update'): ?>


<?php
endif;
}else{
	header("Location: index.php");
	exit(); 
}
?>


<?php include $tpl . "footer.php"; ?>