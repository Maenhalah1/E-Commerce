<?php 
$dsn = "mysql:host=localhost;dbname=Ecommerce";
$userDB = 'root';
$passDB = '';
$option = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
try{
	$con = new PDO($dsn,$userDB,$passDB,$option);
	$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	

}catch(PDOEXCEPTION $e){
	echo 'Faild To Connect : ' . $e->getMessage();
}

?>