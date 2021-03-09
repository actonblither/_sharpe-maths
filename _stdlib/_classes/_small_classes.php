<?php
class _list{
	private $_l_0_ul_id;
	private $_add_new_text;
	private $_l_0_li_id_prefix;
	private $_l_1_header_ul_id;
	private $_l_1_header_li_id_prefix;
	private $_l_1_header_title;
	private $_l_1_item_ul_id;
	private $_l_1_item_li;
	private $_img_closed_id_prefix;
	private $_img_opened_id_prefix;
	private $_ajax_add_new_file;
	private $_title;
	private $_title_prefix;
	private $_use_notes_header;
	private $_list_item_instruction_li;
	private $_list_item_li;
	private $_is_logged_in;
	private $_main_db_tbl;
	private $_sub_db_tbls;
	private $_sub_db_tbl_fields;
	private $_topic_id;
	private $_rows;
	private $_labels;
	private $_del_header;
	private $_del_header_jq;
	private $_del_header_img;
	private $_del_item;
	private $_del_item_jq;
	private $_del_item_img;
	private $_del_header_params;
	private $_del_item_params;
	private $_del_main_class;
	private $_del_sub_class;
	private $_delete_items = true;
	private $_ids;
	private $_extra_jq;
	private $_edit_fields;


	public function __construct($_params){
		$this->_is_logged_in = is_logged_in();
		$this->_l_0_ul_id = rvs($_params['l_0_ul_id']);
		$this->_add_new_text = rvs($_params['add_new_text']);
		$this->_ajax_add_new_file = rvs($_params['ajax_add_new_file']);
		$this->_l_0_li_id_prefix = rvs($_params['l_0_li_id_prefix']);
		$this->_l_1_header_ul_id = rvs($_params['l_1_header_ul_id']);
		$this->_l_1_header_li_id_prefix = rvs($_params['l_1_header_li_id_prefix']);
		$this->_l_1_header_title = rvs($_params['l_1_header_title']);
		$this->_l_1_item_ul_id = rvs($_params['l_1_item_ul_id']);
		$this->_l_1_item_li = rvs($_params['l_1_item_li']);
		$this->_l_1_note_title_li =  rvs($_params['l_1_note_title_li']);
		$this->_img_closed_id_prefix = rvs($_params['img_closed_id_prefix']);
		$this->_img_opened_id_prefix = rvs($_params['img_opened_id_prefix']);
		$this->_main_db_tbl = rvs($_params['main_db_tbl']);
		$this->_sub_db_tbls = rva($_params['sub_db_tbls']);
		$this->_sub_db_tbl_fields = rva($_params['sub_db_tbl_fields']);
		$this->_rows = rva($_params['rows']);
		$this->_topic_id = rvs($_params['topic_id']);
		$this->_labels = rva($_params['labels']);
		$this->_title = rva($_params['title']);
		$this->_instructions = rva($_params['instructions']);
		$this->_body = rva($_params['body']);
		$this->_use_notes_header = rvb($_params['use_notes_header']);
		$this->_del_main_class = $_params['del_main_class'];
		$this->_del_sub_class = $_params['del_sub_class'];
		$this->_the_rest = rva($_params['the_rest']);
		$this->_div_class = rva($_params['div_class']);
		$this->_ids = rva($_params['ids']);
		$this->_delete_items = rvb($_params['delete_items']);
		$this->_extra_jq = rvs($_params['extra_jq']);
		$this->_edit_fields = rvs($_params['edit_fields']);
		//_cl($_params, 'PARAMS');

		$this->_del_header_params = array(
			'main_db_tbl' => $this->_main_db_tbl,
			'sub_db_tbls' => $this->_sub_db_tbls,
			'sub_db_tbl_fields' => $this->_sub_db_tbl_fields,
			'image_class' => $this->_del_main_class,
			'add_script_tags' => false,
			'add_document_ready' => false,
			'main_list_id' => $this->_l_1_header_ul_id,
			'sub_list_id' => $this->_l_1_item_ul_id
		);

		if ($this->_delete_items){
			$this->_del_item_params = array(
				'main_db_tbl' => $this->_sub_db_tbls[0],
				'image_class' => $this->_del_sub_class,
				'add_script_tags' => false,
				'add_document_ready' => false,
				'main_list_id' => $this->_l_1_item_li
			);
		}


	}


	public function _build_list(){
		$tmp = "";
		if ($this->_is_logged_in){
			/* Here we put the delete code as well as the add_new code and the image */

			$tmp .= "<script>$(document).ready(function(e){";
			$tmp .= $this->_build_add_new_jq();
			if (!empty($this->_extra_jq)){
				$tmp .= $this->_extra_jq;
			}


			$tmp .= _build_del_header($this->_del_header_params, true);
			if ($this->_delete_items){
				$tmp .= _build_del_item($this->_del_item_params, true);
			}


			$tmp .= "});";
			$tmp .= "</script>";
		}

		if ($this->_is_logged_in){
			$tmp .= $this->_build_add_new_btn(150);
			$tmp .= "<ul id = '".$this->_l_0_ul_id."' class='sortable-list'>";
		}else{
			$tmp .= "<ul id = '".$this->_l_0_ul_id."'>";
		}

		if (!empty($this->_rows)){
			$_count = 0;
			foreach ($this->_rows as $_row){
				$_a = $_count + 1;
				$_id = $_row['id'];
				$tmp .= "<li class = 'rc' id = '".$this->_l_0_li_id_prefix.$_id."' data-db-tbl='".$this->_main_db_tbl."'>";

				$tmp .= "<ul id = '".$this->_l_1_header_ul_id.$_id."' class = 'topic-header-list w100pc'>";

				if ($this->_is_logged_in){
					$this->_del_header_params['main_db_tbl_field_value'] = $_id;
					$tmp .= _build_del_header($this->_del_header_params, false);
					$tmp .= "<li id = '".$this->_l_1_header_li_id_prefix.$_id."' class = 'point' data-list-id = '".$this->_l_1_item_ul_id.$_id."' data-img-cl = '".$this->_img_closed_id_prefix.$_id."' data-img-op = '".$this->_img_opened_id_prefix.$_id."'>";
				}else{
					$tmp .= "<li id = '".$this->_l_1_header_li_id_prefix.$_id."' class='point open-list' data-list-id = '".$this->_l_1_item_ul_id.$_id."' data-img-cl = '".$this->_img_closed_id_prefix.$_id."' data-img-op = '".$this->_img_opened_id_prefix.$_id."'>";
				}
				$tmp .= "<div class='row w40 p5'>";
				$tmp .= "<img width = '32' height = '32' alt = 'Open' title='Click to open the example.' id = '".$this->_img_opened_id_prefix.$_id."' class = 'ttip open-list' src='".__s_lib_url__."_images/_icons/closed.png' data-list-id = '".$this->_l_1_item_ul_id.$_id."' data-img-cl = '".$this->_img_closed_id_prefix.$_id."' data-img-op = '".$this->_img_opened_id_prefix.$_id."' />";
				$tmp .= "<img width = '32' height = '32' alt = 'Close' title='Click to close the example.' class = 'hidden ttip open-list' id = '".$this->_img_closed_id_prefix.$_id."' src='".__s_lib_url__."_images/_icons/opened.png' data-list-id = '".$this->_l_1_item_ul_id.$_id."' data-img-cl = '".$this->_img_closed_id_prefix.$_id."' data-img-op = '".$this->_img_opened_id_prefix.$_id."' />";
				$tmp .= "</div>";
				if ($this->_is_logged_in){
					if (!empty($this->_edit_fields[$_count])){
						$tmp .= "<div class='row f1'><span class = 'h3n nowrap'>".$this->_l_1_header_title." #".$_a.": </span>". $this->_title[$_count].' '.$this->_edit_fields[$_count]."</div>";
					}else{
						$tmp .= "<div class='row f1'><span class = 'h3n nowrap'>".$this->_l_1_header_title." #".$_a.": </span>". $this->_title[$_count]."</div>";
					}
				}else{
					$tmp .= "<div class='row f1'><span class = 'h3n'>".$this->_l_1_header_title." #".$_a.": ". $this->_title[$_count]."</span></div>";

				}
				$tmp .= "</li></ul>";
				$_oc = rvs($_COOKIE[$this->_l_1_item_ul_id.$_id], 'closed');
				$_oc_class = ($_oc == 'open') ? '' : ' hidden';
				$tmp .= "<ul id = '".$this->_l_1_item_ul_id.$_id."' class = 'topic-item-list".$_oc_class."'>";
				if ($this->_use_notes_header){
					$tmp .= "<li class= 'nb thin'><div class='label'></div><div class='the-rest'>".$this->_l_1_note_title_li."</div></li>";
				}
				if (!empty($this->_instructions[$_count])){
					$tmp .= "<li class= 'instructions'><span class = 'p5 b'>Instructions:</span><div class = 'p4 w80pc'>".$this->_instructions[$_count]."</div></li>";
				}
				if (!empty($this->_body[$_count])){
					$tmp .= "<li class= 'body'><div class = 'p4 w80pc'>".$this->_body[$_count]."</div></li>";
				}
				$_num_rows = 0;
				if (!empty($this->_the_rest[$_count])){
					if (is_array($this->_the_rest[$_count])){
						foreach ($this->_the_rest[$_count] as $_row){
							$_num_divs = 0;
							if (!empty($this->_ids[$_count][$_num_rows])){
								$tmp .= "<li id = 'sub".$this->_ids[$_count][$_num_rows]."'><div class='label'>";
							}else{
								$tmp .= "<li><div class='label'>";
							}
							if ($this->_is_logged_in && !empty($this->_the_rest[$_count])){
								if ($this->_delete_items){
									$this->_del_item_params['main_db_tbl_field_value'] = $this->_ids[$_count][$_num_rows];
									$tmp .= _build_del_item($this->_del_item_params, false);
								}
							}
							$tmp .= rvs($this->_labels[$_count][$_num_rows])."</div><div class='the-rest wrap'>";
							//_cl($_row, 'ROWWWWWWWW');
							if (is_array($_row)){
								foreach ($_row as $_div){
									$tmp .= "<div class = '".rvs($this->_div_class[$_num_divs])."'>".$_div."</div>";
									$_num_divs++;
								}
							}else{
								$tmp .= "<li><div class = '".rvs($this->_div_class[$_num_divs])."'>".$_row."</div>";
								$_num_divs++;
							}
							$_num_rows++;
						}
					}else{

					}
				}
				$tmp .= "</li>";
				$tmp .= "</ul></li>";
				$_count++;
			}
		}
		$tmp .= "</ul>";
		return $tmp;
	}

	private function _build_add_new_jq(){
		$tmp = "
			$(document).on('click', '.add_new_".$this->_add_new_text."', function(e){
				e.preventDefault();
				e.stopImmediatePropagation();
				var id = $(this).attr('id').substring(2);
				var fd = new FormData();
				fd.append('db_tbl', '".$this->_main_db_tbl."');
				fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
				fd.append('topic_id', ".$this->_topic_id.");
				$.ajax({
					type: 'POST',
					async : true,
					cache : false,
					processData	: false,
					contentType	: false,
					url: '".__s_app_url__."_ajax/".$this->_ajax_add_new_file."',
					data: fd,
					dataType: 'json',
					success: function (data) {
						$('ul#".$this->_l_0_ul_id."').append(data);
					}
				});
			});";
		return $tmp;
	}



	private function _build_li($_l, $_t, $_n){
		return "<li><div class='label'>".$_l."</div><div class='the-rest'><div class='text'>".$_t."</div><div class='note'>".$_n."</div></div></li>";
	}

	private function _build_add_new_btn($_wc = 200){
		$tmp = "<button type = 'button' class = 'add_new_".$this->_add_new_text." add w".$_wc." mb5' id = 'to".$this->_topic_id."'>Add new ".str_replace('_', ' ', $this->_add_new_text)."</button>";
		return $tmp;
	}


}


class _title_bar{
	private $_img;
	private $_img_alt;
	private $_title;

	public function __construct(){}

	public function _build_title_bar(){
		$_tmp = "<div class = 'page-title mb10'>";
		$_img = $this->_get_img();
		$_title = $this->_get_title();
		if (!empty($_img)){
			$_img = "_images/_icons/32/".$_img;
			$_img_path = __s_lib_folder__.$_img;
			$_img_url = __s_lib_url__.$_img;
			if (file_exists($_img_path)){
				$_tmp .= "<img class='ml20 mr20' src = '".$_img_url."' alt = '".ucwords($this->_img_alt)."' />";
			}
		}
		$_tmp .= "<span class='page-title-text'>" . ucwords($_title) . '</span></div>';
		return $_tmp;
	}

	public function _get_img() { return $this->_img; }
	public function _get_title() { return $this->_title; }


	public function _set_img($_t) { $this->_img = $_t; }
	public function _set_img_alt($_t) { $this->_img_alt = $_t; }
	public function _set_title($_t) { $this->_title = $_t; }


}

class _filter{
	private $_label = 'Filter';
	private $_input_width_class = 'w300';
	private $_input_css_id = 'sfilter';
	private $_input_title = 'Type into this field to filter the list down to those records which contains those letters or words.';

	public function __construct(){}

	public function _build_filter(){
		$tmp = "<div class='filter-row'><label class='ml10' for = '".$this->_input_css_id."'><h4>".$this->_label.":</h4></label>
		<input id = '".$this->_input_css_id."' class = '".$this->_input_width_class." mr5 ml5 ttip' title = '".$this->_input_title."' type = 'text' /></div>";
		return $tmp;
	}

	public function _get_label() { return $this->_label; }
	public function _get_input_width_class() { return $this->_input_width_class; }
	public function _get_input_css_id() { return $this->_input_css_id; }
	public function _get_input_title() { return $this->_input_title; }

	public function _set_label($_t) { $this->_label = $_t; }
	public function _set_input_width_class($_t) { $this->_input_width_class = $_t; }
	public function _set_input_css_id($_t) { $this->_input_css_id = $_t; }
	public function _set_input_title($_t) { $this->_input_title = $_t; }
}

class _img{

	private $_url;
	private $_class;
	private $_img_name;
	private $_title;
	private $_alt;

	public function __construct(){}

	private function _fetch_src(){
		return $this->_url.$this->_img_name;
	}

	public function _fetch_img(){
		$_class = $this->_fetch_class_str();
		$tmp = "<img src = '".$this->_fetch_src()."' alt = '".$this->_alt."' class = '".$_class."' title = '".$this->_title."' />";
		return $tmp;
	}
	private function _fetch_class_str(){
		$_class = '';
		foreach ($this->_class as $_c){
			$_class .= $_c." ";
		}
		$_class = substr($_class, 0, -1);
		return $_class;
	}


	public function _get_class() { return $this->_class; }
	public function _get_img_name() { return $this->_img_name; }
	public function _get_title() { return $this->_title; }
	public function _get_alt() { return $this->_alt; }
	public function _get_url() { return $this->_url; }

	public function _set_class($_t) { $this->_class = $_t; }
	public function _set_img_name($_t) { $this->_img_name = $_t; }
	public function _set_title($_t) { $this->_title = $_t; }
	public function _set_alt($_t) { $this->_alt = $_t; }
	public function _set_url($_t) { $this->_url = $_t; }
}
?>