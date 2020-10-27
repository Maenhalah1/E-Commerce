<?php 

function lang($phrase) {
	static $lang = array(

		// navbar page

		'Home_Admin' 	=> 'Home',
		'Categories' 	=> 'Categories',
		'Products'		=> 'Products',
		'Members'		=> 'Members',
		'Statistics'	=> 'Statistics',
		'Logs'			=> 'Logs',
		'Edit Profile' 	=> 'Edit Profile',
		'Settings'		=> 'Settings',
		'Logout'		=> 'Logout',
		'Comments'		=> 'Comments'

	);
	return $lang[$phrase];
}
