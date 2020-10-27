<?php 
ob_start();
session_start();
if(isset($_SESSION['user'])):
$pageTitle = 'New Product';
$noSelectit="";
include "init.php"; 
?>

<?php 

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$formError	= array();
	$title 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$desc 		= filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
	$price 		= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
	$country 	= $_POST['countryMade'];
	$status 	= $_POST['status'];
	$tags 		= filter_var($_POST['tags'], FILTER_SANITIZE_STRING); 
	$category 	= $_POST['category'];
	$tags = str_replace(" ", "", $tags);
	$tags = strtolower($tags);

// Validate Title 
	if(empty($title)) {
		$formError['name'] = "Please Type Title Product";
	}else if(strlen($title) < 4) {
		$formError['name'] = "Must Be Larger Than 4 Letters";
	}else {
		setcookie('title', $title, time() + 60);
	}

// Validate Description 
	if(empty($desc)) {
		$formError['desc'] = "Please Type Description Product";
	} else if(strlen($desc) < 4) {
		$formError['desc'] = "Must Be Larger Than 4 Letters";
	}else {
		setcookie('desc', $desc, time() + 60);
	}


// Validate Price 
	
	if(empty($price)) {
		$formError['price'] = "Please Type  Price Product";
	} else if($price <= 0) {
		$formError['price'] = "Must Be Larger than 0";
	}else if ( ! is_numeric($_POST['price'])) {
		$formError['price'] = "Must Be number";
	}else {
		setcookie('price', $price, time() + 60);
	}

// Validate Country 
	if(empty($country)) {
		$formError['country'] = "Please Choose Country of Made of Product";
	}else {
		setcookie('countryMade', $country, time() + 60 );
	}

// Validate Status 
	if(empty($status)) {
		$formError['status'] = "Please Choose Status of Product";
	}else {
		setcookie('status', $status, time() + 60);
	}

// Validate Category
	if(empty($category)) {
		$formError['category'] = "Please Choose the Category that Belongs Product";
	}else {
		setcookie('category', $category, time() + 60);
	}
	setcookie('tags', $tags, time() + 60);
	if(empty($formError)) {
		
		$stmt = $con->prepare("INSERT INTO items (Name, Description, Price, Add_Date, Country_Made, Status, Catg_ID, Member_ID, Tags)
								VALUES (:name, :descr, :price, now(), :country, :status, :catg, :member, :tags)");
		
		$stmt->execute(
			array(      ':name' 	=> $title,
						':descr' 	=> $desc,
						':price' 	=> $price,
						':country'	=> $country,
						':status'	=> $status,
						':catg'		=> $category,
						':member'	=> $_SESSION['uid'],
						':tags' 	=> $tags ));
		if($stmt){
			setcookie('title',"", time() - 60);
			setcookie('desc', "", time() - 60);
			setcookie('price', "", time() - 60);
			setcookie('status', "", time() - 60);
			setcookie('category', "", time() - 60);
			setcookie('countryMade', "", time() - 60);
			setcookie('tags', $tags, time() - 60);
			$msgSuc = "<div class='alert alert-success'> Add new Product is Successfully</div>";

		}
		
	}

}

?>
<div class="info-profile newItem">
	<h3 class="MainHeader"> Create New Product</h3>
	<div class="container">
		<?php if(isset($msgSuc)) { Redirect($msgSuc, "index.php"); }?> 
		<div class="panel-container">
			<div class="panel panel-default mypanel">
				<div class="panel-heading">Add new Product</div>
				<div class="panel-body">
					<div class="add-form">
						<form class="add-box" action="newitem.php" method="POST">
							<div class="field">
								<label>Product Title</label>
								<input type="text" name="name" data-class = '.head' 
									class="live required" required value='<?php echo isset($_COOKIE['title']) ? $_COOKIE['title'] : ""; ?>'>
							<span class="span-notCorrect"><?php echo isset($formError['name']) ? $formError['name'] : "" ?></span>
							</div>
							<div class="field">
								<label>Description of Product</label>
								<input type="text" name="desc" data-class = '.desc'
								 	class="live required" required value='<?php echo isset($_COOKIE['desc']) ? $_COOKIE['desc'] : ""; ?>' >
							<span class="span-notCorrect"><?php echo isset($formError['desc']) ? $formError['desc'] : "" ?></span>
							</div>
							<div class="field">
								<label>Price of Product</label>
								<input type="text" name="price" data-class = '.price' 
									class="live required" required value='<?php echo isset($_COOKIE['price']) ? $_COOKIE['price'] : ""; ?>' >
							<span class="span-notCorrect"><?php echo isset($formError['price']) ? $formError['price'] : "" ?></span>
							</div>
							<div class="field">
								<label> Tags</label>
								<input type="text" name="tags"
									value='<?php echo isset($_COOKIE['tags']) ? $_COOKIE['tags'] : ""; ?>' >
							</div>
							<div class="field">
								<label>Country of Made</label>
								<select name="countryMade">
									<option value="">...</option>
									<?php 
										foreach ($country_list as $country) {
											echo "<option value='". $country ."'";
											if(isset($_COOKIE['countryMade']) && $_COOKIE['countryMade'] == $country) {
												echo "selected";
											}
											echo ">" . $country . "</option>";
										}
									?>
								</select>
								<span class="span-notCorrect"><?php echo isset($formError['country']) ? $formError['country'] : "" ?></span>
							</div>
							<div class="field">
								<label>Status of Product</label>
								<select name="status">
									<div>Status of Product</div>
									<option value="">...</option>
									<option value="Used" <?php if(isset($_COOKIE['status']) && $_COOKIE['status'] == "Used" ) {echo "selected";}?>>Used</option>
									<option value="New" <?php if(isset($_COOKIE['status']) && $_COOKIE['status'] == "New" ) {echo "selected";}?>>New</option>
								</select>
								<span class="span-notCorrect"><?php echo isset($formError['status']) ? $formError['status'] : "" ?></span>
							</div>
							<div class="field">
								<label class="lebelOfinput">Category</label>
								<select name="category">
									<option value="">...</option>
									<?php 
									$stmt2 = $con->prepare("SELECT * FROM categories");
									$stmt2->execute();
									$catgs = $stmt2->fetchAll();
									foreach ($catgs as $catg) {
										echo "<option value='" . $catg['ID'] . "' class='maincatg'";
										if(isset($_COOKIE['category']) && $_COOKIE['category'] == $catg['ID'] ) {
												echo "selected";
											}
										echo ">" . $catg['Name'] . "</option>"; 
										$allSubCatg = getFromAll("*","categories","WHERE Parent_Catg = {$catg['ID']}","Name",ASC);
										if(!empty($allSubCatg)) {
											foreach ($allSubCatg as $subcatg) {									
												echo "<option class='subcatg' value='" . $subcatg['ID'] . "'";
													if(isset($_COOKIE['category']) && $_COOKIE['category'] == $subcatg['ID'] ) {
													echo "selected";
													}
												echo "> -- " .  $subcatg['Name'] . " { " . $catg['Name'] . " }"; 
												 echo"</option>";
											}
										}
									}
									?>
								</select>	
								<span class="span-notCorrect"><?php echo isset($formError['category']) ? $formError['category'] : "" ?></span>
							</div>	
						
								<input type="submit" value="Add new Product" class="btn ">
							
						</form>		
					</div>
					<div class="show-product">
						<div class="CatgItems">
							<div class='ItemBox'>
									<span class='Mainprice'>$<span class="price">0</span></span>
									<img src='https://via.placeholder.com/500/'>
									<h3 class="head">None</h3>
									<p class="desc">None</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>




<?php 

else:
	header("Location: index.php");
endif;
include $tpl ."footer.php";
ob_end_flush();

?>