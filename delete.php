<?php
	include "database.php";
	DB::delete("designs", "id=%s AND owner=%s", $_GET["id"], $_SESSION["user"]["id"]);
	header("Location: designs.php");
?>