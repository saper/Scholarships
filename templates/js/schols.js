function supports_canvas() {
	return !!document.createElement('canvas').getContext;
}

function draw_canvas() {
	var a_canvas = document.getElementById("a");
	var a_context = a_canvas.getContext("2d");
	a_context.fillRect(50, 25, 150, 100);
}
