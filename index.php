<?php 
session_start();
$pageTitle = 'Homepage';
$noSelectit="";
include "init.php"; 
?>

<div class="container">
					
					<div class="CatgItems">
						<?php 
						if (!empty(getFromAll("*","items","WHERE accepte = 1","Add_Date"))) {
							foreach (getFromAll("*","items" ,"WHERE accepte = 1","Add_Date") as $Item) {
								echo "<div class='ItemBox'>";
								echo "<span class='Mainprice'>$" . $Item['Price'] . "</span>";
								echo "<img src='https://via.placeholder.com/500/'>";
								echo "<h3><a href='item.php?id=" . $Item['items_ID'] . "'>" . $Item['Name'] . "</a></h3>";
								echo "<p>" . $Item['Description'] . "</p>";
								echo "<div class='date-ItemBox'>" . $Item['Add_Date'] . "</div>";
								echo "</div>";
							}
						} else {
							echo "<div class='noData'>Sorry, No Data</div>";
						}

						?>
					</div>
	
				</div>


<?php include $tpl ."footer.php"; ?>