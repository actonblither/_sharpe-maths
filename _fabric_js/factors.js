	var grid = 30;
	var move = true;
	var handles = true;
	var color = ['blue', 'red', 'pink', 'green', 'purple', 'yellow'];
	var canvas = new fabric.Canvas('factors');
	set_events();
	draw_grid();


	for (var cols = 0; cols < 6; cols++){
		for (var rows = 0; rows < 15; rows++){
			draw_square(cols, rows, 1, color[cols], true);
		}
	}




		function draw_square(l, t, w, color, move){
			var square = new fabric.Rect({
				width:  w*grid,
				height: w*grid,
				fill: color,
				stroke: 'black',
				left: (l + 1) * grid,
				top: (t + 1) * grid,
				hasBorders: false,
				hasControls: false,
				selectable: move
			});
			canvas.add(square);
		}

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
			for (var i = 0; i < (930 / grid); i++) {
				canvas.add(new fabric.Line([ i * grid, 0, i * grid, 930], { stroke: '#ccc', selectable: false }));
				canvas.add(new fabric.Line([ 0, i * grid, 930, i * grid], { stroke: '#ccc', selectable: false }));
			}
		}