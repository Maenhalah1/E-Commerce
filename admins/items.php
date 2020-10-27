<?php
session_start(); 
if (isset($_SESSION['Username'])){
	$pageTitle = "Products";
	
	include "init.php";

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
?>

	<?php if ($do == 'Manage' || $do == 'Delete' || $do == 'accepte'): ?>

	<?php	if($do == "Delete") {
				
				$idDel = (isset($_GET['id']) && is_numeric($_GET['id']) && checkElement('items_ID', 'items', $_GET['id'])) ? $_GET['id'] : 0;
				if ($idDel != 0) {
					$stmt4 = $con->prepare("DELETE FROM items WHERE items_ID = :id");
					$stmt4->bindParam(":id", $idDel);
					$stmt4->execute();
					$succs = "<div class='alert alert-success'>Deleted The Product was Successfull </div>";
					$url = "back";
				}else {
					$msg = "<div class='alert alert-danger'>ID is Not Correct</div>";
					$url = "home";
					Redirect($msg,$url);
					exit();
				}

						
			} elseif($do == "accepte") {

				$idAcc = ( isset($_GET['id']) && is_numeric($_GET['id']) && checkElement('items_ID','items',$_GET['id']) ) ? $_GET['id'] : 0;
				if ($idAcc != 0) {
					
					$stmt5 = $con->prepare("UPDATE items SET accepte = 1 WHERE items_ID = :id");
					$stmt5->bindParam(":id", $idAcc);
					$stmt5->execute();
					$succs = "<div class='alert alert-success'>Accepted is Successfull</div>";
					$url = "back";


				}else {
					$msg = "<div class='alert alert-danger'>ID is not Correct</div>";
					Redirect($msg,'home');
					exit();
				}
			}

			$stmt = $con->prepare("SELECT items.*, categories.Name AS Catg_name, users.UserName 
									FROM items INNER JOIN categories ON items.Catg_ID = categories.ID 
									INNER JOIN users ON users.UserID = items.Member_ID ORDER BY accepte ASC, Add_Date DESC;");
			$stmt->execute();
			$items = $stmt->fetchAll(); //get all items (products) from items table

			if (!empty($items)){
	
	?>
		<div class="mycontainer">
			<h3 class="mainheader">Manage Products</h3>
			<?php if (isset($succs)) { Redirect($succs,$url,2);} else { echo "";}?>
			<div class="table-responsive">
			<table class="table main-table">
				<tr>
					<td>ID</td>
					<td>Name</td>
					<td>Description</td>
					<td>Price</td>
					<td>Comments</td>
					<td>Category</td>
					<td>User Name</td>
					<td>Adding Date</td>
					<td>Control</td>
				</tr>
				<?php 
				foreach ($items as $item) {
					echo "<tr>";
						echo "<td>".$item['items_ID'] ."</td>";
						echo "<td>".$item['Name'] ."</td>";
						echo "<td>".$item['Description'] ."</td>";
						echo "<td>" . "$" . $item['Price'] ."</td>";
						echo "<td>"; //comments colume
							if (countItem("Comment_ID", "comments", "item_id", $item['items_ID']) > 0){
							 echo "<a href='?do=comments&id=". $item['items_ID'] ."' class='btn allcomment'>" . countItem("Comment_ID", "comments", "item_id", $item['items_ID']) . " Comments" . "</a>"; 
							}else {
								echo "<span class='no-comments'>None</span>";
							}
						echo "</td>";
						echo "<td>".$item['Catg_name'] ."</td>";
						echo "<td>".$item['UserName'] ."</td>";
						echo "<td>".$item['Add_Date']."</td>";
						echo "<td>
									<a href='items.php?do=Edit&id=" . $item['items_ID'] . "' 
										class='btn btn-success'>
									<i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i>
									Edit</a>
									<a href='items.php?do=Delete&id=". $item['items_ID'] . "'
										class='btn btn-danger confirm' 
										onclick='return(checkConfirm())'>
										<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>
										 Delete</a>";	
									if ($item['accepte'] == 0) {
										echo "<a href='items.php?do=accepte&id=". $item['items_ID'] ."' class='btn btn-info activate'>";
										echo "<i class='fa fa-check-circle' aria-hidden='true'></i> Accepte";
										echo "</a>";
									}	  

						 echo "</td>";
															
					echo "</tr>";
				}
				?>
			
			</table>
			</div>
			<a href="?do=Add" class="toAdd itemAdd">
			<i class="fa fa-plus" aria-hidden="true"></i> Add Product</a>

		</div>

		<?php 
			}else {
				echo "<div class='container'>";
					echo "<div class='noData noDataTables'>There isn't Products</div>";
					echo "<a href='?do=Add' class='toAdd itemAdd'>
							<i class='fa fa-plus' aria-hidden='true'></i> Add Product</a>";
				echo "</div>";
			}


		?>

	<?php elseif($do == "comments"): ?>

	<?php 
			$idToComm = isset($_GET['id']) && is_numeric($_GET['id']) && checkElement("items_ID", "items", $_GET['id']) ? $_GET['id'] : 0;

			if ($idToComm > 0) {

				$stmt6 = $con->prepare("SELECT comments.*, users.UserName AS user FROM comments 
										INNER JOIN users ON comments.user_id = users.UserID 
										WHERE item_id = :itemid");
				$stmt6->bindParam(":itemid", $_GET['id']);
				$stmt6->execute();
				$comments = $stmt6->fetchAll();

	?>
		<div class="mycontainer">
			<h3 class="mainheader">Comments</h3>
			<?php if (isset($succs)) { Redirect($succs,$url,1);} else { echo "";}?>
			<div class="table-responsive">
				<table class="table main-table">
					<tr>
						<td>ID</td>
						<td>Comment</td>
						<td>Date Added</td>
						<td>User Name</td>
						<td>Control</td>
					</tr>
					<?php 
					foreach ($comments as $comment) {
						echo "<tr>";
							echo "<td>".$comment['Comment_ID'] ."</td>";
							echo "<td><div class='long-text'>".$comment['Comment'] ."</div></td>";
							echo "<td>".$comment['Comment_Date'] ."</td>";
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
}else{ 
		$msg = "<div class='alert alert-danger'>ID is not correct</div>";
		Redirect($msg,'home');

}

?>


	<?php elseif ($do == "Add" || $do == "Insert"): ?>

		<?php 
			if ($do == 'Insert') {
				if($_SERVER["REQUEST_METHOD"] != "POST") {
					$msg = "<div class='alert alert-danger'>This page cannot be accessed directly</div>";
					Redirect($msg,"home");
					exit();
				}
		}

			$errorsForm = array();
			$infoadd = array();	
				
			if($do == 'Insert') {
	
				$name 			= $_POST['name'];
				$description 	= $_POST['desc'];
				$price 			= $_POST['price'];
				$country 		= $_POST['country'];
				$status 		= $_POST['status'];	
				$member 		= $_POST['member'];
				$category 		= $_POST['category'];
				$tags 			= $_POST['tags'];


				/*== Validate the Data ==*/

				 
					//Validate name product
				if (strlen($name) < 3){
					$errorsForm['nameER'] = "<span class='span-notCorrect'>Must Be More than 2 letters and less than 15 letters </span>";
				} elseif (in_array($name[0],$notLetters)) {
					$errorsForm['nameER'] = "<span class='span-notCorrect'>Must be the first letter a Character</span>";
				} 
					//Validate Descrption product
				
				if (empty($description)) {

					$errorsForm['descER'] = "<span class='span-notCorrect'> Can't Be Empty </span>";
				} elseif (strlen($description) < 5) {
					$errorsForm['descER'] = "<span class='span-notCorrect'> Must Be More than 10 letters</span>";
				}
				
				//Validate price product
					
				if (empty($price)) {
					$errorsForm['priceER'] = "<span class='span-notCorrect'> Can't Be Empty </span>";
				} elseif (!is_numeric($price)) {
					$errorsForm['priceER'] = "<span class='span-notCorrect'>the price Must Be Numbers</span>";
				}
				
				//Validate Country
				
				if($country == "none") {
				$errorsForm['countryER'] = "<span class='span-notCorrect'> please, Choose Country Made of product</span>";
				} 
					//Validate status
				
				if($status == "none") {
				$errorsForm['statusER'] = "<span class='span-notCorrect'> please, Choose Status of product</span>";
				} 

				if($member == "none") {
				$errorsForm['memberER'] = "<span class='span-notCorrect'> please, Choose Member</span>";
				} 

				if($category == "none") {
				$errorsForm['catgER'] = "<span class='span-notCorrect'> please, Choose Category of product</span>";
				} 

				if(empty($errorsForm)) {
					
				$set = $con->prepare('INSERT INTO items (Name, Description, Price, Country_Made, Status, Add_Date, Catg_ID, Member_ID , Tags)
										VALUES(:name, :descr, :price, :country, :status , now(), :catgid, :memberid, :tags)');
				$set->execute(array(':name' => $name, ':descr' => $description,
									 ':price' => $price,':country' => $country,
									 ':status' => $status, ':catgid' => $category, ':memberid' => $member, ':tags' => $tags ));
				$succs = "<div class='alert alert-success'>Add Product is Successfull</div>";
				$url = "items.php?do=Add"; 
				} else {
					$infoadd['name'] = $name;
					$infoadd['desc'] = $description;
					$infoadd['price'] = $price;
					$infoadd['country'] = $country;
					$infoadd['status'] = $status;	
				}	 	
		} 




		?>

				<div class="mainForm">
					<div class="mycontainer">		
						<h3>Add Product</h3>
						<?php if(isset($succs)){ Redirect($succs,$url);} else{ echo "";}?>
						<form  action="?do=Insert" method="POST">
						<!-- Start Name item Field -->
							<div class="field">
								<label class="lebelOfinput">Name</label>
								<input type="text" name="name"  required="" placeholder="Name of Product" 
								class="place" value="<?php echo isset($infoadd['name']) ? $infoadd['name'] : '' ?>">
								<?php echo isset($errorsForm['nameER'])  ? $errorsForm['nameER'] : ""?>		
							</div>	
						<!-- End Name item Field -->

						<!-- Start Description item Field -->
							<div class="field">
								<label class="lebelOfinput">Description</label>
								<input type="text" name="desc"  required="" placeholder="Description of Product" 
								class="place" value="<?php echo isset($infoadd['desc']) ? $infoadd['desc'] : '' ?>">
								<?php echo isset($errorsForm['descER'])  ? $errorsForm['descER'] : ""?>		
							</div>	
						<!-- End Description item Field -->

						<!-- Start Price  Field -->
							<div class="field">
								<label class="lebelOfinput">Price</label>
								<input type="text" name="price"  required="" value="<?php echo isset($infoadd['price']) ? $infoadd['price'] : '' ?>">		
								<?php echo isset($errorsForm['priceER'])  ? $errorsForm['priceER'] : ""?>
							</div>
						<!-- End Price  Field -->
						<!-- Start Tags  Field -->
							<div class="field">
								<label class="lebelOfinput">Description</label>
								<input type="text" name="tags"  placeholder="Supreat it by comma => (,)" class="place" >
										
							</div>	
						<!-- End Tags  Field -->	
						<!-- Start Country  Field -->
							<div class="field">
								<label class="lebelOfinput">Country of Made</label>
								<select name="country">
									<option value="none">...</option>
									<?php 
									foreach ($country_list as $value) {
										echo "<option value='" . $value . "'>" . $value . "</option>"; 
									}
									?>
								</select>	
								<?php echo isset($errorsForm['countryER'])  ? $errorsForm['countryER'] : ""?>
							</div>	
						<!-- End Country Field -->
						<!-- Start status  Field -->
							<div class="field">
								<label class="lebelOfinput">Status of Product</label>
								<select name="status">
									<option value="none">...</option>
									<option value="New">New</option>
									<option value="Used">Used</option>
								</select>	
								<?php echo isset($errorsForm['statusER'])  ? $errorsForm['statusER'] : ""?>
							</div>	
						<!-- End status Field -->

						<!-- Start category Field -->
							<div class="field">
								<label class="lebelOfinput">Category</label>
								<select name="category" class="Catgs">
									<option value="none">...</option>
									<?php 
									$allCatg = getFromAll("*","categories","WHERE Parent_Catg = 0","Name",ASC);
									foreach ($allCatg as $catg) {
										echo "<option value='" . $catg['ID'] . "' class='maincatg'>" . $catg['Name'] . "</option>";
										$allSubCatg = getFromAll("*","categories","WHERE Parent_Catg = {$catg['ID']}","Name",ASC);
										if(!empty($allSubCatg)) {
											foreach ($allSubCatg as $subcatg) {									
												echo "<option class='subcatg' value='" . $subcatg['ID'] . "'>";
												echo "<span class='subcatg'>  -- " .  $subcatg['Name'] . " { " . $catg['Name'] . " }". "</span>"; 
												 echo"</option>";
											}
										}
									}
									?>
								</select>	
								<?php echo isset($errorsForm['catgER'])  ? $errorsForm['catgER'] : ""?>
							</div>	
						<!-- End category Field -->

						<!-- Start member  Field -->
							<div class="field">
								<label class="lebelOfinput">Member</label>
								<select name="member">
									<option value="none">...</option>
									<?php 
									$stmt1 = $con->prepare("SELECT * FROM users");
									$stmt1->execute();
									$users = $stmt1->fetchAll();
									foreach ($users as $user) {
										echo "<option value='" . $user['UserID'] . "'>" . $user['UserName'] . "</option>"; 
									}
									?>
								</select>	
								<?php echo isset($errorsForm['memberER'])  ? $errorsForm['memberER'] : ""?>
							</div>	
						<!-- End member Field -->
						<div class="field bt">
							<input type="submit" value="Add Product">
						</div>
						</form>
					</div>
				</div>



	<?php elseif($do == 'Edit' || $do == 'Update'): ?>

<?php
				if ($do == 'Update') {
					if($_SERVER["REQUEST_METHOD"] != "POST") {
						$msg = "<div class='alert alert-danger'>This page cannot be accessed directly</div>";
						Redirect($msg,'home');
						exit();
					}
				}

		if(( isset($_GET['id']) && is_numeric($_GET['id']) && checkElement('items_ID', 'items', $_GET['id']) ) || $do == 'Update'):

				$errorsForm = array();
				$idReq = ($do == "Edit") ? $_GET['id'] : $_POST['id'];

				$data = $con->prepare('SELECT * From items WHERE items_ID = ? Limit 1');
				$data->execute(array($idReq));
				$item = $data->fetch();

				if($do == 'Update') {

					if($_SERVER["REQUEST_METHOD"] == "POST") {
						$id 		= $_POST['id'];
						$name 		= $_POST['name'];
						$desc 		= $_POST['desc'];
						$price 		= $_POST['price'];
						$status 	= $_POST['status'];
						$country 	= $_POST['country'];
						$category 	= $_POST['category'];
						$member 	= $_POST['member'];

						// Validate Data

						if(in_array($name[0], $notLetters)) {
							$errorsForm['nameER'] = "<span class='span-notCorrect'>Must be the first letter a Character</span>";
						}else if( strlen($name) < 2) {
							$errorsForm['nameER'] = "<span class='span-notCorrect'>Must Be More than 2 letters and less than 15 letters</span>";
						} 

						if (empty($desc)) {

							$errorsForm['descER'] = "<span class='span-notCorrect'> Can't Be Empty </span>";
						} elseif (strlen($desc) < 5) {
							$errorsForm['descER'] = "<span class='span-notCorrect'> Must Be More than 10 letters</span>";
						}
						
						//Validate price product
							
						if (empty($price)) {
							$errorsForm['priceER'] = "<span class='span-notCorrect'> Can't Be Empty </span>";
						} elseif (!is_numeric($price)) {
							$errorsForm['priceER'] = "<span class='span-notCorrect'>the price Must Be Numbers</span>";
						}
						if (empty($errorsForm)) {
							$stmt3 = $con->prepare("UPDATE items SET Name = ?, 
																	 Description = ?, 
																	 Price = ?, 
																	 Status = ?,
																	 Country_Made = ?,
																	 Catg_ID = ?,
																	 Member_ID = ?
																WHERE items_ID = ?");

							$stmt3->execute(array($name,$desc,$price,$status,$country,$category,$member,$id));
							$succs = "<div class='alert alert-success'>Update is Successfull</div>";
							$url = "items.php?do=Edit&" . "id=" . $id;
							$item['Name'] = $name;
							$item['Description'] = $desc;
							$item['Status'] = $status;
							$item['Price'] = $price;
							$item['Country_Made'] = $country;
							$item['Catg_ID'] = $category;
							$item['Member_ID'] = $member;
						}

						
					}
					
				} 
		
	?>
		<div class="mainForm">
					<div class="mycontainer">		
						<h3>Edit Product</h3>
						<?php if(isset($succs)){ Redirect($succs,$url);} else{ echo "";}?>
						<form  action="?do=Update" method="POST">
							<input type="hidden" name="id" value= "<?php echo $_GET['id'];?>" >
						<!-- Start Name item Field -->
							<div class="field">
								<label class="lebelOfinput">Name</label>
								<input type="text" name="name"  required="" placeholder="Name of Product" 
								class="place" value="<?php echo isset($item['Name']) ? $item['Name'] : '' ?>">
								<?php echo isset($errorsForm['nameER'])  ? $errorsForm['nameER'] : ""?>		
							</div>	
						<!-- End Name item Field -->

						<!-- Start Description item Field -->
							<div class="field">
								<label class="lebelOfinput">Description</label>
								<input type="text" name="desc"  required="" placeholder="Description of Product" 
								class="place" value="<?php echo isset($item['Description']) ? $item['Description'] : '' ?>">
								<?php echo isset($errorsForm['descER'])  ? $errorsForm['descER'] : ""?>		
							</div>	
						<!-- End Description item Field -->

						<!-- Start Price  Field -->
							<div class="field">
								<label class="lebelOfinput">Price</label>
								<input type="text" name="price"  required="" value="<?php echo isset($item['Price']) ? $item['Price'] : '' ?>">		
								<?php echo isset($errorsForm['priceER'])  ? $errorsForm['priceER'] : ""?>
							</div>
						<!-- End Price  Field -->
						<!-- Start Country  Field -->
							<div class="field">
								<label class="lebelOfinput">Country of Made</label>
								<select name="country">
									<?php 
									foreach ($country_list as $value) {
										echo "<option value='" . $value . "'";
										if ($item['Country_Made'] == $value) {echo "selected";}
										echo ">" . $value . "</option>"; 
									}
									?>
								</select>	
								<?php echo isset($errorsForm['countryER'])  ? $errorsForm['countryER'] : ""?>
							</div>	
						<!-- End Country Field -->
						<!-- Start status  Field -->
							<div class="field">
								<label class="lebelOfinput">Status of Product</label>
								<select name="status">
									<option value="New"  <?php if($item['Status'] == 'New'){echo "Selected";} ?> >New</option>
									<option value="Used"  <?php if($item['Status'] == 'Used'){echo "Selected";} ?> >Used</option>
								</select>	
								<?php echo isset($errorsForm['statusER'])  ? $errorsForm['statusER'] : ""?>
							</div>	
						<!-- End status Field -->

						<!-- Start category Field -->
							<div class="field">
								<label class="lebelOfinput">Category</label>
								<select name="category">
									<?php 
									$stmt2 = $con->prepare("SELECT * FROM categories");
									$stmt2->execute();
									$catgs = $stmt2->fetchAll();
									foreach ($catgs as $catg) {
										echo "<option value='" . $catg['ID'] . "'";
										if ($item['Catg_ID'] == $catg['ID']) {echo "Selected";}
										echo ">" . $catg['Name'] . "</option>"; 
									}
									?>
								</select>	
								<?php echo isset($errorsForm['catgER'])  ? $errorsForm['catgER'] : ""?>
							</div>	
						<!-- End category Field -->

						<!-- Start member  Field -->
							<div class="field">
								<label class="lebelOfinput">Member</label>
								<select name="member">
									<?php 
									$stmt1 = $con->prepare("SELECT * FROM users");
									$stmt1->execute();
									$users = $stmt1->fetchAll();
									foreach ($users as $user) {
										echo "<option value='" . $user['UserID'] . "'";
										if($item['Member_ID'] == $user['UserID']) {echo "Selected";}
										echo ">" . $user['UserName'] . "</option>"; 
									}
									?>
								</select>	
								<?php echo isset($errorsForm['memberER'])  ? $errorsForm['memberER'] : ""?>
							</div>	
						<!-- End member Field -->
						<div class="field bt">
							<input type="submit" value="Edit Product">
						</div>
						</form>
					</div>
				</div>

<?php		
			
		else: 
			$msg = "<div class='alert alert-danger'>ID is not correct</div>";
			Redirect($msg,'back');
		endif;

?>

<?php
endif;
}else{
	header("Location: index.php");
	exit(); 
}
?>


<?php include $tpl . "footer.php"; ?>