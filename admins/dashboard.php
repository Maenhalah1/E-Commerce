<?php
ob_start();
session_start(); 
if (isset($_SESSION['Username'])){
	$pageTitle = "Dashboard";
	include "init.php";
	/* Start Dashboard page*/

	?>
	<?php 
	//Latest users
	$limituser = 4;
	$latestUsers = getLatest("*", "users", "RegDate", $limituser);  
	//Latest products
	$limitItems = 4;
	$latestItems = getLatest("*", "items", "items_ID", $limitItems); 

	$limitComments = 4;
	$stmt = $con->prepare("SELECT comments.*, users.UserName AS user_name , items.Name AS item_name FROM comments
							INNER JOIN users ON comments.user_id = users.UserID 
							INNER JOIN items ON comments.item_id = items.items_ID
							ORDER BY Comment_Date DESC Limit $limitComments");
	$stmt->execute();
	$latestComments = $stmt->fetchAll();

	?>
	<h1 class="mainheader">Dashboard</h1>
	<div class="status">
		<div class="container">
			<div class="col-md-3">
				<div class="status-ele st-members">
					<i class="fa fa-users"></i>
					<div class="info">
							Total Members
						<span><a href="members.php">
							<?php echo countItem('UserID', 'users', 'TypeUser', '0');?></a>
						</span>
					</div>
				</div>

			</div>
			<div class="col-md-3">
				<div class="status-ele st-pending">
					<i class="fa fa-user-plus" aria-hidden="true"></i>
					<div class="info">
							Pending Members
						<span><a href="members.php?do=Manage&page=pending">
							<?php echo countItem('UserID', 'users', 'AccepteAccount', '0')?>
						</a></span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="status-ele st-items">
					<i class="fa fa-tags" aria-hidden="true"></i>
					<div class="info">
						Total Products
					<span><a href="items.php">
						<?php echo countItem('items_ID', 'items');?>
						</a></span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="status-ele st-comments">
					<i class="fa fa-comments" aria-hidden="true"></i>
					<div class="info">
						Total Comments
					<span><a href="comments.php">
							<?php echo countItem('Comment_ID', 'comments')?>
						</a></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="lastest">
		<div class="container">
			<div class="row">
				<!-- Start letast users -->
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-users"></i>
							 Latest <?php echo $limituser; ?> Registerd Users
							 <span class="toggle-latest pull-right">
								<i class="fa fa-plus-circle fa-lg"></i></span>
							
						</div>
						<div class="panel-body">
							<?php 
							if (!empty($latestUsers)){
									echo "<ul class='latest-item'>";
									foreach ($latestUsers as $user) {
										echo "<li>";
											echo "<span class='latest-user'>" . $user['UserName'] . "</span>";
											echo "<a href='members.php?do=EditMember&id=" . $user['UserID'] . "'>";
												echo "<span class='btn btn-success pull-right'>";
													echo "<i class='fa fa-pencil-square-o'></i> Edit";
												echo "</span>";
											echo "</a>";
											if ($user['AccepteAccount'] == 0) {
												echo "<a href='members.php?do=Activate&id=". $user['UserID'] ."'>";
													echo "<span class='btn btn-info pull-right'>";
														echo "<i class='fa fa-check-circlae'></i> Activate";
													echo "</span>";
												echo "</a>";
											}
										echo "</li>";
									}
									echo "</ul>";
								}else {
									echo "<div class='noData'>There isn't users</div>";
								}
							?>
						</div>
					</div>
				</div>
				<!-- End letast users -->
				<!-- Start letast products -->
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-tag"></i> Latest <?php echo $limitItems;?> Products
							<span class="toggle-latest pull-right">
								<i class="fa fa-plus-circle fa-lg"></i></span>
						</div>
						<div class="panel-body">
								<?php 
								if (!empty($latestItems)){
									echo "<ul class='latest-item'>";
									foreach ($latestItems as $item) {
										echo "<li>";
											echo "<span class='latest-user'>" . $item['Name'] . "</span>";
											echo "<a href='items.php?do=Edit&id=" . $item['items_ID'] . "'>";
												echo "<span class='btn btn-success pull-right'>";
													echo "<i class='fa fa-pencil-square-o'></i> Edit";
												echo "</span>";
											echo "</a>";
											if ($item['accepte'] == 0) {
												echo "<a href='items.php?do=accepte&id=". $item['items_ID'] ."'>";
													echo "<span class='btn btn-info pull-right'>";
														echo "<i class='fa fa-check-circle'></i> Accepte";
													echo "</span>";
												echo "</a>";
											}
										echo "</li>";
									}
									echo "</ul>";
								}else {
									echo "<div class='noData'>There isn't Products</div>";
								}
							?>
						</div>
					</div>
				</div>
				<!-- End letast products -->

				<!-- Start letast comments -->

				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-comments"></i> Latest <?php echo $limitComments;?> Comments
							<span class="toggle-latest pull-right">
								<i class="fa fa-plus-circle fa-lg"></i></span>
						</div>
						<div class="panel-body">
							<?php
								if (!empty($latestComments)){
									foreach ($latestComments as $comment){
										echo "<div class='comment-box'>";
										echo "<span class='head-comment'>";
											echo "<span class='name-commenting'><a href='members.php?do=EditMember&id=" . $comment['user_id'] . "'>" . $comment['user_name'] . "</a></span>";
											echo "<span class='product-commenting'><a href='items.php?do=Edit&id=" . $comment['item_id']. "'>[" . $comment['item_name'] . "]</a></span>"; 
										echo "</span>";
										echo "<span class='body-comment'>" . $comment['Comment'] . "</span>";
										echo "</div>";
									}
								} else {
									echo "<div class='noData'>There isn't Comments</div>";
								}
							?>
						</div>
					</div>
				</div>
				<!-- End letast comments -->
			</div>
		</div>
	</div>
	<?php
	/* End Dashboard page*/
} else {
	header("Location: index.php");
	exit();
}
?>


<?php include $tpl . "footer.php"; ?>

<?php 
ob_end_flush();
?>