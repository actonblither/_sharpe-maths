<?php
class _topic_tab{
	protected $_dbh;
	protected $_item_id;
	protected $_item_title;
	protected $_item_name;
	protected $_items;
	protected $_make_item_tab = false;
	protected $_rows;
	protected $_topic_id;
	protected $_item_count;
	protected $_list_count;
	protected $_is_logged_in;
	protected $_tpl_parent;
	protected $_tpl_head;
	protected $_tpl_head_elements;
	protected $_tpl_sub;
	protected $_oco_class;
	protected $_occ_class;
	protected $_del_params;
	protected $_del_item_params;
	protected $_add_new_params;
	protected $_search_array;
	protected $_replace_array;
	protected $_item_sql;
	protected $_item_sql_admin;
	protected $_sub_sql = false;
	protected $_tpl_sub_instructions;
	protected $_tpl_sub_body;
	protected $_sub_body = false;
	protected $_head_elements = false;
	protected $_template_folder = __s_app_folder__.'_classes/_templates/';
	protected $_field_prefix = '';
	protected $_topic_link_tbl = '';
	protected $_topic_link_tbl_field = '';
	protected $_link_self_ref = false;
	protected $_sortable_list_prefix = 'n';
	protected $_sr;//search replace array
	protected $_header_edit_elements = '';


	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
		$this->_is_logged_in = is_logged_in();
		// This SQL is the default. It can be overridden in the child class
		$this->_item_sql = 'select * from '.$this->_main_db_tbl.' where topic_id = :topic_id and display = :display and archived = :archived order by order_num';
		$this->_item_sql_admin = 'select * from '.$this->_main_db_tbl.' where topic_id = :topic_id and archived = :archived order by order_num';

		if ($this->_is_logged_in){
			$this->_tpl_parent = $this->_template_folder.'_admin_parent_tpl.txt';
			$this->_tpl_head = $this->_template_folder.'_admin_head_tpl.txt';
			$this->_tpl_sub_instructions = $this->_template_folder.'_admin_sub_instructions_tpl.txt';
			$this->_tpl_sub_body = $this->_template_folder.'_admin_sub_body_tpl.txt';
		}else{
			$this->_tpl_parent = $this->_template_folder.'_user_parent_tpl.txt';
			$this->_tpl_head = $this->_template_folder.'_user_head_tpl.txt';
			$this->_tpl_sub_instructions = $this->_template_folder.'_user_sub_instructions_tpl.txt';
			$this->_tpl_sub_body = $this->_template_folder.'_user_sub_body_tpl.txt';
		}
		$this->_del_params = array(
				'main_db_tbl' => $this->_main_db_tbl,
				'image_class' => $this->_del_img_class,
				'add_script_tags' => false,
				'add_document_ready' => false,
				'head_list_id' => $this->_head_list_id,
				'sub_list_id' => $this->_sub_list_id
		);

		$this->_add_new_params = array(
				'db_tbl' => $this->_main_db_tbl,
				'parent_list_id' => $this->_parent_list_id,
				'topic_id' => $this->_topic_id,
				'item_class' => $this->_item_class,
				'item_name' => $this->_item_name,
				'field_prefix' => $this->_field_prefix,
				'admin_template' => $this->_tpl_head,
		);
	}

	protected function _fetch_template_file($_tpl){
		ob_start();
		include($_tpl);
		$_page = ob_get_clean();
		foreach ($this->_sr as $_key => $_value){
			$_page = str_replace('{'.$_key.'}', $_value, $_page);
		}
		//_cl($_page);
		return $_page;
	}

	protected function _build_edit_elements($_item){
		$_id = $_item['id'];

		$_title_field_name = $this->_field_prefix.'title';
		$_title = rvs($_item[$_title_field_name]);

		$_params = array(
			'db_tbl' => $this->_main_db_tbl,
			'el_field_id' => $_title_field_name,
			'el_field_value' => $_title,
			'el_width' => 350,
			'el_id_value' => $_id,
		);
		$_tf = new _form_element($_params);
		$this->_header_edit_elements .= $_tf->_build_text_input();

		$_display = $_item['display'];
		$_params = array(
			'db_tbl' => $this->_main_db_tbl,
			'el_field_id' => 'display',
			'el_field_value' => $_display,
			'el_id_value' => $_id,
		);
		$_df = new _form_element($_params);
		$_display = $_df->_build_checkbox();
		$this->_header_edit_elements .= "<label for='display_".$_id."'> &nbsp;&nbsp; Display: </label>".$_display;
	}

	protected function _build_items(){
		$tmp = "";
		if ($this->_is_logged_in){
			$tmp .= "<script>$(document).ready(function(e){";

			$_del_h = new _delete($this->_del_params);
			$tmp .= $_del_h->_delete_jq();
			if (!empty($this->_del_item_params)){
				$_del_i = new _delete($this->_del_item_params);
				$tmp .= $_del_i->_delete_jq();
			}


			$tmp .= "});";
			$tmp .= "</script>";

			$_an = new _add_new_topic_item($this->_add_new_params);
			$tmp .= $_an->_build_add_new_btn();
		}
		$this->_items = $tmp . $this->_fetch_template($this->_tpl_parent);
		$_inner = '';
		if ($this->_is_logged_in){
			$_sql = $this->_item_sql_admin;
			$_d = array('topic_id' => $this->_topic_id, 'archived' => 0);
			$_f = array('i', 'i');
		}else{
			$_sql = $this->_item_sql;
			$_d = array('topic_id' => $this->_topic_id, 'display' => 1, 'archived' => 0);
			$_f = array('i', 'i', 'i');
		}

		$this->_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);

		if (!empty($this->_rows) || $this->_is_logged_in){$this->_make_item_tab = true;}
		$this->_list_count = 1;

		if (!empty($this->_rows)){
			foreach ($this->_rows as $_r){
				$this->_item_id = $_r['id'];

				$_oc = rvs($_COOKIE[$this->_sub_list_id.$this->_item_id], 'closed');
				if ($_oc == 'open'){
					$this->_oco_class = '';
					$this->_occ_class = ' hidden';
				}else{
					$this->_oco_class = ' hidden';
					$this->_occ_class = '';
				}
				$_inner .= $this->_fetch_template($this->_tpl_head, $_r);
				//NOW LOAD AND REPLACE HEADER/INSTRUCIONS/BODY
				$_inner = $this->_fetch_sub_tpls($_inner, $_r);
				if ($this->_sub_sql){
					$_qs = $this->_fetch_sub_list($_r);
					$_inner = str_replace('{_sub_tpl}', $_qs, $_inner);
				}else{
					if (!empty($this->_tpl_sub)){
						$_sub_tpl = $this->_fetch_template($this->_tpl_sub, $_r);
						$_inner = str_replace('{_sub_tpl}', $_sub_tpl, $_inner);
					}else{
						$_inner = str_replace('{_sub_tpl}', '', $_inner);
					}
				}
				$this->_list_count++;
			}
		}
		$this->_items = str_replace('{_tab_items}', $_inner, $this->_items);
	}

	private function _fetch_sub_tpls($_inner, $_r){

		if ($this->_is_logged_in){
			// Reset before build the next
			$this->_header_edit_elements = '';
			$this->_build_edit_elements($_r);
			$_head_frm_el = $this->_header_edit_elements;
			$_inner = str_replace('{_head_elements}', $_head_frm_el, $_inner);
			if (isset($this->_CKEditor)){
				$_inner = str_replace('{_CKEditor}', $this->_CKEditor, $_inner);
			}
		}else{
			$_inner = str_replace('{_head_elements}', '', $_inner);
		}

		$_instr = $this->_fetch_template($this->_tpl_sub_instructions, $_r);
		$_instruction_field = $this->_field_prefix.'instructions';
		if (!empty($_r[$_instruction_field]) || $this->_is_logged_in){
			$_inner = str_replace('{_sub_instructions}', $_instr, $_inner);
		}else{
			$_inner = str_replace('{_sub_instructions}', '', $_inner);
		}

		if (!empty($this->_tpl_sub_body) && $this->_sub_body === true){
			$_body = $this->_fetch_template($this->_tpl_sub_body, $_r);
			//_cl($_body);
			//$_tips = new _tips($_body);
			//$_body = $_tips->_get_return_txt();
			$_inner = str_replace('{_sub_body}', $_body, $_inner);
		}else{
			$_inner = str_replace('{_sub_body}', '', $_inner);
		}
		return $_inner;
	}

	public function _get_items() { return $this->_items; }
	public function _get_make_item_tab() { return $this->_make_item_tab; }

}