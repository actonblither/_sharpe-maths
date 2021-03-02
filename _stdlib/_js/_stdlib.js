function is_empty(v){
	if (typeof(v) == 'undefined' || v == null || v == ''){
		return true;
	}else{
		return false;
	}
}


function disableEnterKey(e){
	 if(e.which == 13)return false; else return true;
}

//Add an is_numeric function
function is_numeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

// START COOKIES

function createCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
	}
	else var expires = "";
	document.cookie = name + "=" + (value || "") + expires + "; path=/; samesite=lax; secure;";
	//console.log(document.cookie);
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name, "", -1);
}

function checkchange(){
	if(changed==true){
		if (confirm("Do you want to save your changes first?")){
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}
}

// END COOKIES

//START SORT FUNCTION

//n is the number of the column
//type is array (currently with only one value - date. The array is there to make future additions simpler.)
function sort_list(prefix, n, date, pre_sort_cols) {
	var tbl, rows, switching, shouldSwitch, dir, x, y, switchcount = 0,cmp_x,cmp_y;
	var sortable_list = prefix + '_list-sortable-list';

	ul = document.getElementById(sortable_list);
	switching = true;
	//Set the sorting direction to ascending:
	dir = "asc";
	/*Make a loop that will continue until no switching has been done:*/
	while (switching) {
		//start by saying: no switching is done:
		switching = false;
		rows = ul.getElementsByClassName('sort');
		/*Loop through all tbl rows:*/
			for (i = 0; i < (rows.length - 1); i++) {
				//start by saying there should be no switching:
				shouldSwitch = false;
				//Get the two elements you want to compare,	one from current row and one from the next
				x = rows[i].getElementsByTagName('div')[n + pre_sort_cols];
				y = rows[i + 1].getElementsByTagName('div')[n + pre_sort_cols];
				//check if the two rows should switch place, based on the direction, asc or desc
				cmp_x = x.innerHTML.toLowerCase().trim();
				cmp_y = y.innerHTML.toLowerCase().trim();

				if (date){
					var date_format="DD-MM-YYYY HH:mm:ss";
					var cmp_x = moment(cmp_x,date_format);
					var cmp_y = moment(cmp_y,date_format);
				}
				if (dir == 'asc') {
					if (cmp_x > cmp_y) {
						//if so, mark as a switch and break the loop:
						shouldSwitch= true;
						break;
					}
				} else if (dir == 'desc') {
					if (cmp_x < cmp_y) {
						//if so, mark as a switch and break the loop:
						shouldSwitch= true;
						break;
					}
				}
			}
			if (shouldSwitch) {
				/*If a switch has been marked, make the switch
					and mark that a switch has been done:*/
				rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
				switching = true;
				//Each time a switch is done, increase this count by 1:
				switchcount ++;
			} else {
				/*If no switching has been done AND the direction is 'asc',
					set the direction to "desc" and run the while loop again.*/
				if (switchcount == 0 && dir == "asc") {
					dir = 'desc';
					switching = true;
				}
			}
		}
}

//END SORT FUNCTION
