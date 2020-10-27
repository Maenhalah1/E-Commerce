<?php 


//function use to get all categories (V1.0)

function getCatgs() {
	global $con;	
	$stmt = $con->prepare("SELECT * FROM categories ORDER BY Name ASC");
	$stmt->execute();
	return  $stmt->fetchAll();
}

function getFromAll($fields, $table, $where =null , $FieldOrder, $ordering = "DESC") {
	global $con;
	$stmt = $con->prepare("SELECT $fields FROM $table $where ORDER BY $FieldOrder $ordering");


	$stmt->execute();
	return  $stmt->fetchAll();
}

//function use to get items by category id (V1.0)
function getItems($condition = null , $value = null, $active = NULL) {
	global $con;	
	
	if($condition  == null || $value  == null || $value == 0) {
		$sql = ($active == NULL) ? "WHERE accepte = 1" : "";
		$stmt = $con->prepare("SELECT * FROM items $sql ORDER BY items_ID DESC");
		$stmt->execute();
	} else {
		$sql = ($active == NULL) ? "AND accepte = 1" : "";
		$stmt = $con->prepare("SELECT * FROM items WHERE $condition = ? $sql ORDER BY items_ID DESC");
		$stmt->execute(array($value));
	}

	return  $stmt->fetchAll();
}

// This function used to check if the user active or not

function CheckActiveUser($user) {
	global $con;
	$stmtC = $con->prepare("SELECT UserName, AccepteAccount FROM users WHERE UserName = ? AND AccepteAccount = 1");
	$stmtC->execute(array($user));
	return $stmtC->rowCount();
}

//function use to set title of every page
function getTitle() { 
	global $pageTitle;
	if (isset($pageTitle)) {
		echo $pageTitle;
	} else {
		echo "Default";
	}
}

//This function use to Redirect to anthor page (v2.0)
function Redirect($msg ,$url=null ,$second = 2) {
	
	if ($url === null) {
		$url = 'index.php';
	} else {
		if ($url == 'back') {
			$url = (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== null) ? $_SERVER['HTTP_REFERER'] : 'index.php';
		}else if($url == "home"){
			$url = "index.php";
		}
	}
	echo $msg;
	header("refresh:$second;url=$url");
}


//this function use to return element from data base by userid  (v1.0)
function returnElement($select, $table, $id, $valueid) {
	global $con;
	$stmt = $con->prepare("SELECT $select FROM $table WHERE $id = ?");
	$stmt->execute(array($valueid));
	$data = $stmt->fetch(PDO::FETCH_BOTH);
	return $data[0];
}


// This Function use to check if the element exist in data base
function checkElement($select, $table, $value) {
	global $con;
	$stmt = $con->prepare("SELECT $select FROM $table WHERE $select = ? LIMIT 1");
	$stmt->execute(array($value));
	$result = ($stmt->rowCount() > 0) ? true : false;
	return $result;
}


// This Function use to calculate number of items in table from data base (v1.0)
function countItem($select, $table, $condition = null, $valueofCondition = null) {
	global $con;
	$query= $condition == null || $valueofCondition == null ? "" : " WHERE " . $condition . " = " . $valueofCondition;
		$stmt = $con->prepare("SELECT COUNT($select) FROM $table $query");
	
	$stmt->execute();
	$result = $stmt->fetchColumn();
	return $result;
}



// this function use to return the latest items from data base 
function getLatest($select, $table, $order = null, $limit = 5){
	global $con; 
	$getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$getstmt->execute();
	$rows = $getstmt->fetchAll();
	return $rows;
}