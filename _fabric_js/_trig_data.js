	var _q_type = 'tan_ang';
	var _canvas_id = 'ex1_q1';
	var scale = 30;
	var tip = 0;
	var _num_dps = 1;
	var _units = ' mm';
	var _opp = 6.7;
	var _adj = 4.5;
	var _hyp = _pythag_hyp(_opp, _adj, _num_dps);
	var _angle = Math.atan(_opp/_adj);//in radians as required
	var _angle_deg = Math.floor(_angle/Math.PI*180 + 0.5);
	var _angle_radius = 65;

	var _top = 20;
	var _left = 20;








