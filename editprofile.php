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

<div class="info-profile newItem">
	<h3 class="MainHeader">Edit My Profile</h3>
	<div class="container">
		<?php if(isset($msgSuc)) { Redirect($msgSuc, "index.php"); }?> 
		<div class="panel-container">
			<div class="panel panel-default mypanel">
				<div class="panel-heading">Edit My Profile</div>
				<div class="panel-body">
					<div class="add-form">
						<form class="add-box" action="newitem.php" method="POST">
							<div class="field">
								<label>User Name</label>
								<input type="text" name="name" data-class = '.head' 
									class="live required" required >
							<span class="span-notCorrect"><?php echo isset($formError['name']) ? $formError['name'] : "" ?></span>
							</div>
							<div class="field">
								<label>Email</label>
								<input type="text" name="desc" data-class = '.desc'
								 	class="live required" required >
							<span class="span-notCorrect"><?php echo isset($formError['desc']) ? $formError['desc'] : "" ?></span>
							</div>
							<div class="field">
								<label>Full Name</label>
								<input type="text" name="price" data-class = '.price' 
									class="live required" required >
							<span class="span-notCorrect"><?php echo isset($formError['price']) ? $formError['price'] : "" ?></span>
							</div>
							<div class="field">
								<label>Old Password</label>
								<input type="text" name="tags">
							</div>
							<div class="field">
								<label>New Password</label>
								<input type="text" name="tags" >
							</div>
							<div class="field">
								<label>Confirm New Password</label>
								<input type="text" name="tags" >
							</div>

							<input type="submit" value="Edit Profile" class="btn ">
							
						</form>		
					</div>
					<div class="show-product">
						
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>




<?php
else:

endif;

?>




<?php ob_end_flush(); ?>
<?php include $tpl ."footer.php"; ?>