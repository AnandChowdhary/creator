var views = {
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
var currentView = null, currentProduct = null, currentColor = null;
var canvas = new fabric.Canvas("c");

canvas.setWidth(document.querySelector(".canvas-card").offsetWidth - 20);
canvas.setHeight(window.innerHeight - 75);

function saveCanvas() {
	console.log(canvas.toObject());
}

function addText() {
	var text = $("#textInput").val();
	text = text.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, "");
	if (text) {
		canvas.add(new fabric.IText(text, {
			left: 100,
			top: 100,
			fontFamily: $("#fontInput").val()
		}));
	}
	$("#textModal .modal-footer .btn-secondary").click();
	$("#textInput").val("");
}

function updateFontPreview() {
	$(".preview-div").html($("#textInput").val());
	$(".preview-div").css("font-family", $("#fontInput").val());
	if ($("#textInput").val()) {
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

function deleteSelected() {
	canvas.remove(canvas.getActiveObject());
}

String.prototype.capitalize = function() {
	return this.charAt(0).toUpperCase() + this.slice(1);
}

function changeProduct(product) {
	currentProduct = $("[name='productRadio']:checked").val();
	$(".view-types .list-group-item").each(function() {
		$(this).find(".col-md-5").html("<img src='" + "images/" + currentProduct.toLowerCase() + "/" + $(this).attr("class").split(" ")[0].replace("viewbtn-", "").toLowerCase() + ".png" + "'>");
	});
	$("#productModal .btn-secondary").click();
	if (currentView) changeView(currentView);
}
changeProduct("tshirt");

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
	currentColor = $("[name='colorRadio']:checked").val();
	refreshBg();
	$("#colorModal .btn-secondary").click();
}
changeColor();

function refreshBg() {
	fabric.Image.fromURL("images/" + currentProduct.toLowerCase() + "/" + currentView.toLowerCase() + ".png", function(img) {
		img.set({
			left: (canvas.width - img.width) / 2,
			top: (canvas.height - img.height) / 2
		});
		var filter = new fabric.Image.filters.BlendColor({
			color: currentColor,
			mode: "overlay"
		});
		img.filters.push(filter);
		img.applyFilters();
		canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
	 });
}