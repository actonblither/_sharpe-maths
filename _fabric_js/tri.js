	var _q_type = 'sin_opp';
	var _canvas_id = 'cv';
	var scale = 30;
	var tip = 0;
	var _num_dps = 1;
	var _units = ' mm';
	var _opp = 6.7;
	var _adj = 4.5;
	var _angle_radius = 65;
	var _top = 10;
	var _left = 10;
	var _hyp = _pythag_hyp(_opp, _adj, _num_dps);
	var _angle = Math.atan(_opp/_adj);//in radians as required
	var _angle_deg = Math.floor(_angle/Math.PI*180 + 0.5);

	var _angle_label;
	var _hyp_label;
	var _adj_label;
	var _opp_label;
	var _opp_label_font_style;
	var _angle_label_font_style;
	var _hyp_label_font_style;
	var _adj_label_font_style;
	var _opp_label_angle;


	_set_vars(_q_type);
	console.log(_hyp_label);




	var _selectable = true;
	var _hasControls = true;
	var _hasBorders = false;
	var _flipX = false;
	var _flipY = false;
	var _strokeWidth = 1;
	var _txtColor = 'black';
	var _triColor = 'black';
	var _fontSize = 18;

	var _adj_s = _adj * scale;
	var _opp_s = _opp * scale;

	var ras = 0.8 * _opp * scale;
	var ra = 0.2 * _opp * scale;
	if (ra > 20){ra = 20; ras = _opp_s-20;}// Keep the right angle to a maximum of 20 units

	var canvas = new fabric.Canvas(_canvas_id);

	var path = 'm 0 0 v '+ _opp_s + ' h ' + _adj_s +' z m 0 ' + ras + ' h ' + ra + ' v ' + ra;
	var triangle = new fabric.Path(path, {
		fill: 'transparent',
		stroke: _triColor,
		strokeWidth: _strokeWidth,
		left: 0,
		top: 0,
		angle: 0,
		flipX: _flipX,
		flipY: _flipY
	});

	var _angle_arc = new fabric.Circle({
		radius: _angle_radius,
		top: _opp_s-_angle_radius,
		left: _adj_s-_angle_radius,
		angle: 2*_angle,
		startAngle: Math.PI,
		endAngle: Math.PI + _angle,
		stroke: '#000',
		strokeWidth: 1,
		fill: ''
	});

	var _angle_txt = new fabric.Text(_angle_label, {
		fontSize: _fontSize,
		fontStyle: _angle_label_font_style,
		top: _opp_s-25,
		left: _adj_s-50,
		stroke: _txtColor,
		fill: _txtColor,
		angle: 0
	});

	var _opp_txt = new fabric.Text(_opp_label, {
		fontSize: _fontSize,
		fontStyle: _opp_label_font_style,
		stroke: _txtColor,
		fill: _txtColor,
		angle: _opp_label_angle
	});

	_opp_txt.set({
		top: _opp_s/2 + _opp_txt.width/2,
		left: -20,
	});

	var _adj_txt = new fabric.Text(_adj_label, {
		fontSize: _fontSize,
		fontStyle: _adj_label_font_style,
		stroke: _txtColor,
		fill: _txtColor,
		angle: 0
	});

	_adj_txt.set({
		top: _opp_s,
		left: _adj_s/2 - _adj_txt.width/2
	})

	var _hyp_txt = new fabric.Text(_hyp_label, {
		fontSize: _fontSize,
		stroke: _txtColor,
		fill: _txtColor,
		fontStyle: _hyp_label_font_style,
		angle: _angle_deg
	});

	var _angle_rad = _deg_to_rad(_angle_deg);
	var _txt_length_offset = (_hyp_txt.width)/2;
	var _txt_height_offset = _hyp_txt.height;


	_hyp_txt.set({
		top: _opp_s * 0.5 - _txt_length_offset * Math.sin(_angle_rad) - _txt_height_offset * Math.cos(_angle_rad),
		left: _adj_s * 0.5 - _txt_length_offset * Math.cos(_angle_rad) + _txt_height_offset * Math.sin(_angle_rad)
	});


	var triangle_grp = new fabric.Group([triangle,_hyp_txt, _opp_txt, _adj_txt, _angle_txt, _angle_arc], {
		left: _left,
		top: _top,
		hasBorders: _hasBorders,
		hasControls: _hasControls,
		angle: tip,
		selectable: _selectable,
		hasBorders: _hasBorders,
		hasControls: _hasControls
	});

	triangle_grp.setControlsVisibility({
		mt: false,
		mb: false,
		ml: false,
		mr: false,
		tr: false,
		tl: false,
		br: false,
		bl: false,
		mtr: true //the rotating point (default: true)
	});

	canvas.add(triangle_grp);

	function _set_vars(_q_type){
		switch (_q_type){
		case 'all':
			/* ALL SHOWN*/
			_angle_label = _angle_deg+'°';
			_hyp_label = _hyp + _units;
			_adj_label = _adj + _units;
			_opp_label = _opp + _units;
			_htp_label_font_style = 'normal';
			_adj_label_font_style = 'normal';
			_opp_label_font_style = 'normal';
			_angle_label_font_style = 'normal';
			break;
		case 'sin_opp':
			/* SINE FIND OPP */
			_angle_label = _angle_deg + '°';
			_hyp_label = _hyp + _units;
			_adj_label = '';
			_opp_label = 'x';
			_opp_label_font_style = 'italic';
			_opp_label_angle = 0;
			_angle_label_font_style = 'normal';
			_hyp_label_font_style = 'normal';
			_adj_label_font_style = 'normal';
			break;
		case 'sin_hyp':
			/* SINE FIND HYP */
			_angle_label = _angle_deg + '°';
			_hyp_label = 'x';
			_adj_label = '';
			_opp_label = _opp + _units;
			_opp_label_angle = -90
			_hyp_label_font_style = 'italic';
			_angle_label_font_style = 'normal';
			_adj_label_font_style = 'normal';
			_opp_label_font_style = 'normal';
			break;
		case 'sin_ang':
			/* SINE FIND ANGLE */
			_angle_label = 'p°';
			_hyp_label = _hyp + _units;
			_adj_label = '';
			_opp_label = _opp + _units;
			_opp_label_angle = -90;
			_hyp_label_font_style = 'normal';
			_angle_label_font_style = 'italic';
			_adj_label_font_style = 'normal';
			_opp_label_font_style = 'normal';
			break;
		case 'cos_adj':
			/* COSINE FIND ADJ */
			_angle_label = _angle_deg+'°';
			_hyp_label = _hyp + _units;
			_adj_label = 'x';
			_opp_label = '';
			_hyp_label_font_style = 'normal';
			_angle_label_font_style = 'normal';
			_adj_label_font_style = 'italic';
			_opp_label_font_style = 'normal';
			break;
		case 'cos_hyp':
			/* COSINE FIND ADJ */
			_angle_label = _angle_deg+'°';
			_hyp_label = 'x';
			_adj_label = _adj + _units;
			_opp_label = '';
			_hyp_label_font_style = 'italic';
			_angle_label_font_style = 'normal';
			_adj_label_font_style = 'normal';
			_opp_label_font_style = 'normal';
			break;
		case 'cos_ang':
			/* SINE FIND ANGLE */
			_angle_label = 'p°';
			_hyp_label = _hyp + _units;
			_adj_label = _adj + _units;
			_opp_label = '';
			_hyp_label_font_style = 'normal';
			_angle_label_font_style = 'italic';
			_adj_label_font_style = 'normal';
			_opp_label_font_style = 'normal';
			break;
		case 'tan_opp':
			/* TANGENT FIND OPP */
			_angle_label = _angle_deg+'°';
			_hyp_label = '';
			_adj_label = _adj + _units;
			_opp_label = 'x';
			_opp_label_angle = 0;
			_hyp_label_font_style = 'normal';
			_angle_label_font_style = 'normal';
			_adj_label_font_style = 'normal';
			_opp_label_font_style = 'italic';
			break;
		case 'tan_adj':
			/* TANGENT FIND ADJ */
			_angle_label = _angle_deg+'°';
			_hyp_label = '';
			_adj_label = 'x';
			_opp_label = _opp + _units;
			_hyp_label_font_style = 'normal';
			_opp_label_angle = -90;
			_angle_label_font_style = 'normal';
			_adj_label_font_style = 'italic';
			_opp_label_font_style = 'normal';
			break;
		case 'tan_ang':
			/* SINE FIND ANGLE */
			_angle_label = 'p°';
			_hyp_label = '';
			_adj_label = _adj + _units;
			_opp_label = _opp + _units;
			_opp_label_angle = -90;
			_hyp_label_font_style = 'normal';
			_angle_label_font_style = 'italic';
			_adj_label_font_style = 'normal';
			_opp_label_font_style = 'normal';
			break;
		case 'pyth_hyp':
			/* PYTHAGORAS FIND HYP */
			_angle_label = '';
			_hyp_label = 'x';
			_adj_label = _adj + _units;
			_opp_label = _opp + _units;
			_opp_label_angle = -90;
			_hyp_label_font_style = 'italic';
			_angle_label_font_style = 'normal';
			_adj_label_font_style = 'normal';
			_opp_label_font_style = 'normal';
			break;
		case 'pyth_oth':
			/* PYTHAGORAS FIND OPP */
			_angle_label = '';
			_hyp_label = _hyp + _units;
			_adj_label = _adj + _units;
			_opp_label = 'x';
			_opp_label_angle = 0;
			_hyp_label_font_style = 'normal';
			_angle_label_font_style = 'normal';
			_adj_label_font_style = 'normal';
			_opp_label_font_style = 'italic';
			break;
		}
	}
