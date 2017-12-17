<?php
	include "database.php";
	if (!isset($_SESSION["user"]) && !isset($_SESSION["saveDesign"])) {
		$_SESSION["saveDesign"] = $_POST;
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
			$slug = substr(md5($_SESSION["user"]["id"] . $design["design_name"] . time()), 0, 6);
			define("UPLOAD_DIR", "images/designs/");
			$img = $design["image"];
			$img = str_replace("data:image/png;base64,", "", $img);
			$img = str_replace(" ", "+", $img);
			$data = base64_decode($img);
			$file = UPLOAD_DIR . $slug . ".png";
			$success = file_put_contents($file, $data);
			DB::insert("designs", [
				"code" => $design["code"],
				"name" => $design["design_name"],
				"owner" => $_SESSION["user"]["id"],
				"slug" => $slug,
				"created_at" => time()
			]);
			$_SESSION["saveDesign"] = null;
			header("Location: designs.php");
		}
	}
?>