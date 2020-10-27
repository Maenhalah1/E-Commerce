<?php 
session_start();
if(isset($_SESSION['user'])):
$pageTitle = 'Homepage';
$noSelectit="";
include "init.php"; 
?>

<?php 

$stmt = $con->prepare("SELECT * FROM users WHERE UserName = ?");
$stmt->execute(array($sessionUser));
$infoUser = $stmt->fetch();

?>

<div class="information info-profile">
	<h3 class="MainHeader">Profile</h3>
	<div class="container">
		<div class="panel-container">		
			<div class="panel panel-default mypanel">
				<div class="panel-heading">Personal Information</div>
				<div class="panel-body">
					<div class='left'>
						<?php if(!empty($infoUser['Image_Profile'])): ?>
							<img class='profile-img' src="files_upload/profileImg/<?php echo $infoUser['Image_Profile'];?>">
						<?php else:?>
							<img class='profile-img' src="files_upload/profileImg/images.png">
						<?php endif;?>
					</div>
					<div class="right">
						<ul class="UserInfo">
							<li>
								<i class="fa fa-unlock-alt fa-fw" aria-hidden="true"></i>
								<span class="head-UserInfo">Username : </span><?php echo $infoUser['UserName']; ?> </li>
							<li>
								<i class="fa fa-envelope fa-fw" aria-hidden="true"></i>
								<span class="head-UserInfo">Email : </span><?php echo $infoUser['Email']; ?> </br></li>
							<li>
								<i class="fa fa-user fa-fw" aria-hidden="true"></i>
								<span class="head-UserInfo">Full Name : </span><?php echo $infoUser['FullName']; ?></li> 
							<li>
								<i class="fa fa-calendar fa-fw" aria-hidden="true"></i>
								<span class="head-UserInfo">Register Date : </span><?php echo $infoUser['RegDate'];?></li>
							<li>
								<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>
								<span class="head-UserInfo">Activation : </span><?php if (CheckActiveUser($sessionUser)) 
							{ echo "<span style='color:#080;'>Active</span>";}else{
								echo "<span style='color:#F00;'>not Active</span>";
							} ?> </li>
							<li> 
								<i class="fa fa-tags fa-fw" aria-hidden="true"></i>
								<span class="head-UserInfo">Favourite Category : </span></li>
							<div class="edit">
								<a href="editprofile.php?id=<?php echo $_SESSION['uid']?>" class="btn">Edit Information</a>
							</div>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="advs info-profile">
	<div class="container">
		<div class="panel-container">
			<div class="panel panel-default mypanel">
				<div class="panel-heading">Advertisements and Products</div>
				<div class="panel-body">
					<div class="CatgItems">
						<?php 
						$allitems = getFromAll("*","items","WHERE Member_ID = {$infoUser['UserID']}","Add_Date");

						if (!empty($allitems)) {
							foreach ($allitems as $Item) {
								echo "<div class='ItemBox'>";
								if($Item['accepte'] != 1){echo "<div class='notactive'>not Active</div>";}
								echo "<span class='Mainprice'>$" . $Item['Price'] . "</span>";
								echo "<img src='https://via.placeholder.com/500/'>";
								echo "<h3><a href='item.php?id=" . $Item['items_ID'] . "'>" . $Item['Name'] . "</a></h3>";
								echo "<p>" . $Item['Description'] . "</p>";
								echo "<div class='date-ItemBox'>" . $Item['Add_Date'] . "</div>";
								echo "</div>";
							}
						} else {
							echo "<div class='noData'>Sorry, There isn't Items</div>";
						}

						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 

$stmtComments = $con->prepare("SELECT * FROM comments WHERE user_id = ?");
$stmtComments->execute(array($infoUser['UserID']));
$comments = $stmtComments->fetchAll();
?>
<div class="information info-profile">
	<div class="container">
		<div class="panel-container">
			<div class="panel panel-default mypanel">
				<div class="panel-heading">Latest Comments</div>
				<div class="panel-body">
					<?php if(!empty($comments)): ?>
						<?php foreach ($comments as $comment) {
							echo "<p>" . $comment['Comment'] . "</p>";
						}
						?>

					<?php else: ?>
						<?php echo "<div class='noData'>Sorry, There isn't Comments</div>";?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
else:
	header("Location: index.php");
endif;

?>
<?php include $tpl ."footer.php"; ?>