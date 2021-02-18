<?php
class _activity{

	private $_dbh;
	private $_activities;
	private $_topic_id;
	private $_topic_act_id;
	private $_del_act;

	private $_act_title;
	private $_act_instructions;
	private $_act_body;
	private $_act_count;
	private $_act_c;
	private $_make_act_tab = false;

	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
	}



	public function _fetch_activity(){
		$tmp = $this->_build_activities();
		return $tmp;
	}

	private function _build_activities(){
		$tmp = "<script>";
		$tmp .= "$(document).ready(function(){".PHP_EOL;
		if (is_logged_in()){
			$tmp .= $this->_build_del_act_jq_code();
			$tmp .= $this->_build_add_new_activity();
		}
		$tmp .= "});</script>";
		$_rows = $this->_fetch_topic_activities();
		$this->_act_count = count($_rows);
		if (!empty($_rows) || is_logged_in()){$this->_make_act_tab = true;}

		if (is_logged_in()){
			$tmp .= $this->_build_add_new_btn_act();
			$tmp .= "<ul id = 'activities' class = 'sortable-list'>";
		}else{
			$tmp .= "<ul id = 'activities' class = 'flist'>";
		}
		if (!empty($_rows)){
			$this->_act_c=1;
			foreach ($_rows as $_row){
				$tmp .= "<li id = 'e".$_row['id']."' data-db-tbl='_app_topic_act' class='act'>";
				$tmp .= $this->_build_activity($_row);
				$tmp .= "</li>";
				$this->_act_c++;
			}
		}
		$tmp .= "</ul>";
		$this->_topic_act = $tmp;
		return $this->_topic_act;
	}

	private function _build_activity($_row){
		$this->_topic_act_id = $_row['id'];
		$this->_act_title = $_row['title'];
		$this->_act_body = $_row['body'];
		$this->_act_instructions = $_row['instructions'];
		if (is_logged_in()){
			$this->_act_title = $this->_build_varchar('_app_topic_act', 'title', $this->_act_title, $this->_topic_act_id);
			$this->_act_body = $this->_build_text('_app_topic_act', 'body', $this->_act_body, $this->_topic_act_id);
			$this->_act_instructions = $this->_build_text('_app_topic_act', 'instructions', $this->_act_instructions, $this->_topic_act_id);

			return $this->_build_act_edit_list();
		}else{
			return $this->_build_act_view_list();
		}
	}


	private function _build_act_view_list(){
		$tmp = "<ul id = 'acth".$this->_topic_act_id."' class = 'topic_activity'>";
		$tmp .= "<li id = 'ja".$this->_topic_act_id."' class='w100pc open_act point'><div class='ex_eg w100pc'>";
		$tmp .= "<div class='w32'><img title = 'Click to open activity.' alt = 'Open' class = 'open_act point ttip' id = 'jo".$this->_topic_act_id."' src='".__s_lib_url__."_images/_icons/closed.png' />";
		$tmp .= "<img title = 'Click to close activity.' alt = 'Close' class = 'hidden open_act point ttip' id = 'jc".$this->_topic_act_id."' src='".__s_lib_url__."_images/_icons/opened.png' /></div>";
		$tmp .= "<h3>Activity $this->_act_c: $this->_act_title</h3></div></li>";
		$tmp .= "</ul>";
		$tmp .= "<ul id='acts".$this->_topic_act_id."' class = 'topic_activity hidden'>";
		$tmp .= "<li>".$this->_act_instructions."</li>";
		$tmp .= "<li>".$this->_act_body."</li>";
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

	private function _build_add_new_btn_act(){
		$tmp = "<button type = 'button' class = 'w170 add_new_act add mt5' id = 'to".$this->_topic_id."'>Add new activity</button>";
		return $tmp;
	}

	private function _build_add_new_activity(){
		$tmp = "$(document).on('click', '.add_new_act', function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(2);
					var fd = new FormData();
					fd.append('db_tbl', '_app_topic_act');
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('act_id', id);
					fd.append('topic_id', ".$this->_topic_id.");
					$.ajax({
						type: 'POST',
						async : true,
						cache : false,
						processData	: false,
						contentType	: false,
						url: '".__s_app_url__."_ajax/_add_activity.php',
						data: fd,
						dataType: 'json',
						success: function (data) {}
					});
				});";
		return $tmp;
	}

	private function _build_del_act_jq_code($auto = true){
		$this->_del_act = new _delete();
		$this->_del_act->_set_db_main_tbl('_app_topic_act');
		$this->_del_act->_set_img_class('delete_activity');
		$this->_del_act->_set_list_id('ul#acth');
		$this->_del_act->_set_add_script_tags(false);
		$this->_del_act->_set_add_document_ready(false);
		if ($auto){return $this->_del_act->_delete_jq();}else{return $this->_del_act;}
	}


	public function _fetch_topic_activities(){
		$_sql = 'select * from _app_topic_act where topic_id = :topic_id order by order_num';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		return $_rows;
	}


	private function _build_act_edit_list(){
		$tmp = "<ul id = 'acth".$this->_topic_act_id."' class = 'topic_activity'>";
		$tmp .= "<li class='w100pc'><div class='ex_eg'>";

		$this->_del_act->_set_db_tbl_field_value($this->_topic_act_id);
		$tmp .= $this->_del_act->_delete_img();
		$tmp .= "<div class='w32 hauto'><img class = 'open_act point ttip' title = 'Click to open the activity.' id = 'jo".$this->_topic_act_id."' src='".__s_lib_url__."_images/_icons/closed.png' alt = 'Open' />";
		$tmp .= "<img class = 'hidden open_act point ttip' title = 'Click to close the activity.' id = 'jc".$this->_topic_act_id."' src='".__s_lib_url__."_images/_icons/opened.png' alt = 'Close' /></div>";
		$tmp .= "<div class='ex_eg'><h3>Activity $this->_act_c: </h3>$this->_act_title</div></li></ul>";
		$tmp .= "<ul id='acts".$this->_topic_act_id."' class = 'topic_activity hidden'><li>";
		$tmp .= "<div class='row-container'>".$this->_act_body."</div>";
		$tmp .= "</li></ul>";

		return $tmp;
	}




	private function _build_add_new_act_jq(){
		$tmp = "$(document).on('click', '.add_new_act', function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(2);
					var fd = new FormData();
					fd.append('db_tbl', '_app_topic_act');
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('act_id', id);
					fd.append('topic_id', ".$this->_topic_id.");
					$.ajax({
						type: 'POST',
						async : true,
						cache : false,
						processData	: false,
						contentType	: false,
						url: '".__s_app_url__."_ajax/_add_activity.php',
						data: fd,
						dataType: 'json',
						success: function (data) {
							$('ul#acts'+id).append(data);
						}
					});
				});";
		return $tmp;
	}


	public function _set_topic_id($_t){$this->_topic_id = $_t;}
	public function _set_del_act($_t) { $this->_del_act = $_t; }
	public function _set_topic_act_id($_t) { $this->_topic_act_id = $_t; }
	public function _set_make_act_tab($_t) { $this->_make_act_tab = $_t; }
	public function _get_make_act_tab() { return $this->_make_act_tab; }
}
?>