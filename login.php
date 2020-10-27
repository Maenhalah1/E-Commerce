<?php 
ob_start();
session_start();
$pageTitle = 'Log in';
if (isset($_SESSION['user'])) {
	header("Location: index.php");
	
}
include "init.php"; 
?>

<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST') {


	// if the request from login
	if(isset($_POST['login'])) {

		$username = $_POST['username'];
		$pass = sha1($_POST['password']);

		$stmt = $con->prepare("SELECT UserID, UserName, Password FROM users WHERE UserName = ? AND Password = ?");
		$stmt->execute(array($username, $pass));
		$count = $stmt->rowCount();
		$data = $stmt->fetch();
		if ($count > 0) {
			$_SESSION['user'] = $username;
			$_SESSION['uid'] = $data['UserID'];
			header("Location: index.php");
		}else {
			$notCorrect = "<span class='span-notCorrect'>User Name or Password are not Correct</span>";
		}


	}else{  // if the request from Register
		$RegErrors = array();
		$username = $_POST['username'];
		$Password = $_POST['password'];
		$RePassword = $_POST['re-password'];
		$email = $_POST['Email'];

	
		// user name Vaildation and Filtering
		if(isset($_POST['username'])) {
			$user = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
			if(empty($user)){
				$RegErrors['username'] = "<span class='span-notCorrect'>Please Type Your Username</span>";
			}
			elseif(strlen($user) < 3) {
				$RegErrors['username'] = "<span class='span-notCorrect'>Username Must be Larger than 3 Letters</span>";
			} else if (!preg_match("/^[a-zA-Z][0-9a-zA-Z]+$/", $user)) {
				$RegErrors['username'] = "<span class='span-notCorrect'>The first letter  Must be a Character </span>";
			} else if(checkElement('UserName', 'users', $username)) {
				$RegErrors['username'] = "<span class='span-notCorrect'>Sorry, Username Already Exists</span>";
			}
		}



		// Paswword Vaildation and Filtering
		if(isset($_POST['password']) && isset($_POST['re-password'])) {

			if(empty($_POST['password'])) {
				$RegErrors['password'] = "<span class='span-notCorrect'> Please Type Your Password</span>";
			}
			elseif ( sha1($_POST['password']) != sha1($_POST['re-password'])) {
				$RegErrors['password'] = "<span class='span-notCorrect'> Confirm Password Doesn't Match With Password</span>";
			}
		}



		// Email Vaildation and Filtering
		if(isset($_POST['Email'])) {
			$Email = filter_var($_POST['Email'], FILTER_SANITIZE_STRING);
			if(empty($Email)){
				$RegErrors['Email'] = "<span class='span-notCorrect'>Please Type Your Email</span>";
			}
			if(!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
				$RegErrors['Email'] = "<span class='span-notCorrect'>Sorry, The Email isn't Vaild</span>";
			} 
		}



		if(empty($RegErrors)) {
			$hashPass = sha1($Password);
			$stmt = $con->prepare("INSERT INTO users (UserName, Password, Email, TypeUser, AccepteAccount, RegDate)
				VALUES (:user, :pass, :email, 0, 0, now()) ");
			$stmt->bindParam(':user', $username);
			$stmt->bindParam(':pass', $hashPass);
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			$_SESSION['user'] = $username;
			$_SESSION['uid'] = returnElement("UserID", "users", "UserName", $username);
			$msgSucc = "<div class='SuccssReg'><i class='fa fa-check'></i>Successfully Registered</div>";
		}
	}
}
?> 


<div class="left-log">
	<img src="layout/images/login.svg" class="loginImg">
	<img src="layout/images/signup.svg" class="signupImg">
</div>
<div class="right-log">
	<div class="log-box">	
		<h3><span class="viewed" data-class='login'>Login</span> | <span data-class='signup' class="localReg">Register</span></h3>
		

		<!-- Login Form -->
		<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<i class="fa fa-user-circle-o logo-user" aria-hidden="true"></i>
			<input type="text" name="username" autocomplete="off" placeholder="User name" class="place"  required>
			<div class="pass-field">
				<input type="password" name="password" autocomplete="new-password" placeholder="Password" class="place pass">
				<i class="fa fa-eye showing" aria-hidden="true"></i>
			</div>
			<?php echo isset($notCorrect) ? $notCorrect : '';?>
			<input type="submit" value="Log in" name='login'>
		</form>


		<!-- Registar Form -->
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<i class="fa fa-user-o logo-user" aria-hidden="true"></i>
			<?php if(isset($msgSucc)){ Redirect($msgSucc, "home");} ?>
			<input type="text" name="username"  placeholder="Type User name" class="place" required>
			<?php echo isset($RegErrors['username']) ? $RegErrors['username'] : ''; ?>
			<input type="email" name="Email" placeholder="Type Valid Email" class="place" required>
			<?php echo isset($RegErrors['Email']) ? $RegErrors['Email'] : ''; ?>
			<div class="pass-field">
				<input type="password" name="password" autocomplete="new-password" placeholder="Password" class="place pass" required>
				<i class="fa fa-eye showing" aria-hidden="true"></i>
			</div>
			<div class="pass-field">
				<input type="password" name="re-password" autocomplete="new-password" placeholder="Confirm Password" class="place pass" required>
				<i class="fa fa-eye showing" aria-hidden="true"></i>
				<?php echo isset($RegErrors['password']) ? $RegErrors['password'] : ''; ?>
			</div>
			<input type="submit" value="Register" name = 'signup'>
		</form>
	</div>
</div>


<?php ob_end_flush(); ?>
<?php include $tpl ."footer.php"; ?>