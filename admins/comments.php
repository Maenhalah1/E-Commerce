<?php
session_start(); 
if (isset($_SESSION['Username'])){
	$pageTitle = "Comments";
	
	include "init.php";

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
?>

	<?php if ($do == 'Manage' || $do == 'Delete' || $do == 'Activate' || $do == 'Deactivate'): ?>

		<?php 

		if ($do == 'Delete') {
			$id = isset($_GET['id']) && is_numeric($_GET['id']) && checkElement('Comment_ID', 'comments', $_GET['id']) ? $_GET['id'] : 0;
			if ($id != 0) {

				$stmt2 = $con->prepare("DELETE FROM comments WHERE Comment_ID = :id");
				$stmt2->bindParam(':id', $id);
				$stmt2->execute();
				$succs = "<div class='alert alert-success'> Deleted Was Successfull</div>";
				$url = 'back';
				if (!isset($_SERVER['HTTP_REFERER'])) {
					Redirect($succs,'home',1);
					exit();
				}elseif ($_SERVER['HTTP_REFERER'] != "http://localhost/e-commerce/admins/comments.php"){
					Redirect($succs,$url,1);
					exit();
				}
			}else{
				$msg = "<div class='alert alert-danger'> Sorry, ID is not Correct</div>";
				Redirect($msg, 'home', 1);
				exit();
			}

		} elseif ($do == 'Activate') {
			$id = isset($_GET['id']) && is_numeric($_GET['id']) && checkElement('Comment_ID', 'comments', $_GET['id']) ? $_GET['id'] : 0;
			if ($id != 0) {

				$stmt2 = $con->prepare("UPDATE comments SET Status = 1 WHERE Comment_ID = :id");
				$stmt2->bindParam(':id', $id);
				$stmt2->execute();
				$succs = "<div class='alert alert-success'> Activate Was Successfull</div>";
				$url = 'back';
				if (!isset($_SERVER['HTTP_REFERER'])) {
					Redirect($succs,'home',1);
					exit();
				}elseif ($_SERVER['HTTP_REFERER'] != "http://localhost/e-commerce/admins/comments.php"){
					Redirect($succs,$url,1);
					exit();
				}
			}else{
				$msg = "<div class='alert alert-danger'> Sorry, ID is not Correct</div>";
				Redirect($msg, 'home', 1);
				exit();
			}

		}elseif($do == 'Deactivate') {
			$id = isset($_GET['id']) && is_numeric($_GET['id']) && checkElement('Comment_ID', 'comments', $_GET['id']) ? $_GET['id'] : 0;
			if ($id != 0) {

				$stmt2 = $con->prepare("UPDATE comments SET Status = 0 WHERE Comment_ID = :id");
				$stmt2->bindParam(':id', $id);
				$stmt2->execute();
				$succs = "<div class='alert alert-success'> Deactivate Was Successfull</div>";
				$url = 'back';
				if (!isset($_SERVER['HTTP_REFERER'])) {
					Redirect($succs,'home',1);
				exit();
				}elseif ($_SERVER['HTTP_REFERER'] != "http://localhost/e-commerce/admins/comments.php"){
					Redirect($succs,$url,1);
					exit();
				}
			}else{
				$msg = "<div class='alert alert-danger'> Sorry, ID is not Correct</div>";
				Redirect($msg, 'home', 1);
				exit();
			}
			
		}
		$stmt = $con->prepare("SELECT comments.*, users.UserName AS user, items.Name AS item FROM comments
								INNER JOIN items ON comments.item_id = items.items_ID
								INNER JOIN users ON comments.user_id = users.UserID 
								ORDER BY Status ASC, Comment_Date DESC");
		$stmt->execute();
		$comments = $stmt->fetchAll();
		if (!empty($comments)) {
		?>


		<div class="mycontainer">
			<h3 class="mainheader">Manage Comments</h3>
			<?php if (isset($succs)) { Redirect($succs,$url,1);} else { echo "";}?>
			<div class="table-responsive">
				<table class="table main-table">
					<tr>
						<td>ID</td>
						<td>Comment</td>
						<td>Date Added</td>
						<td>Item Name</td>
						<td>User Name</td>
						<td>Control</td>
					</tr>
					<?php 
					foreach ($comments as $comment) {
						echo "<tr>";
							echo "<td>".$comment['Comment_ID'] ."</td>";
							echo "<td><div class='long-text'>".$comment['Comment'] ."</div></td>";
							echo "<td>".$comment['Comment_Date'] ."</td>";
							echo "<td>".$comment['item'] ."</td>";
							echo "<td>".$comment['user']."</td>";
							echo "<td>
										<a href='comments.php?do=Delete&id=". $comment['Comment_ID'] . "'
											class='btn btn-danger confirm' 
											onclick='return(checkConfirm())'>
											<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>
											 Delete</a>";
										if ($comment['Status'] == 0){
											echo "<a href='comments.php?do=Activate&id=". $comment['Comment_ID'] . "'
											class='btn btn-info activate act-comm'>
											<i class=\"fa fa-check-circle\" aria-hidden=\"true\"></i>
											Activate</a>";
										}else {
											echo "<a href='comments.php?do=Deactivate&id=". $comment['Comment_ID'] . "'
											class='btn btn-primary activate act-comm'>
											<i class=\"fa fa-times-circle\" aria-hidden=\"true\"></i>
											 Deactivate</a>";
										}	 

							 echo "</td>";
																
						echo "</tr>";
					}


					?>
				
				</table>
			</div>

		</div>

<?php 
} else {

		echo "<div class='container'>";
			echo "<div class='noData noDataTables'>There isn't Comments</div>";
		echo "</div>";
}

?>


<?php
endif;
}else{
	header("Location: index.php");
	exit(); 
}
?>


<?php include $tpl . "footer.php"; ?>