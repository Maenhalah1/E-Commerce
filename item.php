<?php
	ob_start();
	session_start();
	$pageTitle = "Profile";
	$pageTitle = 'Product';
	include "init.php";

?>
<?php

	$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

	$stmt = $con->prepare("SELECT items.*, categories.Name AS Catg_Name, users.UserName  FROM items
							INNER JOIN categories
							ON items.Catg_ID = categories.ID
							INNER JOIN users
							ON items.Member_ID = users.UserID
							WHERE items_ID = ? ");
	$stmt->execute(array($id));

	if($stmt->rowCount() > 0){

		$product = $stmt->fetch();
		if($product['accepte'] == 1){

 ?>

	<?php if(isset($_SESSION['uid'])): ?>
				<?php 
					if($_SERVER['REQUEST_METHOD'] == 'POST') {
						$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
						$itemid = $product['items_ID'];
						$userid = $_SESSION['uid'];

						if(! empty($comment) && ! ctype_space($comment)) {
							$comment = trim($comment);

							$stmt = $con->prepare("INSERT INTO comments(Comment, Status,Comment_Date, item_id, user_id)
													VALUES(:Fcomment, 1, NOW(), :Fitem, :Fuser)");
							$stmt->execute(array(
								":Fcomment" => $comment,
								":Fitem" => $itemid,
								":Fuser" => $userid
							));
							if($stmt) {

								$msg = "<div class='alert alert-success'>Add Comment is Succssefull</div>";
							}else {
								$msg = "<div class='alert alert-danger'>Sorry, That happen a problem , Please Try Again </div>";
							}

						} else {

							$msg = "<div class='alert alert-danger'>Comment Field Can't Be Empty</div>";

						}
					}
		endif;
				?>	

	<?php 

		$stmt = $con->prepare("SELECT comments.* , users.UserName AS Member FROM comments 
								INNER JOIN users ON users.UserID = comments.user_id 
								WHERE item_ID = :itemid AND Status = 1 ORDER BY Comment_Date DESC");
		$stmt->execute(array(":itemid" => $product['items_ID']));
		$allcomments = $stmt->fetchAll();


	?> 

	<div class="info-item">
		<div class="container">
			<?php isset($msg) ? Redirect($msg,"item.php?id=" . $product['items_ID'], 2) : ""?>
			<div class="item-container">
				<div class="back-item">
					<div class="imgs">
						<img src='https://via.placeholder.com/500/'>
					</div>
					<div class="info">
						<h3 class="title-product"><?php echo $product['Name'];?></h3> 
						<p><?php echo $product['Description'];?></p>
						<ul class="list-unstyled">
						<li><i class="fa fa-calendar fa-fw" aria-hidden="true"></i>
							<span class="name-row">Added Date</span><?php echo $product['Add_Date'];?>
							</li>
						<li><i class="fa fa-money fa-fw" aria-hidden="true"></i>
							<span class="name-row">Price</span>$<?php echo $product['Price'];?>
							</li>
						<li><i class="fa fa-building-o fa-fw" aria-hidden="true"></i>
							<span class="name-row">Made in</span><?php echo $product['Country_Made'];?></li>
						<li><i class="fa fa-question-circle fa-fw" aria-hidden="true"></i>
							<span class="name-row">Status</span><?php echo $product['Status'];?></li>
						<li><i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>
							<span class="name-row">Activation</span><?php echo $product['accepte'] == 1 ? '<span class="active">Active</span>' : '<span class="notactive">Not Active</span>'?></li>
						<li><i class="fa fa-tag fa-fw" aria-hidden="true"></i>
							<span class="name-row">Category</span><a class="mainlink" href="category.php?catgid=<?php echo $product['Catg_ID']?>&pagename=<?php echo str_replace(" ", "-",$product['Catg_Name']);?>"><?php echo $product['Catg_Name'];?></a></li>
						<li><i class="fa fa-user fa-fw" aria-hidden="true"></i>
							<span class="name-row">Created By </span><a href="#" class="mainlink"><?php echo $product['UserName'];?></a></li>
						<li><i class="fa fa-thumb-tack fa-fw" aria-hidden="true"></i>
							<span class="name-row">Tags </span>
							<?php if(!empty($product['Tags'])):?>
								<?php 
								$product['Tags'] = str_replace(" ", "", $product['Tags']);
								$alltags = explode(',', $product['Tags']);
								foreach ($alltags as $tag) {
									$link = strtolower($tag);
									echo "<a href = 'tags.php?name={$link}' class='tag'>" . $tag . "</a>";
								}
								?>
							<?php else:?>
								<span >No Tags</span>
							<?php endif;?>

						</li>	
						</ul>				
					</div>
					<div class="clear"></div>
				</div>
				<hr>
				<div class="comments">
					<h1>Comments</h1>
					<?php if(isset($_SESSION['uid'])): ?>
					
						<div class='add-comment'>
							<form action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $product['items_ID']; ?>" method='POST'>
								<textarea name="comment"></textarea>
								<input type="submit" value='add comment' class="btn">
							</form>
						</div>
					<?php endif;?>
					<div class="back-comment">
						<div class="show-comments">
							<?php if(!empty($allcomments)): ?>
								<?php foreach ($allcomments as $comment): ?>
										<div class="comment-part">
											<div class='user-comment'>
												<img src="<?php echo $Urlimgs;?>images.png"> 
												<h3><a href="#"><?php echo $comment['Member']; ?></a></h3>
											</div>

											<div class='info-comment'>
												<p><?php echo $comment['Comment']; ?></p>
											</div>
											<div class="clear"></div>

										</div>
										<hr class="bet-com">
								<?php endforeach;?>
							<?php else : ?>
									<div class="noData">This Product Hasn't Comments</div>
							<?php endif;?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>






<?php
		}else{
			$msg = "<div class='alert alert-danger'>This Product is Waiting for Activation</div>";
			Redirect($msg,'back');
			exit();
		}
	}else{
		header("Location: index.php");
		exit(); 
	}
?>


<?php include $tpl . "footer.php"; ?>