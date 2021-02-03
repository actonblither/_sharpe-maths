var scale = 30;
var grid = 30;
var canvas = new fabric.Canvas('Pythagoras');

draw_grid();

draw_jig_square();
draw_opp_square();
draw_adj_square();
draw_hyp_square();
draw_triangles();

set_events();

function set_events(){
	canvas.on('object:moving', function(options) {
		options.target.set({
			left: Math.round(options.target.left / grid) * grid,
			top: Math.round(options.target.top / grid) * grid
		});
	});

	canvas.on('object:rotating', function(options) {
		options.target.set({
			angle: Math.round(options.target.angle / 5) * 5
		});
	});
}

function draw_grid(){
	for (var i = 0; i <=(660 / grid); i++) {
		canvas.add(new fabric.Line([ i * grid, 0, i * grid, 630], { stroke: '#ccc', selectable: false }));
		canvas.add(new fabric.Line([ 0, i * grid, 630, i * grid], { stroke: '#ccc', selectable: false }));
	}
}

function draw_opp_square(){
	var opp_square = new fabric.Rect({
		width: 3 * scale,
		height: 3 * scale,
		fill: 'purple',
		stroke: 'purple',
		strokeWidth: 1
	});

	var opp_square_txt = new fabric.Text('A', {
		fontSize: 50,
		top: 15,
		left: 27,
		stroke: 'white',
		fill: 'white',
		opacity: 0.9
	});

	var opp_square_grp = new fabric.Group([opp_square, opp_square_txt], {
		left: 7 * scale,
		top: 4 * scale,
		hasBorders: false,
		hasControls: false
	});

	canvas.add(opp_square_grp);
}


function draw_adj_square(){

	var adj_square = new fabric.Rect({
		width: 4 * scale,
		height: 4 * scale,
		fill: 'red',
		stroke: 'red',
		strokeWidth: 1
	});

	var adj_square_txt = new fabric.Text('B', {
		fontSize: 50,
		top: 30,
		left: 42,
		stroke: 'white',
		fill: 'white',
		opacity: 0.9
	});

	var adj_square_grp = new fabric.Group([adj_square, adj_square_txt], {
		left: 3 * scale,
		top: 7 * scale,
		hasBorders: false,
		hasControls: false
	});

	canvas.add(adj_square_grp);
}

function draw_hyp_square(){
	var hyp_square = new fabric.Rect({
		width: 5 * scale,
		height: 5 * scale,
		fill: 'pink',
		stroke: 'pink',
		angle: 53.13,
		strokeWidth: 1
	});

	var hyp_square_txt = new fabric.Text('H', {
		fontSize: 50,
		top: 75,
		left: -35,
		stroke: 'black',
		fill: 'black',
		opacity: 0.6,
	});

	var hyp_square_grp = new fabric.Group([hyp_square, hyp_square_txt], {
		left: 0 * scale,
		top: 0 * scale,
		hasBorders: false,
		hasControls: false
	});
	canvas.add(hyp_square_grp);
}

function draw_jig_square(){
	var jig_square = new fabric.Rect({
		width: 7 * scale+1,
		height: 7 * scale+1,
		fill: 'transparent',
		stroke: 'black',
		selectable: false
	});

	var jig_square_txt = new fabric.Text('Jigsaw frame', {
		fontSize: 30,
		top: 90,
		left: 25,
		stroke: 'black',
		fill: 'black',
		opacity: 0.3
	});

	var jig_square_grp = new fabric.Group([jig_square, jig_square_txt], {
		left: 13 * scale,
		top: 9 * scale,
		hasBorders: false,
		hasControls: false,
		selectable: false
	});

	canvas.add(jig_square_grp);
}

function draw_triangles(){
	var tri_opts = {
		fill: 'green',
		stroke: 'green',
		strokeWidth: 0
	};

	var tri_txt_opts = {
		fontSize: 50,
		top: -15,
		left: 95,
		stroke: 'white',
		fill: 'white'
	};

	var rt1 = new fabric.Path('m 10 50 h ' + 4 * scale + ' v -' + 3 * scale + ' z ', tri_opts);
	var rt2 = new fabric.Path('m 10 50 h ' + 4 * scale + ' v -' + 3 * scale + ' z ', tri_opts);
	var rt3 = new fabric.Path('m 10 50 h ' + 4 * scale + ' v -' + 3 * scale + ' z ', tri_opts);
	var rt4 = new fabric.Path('m 10 50 h ' + 4 * scale + ' v -' + 3 * scale + ' z ', tri_opts);

	var rt1_txt = new fabric.Text('1', tri_txt_opts);
	var rt2_txt = new fabric.Text('2', tri_txt_opts);
	var rt3_txt = new fabric.Text('3', tri_txt_opts);
	var rt4_txt = new fabric.Text('4', tri_txt_opts);

	var rt1_grp = new fabric.Group([rt1, rt1_txt], {
		left: 3 * scale,
		top: 4 * scale,
		hasBorders: false,
		hasControls: true
	});

	var rt2_grp = new fabric.Group([rt2, rt2_txt], {
		left: 11 * scale,
		top: 1 * scale,
		lockUniScaling: true,
		hasBorders: true,
		hasControls: true
	});

	var rt3_grp = new fabric.Group([rt3, rt3_txt], {
		left: 16 * scale,
		top: 1 * scale,
		hasBorders: true,
		hasControls: true
	});

	var rt4_grp = new fabric.Group([rt4, rt4_txt], {
		left: 16 * scale,
		top: 5 * scale,
		lockScalingX: true,
		lockScalingY: true,
		hasBorders: true,
		hasControls: true
	});

	rt1_grp.setControlsVisibility({bl: false, br: false, ml: false, mr: false, mb: false, mt: false, lb: false, tl: false, tr: false});
	rt2_grp.setControlsVisibility({bl: false, br: false, ml: false, mr: false, mb: false, mt: false, lb: false, tl: false, tr: false});
	rt3_grp.setControlsVisibility({bl: false, br: false, ml: false, mr: false, mb: false, mt: false, lb: false, tl: false, tr: false});
	rt4_grp.setControlsVisibility({bl: false, br: false, ml: false, mr: false, mb: false, mt: false, lb: false, tl: false, tr: false});

	canvas.add(rt1_grp);
	canvas.add(rt2_grp);
	canvas.add(rt3_grp);
	canvas.add(rt4_grp);
}
