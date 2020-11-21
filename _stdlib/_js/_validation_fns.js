/**
 * All of the functions herein deal with particular
 * instances of form element validation.
 * All possible validation problems can be dealt
 * with in this way.
 *
 * These functions can be called from anywhere but
 * are mostly used in _tbl_record_form.php
 *
 * PHP validation functions (server side validation)
 * are stored in lib/_validation.php
 */

function required(str){
	if (str == '' || str == 'null'){return false;}else{return true;}
}

function equalpw(value1){
	var v1 = document.getElementById('new_pass1').value;
	var v2 = document.getElementById('new_pass2').value;
	if (v1 === v2){
		return true;
	}else{
		return false;
	}
}

function not_equal(value1,value2){
	if (value1 != value2){return true;}else{return false;}
}



function test_valid_email(email) {
	if (typeof(email)==='undefined'){return true;}
	if (email===''){return true;}
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(String(email).toLowerCase());
}

function url(str){
	if (typeof(str)=='undefined'){return true;}
	if (str==''){return true;}
	if (validator.isURL(str)){
		return true;
	}else{
		return false;
	}
}



function is_integer(in_num_str){
	var is_num=true;
	var i;
	for (i=0; i<in_num_str.length; i++)
		if (!is_digit(in_num_str.charAt(i)))
			is_num=false;
		return is_num;
}

function is_number(in_num_str){
	var is_num=true;
	var i;
	for (i=0; i<in_num_str.length; i++){
		if (!is_digit_plus(in_num_str.charAt(i))){is_num=false;}
	}
	return is_num;
}

function is_digit_plus(ch) {
	return (((ch >= '0') && (ch <= '9')) || ch=='.');
}

function is_digit(ch) {
	return ((ch >= '0') && (ch <= '9'));
}

