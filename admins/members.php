<?php
session_start(); 
if (isset($_SESSION['Username'])){
	$pageTitle = "Members";
	
	include "init.php";

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
?>


	<?php if ($do == 'Manage' || $do == 'Delete' || $do == 'Activate'): ;// Manage Members page 

				if($do == "Delete") {
						$userid = (isset($_GET['id']) &&  is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
						$check = checkElement('UserID', "users", $userid);
						if ($check > 0){
							$stmt = $con-> prepare('DELETE FROM users WHERE UserID = :id');
							$stmt->bindParam(":id", $userid);
							$stmt->execute();
							$succs = "<div class='alert alert-success'>Deleted was Successfull</div>";
							$url = 'back';
						}else{
							$succs = "<div class= 'alert alert-danger'>The User is not Founded</div>";
							$url = 'members.php';
						}
				}else if($do == "Activate"){
					$userid = (isset($_GET['id']) &&  is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
					$check = checkElement('UserID', "users", $userid);
						if ($check > 0){
							$stmt = $con-> prepare('UPDATE users SET AccepteAccount = 1 WHERE UserID = :id');
							$stmt->bindParam(":id", $userid);
							$stmt->execute();
							$succs = "<div class='alert alert-success'>Activation was Successfull</div>";
							$url = 'back';
						}else{
							$succs = "<div class= 'alert alert-danger'>The User is not Founded</div>";
							$url = 'members.php';
						}
				}
				$query = '';
				if (isset($_GET['page']) && $_GET['page'] == 'pending')
				{
					$query = 'AND AccepteAccount = 0';
				}

				$stmt = $con->prepare("SELECT * FROM users WHERE TypeUser != 1 $query ORDER BY AccepteAccount ASC, RegDate DESC");
				$stmt->execute();
				$data = $stmt->fetchAll();

				if (!empty($data)) {

	
	?>
		<div class="mycontainer">
			<h3 class="mainheader">Manage Members</h3>
			<?php if (isset($succs)) { Redirect($succs,$url,1);} else { echo "";}?>
			<div class="table-responsive">
			<table class="table main-table">
				<tr>
					<td>ID</td>
					<td>User Name</td>
					<td>Email</td>
					<td>Image Profile</td>
					<td>Full Name</td>
					<td>Registerd Date</td>
					<td>Control</td>
				</tr>
				<?php 
				foreach ($data as $row) {
					echo "<tr>";
						echo "<td>".$row['UserID'] ."</td>";
						echo "<td>".$row['UserName'] ."</td>";
						echo "<td>".$row['Email'] ."</td>";

						if(!empty($row['Image_Profile'])) {
							echo "<td><img src='../files_upload/profileImg/" . $row['Image_Profile'] . "' alt='Profile Image'></td>";
						} else {
							echo "<td style= 'color:red'>No Image </td>";
						}


						echo "<td>".$row['FullName'] ."</td>";
						echo "<td>".$row['RegDate']."</td>";
						echo "<td>
									<a href='members.php?do=EditMember&id=" . $row['UserID'] . "' 
										class='btn btn-success'>
									<i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i>
									Edit</a>
									<a href='members.php?do=Delete&id=". $row['UserID'] . "'
										class='btn btn-danger confirm' 
										onclick='return(checkConfirm())'>
										<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>
										 Delete</a>";
									if ($row['AccepteAccount'] == 0){
										echo "<a href='members.php?do=Activate&id=". $row['UserID'] . "'
										class='btn btn-info activate'>
										<i class=\"fa fa-check-circle\" aria-hidden=\"true\"></i>
										 Activate</a>";
									}	 

						 echo "</td>";
															
					echo "</tr>";
				}


				?>
			
			</table>
			</div>
			<a href="?do=Add&id=<?php echo $_SESSION['userid'];?>" class="toAdd">
				<i class="fa fa-user-plus" aria-hidden="true"></i> Add Members</a>

		</div>

		<?php 
			}else{
				echo "<div class='container'>";
					echo "<div class='noData noDataTables'>There isn't Members</div>";
					echo '<a href="?do=Add&id=<?php echo $_SESSION[\'userid\'];?>" class="toAdd">
				<i class="fa fa-user-plus" aria-hidden="true"></i> Add Members</a>';
				echo "</div>";
			}

		?>

	<?php elseif($do == 'Edit' || $do == 'Update' || $do == 'EditMember' || $do == 'UpdateMember')://Edit  Page

		if ($do == 'Update' || $do == 'UpdateMember') {
			if($_SERVER["REQUEST_METHOD"] != "POST") {
				$msg = "<div class='alert alert-danger'>This page cannot be accessed directly</div>";
				Redirect($msg,'back');
				exit();
			}
		}

		if(isset($_GET['id']) && is_numeric($_GET['id']) && 
			($_GET['id'] == $_SESSION['userid'] || $do == 'EditMember' || $do == 'UpdateMember') && 
			checkElement('UserID', 'users', $_GET['id'])):

				$errorsForm = array();
				$head = '';
				if($do == 'Update' || $do == 'Edit'){ $head = 'Edit Profile'; }else{ $head='Edit Member';}
				$data = $con->prepare('SELECT * From users WHERE UserID = ? Limit 1');
				$data->execute(array($_GET['id']));
				$count = $data->rowCount();
				if ($count > 0){
					$info = $data->fetch();
				}
			if($do == 'Update' || $do == 'UpdateMember') {

				if($_SERVER["REQUEST_METHOD"] == "POST") {

				$userid = $_POST['id'];
				$username= $_POST['username'];
				$name = $_POST['fullname'];
				$email = $_POST['email'];
				$oldpass = $_POST['oldpassword'];
				$newpass = $_POST['newpassword'];
				$renewpass = $_POST['renewpassword'];	
				$myusername =	returnElement('UserName', 'users', 'UserID',$userid); 
				/*== Validate the Data ==*/

					// Validate user name
				$notLetters = array ('1','2','3','4','5','6','7','8','9','0','*','-','@','!','_','.','&','%','+',
									'(','/',')','[',']','^','$','#',',','?','>','<',',');
				 
				$find = checkElement('UserName', 'users', $username);

				if (strlen($username) < 3 || strlen($username) > 15){
					$errorsForm['usernameER'] = "<span class='span-notCorrect'>Must Be More than 2 letters and less than 15 letters </span>";
				} elseif (in_array($username[0],$notLetters)) {
					$errorsForm['usernameER'] = "<span class='span-notCorrect'>Must be the first letter a letter</span>";
				} elseif($find == true && $myusername['UserName'] != $username) {

					$errorsForm['usernameER'] = "<span class='span-notCorrect'> User Name Already Exists</span>";
				}
					//Validate email
				
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$errorsForm['emailER'] = "<span class='span-notCorrect'>Write A correct Email</span>";
				}
					//Validate password
				if( empty($oldpass) && empty($newpass) && empty($renewpassword) ) {
					$pass = $_POST['valueoldpass'];
				} else {
					if (empty($oldpass)){
						$errorsForm['oldpassER'] = "<span class='span-notCorrect'>Write Your old password</span>";
					} else if($info['Password'] != sha1($oldpass)) {
						$errorsForm['oldpassER'] = "<span class='span-notCorrect'>not correct password</span>";
					}
					elseif(empty($newpass)) {
						$errorsForm['newpassER'] = "<span class='span-notCorrect'>Write Your new password</span>";

					} elseif(strlen($newpass) < 8 || (strpbrk($newpass,'0123456789') == null)) {
					 	$errorsForm['newpassER'] = "<span class='span-notCorrect'>Must be More Than 8 Letters and it Contains numbers</span>";

					 } elseif(empty($renewpass)) {
						$errorsForm['renewpassER'] = "<span class='span-notCorrect'>Rewrite Here Your new password</span>";
					} elseif($renewpass != $newpass) {
						$errorsForm['renewpassER'] = "<span class='span-notCorrect'>Password and Confirm Password are not Equal</span>";
					} else {
						$pass = $newpass;
					}
				}

				$info['UserName'] = $username;
				$info['Email'] = $email;
				$info['FullName'] = $name;

				if(empty($errorsForm)) {
				$set = $con->prepare('UPDATE users SET UserName = ?, Email = ?, FullName = ?, Password = ? Where UserID = ?');
				$set->execute(array($username, $email, $name, sha1($pass), $userid));
				$succs = "<div class='alert alert-success'>Update is Successfull</div>";
				$url = "back"; 
				}
			}
		} 
		
	?>
		<!-- Without Bootstrap -->
				<div class="mainForm">
					<div class="mycontainer">		
						<h3><?php echo $head; ?></h3>
						<form class="updateData" 
							action="<?php 
										if ($do == 'EditMember') {
											$to = 'UpdateMember';
										}else{
											$to = 'Update';
										}
										echo '?do=' . $to . '&id=' . $_GET['id'];
									?>" 
							  method="POST">
						<?php if (isset($succs)) { Redirect($succs,$url);} else { echo "";}?>
							<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
							<div class="field">
								<label class="lebelOfinput">User Name</label>
								<input type="text" name="username" autocomplete="off"  required="required"
								value="<?php echo $info['UserName']; ?>" >
								<?php echo isset($errorsForm['usernameER'])?$errorsForm['usernameER']:"";?>
							</div>	

							<div class="field">
								<label class="lebelOfinput" >Email</label>
								<input type="text" name="email" autocomplete="off" required="required"
								value="<?php echo $info['Email']; ?>">	
								<?php echo isset($errorsForm['emailER'])?$errorsForm['emailER']:"";?>
							</div>

							<div class="field">
								<label class="lebelOfinput">Full Name</label>
								<input type="text" name="fullname" autocomplete="off" required="required"
								value="<?php echo $info['FullName']; ?>">	
						</div>
							<input type="hidden" name="valueoldpass" value="<?php echo $info['Password']; ?>">
							
							<div class="field pass">
								<label class="lebelOfinput">Old Password</label>
								<input type="password" name="oldpassword" autocomplete="new-password" class="pass">
								<i class="fa fa-eye showing" aria-hidden="true"></i>
								<?php echo isset($errorsForm['oldpassER'])?$errorsForm['oldpassER']:"";?>
								
							</div>
							<div class="field pass">
								<label class="lebelOfinput">New Password</label>
								<input type="password" name="newpassword" autocomplete="new-password" class="pass">
								<i class="fa fa-eye showing" aria-hidden="true"></i>
								<?php echo isset($errorsForm['newpassER'])?$errorsForm['newpassER']:"";?>
							</div>
							<div class="field pass">
								<label class="lebelOfinput">Confirm New Password</label>
								<input type="password" name="renewpassword" autocomplete="new-password" class="pass">
								<i class="fa fa-eye showing" aria-hidden="true"></i>
								<?php echo isset($errorsForm['renewpassER'])?$errorsForm['renewpassER']:"";?>
							</div>
						
						<div class="clear"></div>
						<div class="field bt">
							<input type="submit" value="Save">
							
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
	elseif ($do == "Add" || $do == "Insert"): 
			if ($do == 'Insert') {
			if($_SERVER["REQUEST_METHOD"] != "POST") {
				$msg = "<div class='alert alert-danger'>This page cannot be accessed directly</div>";
				Redirect($msg,"back");
				exit();
			}
		}

		if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] == $_SESSION['userid']):

			$errorsForm = array();
			$infoadd = array();	
				
			if($do == 'Insert') {

				if($_SERVER["REQUEST_METHOD"] == "POST") {

				$userid = $_SESSION['userid'];
				$username= $_POST['username'];
				$name = $_POST['fullname'];
				$email = $_POST['email'];
				$newpass = $_POST['newpassword'];
				$renewpass = $_POST['renewpassword'];
				if(!empty($_FILES['profileImg']['name'])){
					$NameImg = $_FILES['profileImg']['name'];
					$typeImg = $_FILES['profileImg']['type'];
					$tmpImg = $_FILES['profileImg']['tmp_name']; 
					$sizeImg = $_FILES['profileImg']['size'];
					$ExtsAllowImg = array("jpeg", "jpg","png", "gif");
					$ExtImg = explode('.', $NameImg);
					$ExtImg = strtolower(end($ExtImg));

					// Vaildate Image Profile
					if(!in_array($ExtImg, $ExtsAllowImg)) {
						$errorsForm['Img'] = "<span class='span-notCorrect'>Your Type File is not Allowed ,please Choose a Image</span>";
					} else if ($sizeImg > 5242880) {
						$errorsForm['Img'] = "<span class='span-notCorrect'>Sorry, The Image Must be Less Than 5MB </span>";
					}
				} 
					 
				/*== Validate the Data ==*/

					// Validate user name
				$notLetters = array ('1','2','3','4','5','6','7','8','9','0','*','-','@','!','_','.','&','%','+',
									'(','/',')','[',']','^','$','#',',','?','>','<',',');

				 $find = checkElement('UserName', 'users', $username);

				if (strlen($username) < 3 || strlen($username) > 15){
					$errorsForm['usernameER'] = "<span class='span-notCorrect'>Must Be More than 2 letters and less than 15 letters </span>";
				} elseif (in_array($username[0],$notLetters)) {
					$errorsForm['usernameER'] = "<span class='span-notCorrect'>Must be the first letter a letter</span>";
				} elseif($find == true) {
					$errorsForm['usernameER'] = "<span class='span-notCorrect'> User Name Already Exists</span>";
				}
					//Validate email
				
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$errorsForm['emailER'] = "<span class='span-notCorrect'>Write A correct Email</span>";
				}
					//Validate password
				
				
					
					elseif(empty($newpass)) {
						$errorsForm['newpassER'] = "<span class='span-notCorrect'>Write Your password</span>";

					} elseif(strlen($newpass) < 8 || (strpbrk($newpass,'0123456789') == null)) {
					 	$errorsForm['newpassER'] = "<span class='span-notCorrect'>Must be More Than 8 Letters and it Contains numbers</span>";

					 } elseif(empty($renewpass)) {
						$errorsForm['renewpassER'] = "<span class='span-notCorrect'>Rewrite Here Your password</span>";
					} elseif($renewpass != $newpass) {
						$errorsForm['renewpassER'] = "<span class='span-notCorrect'>Password and Confirm Password are not Equal</span>";
					} else {
						$pass = $newpass;
					}
				

				

				if(empty($errorsForm)) {
					$rand = rand(0, 1000000000);
					$imgSource = $rand . "_" . $NameImg;
					move_uploaded_file($tmpImg, "../files_upload/profileImg/" . $imgSource);
					
				$set = $con->prepare('INSERT INTO users (UserName, Email, Password, FullName, AccepteAccount, RegDate, Image_Profile)
										VALUES(:user, :email, :password,:fullname, 1,now(), :img)');
				$set->execute(array('user' => $username, 'email' => $email,
									 'fullname' => $name,'password' => sha1($pass),
									 ':img' => $imgSource));
				$succs = "<div class='alert alert-success'>Add Member is Successfull</div>";
				$url = "members.php"; 
				} else {
					$infoadd['UserName'] = $username;
					$infoadd['Email'] = $email;
					$infoadd['FullName'] = $name;
				}
			} 	
		} 


		?>
	
			<!-- Without Bootstrap -->
				<div class="mainForm">
					<div class="mycontainer">		
						<h3>Add Members</h3>
						<form  action="?do=Insert&id=<?php echo $_SESSION['userid']; ?>" 
							method="POST" enctype="multipart/form-data">
						<?php if (isset($succs)) { Redirect($succs,$url);} else { echo "";}?>
							<div class="field">
								<label class="lebelOfinput">User Name</label>
								<input type="text" name="username" autocomplete="off" required=""
								value="<?php echo isset($infoadd['UserName'])? $infoadd['UserName']:""; ?>">
								<?php echo isset($errorsForm['usernameER'])?$errorsForm['usernameER']:"";?>
							</div>	

							<div class="field">
								<label class="lebelOfinput" >Email</label>
								<input type="text" name="email" autocomplete="off" required=""
								value="<?php echo isset($infoadd['Email'])? $infoadd['Email']:""; ?>">	
								<?php echo isset($errorsForm['emailER'])?$errorsForm['emailER']:"";?>
								
							</div>

							<div class="field">
								<label class="lebelOfinput">Full Name</label>
								<input type="text" name="fullname" autocomplete="off" required=""
								value="<?php echo isset($infoadd['FullName'])? $infoadd['FullName']:""; ?>">
							</div>	
							
							<div class="field">
								<label class="lebelOfinput">Password</label>
								<input type="password" name="newpassword" autocomplete="new-password" required="" class="pass hid">
								<i class="fa fa-eye showing" aria-hidden="true"></i>
								<?php echo isset($errorsForm['newpassER'])?$errorsForm['newpassER']:"";?>
							</div>
							<div class="field">
								<label class="lebelOfinput">Confirm Password</label>
								<input type="password" name="renewpassword" autocomplete="new-password" required=""
								class="pass hid">
								<i class="fa fa-eye showing" aria-hidden="true"></i>
								<?php echo isset($errorsForm['renewpassER'])?$errorsForm['renewpassER']:"";?>
							</div>

							<div class="field">
								<label class="lebelOfinput">Profile Image</label>
								<input type="file" name="profileImg" >
								<?php echo isset($errorsForm['Img'])?$errorsForm['Img']:"";?>
							</div>
						
						<div class="field bt">
							<input type="submit" value="Add Member">
							
						</div>
						</form>
					</div>
				</div>
<?php			
		else: 
			$msg = "<div class='alert alert-danger'>ID is not correct</div>";
				Redirect($msg,"back");
		endif;
			else:
				header("Location: members.php");
			endif;
?>


	<?php 
}else{
	header("Location: index.php");
	exit(); 
}
?>


<?php include $tpl . "footer.php"; ?>