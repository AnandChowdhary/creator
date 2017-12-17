<?php
	include "database.php";
	if (!isset($_SESSION["user"])) {
		header("Location: login.php?message=You+must+be+logged+in+to+see+this+page.");
	}
?>
<!doctype html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<title>My Designs &ndash; Creator</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			.material-icons { font-size: 110%; vertical-align: middle; margin-top: -3px; }
			img { max-width: 100% }
			.card, .shadow { box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1) }
			button:focus { position: relative; z-index: 1 }
		</style>

	</head>

	<body class="bg-light">

		<nav class="navbar navbar-expand-lg navbar-light bg-white shadow">
			<span class="navbar-brand">Melangebox</span>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item">
						<a class="nav-link" href="designs.php">Designs</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="designs.php">Orders</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="index.php">Creator</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="upload.php">Upload</a>
					</li>
				</ul>
				<ul class="navbar-nav">
					<li class="nav-item">
						<span class="nav-link"><?= $_SESSION["user"]["name"] ?><?= $_SESSION["user"]["company"] ? " (" . $_SESSION["user"]["company"] . ")" : "" ?></span>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="logout.php">Logout</a>
					</li>
				</ul>
			</div>
		</nav>

		<main id="content">
			<div class="container pb-4 mt-5">
				<div class="row justify-content-center">
					<?php if ($_GET["message"]) { ?><div class="alert alert-info mt-3" role="alert">
						<?= $_GET["message"]; ?>
					</div><?php } ?>
					<div class="col-md-12 card p-3">
						<div class="row">
							<div class="col">
								<h1 class="h4 mt-3 ml-3">🎨 &nbsp;My Designs</h1>
							</div>
							<div class="col text-right">
								<a href="upload.php" class="btn btn-secondary mr-3 mt-2">Upload design</a>
								<a href="index.php" class="btn btn-primary mr-3 mt-2">Create new design</a>
							</div>
						</div>
						<table class="table mt-4 mb-0">
							<thead>
								<tr>
									<th>Product ID</th>
									<th>Name</th>
									<th>Created</th>
									<th>Last Order</th>
									<th>Delete</th>
									<th>Order</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$designs = DB::query("SELECT * FROM designs WHERE owner=%s", $_SESSION["user"]["id"]);
									if (sizeof($designs) == 0) {
								?>
								<td colspan="42" class="text-center pt-5 pb-5">You have not saved any designs yet. 😞<br><br><a href="index.php" class="btn btn-primary mr-3 mt-2">Start designing!</a></td>
								<?php
									}
									foreach ($designs as $design) {
										if (strpos($design["name"], "MANUAL_UPLOAD_") !== false) {
											$manual = 1;
										} else {
											$manual = 0;
										}
								?>
								<tr>
									<td><a target="_blank" href="<?= $manual == 1 ? $design["code"] : "index.php?slug=" . $design["slug"] ?>"><?= $design["slug"] ?></a></td>
									<td><?= $manual == 1 ? substr($design["name"], 14, strlen($design["name"])) : $design["name"]; ?></td>
									<td><?= date("d-M-Y H:i a", $design["created_at"]) ?></td>
									<td>Never ordered</td>
									<td><a href="delete.php?id=<?= $design["id"] ?>" class="text-danger"><i class="material-icons mr-2">delete_forever</i>Delete</a></td>
									<td><a href="order.php?id=<?= $design["id"] ?>"><i class="material-icons mr-2">shopping_cart</i>Order Now</a></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="col-md-12 card p-3 mt-4">
						<h1 class="mt-3 h4 ml-3">🔖 &nbsp;My Orders</h1>
						<table class="table mt-4 mb-0">
							<thead>
								<tr>
									<th>Order ID</th>
									<th>Product Name</th>
									<th>Placed</th>
									<th>Last Updated</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$orders = DB::query("SELECT * FROM orders WHERE orderedby=%s", $_SESSION["user"]["id"]);
									if (sizeof($orders) == 0) {
								?>
								<td colspan="42" class="text-center pt-5 pb-5">You have not placed any orders yet. 😞</td>
								<?php
									}
									foreach ($orders as $order) {
										if (strpos($order["name"], "MANUAL_UPLOAD_") !== false) {
											$manual = 1;
										} else {
											$manual = 0;
										}
								?>
								<tr>
									<td><a target="_blank" href="details.php?id=<?= $order["id"] ?>">MEBX-CR-<?= $order["id"] ?></a></td>
									<td><?= DB::queryFirstRow("SELECT name FROM designs WHERE id=%s", $order["product"])["name"] ?></td>
									<td><?= date("d-M-Y H:i a", $order["ordered_at"]) ?></td>
									<td><?= date("d-M-Y H:i a", $order["ordered_at"]) ?></td>
									<td><?= $order["status"] ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</main>

		<script src="https://a11y.co/agastya.js" async defer></script>
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<script>
			$("[data-toggle='tooltip']").tooltip();
		</script>
	</body>

</html>