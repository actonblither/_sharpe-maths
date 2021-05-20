var scale = 30;
var grid = 30;
var canvas = new fabric.Canvas('triangle_angles');



draw_grid();

draw_triangles();

set_events();

function set_events(){
	canvas.on('object:moving', function(options) {
		options.target.set({
			left: Math.round(options.target.left / 2) * 2,
			top: Math.round(options.target.top / 2) * 2
		});
	});

	canvas.on('object:rotating', function(options) {
		options.target.set({
			angle: Math.round(options.target.angle / 1) * 1
		});
	});
}

function draw_grid(){
	for (var i = 0; i <=(660 / grid); i++) {
		canvas.add(new fabric.Line([ i * grid, 0, i * grid, 630], { stroke: '#ccc', selectable: false }));
		canvas.add(new fabric.Line([ 0, i * grid, 630, i * grid], { stroke: '#ccc', selectable: false }));
	}
}

function draw_triangles(){

	var tri_txt_opts = {
		fontSize: 50,
		top: -15,
		left: 95,
		stroke: 'white',
		fill: 'white'
	};



	var tri = get_random_int(11);
	console.log(tri);

	var path = new Array(
		'm 0 0 l 10 200 l 210 50 z',
		'm 0 0 l 40 180 l 250 5 z',
		'm 0 0 l 140 10 l 60 100 z',
		'm 0 0 l 55 200 l 120 15 z',
		'm 0 0 l 0 180 l 210 90 z',
		'm 0 0 l 211 12 l -53 197 z',
		'm 0 0 l 278 42 l -114 150 z',
		'm 0 0 l 56 133 l 56 -65 z',
		'm 0 0 l 56 133 l 104 -121 z',
		'm 0 0 l 187 97 l -172 22 z',
		'm 0 0 l 205 44 l -60 133 z',
		'm 0 0 l 294 19 l -267 201 z'
	);

	var path1 = path[tri];
	var tri_opts1 = {
		fill: 'blue',
		stroke: 'green',
		strokeWidth: 1,
		left: 0,
		top: 0
	};

	var tri_opts2 = {
			fill: 'yellow',
			stroke: 'red',
			strokeWidth: 1,
			left: 100,
			top: 100
		};

	var tri_opts3 = {
			fill: 'pink',
			stroke: 'brown',
			strokeWidth: 1,
			left: 200,
			top: 200
		};

	var rt1 = new fabric.Path(path1, tri_opts1);
	var rt2 = new fabric.Path(path1, tri_opts2);
	var rt3 = new fabric.Path(path1, tri_opts3);

	rt1.setControlsVisibility({bl: false, br: false, ml: false, mr: false, mb: false, mt: false, lb: false, tl: false, tr: false});
	rt2.setControlsVisibility({bl: false, br: false, ml: false, mr: false, mb: false, mt: false, lb: false, tl: false, tr: false});
	rt3.setControlsVisibility({bl: false, br: false, ml: false, mr: false, mb: false, mt: false, lb: false, tl: false, tr: false});

	canvas.add(rt1);
	canvas.add(rt2);
	canvas.add(rt3);
}
