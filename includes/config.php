<?php

try {
$db_user = 'root';
$db_password= '';
$db_name = 'phprest';

$db = new PDO('mysql:host=127.0.0.1;dbname='.$db_name.';charset=utf8',$db_user,$db_password);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

?>

