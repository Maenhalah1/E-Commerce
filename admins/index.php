<?php 
session_start();
if (isset($_SESSION['Username'])){
	header("Location: dashboard.php");
}
$pageTitle = "Admin Login";
$span=""; // this span showing in page if the have a error in  
$noNavbar ="";
?>

<?php include "init.php"; ?>

<?php //check if data from form is exist in database and is the user is admin 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['username'];
	$pass = $_POST['pass'];
	$hashPass = sha1($pass); // hashed the passowrd 'Security'
	$data = $con->prepare('SELECT UserName, Password, TypeUser, UserID 
							From users 
							WHERE UserName = ? AND Password = ? 
							Limit 1');
	$data->execute(array($username, $hashPass));
	$count = $data->rowCount();

	if ($count > 0):      // if count bigger than 0 that mean the data founded in data base
		$rows = $data->fetch(PDO::FETCH_ASSOC);
		if ($rows['TypeUser'] == 1):			// check if the user is Admin
			$_SESSION['Username'] = $username;
			$_SESSION['userid'] = $rows['UserID'];
			header("Location: dashboard.php");
			exit();
		 else:
			$span = "<span class='span-notCorrect'>Sorry, You Are not Admin</span>";
		endif;
	
	else:
		$span = "<span class='span-notCorrect'>Username or Password is not correct</span>";
	endif;
}


?>


<form class="login" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<h2 class="text-center">Admin Login</h2>
	<input class="form-control place" type="text" name="username" placeholder="User Name" autocomplete="off">
	<input class="form-control place" type="password" name="pass" placeholder="Password" autocomplete="new-password">
	<?php echo $span ?>
	<input class="btn btn-primary btn-block" type="submit" value="Log in">
</form>

<?php include $tpl ."footer.php"; ?>