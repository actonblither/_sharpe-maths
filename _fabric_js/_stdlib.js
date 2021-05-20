function _pythag_hyp(_opp, _adj, _num_dps){
	return Math.floor(Math.sqrt(Math.pow(_opp, 2) + Math.pow(_adj, 2)) * Math.pow(10, _num_dps) + 0.5)/Math.pow(10, _num_dps);
}

function _pythag_opp(_hyp, _adj, _num_dps){
	return Math.floor(Math.sqrt(Math.pow(_hyp, 2) - Math.pow(_adj, 2)) * Math.pow(10, _num_dps) + 0.5)/Math.pow(10, _num_dps);
}

function _deg_to_rad(_deg){
	return _deg * Math.PI / 180;
}

function _rad_to_deg(_rad){
	return _rad * 180 / Math.PI;
}

function get_random_int(max) {
	return Math.floor(Math.random() * Math.floor(max));
}