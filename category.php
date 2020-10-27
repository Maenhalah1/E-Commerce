<?php 
session_start();
$pageTitle= isset($_GET['pagename']) ? str_replace("_", " ", $_GET['pagename']) : 'All items';
include "init.php"; 
?>

<?php 
	if(isset($_GET['catgid']) && isset($_GET['pagename'])) {

		$_GET['pagename'] = str_replace("_", " ", $_GET['pagename']);
		
		if ( (checkElement('ID', 'categories', $_GET['catgid']) || $_GET['catgid'] == 0) && 
			 (checkElement('Name', 'categories', $_GET['pagename']) || $_GET['pagename'] == 'all') ) {

?>
				<div class="container">
					<h1 class="catgName">
						<?php echo $_GET['pagename'] != 'all' ? $_GET['pagename'] : 'All items' ?>		
					</h1>
					<div class="CatgItems">
						<?php 
						$whereCon = 'WHERE accepte = 1';
						if ($_GET['catgid'] != 0) {
							$subwhere =" OR Catg_ID IN (SELECT ID FROM categories WHERE Parent_Catg = {$_GET['catgid']}) ";
							$whereCon = "WHERE Catg_ID = " . $_GET['catgid'] . $subwhere . " AND accepte = 1";
						}
						$allitems = getFromAll("*","items",$whereCon ,"Add_Date" ,"DESC");
						if (!empty($allitems)) {
							foreach ($allitems as $Item) {
								echo "<div class='ItemBox'>";
								echo "<span class='Mainprice'>$" . $Item['Price'] . "</span>";
								echo "<img src='https://via.placeholder.com/500/'>";
								echo "<h3><a href='item.php?id=" . $Item['items_ID'] . "'>" . $Item['Name'] . "</a></h3>";
								echo "<p>" . $Item['Description'] . "</p>";
								echo "<div class='date-ItemBox'>" . $Item['Add_Date'] . "</div>";
								echo "</div>";
							}
						} else {
							echo "<div class='noData'>Sorry, This Category Hasn't Items</div>";
						}

						?>
					</div>
	
				</div>



<?php
		}else{

			$msg = "<div class='alert alert-danger'> Sorry, You Has Error To Access </div>";
			Redirect($msg, "index.php",2);

		}


	}else {
		$msg = "<div class='alert alert-danger'> Sorry, You Has Error To Access </div>";
			Redirect($msg, "index.php",2);
	}	

 ?>


<?php include $tpl ."footer.php"; ?>