var views = {
	product: null,
	color: null,
	Front: {
		canvas: [],
		image: null,
		colors: null
	},
	Back: {
		canvas: [],
		image: null,
		colors: null
	},
	Left: {
		canvas: [],
		image: null,
		colors: null
	},
	Right: {
		canvas: [],
		image: null,
		colors: null
	}
};
var currentView = null;
var canvas = new fabric.Canvas("c");

canvas.setWidth(document.querySelector(".canvas-card").offsetWidth - 20);
canvas.setHeight(window.innerHeight - 75);

if (localStorage.getItem("savedDesign")) {
	views = JSON.parse(localStorage.getItem("savedDesign"));
}

function saveCanvas() {
	views[currentView]["canvas"] = canvas.toObject().objects;
	localStorage.setItem("savedDesign", JSON.stringify(views));
}

function addText() {
	var text = $("#textContent").val();
	text = text.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, "");
	if (text) {
		canvas.add(new fabric.IText(text, {
			left: 100,
			top: 100,
			fontFamily: $("#fontInput").val()
		}));
	}
	$("#textModal .modal-footer .btn-secondary").click();
	$("#textContent").val("");
}

function updateFontPreview() {
	$(".preview-div").html($("#textContent").val());
	$(".preview-div").css("font-family", $("#fontInput").val());
	if ($("#textContent").val()) {
		$(".preview-panel").show();
	} else {
		$(".preview-panel").hide();
	}
}
updateFontPreview();

canvas.on("object:selected", function(options) {
	console.log(options);
	$(".element-editor").show();
});
canvas.on("selection:cleared", function(options) {
	$(".element-editor").hide();
});
$(".element-editor").hide();

canvas.on("object:added", saveCanvas);
canvas.on("object:modified", saveCanvas);
canvas.on("object:removed", saveCanvas);

function deleteSelected() {
	canvas.remove(canvas.getActiveObject());
}

String.prototype.capitalize = function() {
	return this.charAt(0).toUpperCase() + this.slice(1);
}

function changeProduct(product) {
	views.product = $("[name='productRadio']:checked").val();
	$(".view-types .list-group-item").each(function() {
		$(this).find(".col-md-5").html("<img src='" + "images/" + views.product.toLowerCase() + "/" + $(this).attr("class").split(" ")[0].replace("viewbtn-", "").toLowerCase() + ".png" + "'>");
	});
	$("#productModal .btn-secondary").click();
	if (currentView) changeView(currentView);
}
if (!views.product) {
	changeProduct("tshirt");
} else {
	changeProduct(views.product);
}

function changeView(view) {
	if (currentView) {
		views[currentView]["canvas"] = canvas.toObject().objects;
	}
	currentView = view;
	$(".viewbtn-" + view).parent().find(".list-group-item").removeClass("bg-light");
	$(".viewbtn-" + view).addClass("bg-light");
	canvas.loadFromJSON(JSON.stringify(views[currentView]["canvas"].length > 0 ? {
		version: "2.0.0-rc.3",
		objects: views[currentView]["canvas"]
	} : []));
	refreshBg();
}
changeView("Front");

UPLOADCARE_PUBLIC_KEY = "b84367cf8636092ee2f9";

function insertImage() {
	fabric.Image.fromURL($(".imageUploader").val(), function(oImg) {
		oImg.scaleToWidth(canvas.width / 2.5);
		canvas.add(oImg);
	});
	$("#imageModal .btn-secondary").click();
	$(".imageUploader").val("");
};

function changeColor() {
	views.color = $("[name='colorRadio']:checked").val();
	refreshBg();
	$("#colorModal .btn-secondary").click();
}
if (!views.color) {
	changeColor();
}

function refreshBg() {
	fabric.Image.fromURL("images/" + views.product.toLowerCase() + "/" + currentView.toLowerCase() + ".png", function(img) {
		img.set({
			left: (canvas.width - img.width) / 2,
			top: (canvas.height - img.height) / 2
		});
		var filter = new fabric.Image.filters.BlendColor({
			color: views.color,
			mode: "overlay"
		});
		img.filters.push(filter);
		img.applyFilters();
		canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
	 });
}

function saveDesign() {
	saveCanvas();
	$("[name='code']").val(JSON.stringify(views));
	$("[name='image']").val(canvas.lowerCanvasEl.toDataURL("png"));
	localStorage.removeItem("savedDesign");
}

function clearDesign() {
	localStorage.removeItem("savedDesign");
	window.location.reload();
}

function loadGraphics() {
	$.get("graphics.php", function(data) {
		data.forEach(function(elt) {
			$(".graphic-body").append('<div class="col-4 col-md-3 mt-3"><img alt="" src="images/graphics/' + elt + '"><button type="button" onclick="insertSvg(\'images/graphics/' + elt + '\');" data-dismiss="modal" class="btn btn-primary btn-sm">Insert</button></div>');
		});
	});
}

function insertSvg(url) {
	// fabric.loadSVGFromURL(url, function(svg) {
	// 	console.log(svg);
	// 	// oImg.scaleToWidth(canvas.width / 2.5);
	// });
	// fabric.loadSVGFromURL(url, function(objects, options) { 
	// 	var dollars = fabric.util.groupSVGElements(objects, options);
	// 	canvas.add(dollars); 
	// 	canvas.calcOffset();
	// 	canvas.renderAll();
	// }); 	
}