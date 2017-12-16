<?php
	include "database.php";
	if (!isset($_SESSION["user"]) && !isset($_SESSION["saveDesign"])) {
		$_SESSION["saveDesign"] = $_POST;
		var_dump($_SESSION["saveDesign"]);
	}
	if (!isset($_SESSION["user"])) {
		header("Location: login.php?message=Please+log+in+or+register+to+save+this+design.");
	}
	if (isset($_SESSION["saveDesign"])) {
		$design = $_SESSION["saveDesign"];
	} else {
		$design = $_POST;
	}
	if (isset($_SESSION["user"])) {
		if ($design) {
			DB::insert("designs", [
				"code" => $design["code"],
				"name" => $design["design_name"],
				"owner" => $_SESSION["user"]["id"],
				"created_at" => time()
			]);
			$_SESSION["saveDesign"] = null;
			header("Location: designs.php");
		}
	}
?>