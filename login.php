<?php
	include "database.php";
	if ($_POST) {
		if (isset($_POST["email"]) && isset($_POST["password"])) {
			$me = DB::queryFirstRow("SELECT * FROM users WHERE email=%s", $_POST["email"]);
			if (password_verify($_POST["password"], $me["password"])) {
				$_SESSION["user"] = $me;
				header("Location: designs.php");
			} else {
				$error = "Incorrect email or password.";
			}
		}
	}
?>
<!doctype html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<title>Login &ndash; Creator</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			.material-icons { font-size: 110%; vertical-align: middle; margin-top: -3px; }
			img { max-width: 100% }
			.card { box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1) }
			button:focus { position: relative; z-index: 1 }
		</style>

	</head>

	<body class="p-3 bg-light">

		<main id="content">
			<div class="container pt-4 mt-4 pb-4">
				<div class="row justify-content-center">
					<div class="col-md-5 card p-5">
						<h1 class="h4 text-center">Login</h1>
						<?php if ($_GET["message"]) { ?><div class="alert alert-info mt-3" role="alert">
							<?= $_GET["message"]; ?>
						</div><?php } ?>
						<?php if ($error) { ?><div class="alert alert-danger mt-3" role="alert">
							<?= $error; ?>
						</div><?php } ?>
						<form class="mt-1" method="post">
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required="">
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required="">
							</div>
							<button class="btn btn-primary" type="submit"><i class="material-icons mr-2">lock</i>Log in</button>
							<a class="btn btn-secondary ml-1" href="register.php">Register</a>
						</form>
						<!-- <p class="mt-3">Forgot your password? <a href="/recover">Recover it now</a></p> -->
					</div>
				</div>
			</div>
		</main>

		<script src="https://a11y.co/agastya.js" async defer></script>
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<script src="fabric.js"></script>
		<script src="https://ucarecdn.com/libs/widget/3.2.1/uploadcare.min.js" charset="utf-8"></script>
		<script src="creator.js"></script>
		<script>
			$("[data-toggle='tooltip']").tooltip();
		</script>
	</body>

</html>