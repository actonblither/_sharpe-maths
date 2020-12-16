/* _modal_timeout.js
 *
 *The script is dependent on jQuery, which makes javascript a lot easier to play with
 *so jquery must be loaded before this script.
 *
 *This script started off life as a simple modal window from the W3schools.com site
 *and was then adapted to my needs. Use it please if it is of any use to you whatever.
 *
 *This css file can be altered to your taste and should be loaded ahead of loading the js script.
 *	<link rel = 'stylesheet' href = 'path_to/modal-timeout.css' type='text/css' />
 *
 *This javascript file should be loaded at the bottom of the index.php file.
 *	<script src = 'path_to/modal-timeout.js'></script>
 *
 *There should be a blank div somewhere at the bottom of your
 *HTML BODY to hold the timeout modal code:
 *	<div id = 'modal-timeout'></div>
 *
 *If you place a <div id = 'modal-debug'></div> at the top of your page
 * and set the variable _debug to true, this will give a visible countdown.
 */
	var _debug = false;
/*
 *Time is calculated in minutes and seconds
 *idle_max holds a constant with the maximum time before logout.
 *idle_warn holds a constant less than idle_max with the time before the modal dialog box appears.
 *idle_time is a variable, which is the time counter.
 *
 *idle_time is reset whenever the mouse moves over the appropriate browser window.
 *
 *The base unit for setInterval in javascript is the millisecond or 0.001s
 *so the variable _a_second is set to 1000ms, _a_minute = 60 * _a_second.
 *
 *You can edit the following 2 variables to your preferences, remembering that
 *_idle_max_minutes must be bigger than _idle_warn_minutes. The units of time of these
 *variables is the minute.
 */

	var _idle_max_minutes  = 1600;
	var _idle_warn_minutes = 1595;

/* This next little section sets the value of focused to true or false
 * depending on whether the tab is focused (on top/active etc).
 * When focused the timer will continue counting, but if not the
 * timer will stop.
 */
	var focused = true;
	window.onfocus = function(){
		focused = true;
	}

	window.onblur = function(){
		focused = false;
	}
/* End of focus section*/

/*
 * You can also edit this string which is the initially hidden modal window,
 * though the _modal_timeout.css will need changing as well.
 */

	var _modal_timeout = `
		<div id = 'modal-dialog' class = 'modal hidden'>
			<div class = 'modal-content'>
				<div class = 'modal-text'>
					This page has been inactive for <span id='modal-inactive'></span> minutes.
				</div>
				<div class = 'modal-body'>
					You will be logged out automatically in <span id='modal-counter'></span> seconds.
				</div>
				<div class = 'modal-text'>
					Moving the mouse over the browser window will reset the timer.
				</div>
			</div>
		</div>`;

	/* It's probably best not to edit below this point */
	$('#modal-timeout').html(_modal_timeout);

	var _a_second = 1000;
	var _a_minute = 60 * _a_second;
	var _idle_max  = _idle_max_minutes * _a_minute;
	var _idle_warn = _idle_warn_minutes * _a_minute;
	var _idle_time = 0;
	var _show_date = true;

	var _idle_interval = setInterval('_timer_increment()', _a_second);
	var _show_modal = function (){$('div#modal-dialog').removeClass('hidden').addClass('block');}
	var _hide_modal = function (){$('div#modal-dialog').removeClass('block').addClass('hidden');}

	var _timer_increment = function () {
		//If line 96 is replaced with line 94 the countdown will stop when unfocussed
		//if (focused) { _idle_time = _idle_time + _a_second; };

		_idle_time = _idle_time + _a_second;

		var _seconds_left = ( _idle_max - _idle_time )/_a_second;
		var _minutes_inactive = _idle_time/_a_minute;
		if (_seconds_left >= 0){ $('#modal-counter').html(Math.round(_seconds_left)); }
		$('#modal-inactive').html( Math.round(_minutes_inactive));
		if (_debug){
			$('#modal-debug').html(Math.round(_seconds_left) + ' seconds to go.');
		}
		if (_idle_time > _idle_warn) { _show_modal();}
		if (_idle_time > _idle_max){ window.location = 'index.php?main=logout';}
		if (_show_date){
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1;
			var yyyy = today.getFullYear();
			var H = today.getHours();
			var m = today.getMinutes();
			var s = today.getSeconds();
			if(H < 10) { H = '0' + H; }
			if(m < 10) { m = '0' + m; }
			if(s < 10) { s = '0' + s; }
			if(dd < 10) { dd = '0' + dd; }
			if(mm < 10) { mm = '0' + mm; }
			var now = dd + '-' + mm + '-' + yyyy + ' '+ H + ':' + m + ':' + s;
			$('#now-date').html(now);
		}
	}

	$(document).on('mousemove', function(e) {
		e.stopPropagation();
		_idle_time = 0;
		_hide_modal();
	});
