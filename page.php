<?php 

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

	if ($do == 'Add') {
		echo "Welcome to Add Page ";
	} elseif($do = 'Manage') {
		echo "Welcome To Manage Page";
	} elseif ($do == "Delete") {
		echo "Welcome To Delete Page";
	} else {
		$do = "Manage";
		echo "Welcome To Manage Page";
	}