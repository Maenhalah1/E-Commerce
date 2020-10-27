<?php
session_start(); 
if (isset($_SESSION['Username'])){
	$pageTitle = "Categories";
	
	include "init.php";

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
?>

	<?php if ($do == 'Manage' || $do == 'Delete'): // Manage Page 

			if ($do == 'Delete') {
				if (isset($_GET['id']) && checkElement("ID","categories",$_GET['id'])) {
					$del = $con->prepare("DELETE FROM categories WHERE ID = :id");
					$del->bindParam(":id", $_GET['id']);
					$del->execute();
					$succs = "<div class='alert alert-success'>Deleted was Successfull</div>";
					$url = 'back';
				} else {
					$succs = "<div class='alert alert-danger'>Sorry, id isn't Correct</div>";
					$url = 'categories.php';
				}
			}
			$sort = "Ordering";
			$typesort = "ASC";
			$arrtypesort = array("ASC","DESC");
			$arrsort = array("Name", "Ordering"); 
			if (isset($_GET['sort']) && in_array($_GET['sort'], $arrtypesort)) {
				$typesort = $_GET['sort'];
			}
			if (isset($_GET['sortby']) && in_array($_GET['sortby'], $arrsort)) {
				$sort = $_GET['sortby'];
			}
			$parent ='0';
			if(isset($_GET['parentCatg']) && is_numeric($_GET['parentCatg']) && checkElement('ID', 'categories', $_GET['parentCatg'])) {
				$parent= $_GET['parentCatg'];
			}
			$stmt = $con->prepare("SELECT * FROM categories WHERE Parent_Catg = $parent ORDER BY $sort $typesort");
			$stmt->execute();
			$catgs = $stmt->fetchAll();	

			if (!empty($catgs)) {
				$header = 'Manage Categories';
				if ($parent != 0) {
					$data = returnElement("*", "categories", "ID", $parent);
					$header = "Manage " . $data['Name'] . " Category";
				}

	?> 

	<h1 class="mainheader"><?php echo $header; ?></h1>
	<div class="container categories">
		<?php if (isset($succs)){ Redirect($succs, $url, 2);}else{echo "";} ?>
		<div class="panel panel-default">
			<div class="panel-heading"><i class='fa fa-tasks' aria-hidden='true'></i>Manage Categories
				<div class="options pull-right">
					<i class='fa fa-sort' aria-hidden='true'></i>Order : 
					<a class="<?php echo $typesort == 'ASC' ? 'active' : ''; ?>" href="?sortby=<?php echo $sort;?>&sort=ASC&parentCatg=<?php echo $parent ?>">Ascending</a> | 
					<a class="<?php  echo $typesort == 'DESC' ? 'active' : ''; ?>" href="?sortby=<?php echo $sort;?>&sort=DESC&parentCatg=<?php echo $parent ?>">Descending</a>
				</div>
				<div class="options pull-right">
					<i class='fa fa-sort' aria-hidden='true'></i>Order By : 
					<a class="<?php echo $sort == 'Ordering' ? 'active' : ''; ?>" href="?sortby=Ordering&sort=<?php echo $typesort;?>&parentCatg=<?php echo $parent ?>">Ordering</a> | 
					<a class="<?php  echo $sort == 'Name' ? 'active' : ''; ?>" href="?sortby=Name&sort=<?php echo $typesort;?>&parentCatg=<?php echo $parent ?>">Name</a>
				</div>
				<div class="options pull-right">
					<i class='fa fa-eye' aria-hidden='true'></i>View : 
					<span class="active" data-view="full">Full</span> | <span data-view="classic">Classic</span>
				</div>
			</div>
			<div class="panel-body">
				<?php 
				foreach ($catgs as $catg) {
					echo "<div class='catg'>";
						echo "<div class='catg-buttons'>";
							echo "<a href='?do=Edit&id=". $catg['ID'] ."' class='btn btn-success'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Edit</a>";
							$SubCatg = getFromAll("*","categories", "WHERE Parent_Catg = {$catg['ID']}", "Name");
							if(!empty($SubCatg)) {
								echo "<a href='categories.php?parentCatg=" . $catg['ID'] . "' class='btn btn-primary'><i class='fa fa-caret-square-o-down' aria-hidden='true'></i> Subcategory</a>";
							}
							echo "<a href='?do=Delete&id=" . $catg['ID'] . "' class='btn btn-danger confirm' onclick='return(checkConfirm());'><i class='fa fa-trash' aria-hidden='true'></i> Delete</a>";
							
						echo "</div>";
						echo "<h2>" . $catg['Name'] . "</h2>";
						echo "<div class='view-catg'>";
							echo $catg['Description'] != null ? "<p>" . $catg['Description'] . "</p>": "";
							echo ($catg['Visibility'] == 0 || $catg['Allow_comments'] == 0 || $catg['Allow_Advs'] == 0) ? "<div class='catg-allspan'>":"";
								echo $catg['Visibility'] == 0 ? "<span class='catg-span catg-visible'> <i class='fa fa-eye-slash' aria-hidden='true'></i>Hidden</span>": "";
								echo $catg['Allow_comments'] == 0 ? "<span class='catg-span catg-comments'><i class='fa fa-times' aria-hidden='true'></i>Comments Disabled</span>": "";
								echo $catg['Allow_Advs'] == 0 ? "<span class='catg-span catg-advs'><i class='fa fa-times' aria-hidden='true'></i>Advs Disabled</span>": "";
							echo ($catg['Visibility'] == 0 || $catg['Allow_comments'] == 0 || $catg['Allow_Advs'] == 0) ? "</div>":"";
					echo "</div>";
					echo "</div>";
					echo "<hr>";
				}

				?>
			</div>
		</div>
		<a href="categories.php?do=Add" class="toAdd"><i class="fa fa-plus"></i>Add New Category</a>
	</div>


	<?php 
	}else{
		echo "<div class='container'>";
				echo "<div class='noData noDataTables'>There isn't Products</div>";
				echo '<a href="categories.php?do=Add" class="toAdd"><i class="fa fa-plus"></i>Add New Category</a>';
		echo "</div>";
	}
	?>

<!--  Add Categories Page -->
	<?php 
	elseif ($do == "Add" || $do == "Insert"): 
		if ($do == 'Insert') {
			if($_SERVER["REQUEST_METHOD"] != "POST") {
				$msg = "<div class='alert alert-danger'>This page cannot be accessed directly</div>";
				Redirect($msg);
				exit();
			}
		}


			$errorsForm = array();
			$infoadd = array();	
				
			if($do == 'Insert') {

				if($_SERVER["REQUEST_METHOD"] == "POST") {

				$name= $_POST['name'];
				$desc = $_POST['desc'];
				$parentCatg = $_POST['ParentCatg']; 
				$order = $_POST['order'];
				$visibility = $_POST['visibility'];
				$allowComment = $_POST['allowComment'];
				$allowAdvs = $_POST['allowAdvs'];		 
				/*== Validate the Data ==*/

					// Validate user name
				$notLetters = array ('1','2','3','4','5','6','7','8','9','0','*','-','@','!','_','.','&','%','+',
									'(','/',')','[',']','^','$','#',',','?','>','<',',');

				 $find = checkElement('name', 'categories', $name);

				 //Validate name
				if (strlen($name) < 2 ){
					$errorsForm['nameER'] = "<span class='span-notCorrect'>Must Be More than 1 letters </span>";
				} elseif (in_array($name[0],$notLetters)) {
					$errorsForm['nameER'] = "<span class='span-notCorrect'>Must be the first letter a letter</span>";
				} elseif($find == true) {
					$errorsForm['nameER'] = "<span class='span-notCorrect'> User Name Already Exists</span>";
				}
					
					//Validate Ordering
					if (empty($order)){
						$errorsForm['orderER'] = "<span class='span-notCorrect'> Write Order number</span>";
					}
					elseif (!is_numeric($order)) {
						$errorsForm['orderER'] = "<span class='span-notCorrect'> Must Be a Number</span>";
					} 


				if(empty($errorsForm)) {
				$set = $con->prepare('INSERT INTO categories (Name, Description, Parent_Catg, Ordering, Visibility, Allow_comments, Allow_Advs)
										VALUES(:name, :descr, :parent, :order, :visibl, :comment, :advs)');
				$set->execute(array(':name' => $name, ':descr' => $desc,
									 ':parent' => $parentCatg,':order' => $order, ':visibl' => $visibility,
									 ':comment' => $allowComment, ':advs' => $allowAdvs));
				if($set) {
					$succs = "<div class='alert alert-success'>Add Category is Successfull</div>";
					$url = "categories.php?do=Add&id=" . $_SESSION['userid']; 
				}
				} else {
					$infoadd['name'] = $name;
					$infoadd['desc'] = $desc;
					$infoadd['order'] = $order;
					
				}
			} 	
		} 

	?>

					<div class="mainForm">
					<div class="mycontainer">		
						<h3>Add Category</h3>
						<form  action="?do=Insert&id=<?php echo $_SESSION['userid']; ?>" 
							method="POST">
						<?php if (isset($succs)) { Redirect($succs,$url);} else { echo "";}?>
						<!-- Start Name Category Field -->
							<div class="field">
								<label class="lebelOfinput">Name</label>
								<input type="text" name="name" autocomplete="off" required="" 
										value="<?php echo isset($infoadd['name']) ? $infoadd['name'] : ""; ?>">
								<?php echo isset($errorsForm['nameER'])?$errorsForm['nameER']:"";?>
							</div>	
						<!-- End Name Category Field -->

						<!-- Start Description Category Field -->
							<div class="field">
								<label class="lebelOfinput" >Description</label>
								<input type="text" name="desc"
										value="<?php echo isset($infoadd['desc']) ? $infoadd['desc'] : ""; ?>">	
							</div>
						<!-- End Description Category Field -->

						<!-- Start Ordering Category Field -->
							<div class="field">
								<label class="lebelOfinput">Ordering</label>
								<input type="text" name="order" autocomplete="off" required=""
										value="<?php echo isset($infoadd['order']) ? $infoadd['order'] : ""; ?>" >
								<?php echo isset($errorsForm['orderER'])?$errorsForm['orderER']:"";?>

							</div>	
							<!-- End Ordering Category Field -->

							<!-- Start Parent of Category Field -->
							<?php 

								$allCatgs = getFromAll("*", "categories", "WHERE Parent_Catg = 0", "Name");

							?>
							<div class="field">
								<label class="lebelOfinput">Parent Category</label>
								<select name="ParentCatg">
									<option value='0'>None(Main)</option>
									<?php 
										foreach ($allCatgs as $catg) {
											echo "<option value='" . $catg['ID'] . "'>";
												echo $catg['Name'];
											echo "</option>";
										}
									?>
								</select>
							</div>



						<!-- End Parent of Category Field -->

						<!-- Start Visibility Category Field -->
							<div class="field">
								<label class="lebelOfinput">Visibility</label>
								<div class="radiopart">
									<div class="radiofield">
										<input id="visi-yes" type="radio" name="visibility" value="1" checked> 
										<label for="visi-yes">Yes</label>
									</div>
									<div class="radiofield">
										<input id="visi-no" type="radio" name="visibility" value="0"> 
										<label for="visi-no">No</label>
									</div>
								</div>
							</div>

						<!-- End Visibility Category Field -->
							
						<!-- Start Allow comments Category Field -->
							<div class="field">
								<label class="lebelOfinput">Allow Comments</label>
								<div class="radiopart">
									<div class="radiofield">
										<input id="com-yes" type="radio" name="allowComment" value="1" checked> 
										<label for="com-yes">Yes</label>
									</div>
									<div class="radiofield">
										<input id="com-no" type="radio" name="allowComment" value="0"> 
										<label for="com-no">No</label>
									</div>
								</div>
							</div>
						<!-- End Visibility Category Field -->	

						<!-- Start Allow comments Category Field -->
							<div class="field">
								<label class="lebelOfinput">Allow Advertisement</label>
								<div class="radiopart">
									<div class="radiofield">
										<input id="advs-yes" type="radio" name="allowAdvs" value="1" checked> 
										<label for="advs-yes">Yes</label>
									</div>
									<div class="radiofield">
										<input id="advs-no" type="radio" name="allowAdvs" value="0"> 
										<label for="advs-no">No</label>
									</div>
								</div>
							</div>
						<!-- End Visibility Category Field -->	
						<div class="field bt">
							<input type="submit" value="Add Category">
							
						</div>
						</form>
					</div>
				</div>


	

	<?php elseif($do == 'Edit' || $do == 'Update'): ?>
			<?php 
				if ($do == 'Update') {
					if($_SERVER["REQUEST_METHOD"] != "POST") {
					$msg = "<div class='alert alert-danger'>This page cannot be accessed directly</div>";
					Redirect($msg,'back');
					exit();
					}	
				}

				if((isset($_GET['id']) && is_numeric($_GET['id']) && checkElement('ID', 'categories', $_GET['id'])) ||
					($do == "Update" && $_SERVER["REQUEST_METHOD"] == "POST" && checkElement('ID', 'categories', $_POST['id'])) ):

						$idReq = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
						$errorsForm = array();
						$data = $con->prepare('SELECT * From categories WHERE ID = ?');
						$data->execute(array($idReq));
						$catgs = $data->fetch();
						$info=array();
						$info['name'] = $catgs['Name'];
						$info['desc'] = $catgs['Description'];
						$info['order'] = $catgs['Ordering'];
						$info['visibl'] = $catgs['Visibility'];
						$info['allowComment'] = $catgs['Allow_comments'];
						$info['allowAdvs'] = $catgs['Allow_Advs'];

					if($do == 'Update' || $do == 'UpdateMember') {
						$id = $_POST['id'];
						$name= $_POST['name'];
						$desc = $_POST['desc'];
						$order = $_POST['order'];
						$parent = $_POST['ParentCatg'];
						$visibl = $_POST['visibility'];
						$comments = $_POST['allowComment'];
						$advs = $_POST['allowAdvs'];
						/*== Validate the Data ==*/

							// Validate user name
						$notLetters = array ('1','2','3','4','5','6','7','8','9','0','*','-','@','!','_','.','&','%','+',
									'(','/',')','[',']','^','$','#',',','?','>','<',',');

					 	$find = checkElement('name', 'categories', $name);
					 	$nameValid = returnElement('Name', 'categories', 'ID', $id);

					 //Validate name
						if (strlen($name) < 2 || strlen($name) > 15){
							$errorsForm['nameER'] = "<span class='span-notCorrect'>Must Be More than 1 letters and less than 15 letters </span>";
						} elseif (in_array($name[0],$notLetters)) {
							$errorsForm['nameER'] = "<span class='span-notCorrect'>Must be the first letter a letter</span>";
						} elseif($find == true && $nameValid['Name'] !== $name) {
							$errorsForm['nameER'] = "<span class='span-notCorrect'> Category Name Already Exists</span>";
						}
						
						//Validate Ordering
						if (empty($order)){
							$errorsForm['orderER'] = "<span class='span-notCorrect'> Write Order number</span>";
						}
						elseif (!is_numeric($order)) {
							$errorsForm['orderER'] = "<span class='span-notCorrect'> Must Be a Number</span>";
						} 
						$info['name'] = $name;
						$info['desc'] = $desc;
						$info['order'] = $order;
						$info['visibl'] = $visibl;
						$info['allowComment'] = $comments;
						$info['allowAdvs'] = $advs;

						if(empty($errorsForm)) {
							$set = $con->prepare('UPDATE categories 
													SET Name = ?, Description = ?,
													 	Ordering = ?, Parent_Catg = ?, visibility = ?,
													 	Allow_comments = ?, Allow_Advs = ?
													 Where ID = ?');
							$set->execute(array($name, $desc, $order, $parent,$visibl, $comments, $advs, $id));
							$succs = "<div class='alert alert-success'>Update is Successfull</div>";
							$url = "categories.php"; 
						}
						
					}
			?>
			<div class="mainForm">
					<div class="mycontainer">		
						<h3>Edit Category</h3>
						<form  action="?do=Update" method="POST">
						<?php if (isset($succs)) { Redirect($succs,$url);} else { echo "";}?>
						<!-- Start Name Category Field -->
						<input type="hidden" name="id" value="<?php echo $catgs['ID']; ?>">
							<div class="field">
								<label class="lebelOfinput">Name</label>
								<input type="text" name="name" autocomplete="off" required="" 
										value="<?php echo isset($info['name']) ? $info['name'] : ""; ?>">
								<?php echo isset($errorsForm['nameER'])?$errorsForm['nameER']:"";?>
							</div>	
						<!-- End Name Category Field -->

						<!-- Start Description Category Field -->
							<div class="field">
								<label class="lebelOfinput" >Description</label>
								<input type="text" name="desc"
										value="<?php echo isset($info['desc']) ? $info['desc'] : ""; ?>">	
							</div>
						<!-- End Description Category Field -->

						<!-- Start Ordering Category Field -->
							<div class="field">
								<label class="lebelOfinput">Ordering</label>
								<input type="text" name="order" autocomplete="off" required=""
										value="<?php echo isset($info['order']) ? $info['order'] : ""; ?>" >
								<?php echo isset($errorsForm['orderER'])?$errorsForm['orderER']:"";?>

							</div>	
						<!-- End Ordering Category Field -->

							<!-- Start Parent of Category Field -->
							<?php 

								$allCatgs = getFromAll("*", "categories", "WHERE Parent_Catg = 0", "Name");

							?>
							<div class="field">
								<label class="lebelOfinput">Parent Category</label>
								<select name="ParentCatg">
									<option value='0'>None(Main)</option>
									<?php 
										foreach ($allCatgs as $maincatg) {
											echo "<option value='" . $maincatg['ID'] . "'";
											if($catgs["Parent_Catg"] == $maincatg['ID']) {
												echo "Selected";
											}
											echo ">";
												echo $maincatg['Name'];
											echo "</option>";
										}
									?>
								</select>
							</div>



						<!-- End Parent of Category Field -->

						<!-- Start Visibility Category Field -->
							<div class="field">
								<label class="lebelOfinput">Visibility</label>
								<div class="radiopart">
									<div class="radiofield">
										<input id="visi-yes" type="radio" name="visibility" 
										value="1" <?php echo isset($info['visibl']) && $info['visibl'] == 1 ? "checked" : ""; ?> > 
										<label for="visi-yes">Yes</label>
									</div>
									<div class="radiofield">
										<input id="visi-no" type="radio" name="visibility" value="0"
										<?php echo isset($info['visibl']) && $info['visibl'] == 0 ? "checked" : ""; ?> > 
										<label for="visi-no">No</label>
									</div>
								</div>
							</div>

						<!-- End Visibility Category Field -->
							
						<!-- Start Allow comments Category Field -->
							<div class="field">
								<label class="lebelOfinput">Allow Comments</label>
								<div class="radiopart">
									<div class="radiofield">
										<input id="com-yes" type="radio" name="allowComment" value="1" 
										<?php echo isset($info['allowComment']) && $info['allowComment'] == 1 ? "checked" : ""; ?> > 
										<label for="com-yes">Yes</label>
									</div>
									<div class="radiofield">
										<input id="com-no" type="radio" name="allowComment" value="0"
										<?php echo isset($info['allowComment']) && $info['allowComment'] == 0 ? "checked" : ""; ?>> 
										<label for="com-no">No</label>
									</div>
								</div>
							</div>
						<!-- End Visibility Category Field -->	
						
						<!-- Start Allow advs Category Field -->
							<div class="field">
								<label class="lebelOfinput">Allow Advertisement</label>
								<div class="radiopart">
									<div class="radiofield">
										<input id="advs-yes" type="radio" name="allowAdvs" value="1" 
										<?php echo isset($info['allowAdvs']) && $info['allowAdvs'] == 1 ? "checked" : ""; ?>> 
										<label for="advs-yes">Yes</label>
									</div>
									<div class="radiofield">
										<input id="advs-no" type="radio" name="allowAdvs" value="0"
										<?php echo isset($info['allowAdvs']) && $info['allowAdvs'] == 0 ? "checked" : ""; ?>> 
										<label for="advs-no">No</label>
									</div>
								</div>
							</div>
						<!-- End advs Category Field -->	
						<div class="field bt">
							<input type="submit" value="Save">
							
						</div>
						</form>
					</div>
				</div>


<?php 
			else: 
					$msg = "<div class='alert alert-danger'>ID is not correct</div>";
						Redirect($msg,"back");
			endif;
?>

<?php
else:
	header("Location:categories.php");
endif;
}else{
	header("Location: index.php");
	exit(); 
}
?>


<?php include $tpl . "footer.php"; ?>