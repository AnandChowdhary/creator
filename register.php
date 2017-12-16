<?php
	include "database.php";
	if ($_POST) {
		$error = null;
		if (!($_POST["name"] && $_POST["email"] && $_POST["password"])) {
			$error = "Please enter your name, email, and password.";
		}
		$url = "https://www.google.com/recaptcha/api/siteverify";
		$options = array(
			"http" => array(
				"header"  => "Content-type: application/x-www-form-urlencoded\r\n",
				"method"  => "POST",
				"content" => http_build_query([
					"secret" => "6LdExBIUAAAAAKJf_kG2yAMqvMdhehrU_nazjUMm",
					"response" => $_POST["g-recaptcha-response"]
				])
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE || json_decode($result)->success == false) {
			$error = "The captcha you entered is incorrect.";
		}
		if ($_POST["password"] != $_POST["password2"]) {
			$error = "Your password do not match.";
		}
		if ($error == null) {
			DB::insert("users", [
				"name" => $_POST["name"],
				"email" => $_POST["email"],
				"company" => $_POST["company"],
				"password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
				"created_at" => time()
			]);
			header("Location: login.php?message=Your+account+has+been+created.+You+can+log+in+now.");
		}
	}
?>
<!doctype html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<title>Register &ndash; Creator</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			.material-icons { font-size: 110%; vertical-align: middle; margin-top: -3px; }
			img { max-width: 100% }
			.card { box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1) }
			button:focus { position: relative; z-index: 1 }
		</style>

		<script src="https://www.google.com/recaptcha/api.js"></script>

	</head>

	<body class="p-3 bg-light">

		<main id="content">
			<div class="container pt-4 mt-4 pb-4">
				<div class="row justify-content-center">
					<div class="col-md-5 card p-5">
						<h1 class="h4 text-center">Register</h1>
						<?php if ($error) { ?><div class="alert alert-danger mt-3" role="alert">
							<?= $error; ?>
						</div><?php } ?>
						<form class="mt-1" method="post">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" name="name" id="name" placeholder="Enter your full name" required>
							</div>
							<div class="form-group">
								<label for="company">Company</label>
								<input class="form-control" type="text" name="company" id="company" placeholder="Enter your company (optional)">
							</div>
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
							</div>
								<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
							</div>
							<div class="form-group">
								<label for="password2">Confirm Password</label>
								<input type="password" class="form-control" name="password2" id="password2" placeholder="Re-enter your password" required>
							</div>
							<div class="g-recaptcha" data-sitekey="6LdExBIUAAAAAPB6nhoIar2LDZQDEpJb2eDCopUu"></div>
							<button class="btn btn-primary mt-3" type="submit">Register for an account</button>
						</form>
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