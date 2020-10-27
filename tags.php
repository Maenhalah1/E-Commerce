<?php 
session_start();
$pageTitle= "Tags" ;
include "init.php"; 
?>

<?php 
	if(isset($_GET['name']) ) {

		
		

?>
				<div class="container">
					<h1 class="catgName">
						<?php echo $_GET['name'] ?>		
					</h1>
					<div class="CatgItems">
						<?php 
						$tag = $_GET['name'];
						$whereCon = "WHERE Tags Like '%$tag%' AND accepte = 1";
						
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
							echo "<div class='noData'>Sorry, This Tags Hasn't Items</div>";
						}

						?>
					</div>
	
				</div>



<?php
		


	}else {
		$msg = "<div class='alert alert-danger'> Sorry, You Has Error To Access </div>";
			Redirect($msg, "index.php",2);
	}	

 ?>


<?php include $tpl ."footer.php"; ?>