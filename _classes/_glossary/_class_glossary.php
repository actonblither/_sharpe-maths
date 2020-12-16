<?php
class _glossary extends _setup{
	private $_gid;
	private $_g_title;
	private $_g_letter;
	private $_g_description;
	private $_g_example_usage;
	private $_g_db_tbl = '_app_glossary';
	private $_g_link_tbl = '_app_link_glossary';

	public function __construct(){
		parent::__construct();
		$this->_gid = rvz($_REQUEST['gid']);
		if (isset($this->_gid) && is_int($this->_gid) && $this->_gid > 0){
			$this->_g_letter = chr($this->_gid);
		}
		echo $this->_build_glossary_nav();
		echo "<h1>Glossary of terms</h1>";
		echo "<h2>$this->_g_letter</h2>";
		echo $this->_load_glossary();
	}

	private function _load_glossary(){
		$tmp = "<section class = 'glossary'>";
		$_sql = "select * from ".$this->_g_db_tbl." where left(title, 1) = :letter and display = :display and archived = :archived order by title";
		$_d = array('letter' => $this->_g_letter, 'display' => 1, 'archived' => 0);
		$_f = array('s', 'i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		foreach ($_rows as $_r){
			if (is_logged_in()){
				$_r['title'] = $this->_build_varchar('_app_glossary', 'title', $_r['title'], $_r['id']);
				$_r['body'] = $this->_build_text('_app_glossary', 'body', $_r['body'], $_r['id']);
				$_r['example_of_use'] = $this->_build_text('_app_glossary', 'example_of_use', $_r['example_of_use'], $_r['id']);
				$_r['connectors'] = $this->_build_multiple_select($_r['id']);
			}else{
				$_r['connectors'] = $this->_build_connector_str($_r['id']);
			}
			$tmp .= "<ul class='glossary'>";
			$tmp .= "<li><div class='label'<p>Title:&nbsp;</p></div><div class='text'><h3 class='top'>".$_r['title']."</h3></div></li>";
			$tmp .= "<li class='line'></li>";
			$tmp .= "<li><div class='label'><p>Description:&nbsp;</p></div><div class='text'>".$_r['body']."</div></li>";
			$tmp .= "<li class='line'></li>";
			$tmp .= "<li><div class='label'><p>Example of use:&nbsp;</p></div><div class='text'>".$_r['example_of_use']."</div></li>";
			$tmp .= "<li class='line'></li>";
			$tmp .= "<li><div class='label'><p>See also:&nbsp;</p></div><div class='text'><p class='t'>".$_r['connectors']."</p></div></li>";
			$tmp .= "</ul>";
		}
		$tmp .= "</section>";
		return $tmp;
	}

	private function _build_connector_str($_id){
		$_sql = 'select distinct g.* from _app_glossary g left join _app_link_glossary lg on (lg.id_1 = g.id or lg.id_2 = g.id) where g.display = :display and g.archived = :archived and (lg.id_1 = :id or lg.id_2 = :id2) order by left(g.title, 1);';
		$_d = array('display' => 1, 'archived' => 0, 'id' => $_id, 'id2' => $_id);
		$_f = array('i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		$tmp = "";
		//_cl($_rows);
		if (!empty($_rows)){
			foreach ($_rows as $_r){
				$_g_letter = substr($_r['title'], 0, 1);
				$_gid = ord($_g_letter);
				if ($_r['id'] !== $_id){
					$tmp .= "<a href = 'index.php?main=topic&id=5&gid=".$_gid."'>".$_r['title']."</a>, ";
				}
			}
		}

		$tmp = substr($tmp, 0, -2);
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
		$_el->_set_el_width(30);
		$_el->_set_el_height(100);
		$_el->_set_el_width_units('%');
		$_el->_set_el_height_units('%');
		return $_el->_build_text_input();
	}

	private function _build_multiple_select($_id){
		$_db_tbl = '_app_link_glossary';
		$_db_tbl_field_1 = 'id_1';
		$_db_tbl_field_2 = 'id_2';
		$_sql = 'select * from _app_glossary where display = :display and archived = :archived order by left(title, 1);';
		$_d = array('display' => 1, 'archived' => 0);
		$_f = array('i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		$tmp = "<select id = 'link_id_".$_id."' multiple data-id = '".$_id."' data-field = 'id_1' data-db_tbl = '_app_link_glossary' class = 'sel-field h300'>";
		if (!empty($_rows)){
			for ($i = 0; $i < count($_rows); $i++){
				//Only output the option if it does not self-connects
				if ($_rows[$i]['id'] !== $_id){
					$_sql = "(select id_1, id_2 from _app_link_glossary where (id_1 = ".$_rows[$i]['id'].") and (id_2 = ".$_id.")) union (select id_1, id_2 from _app_link_glossary where (id_1 = ".$_id.") and (id_2 = ".$_rows[$i]['id']."))";
					$_sel_row = $this->_dbh->_fetch_db_row($_sql);
					if ($_sel_row['id_1'] === $_rows[$i]['id'] && $_sel_row['id_2'] === $_id || $_sel_row['id_2'] === $_rows[$i]['id'] && $_sel_row['id_1'] === $_id){
						$_sel_text = "selected = 'selected'";
					}else{
						$_sel_text = '';
					}
					$tmp .= "<option value='".$_rows[$i]['id']."' ".$_sel_text.">".$_rows[$i]['title']."</option>";
				}
			}
		}
		$tmp .= "</select>";
		return $tmp;
	}

	private function _build_add_new_exercise_btn(){
		$tmp = "<button type = 'button' class = 'w200 add_new_ex add mt5 ml10' id = 'to".$this->_topic_id."'>Add new exercise</button>";
		return $tmp;
	}

	private function _build_glossary_nav(){
		$tmp = "<nav id='glossary'>";
		for ($_i = 65; $_i < 91; $_i++){
			$tmp .= "<a href = 'index.php?main=topic&id=5&gid=".$_i."'>".chr($_i)."</a>&nbsp;&nbsp;";
		}
		$tmp .= "</nav>";
		return $tmp;
	}
}
?>