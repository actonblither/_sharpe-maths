<?php
class _exposition{

	private $_dbh;
	private $_expositions;
	private $_topic_id;
	private $_topic_exp_id;
	private $_del_exp;

	private $_exp_title;
	private $_exp_instructions;
	private $_exp_body;
	private $_exp_count;
	private $_exp_c;
	private $_make_exp_tab = false;

	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
	}



	public function _fetch_exposition(){
		$tmp = $this->_build_expositions();
		return $tmp;
	}

	private function _build_expositions(){
		$tmp = "<script>";
		$tmp .= "$(document).ready(function(){".PHP_EOL;
		if (is_logged_in()){
			$tmp .= $this->_build_del_exp_jq_code();
			$tmp .= $this->_build_add_new_exposition();
		}
		$tmp .= "});</script>";
		$_rows = $this->_fetch_topic_expositions();
		$this->_exp_count = count($_rows);
		if (!empty($_rows) || is_logged_in()){$this->_make_exp_tab = true;}

		if (is_logged_in()){
			$tmp .= $this->_build_add_new_btn_exp();
			$tmp .= "<ul id = 'expositions' class = 'sortable-list'>";
		}else{
			$tmp .= "<ul id = 'expositions' class = 'flist'>";
		}
		if (!empty($_rows)){
			$this->_exp_c=1;
			foreach ($_rows as $_row){
				$tmp .= "<li id = 'e".$_row['id']."' data-db-tbl='_app_topic_exp' class='exp'>";
				$tmp .= $this->_build_exposition($_row);
				$tmp .= "</li>";
				$this->_exp_c++;
			}
		}
		$tmp .= "</ul>";
		$this->_topic_exp = $tmp;
		return $this->_topic_exp;
	}

	private function _build_exposition($_row){
		$this->_topic_exp_id = $_row['id'];
		$this->_exp_title = $_row['title'];
		$this->_exp_body = $_row['body'];
		$this->_exp_instructions = $_row['instructions'];
		if (is_logged_in()){
			$this->_exp_title = $this->_build_varchar('_app_topic_exp', 'title', $this->_exp_title, $this->_topic_exp_id);
			$this->_exp_body = $this->_build_text('_app_topic_exp', 'body', $this->_exp_body, $this->_topic_exp_id);
			$this->_exp_instructions = $this->_build_text('_app_topic_exp', 'instructions', $this->_exp_instructions, $this->_topic_exp_id);

			return $this->_build_exp_edit_list();
		}else{
			return $this->_build_exp_view_list();
		}
	}


	private function _build_exp_view_list(){
		$tmp = "<ul id = 'exph".$this->_topic_exp_id."' class = 'topic_exposition'>";
		$tmp .= "<li id = 'ja".$this->_topic_exp_id."' class='w100pc open_exp point'><div class='ex_eg w100pc'>";
		$tmp .= "<div class='w32'><img title = 'Click to open exposition.' alt = 'Open' class = 'open_exp point ttip' id = 'jo".$this->_topic_exp_id."' src='".__s_lib_url__."_images/_icons/closed.png' />";
		$tmp .= "<img title = 'Click to close exposition.' alt = 'Close' class = 'hidden open_exp point ttip' id = 'jc".$this->_topic_exp_id."' src='".__s_lib_url__."_images/_icons/opened.png' /></div>";
		$tmp .= "<h3>Activity $this->_exp_c: $this->_exp_title</h3></div></li>";
		$tmp .= "</ul>";
		$tmp .= "<ul id='exps".$this->_topic_exp_id."' class = 'topic_exposition hidden'>";
		$tmp .= "<li>".$this->_exp_instructions."</li>";
		$tmp .= "<li>".$this->_exp_body."</li>";
		$tmp .= "</ul>";
		return $tmp;
	}


	private function _build_text($_db_tbl, $_field_name, $_field_value, $_data_id){
		$_el = new _form_element();
		$_el->_set_el_field_id($_field_name);
		$_el->_set_el_field_value($_field_value);
		$_el->_set_db_tbl($_db_tbl);
		$_el->_set_el_id_value($_data_id);
		$_el->_set_el_width(100);
		$_el->_set_el_height(200);
		$_el->_set_el_width_units('%');
		$_el->_set_el_height_units('px');
		return $_el->_build_textarea();
	}

	private function _build_varchar($_db_tbl, $_field_name, $_field_value, $_data_id){
		$_el = new _form_element();
		$_el->_set_el_field_id($_field_name);
		$_el->_set_el_field_value($_field_value);
		$_el->_set_db_tbl($_db_tbl);
		$_el->_set_el_id_value($_data_id);
		$_el->_set_el_width(500);
		$_el->_set_el_width_units('px');
		return $_el->_build_text_input();
	}

	private function _build_add_new_btn_exp(){
		$tmp = "<button type = 'button' class = 'w170 add_new_exp add mt5' id = 'to".$this->_topic_id."'>Add new exposition</button>";
		return $tmp;
	}

	private function _build_add_new_exposition(){
		$tmp = "$(document).on('click', '.add_new_exp', function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(2);
					var fd = new FormData();
					fd.append('db_tbl', '_app_topic_exp');
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('exp_id', id);
					fd.append('topic_id', ".$this->_topic_id.");
					$.ajax({
						type: 'POST',
						async : true,
						cache : false,
						processData	: false,
						contentType	: false,
						url: '".__s_app_url__."_ajax/_add_exposition.php',
						data: fd,
						dataType: 'json',
						success: function (data) {}
					});
				});";
		return $tmp;
	}

	private function _build_del_exp_jq_code($auto = true){
		$this->_del_exp = new _delete();
		$this->_del_exp->_set_db_main_tbl('_app_topic_exp');
		$this->_del_exp->_set_img_class('delete_exposition');
		$this->_del_exp->_set_list_id('ul#exph');
		$this->_del_exp->_set_add_script_tags(false);
		$this->_del_exp->_set_add_document_ready(false);
		if ($auto){return $this->_del_exp->_delete_jq();}else{return $this->_del_exp;}
	}


	public function _fetch_topic_expositions(){
		$_sql = 'select * from _app_topic_exp where topic_id = :topic_id order by order_num';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		return $_rows;
	}


	private function _build_exp_edit_list(){
		$tmp = "<ul id = 'exph".$this->_topic_exp_id."' class = 'topic_exposition'>";
		$tmp .= "<li class='w100pc'><div class='ex_eg'>";

		$this->_del_exp->_set_db_tbl_field_value($this->_topic_exp_id);
		$tmp .= $this->_del_exp->_delete_img();
		$tmp .= "<div class='w32 hauto'><img class = 'open_exp point ttip' title = 'Click to open the exposition.' id = 'jo".$this->_topic_exp_id."' src='".__s_lib_url__."_images/_icons/closed.png' alt = 'Open' />";
		$tmp .= "<img class = 'hidden open_exp point ttip' title = 'Click to close the exposition.' id = 'jc".$this->_topic_exp_id."' src='".__s_lib_url__."_images/_icons/opened.png' alt = 'Close' /></div>";
		$tmp .= "<div class='ex_eg'><h3>Activity $this->_exp_c: </h3>$this->_exp_title</div></li></ul>";
		$tmp .= "<ul id='exps".$this->_topic_exp_id."' class = 'topic_exposition hidden'><li>";
		$tmp .= "<div class='container'>".$this->_exp_body."</div>";
		$tmp .= "</li></ul>";

		return $tmp;
	}




	private function _build_add_new_exp_jq(){
		$tmp = "$(document).on('click', '.add_new_exp', function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(2);
					var fd = new FormData();
					fd.append('db_tbl', '_app_topic_exp');
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('exp_id', id);
					fd.append('topic_id', ".$this->_topic_id.");
					$.ajax({
						type: 'POST',
						async : true,
						cache : false,
						processData	: false,
						contentType	: false,
						url: '".__s_app_url__."_ajax/_add_tp.php',
						data: fd,
						dataType: 'json',
						success: function (data) {
							$('ul#exqs'+id).append(data);
						}
					});
				});";
		return $tmp;
	}


	public function _set_topic_id($_t){$this->_topic_id = $_t;}
	public function _set_del_exp($_t) { $this->_del_exp = $_t; }
	public function _set_topic_exp_id($_t) { $this->_topic_exp_id = $_t; }
	public function _set_make_exp_tab($_t) { $this->_make_exp_tab = $_t; }
	public function _get_make_exp_tab() { return $this->_make_exp_tab; }
}
?>