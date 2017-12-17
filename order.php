<?php
	include "database.php";
	if (!isset($_SESSION["user"])) {
		header("Location: login.php?message=You+must+be+logged+in+to+see+this+page.");
	}
	if (isset($_GET["id"])) {
		$design = DB::queryFirstRow("SELECT * FROM designs WHERE id=%s", $_GET["id"]);
		if (strpos($design["name"], "MANUAL_UPLOAD_") !== false) {
			$manual = 1;
		} else {
			$manual = 0;
		}
		$cost = 299;
		$product = json_decode($design["code"])->product;
	} else {
		header("Location: designs.php");
	}
	if ($_POST) {
		DB::insert("orders", [
			"qty_XS" => $_POST["qty_XS"],
			"qty_S" => $_POST["qty_S"],
			"qty_M" => $_POST["qty_M"],
			"qty_L" => $_POST["qty_L"],
			"qty_XL" => $_POST["qty_XL"],
			"qty_XXL" => $_POST["qty_XXL"],
			"customerName" => $_POST["customerName"],
			"customerContactNo" => $_POST["customerContactNo"],
			"customerEmail" => $_POST["customerEmail"],
			"shippingAddress1" => $_POST["shippingAddress1"],
			"shippingAddress2" => $_POST["shippingAddress2"],
			"shippingPin" => $_POST["shippingPin"],
			"shippingCity" => $_POST["shippingCity"],
			"shippingCountry" => $_POST["shippingCountry"],
			"razorpay_payment_id" => $_POST["razorpay_payment_id"],
			"status" => $_POST["status"],
			"ordered_at" => time(),
			"last_updated" => time(),
			"orderedby" => $_SESSION["user"]["id"],
			"product" => $_GET["id"],
		]);
		header("Location: designs.php?message=Your+order+has+been+placed");
	}
?>
<!doctype html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<title>Order &ndash; Creator</title>

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
			<div class="container pt-4 mt-4 pb-4">
				<div class="row justify-content-center">
					<div class="col-md-5">
						<div class="card p-5">
							<h1 class="h4 text-center">Your design</h1>
							<img class="mt-4" src="<?= $manual == 1 ? $design["code"] : "images/designs/" . $design["slug"] . ".png" ?>">
							<ul class="list-group mt-4">
								<li class="list-group-item"><strong>Product ID: </strong><?= $design["slug"] ?></li>
								<li class="list-group-item"><strong>Type: </strong><?= ucfirst($product) ?></li>
								<li class="list-group-item"><strong>Name: </strong><?= $design["name"] ?></li>
								<li class="list-group-item"><strong>Created: </strong><?= date("d-M-Y H:i a", $design["created_at"]) ?></li>
								<li class="list-group-item"><strong>Cost per piece: </strong>&#8377;<?= number_format((float)$cost, 2, '.', '') ?></li>
							</ul>
						</div>
					</div>
					<div class="col-md-5">
						<div class="card p-5">
							<h1 class="h4 text-center">New order</h1>
							<?php if ($_GET["message"]) { ?><div class="alert alert-info mt-3" role="alert">
								<?= $_GET["message"]; ?>
							</div><?php } ?>
							<?php if ($error) { ?><div class="alert alert-danger mt-3" role="alert">
								<?= $error; ?>
							</div><?php } ?>
							<form class="mt-3 form-order" method="post">
								<section>
									<p>What sizes do you need?</p>
									<div class="row">
										<div class="col-6 col-md-4">
											<div class="form-group">
												<label for="qty_XS">XS</label>
												<input type="number" onchange="updateQty()" min="0" class="form-control" name="qty_XS" id="qty_XS" value="0" required>
											</div>
										</div>
										<div class="col-6 col-md-4">
											<div class="form-group">
												<label for="qty_S">S</label>
												<input type="number" onchange="updateQty()" min="0" class="form-control" name="qty_S" id="qty_S" value="0" required>
											</div>
										</div>
										<div class="col-6 col-md-4">
											<div class="form-group">
												<label for="qty_M">M</label>
												<input type="number" onchange="updateQty()" min="0" class="form-control" name="qty_M" id="qty_M" value="1" required>
											</div>
										</div>
										<div class="col-6 col-md-4">
											<div class="form-group">
												<label for="qty_L">L</label>
												<input type="number" onchange="updateQty()" min="0" class="form-control" name="qty_L" id="qty_L" value="0" required>
											</div>
										</div>
										<div class="col-6 col-md-4">
											<div class="form-group">
												<label for="qty_XL">XL</label>
												<input type="number" onchange="updateQty()" min="0" class="form-control" name="qty_XL" id="qty_XL" value="0" required>
											</div>
										</div>
										<div class="col-6 col-md-4">
											<div class="form-group">
												<label for="qty_XXL">XXL</label>
												<input type="number" onchange="updateQty()" min="0" class="form-control" name="qty_XXL" id="qty_XXL" value="0" required>
											</div>
										</div>
									</div>
									<p class="mb-2 mt-2">Total quantity: <strong class="total-qty">1</strong><br></p>
									<p>Total price: <strong>&#8377;<span class="total-price"><?= number_format((float)$cost, 2, '.', '') ?></span></strong></p>
									<button onclick="$(this).parent().hide();$(this).parent().next().show()" type="button" class="btn btn-primary btn-block mt-4">Continue to shipping info<i class="material-icons ml-2">arrow_forward</i></button>
								</section>
								<section style="display: none">
									<p>What is the customer information?</p>
									<div class="form-group">
										<label for="customerName">Customer name</label>
										<input type="text" class="form-control" name="customerName" id="customerName" placeholder="Enter full name" required>
									</div>
									<div class="form-group">
										<label for="customerContactNo">Customer contact number</label>
										<input type="text" class="form-control" name="customerContactNo" id="customerContactNo" placeholder="Enter phone number" required>
									</div>
									<div class="form-group">
										<label for="customerEmail">Customer email</label>
										<input type="text" class="form-control" name="customerEmail" id="customerEmail" placeholder="Enter email" required>
									</div>
									<div class="form-group">
										<label for="shippingAddress1">Shipping address 1</label>
										<input type="text" class="form-control" name="shippingAddress1" id="shippingAddress1" placeholder="Enter shipping address 1" required>
									</div>
									<div class="form-group">
										<label for="shippingAddress2">Shipping address 2</label>
										<input type="text" class="form-control" name="shippingAddress2" id="shippingAddress2" placeholder="Enter shipping address 2 (optional)">
									</div>
									<div class="form-group">
										<label for="shippingPin">PIN code</label>
										<input type="text" class="form-control" name="shippingPin" id="shippingPin" placeholder="Enter city" required>
									</div>
									<div class="form-group">
										<label for="shippingCity">City</label>
										<input type="text" class="form-control" name="shippingCity" id="shippingCity" placeholder="Enter city" required>
									</div>
									<div class="form-group">
										<label for="shippingCountry">Country</label>
										<input type="text" class="form-control" name="shippingCountry" id="shippingCountry" placeholder="Enter country" value="India" required>
									</div>
									<button onclick="$(this).parent().hide();$(this).parent().next().show()" type="button" class="btn btn-primary btn-block mt-4">Continue to order summary<i class="material-icons ml-2">arrow_forward</i></button>
								</section>
								<section style="display: none">
									<p>Your order summary is as follows:</p>
									<ul class="list-group mt-4">
										<li class="list-group-item"><strong>Cost per piece: </strong>&#8377;<?= number_format((float)$cost, 2, '.', '') ?></li>
										<li class="list-group-item"><strong>Total quantity: </strong><span class="total-qty">1</span></li>
										<li class="list-group-item"><strong>Total price: </strong>&#8377;<span class="total-price"><?= number_format((float)$cost, 2, '.', '') ?></span></li>
									</ul>
									<input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
									<button type="button" onclick="razorPay();" class="btn btn-primary btn-block mt-4">Order now<i class="material-icons ml-2">arrow_forward</i></button>
								</section>
							</form>
						</div>
					</div>
				</div>
			</div>
		</main>

		<script src="https://a11y.co/agastya.js" async defer></script>
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<script src="https://checkout.razorpay.com/v1/checkout.js" async defer></script>
		<script>
			$("[data-toggle='tooltip']").tooltip();
			var totalQty = 1;
			function updateQty() {
				totalQty = parseInt($("#qty_XS").val()) + parseInt($("#qty_S").val()) + parseInt($("#qty_M").val()) + parseInt($("#qty_L").val()) + parseInt($("#qty_XL").val()) + parseInt($("#qty_XXL").val());
				$(".total-qty").text(totalQty);
				$(".total-price").text(<?= $cost ?> * totalQty + ".00");
			}
			function razorPay() {
				options = {
					"key": "rzp_test_4C2ERClkBUlDzJ",
					"amount": parseInt(<?= $cost ?> * totalQty * 100),
					"name": "Melangebox",
					"description": totalQty + " custom-made " + "<?= $product ?>" + (totalQty == 1 ? "" : "s"),
					"image": "",
					"prefill": {
						"contact": "<?= $_SESSION["user"]["phone"] ?>",
						"email": "<?= $_SESSION["user"]["email"] ?>"
					},
					"handler": function(transaction) {
						transactionHandler(transaction);
					}
				};
				var rzp1 = new Razorpay(options);
				rzp1.open();
			}
			function transactionHandler(transaction) {
				console.log(transaction);
				if (transaction.razorpay_payment_id) {
					$("#razorpay_payment_id").val(transaction.razorpay_payment_id);
					$(".form-order").submit();
				} else {
					alert("Payment could not be completed. Please try again!");
				}
			}
		</script>
	</body>

</html>