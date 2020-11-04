<?php
ob_start();
session_start();
$pageTitle = 'Log in';
if (!isset($_SESSION['user'])) {
	header("Location: index.php");
	die();	
}
include "init.php"; 
?>
<?php if(isset($_GET['id']) && $_GET['id'] == $_SESSION['uid']): ?>

<?php 

$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?");
$stmt->execute(array($_SESSION['uid']));
$userinfo = $stmt->fetch();
$img_profile = $userinfo['Image_Profile'];

?>
<div class="info-profile newItem editprofile">
	<h3 class="MainHeader">Edit My Profile</h3>
	<div class="container">
		<?php if(isset($msgSuc)) { Redirect($msgSuc, "index.php"); }?> 
		<div class="panel-container">
			<div class="panel panel-default mypanel">
				<div class="panel-heading">Edit My Profile</div>
				<div class="panel-body">

					<div class="add-form">
						<form class="add-box">
							<div class='info'>
								<div class="field">
									<label>User Name</label>
									<input type="text" name="username" value="<?php echo isset($userinfo['UserName']) ? $userinfo['UserName'] : ''; ?>" 
									class="required" required >
								<span class="span-notCorrect"><?php echo isset($formError['name']) ? $formError['name'] : "" ?></span>
								</div>
								<div class="field">
									<label>Email</label>
									<input type="text" name="email" value="<?php echo isset($userinfo['Email']) ? $userinfo['Email'] : ''; ?>"
										class="required" required  >
								<span class="span-notCorrect"><?php echo isset($formError['desc']) ? $formError['desc'] : "" ?></span>
								</div>
								<div class="field">
									<label>Full Name</label>
									<input type="text" name="fullname"  value="<?php echo isset($userinfo['FullName']) ? $userinfo['FullName'] : ''; ?>"
										class="required" required >
								<span class="span-notCorrect"><?php echo isset($formError['price']) ? $formError['price'] : "" ?></span>
								</div>
								<div class="field">
									<label>Old Password</label>
									<input type="password" name="oldpass">
								</div>
								<div class="field">
									<label>New Password</label>
									<input type="password" name="newpass" >
								</div>
								<div class="field">
									<label>Confirm New Password</label>
									<input type="password" name="repass" >
								</div>
								
							</div>
							<div class="img-edit">
								<div class="overlow"></div>
									<?php if (empty($img_profile)):?>
										<img src="files_upload/profileImg/images.png" class="profile-img">
									<?php else: ?>
										<img src="files_upload/profileImg/<?php echo $img_profile; ?>" class="profile-img">
									<?php endif;?>
								
							</div>
							<div class="clear"></div>
							<input type="submit" value="Edit Profile" class="btn editprofile-btn">
						</form>
								
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>




<?php
else:
header("location:index.php");
endif;

?>




<?php ob_end_flush(); ?>
<?php include $tpl ."footer.php"; ?>