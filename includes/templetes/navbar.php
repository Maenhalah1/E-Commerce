<div class="container"> 
  <div class="upperBar">
    <?php if (!isset($_SESSION['user'])) {?>
    <a href="login.php" class="loginUpperBar"><span>Login / Signup</span></a>
  <?php }else{
      if(!CheckActiveUser($_SESSION['user'])) {
        echo "not Active";
      }?>
      <?php 
        $Img = returnElement("Image_Profile", "users", "UserID", $_SESSION['uid']);
      ?>
      <div class="dropdownuser">
        <span class="username-dropdown">
          <?php if(empty($Img)):?>
            <image src="<?php echo $Urlimgs;?>images.png"></image>
          <?php else:?>
             <image src="files_upload/profileImg/<?php echo $Img;?>"></image>
          <?php endif;?>
            <span><?php echo $_SESSION['user'];?></span>
            <i class="fa fa-caret-down" aria-hidden="true"></i>
        </span>
        <ul class="linksdropdown">
          <a href="profile.php" ><li>My Profile</li></a>
          <a href="newitem.php" ><li>Add Product</li></a>
          <a href="#" ><li>test</li></a>
          <a href="logout.php" ><li>Log out</li></a>
        </ul>
      </div>
     
    
   
   
  <?php }?>
  </div>
</div>
<nav class="navbar navbar">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">E-Commerce</a>
    </div>

    
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav mylinks-nav">
        <li><a href="index.php">HomePage</a></li>
      </ul>

      
      <!-- <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> user <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="members.php?do=Edit&id=<?php// echo $_SESSION['userid']; ?>"><?php //echo lang('Edit Profile')?></a>
            </li>
            <li><a href="../index.php">To Site</a></li>
            <li><a href="#"><?php //echo lang('Settings')?></a></li>
            <li><a href="logout.php"><?php //echo lang('Logout')?></a></li>
          </ul>
        </li>
      </ul> -->
      <div class="myselect-nav navbar-right">
        <select class="catgs-box" onchange="location = this.value;">
          <option value="category.php?catgid=0&pagename=all">All Categories</option>
          <?php 
            $allCatg = getFromAll("*", "Categories", "WHERE Parent_Catg = 0", "Name", "ASC");
            foreach ($allCatg as $catg) {
              echo "<option value='category.php?catgid=". $catg['ID'] ."&pagename=" . str_replace(" ", "_", $catg['Name']) . "'";
              if(isset($_GET['catgid']) && $_GET['catgid'] == $catg['ID']) {echo "Selected >";}else{echo ">";}
              echo  $catg['Name']; 
              echo "</option>";
            }

          ?>
          <
        </select>
      </div>
    </div>
  </div>
</nav>