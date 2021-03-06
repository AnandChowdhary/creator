<?php include "database.php"; ?>
<!doctype html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<title>Creator</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			.material-icons { font-size: 125%; vertical-align: middle; margin-top: -5px; }
			img { max-width: 100% }
			.card, .shadow { box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1) }
			button:focus { position: relative; z-index: 1 }
		</style>

	</head>

	<body class="bg-light">

		<? if (isset($_SESSION["user"])) { ?><nav class="navbar navbar-expand-lg navbar-light bg-white shadow">
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
		</nav><? } ?>

		<div class="container mt-3 mb-3" style="max-width: 1500px">
			<div class="row">
				<div class="col-md-3">
					<div class="card card-body mb-4 text-center">
						<div>
							<img alt="Melangebox" src="https://cdn.shopify.com/s/files/1/1836/6841/files/icon.png?7012573658297170903" style="height: 40px">
						</div>
					</div>
					<div class="list-group mb-4">
						<button type="button" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#productModal"><i class="material-icons mr-2">create</i>Change product</button>
						<button type="button" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#colorModal"><i class="material-icons mr-2">color_lens</i>Change color</button>
					</div>
					<div class="list-group mb-4">
						<button type="button" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#textModal"><i class="material-icons mr-2">text_fields</i>Add text</button>
						<!-- <button type="button" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#graphicsModal" onclick="loadGraphics()"><i class="material-icons mr-2">filter_vintage</i>Insert graphic</button> -->
						<button type="button" class="list-group-item list-group-item-action "data-toggle="modal" data-target="#imageModal"><i class="material-icons mr-2">insert_photo</i>Upload image</button>
						<button type="button" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#nameNumberModal"><i class="material-icons mr-2">format_list_numbered</i>Names &amp; numbers</button>
						<button type="button" onclick="clearDesign()" class="list-group-item list-group-item-action"><i class="material-icons mr-2">delete_forever</i>Reset design</button>
					</div>
					<?php if ($_SESSION["user"]) { ?>
						<div class="list-group">
							<button type="button" class="list-group-item list-group-item-action"><strong><?= $_SESSION["user"]["name"] ?></strong><br><?= $_SESSION["user"]["company"] ? $_SESSION["user"]["company"] : "Profile" ?></button>
							<a href="designs.php" class="list-group-item list-group-item-action"><i class="material-icons mr-2">burst_mode</i>My designs &amp; orders</a>
							<a href="logout.php" class="list-group-item list-group-item-action"><i class="material-icons mr-2">burst_mode</i>Log out</a>
						</div>
					<?php } else { ?>
						<div class="list-group">
							<button type="button" class="list-group-item list-group-item-action"><strong>Guest User</strong><br>Log in to save this design</button>
							<a href="login.php" class="list-group-item list-group-item-action"><i class="material-icons mr-2">burst_mode</i>Log in</a>
							<a href="register.php" class="list-group-item list-group-item-action"><i class="material-icons mr-2">person</i>Register</a>
						</div>
					<?php } ?>
				</div>
				<div class="col-md">
					<div class="card canvas-card">
						<canvas id="c"></canvas>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card card-body mb-4 element-editor">
						<p><strong>Delete this element</strong></p>
						<div>
							<button class="btn btn-danger" onclick="deleteSelected();">Delete</button>
						</div>
					</div>
					<div class="list-group mb-4 view-types">
						<button type="button" class="viewbtn-Front list-group-item list-group-item-action" onclick="changeView('Front');">
							<div class="row align-items-center">
								<div class="col-md-5">
									<div class="bg-light" style="height: 100px">1</div>
								</div>
								<div class="col-md">Front</div>
							</div>
						</button>
						<button type="button" class="viewbtn-Back list-group-item list-group-item-action" onclick="changeView('Back');">
							<div class="row align-items-center">
								<div class="col-md-5">
									<div class="bg-light" style="height: 100px">2</div>
								</div>
								<div class="col-md">Back</div>
							</div>
						</button>
						<button type="button" class="viewbtn-Left list-group-item list-group-item-action" onclick="changeView('Left');">
							<div class="row align-items-center">
								<div class="col-md-5">
									<div class="bg-light" style="height: 100px">3</div>
								</div>
								<div class="col-md">Left</div>
							</div>
						</button>
						<button type="button" class="viewbtn-Right list-group-item list-group-item-action" onclick="changeView('Right');">
							<div class="row align-items-center">
								<div class="col-md-5">
									<div class="bg-light" style="height: 100px">4</div>
								</div>
								<div class="col-md">Right</div>
							</div>
						</button>
					</div>
					<button type="button" data-toggle="modal" data-target="#saveModal" class="btn btn-secondary btn-block mb-3" onclick="saveDesign();"><i class="material-icons mr-2">save</i>Save this design</button>
					<button class="btn btn-primary btn-block" onclick="orderDesign();">Order this design<i class="material-icons ml-2">arrow_forward</i></button>
				</div>
			</div>
		</div>

		<div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="saveModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form method="post" action="save.php">
						<div class="modal-header">
							<h5 class="modal-title" id="saveModalLabel">Save this design</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p>After saving this design, you will be able to share it with your friends and place orders.</p>
							<textarea name="code" style="display: none"></textarea>
							<textarea name="image" style="display: none"></textarea>
							<div class="form-group">
								<label for="designName">Name</label>
								<input type="text" class="form-control" id="designName" name="design_name" placeholder="Enter a name for this design" required>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Save this design</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="graphicsModal" tabindex="-1" role="dialog" aria-labelledby="graphicsModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form method="post" action="save.php">
						<div class="modal-header">
							<h5 class="modal-title" id="graphicsModalLabel">Insert graphics</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body text-center">
							<div class="row mb-3 graphic-body"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="textModal" tabindex="-1" role="dialog" aria-labelledby="textModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="textModalLabel">Add text</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form>
							<div class="form-group">
								<label for="textContent">Content</label>
								<input type="text" class="form-control" id="textContent" placeholder="Enter your text content" onkeyup="updateFontPreview();">
							</div>
							<div class="form-group">
								<label for="fontInput">Font</label>
								<select onchange="updateFontPreview();" id="fontInput" class="form-control">
									<option selected value="Helvetica, Helvetica Neue, Arial, sans-serif">Helvetica</option>
									<option value="Times, Times New Roman serif">Times</option>
									<option value="Courier, Courier New, monospace">Courier</option>
									<option value="Comic Sans MS, cursive">Comic</option>
									<option value="Impact">Impact</option>
									<option value="Georgia">Georgia</option>
								</select>
							</div>
							<div class="form-group preview-panel">
								<label>Preview</label>
								<div class="card card-body preview-div">
									Preview
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="addText()">Add text</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="colorModal" tabindex="-1" role="dialog" aria-labelledby="colorModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="colorModalLabel">Change color</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row mb-3">
							<div class="col-4 mt-3">
								<label style="background: White; color: #000" class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="colorRadio" checked value="#999">
									<div class="mt-2">White</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label style="background: #eee; color: #000" class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="colorRadio" value="#666">
									<div class="mt-2">Grey</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label style="background: #e67e22; color: #fff" class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="colorRadio" value="#d35400">
									<div class="mt-2">Orange</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label style="background: #e74c3c; color: #fff" class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="colorRadio" value="#f33">
									<div class="mt-2">Red</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label style="background: #3498db; color: #fff" class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="colorRadio" value="#229">
									<div class="mt-2">Blue</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label style="background: Navy; color: #fff" class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="colorRadio" value="#004">
									<div class="mt-2">Navy</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label style="background: #000; color: #fff" class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="colorRadio" value="#111">
									<div class="mt-2">Black</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label style="background: DimGrey; color: #fff" class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="colorRadio" value="#333">
									<div class="mt-2">DimGrey</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label style="background: #f1c40f; color: #fff" class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="colorRadio" value="#f39c12">
									<div class="mt-2">Gold</div>
								</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="changeColor()">Change color</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="productModalLabel">Change product</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row mb-3">
							<div class="col-4 mt-3">
								<label class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="productRadio" checked value="tshirt" checked>
									<div class="mt-2">Roundneck Half-sleeves Tee</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="productRadio" value="hoodie">
									<div class="mt-2">Hoodie</div>
								</label>
							</div>
							<div class="col-4 mt-3">
								<label class="card card-block text-center pb-3 pt-3">
									<input style="margin: 0 auto" type="radio" name="productRadio" value="jacket">
									<div class="mt-2">Jacket</div>
								</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="changeProduct();">Change product</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="imageModalLabel">Upload image</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" role="uploadcare-uploader" class="imageUploader">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="insertImage()">Insert image</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="nameNumberModal" tabindex="-1" role="dialog" aria-labelledby="nameNumberModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="nameNumberModalLabel">Names &amp; numbers</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p><strong>Instruction: </strong>Add a sample name and number in your design. Then, add the list of names and numbers below. We will replace the sample name and number with each of them while printing.</p>
						<textarea class="form-control" placeholder="Add names and numbers here..." rows="7"></textarea>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<script src="https://a11y.co/agastya.js" async defer></script>
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<script src="fabric.js"></script>
		<script src="https://ucarecdn.com/libs/widget/3.2.1/uploadcare.min.js" charset="utf-8"></script>
		<script src="creator.js"></script>
		<script>
			$("[data-toggle='tooltip']").tooltip();
			<?php if ($_GET["slug"]) {
				$designCode = DB::queryFirstRow("SELECT * FROM designs WHERE slug=%s", $_GET["slug"]); ?>
			views = JSON.parse('<?= $designCode["code"]; ?>');
			canvas.loadFromJSON(JSON.stringify(views[currentView]["canvas"].length > 0 ? {
				version: "2.0.0-rc.3",
				objects: views[currentView]["canvas"]
			} : []));
			refreshBg();
			saveCanvas();
			<?php } ?>
		</script>
	</body>

</html>