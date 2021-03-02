<?php
class _topic extends _setup{

	private $_topic_id;
	private $_topic_order_num;
	private $_topic_parent_id;
	private $_topic_route_id;
	private $_topic_tab_bar;
	private $_topic_order_array;

	private $_topic_title_bar_img;
	private $_topic_title;
	private $_topic_intro;

	private $_topic_ex;
	private $_topic_eg;
	private $_topic_pz;
	private $_topic_act;
	private $_topic_geo;

	private $_show_intro_tab = false;
	private $_show_eg_tab = false;
	private $_show_ex_tab = false;
	private $_show_pz_tab = false;
	private $_show_act_tab = false;
	private $_show_geo_tab = false;

	private $_ex_count;
	private $_ex_title;
	private $_ex_instructions;
	private $_row;


	public function __construct($_id = null){
		parent::__construct();
		$this->_dbh = new _db();
		$this->_topic_order_array = $_SESSION['s_topic_order'];
		$this->_topic_route_id = rvz($_id);
	}

	public function _build_topic(){
		$this->_fetch_topic_id();
		$this->_load_topic_data();
		$this->_build_intro_text();
		$this->_build_examples();
		$this->_build_exercises();
		$this->_build_puzzles();
		$this->_build_activity();
		$tmp = $this->_build_navigation_bar();
		$tmp .= $this->_build_tab_bar();
		return $tmp;
	}

	private function _load_topic_data(){
		$_sql = 'select * from _app_topic where id = :id';
		$_d = array('id' => $this->_topic_id);
		$_f = array('i');
		$this->_row = $this->_dbh->_fetch_db_row_p($_sql, $_d, $_f);
		$this->_set_topic_title($this->_row['title']);
		$this->_set_topic_intro($this->_row['intro']);
		$this->_set_topic_title_bar_img($this->_row['title_bar_img']);
		if (!empty($this->_row['intro'])){$this->_show_intro_tab = true;}
	}

	private function _fetch_topic_id(){
		$_sql = 'select id from _app_topic where route_id = :route_id';
		$_d = array('route_id' => $this->_get_topic_route_id());
		$_f = array('i');
		$_id = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
		$this->_topic_id = $_id;

		$_sql = 'select parent_id, order_num from _app_nav_routes where id = :id';
		$_d = array('id' => $this->_topic_route_id);
		$_f = array('i');
		$_row = $this->_dbh->_fetch_db_row_p($_sql, $_d, $_f);
		$this->_topic_parent_id = $_row['parent_id'];
		$this->_topic_order_num = $_row['order_num'];
	}


	private function _build_navigation_bar(){
		$index = array_search($this->_topic_route_id, $this->_topic_order_array);
		if($index !== false && $index > 0 ) $prev = $this->_topic_order_array[$index-1];
		if($index !== false && $index < count($this->_topic_order_array)-1) $next = $this->_topic_order_array[$index+1];
		$tmp = "<div class='c page-title mb5'>";
		if (rvz($prev) > 0){
			$_prev_topic_id = $this->_fetch_topic_id_from_route_id($prev);
			$_sql = 'select title from _app_topic where id = :id';
			$_d = array('id' => $_prev_topic_id);
			$_f = array('i');
			$_prev_title = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);

			$tmp .= "
				<div class='ifc w25pc col ml10 hh'>
					<div class='topic-label'>Previous topic:</div>
					<div class='topic-title prev'>".$_prev_title."</div>
				</div>
				<div class='ifc w10pc'>
					<img class='point nav_arrow' data-id='".$prev."' data-main= 'topic' src='_stdlib/_images/_icons/arrow_left50.png' alt='Previous topic' />
				</div>";
		}else{
			$tmp .= "<div class='ifc w25pc ml20 hh'></div><div class='w10pc'></div>";
		}
		$tmp .= "<div class='ifc col w25pc hh c'><div class='topic-label'>Current topic:</div><div class='topic-title'>".$this->_get_topic_title()."</div></div>";
		if (rvz($next) > 0){
			$_next_topic_id = $this->_fetch_topic_id_from_route_id($next);
			$_sql = 'select title from _app_topic where id = :id';
			$_d = array('id' => $_next_topic_id);
			$_f = array('i');
			$_next_title = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
			$tmp .= "
				<div class='ifc w10pc r'>
					<img class='point nav_arrow' data-id='".$next."' data-main= 'topic' src='_stdlib/_images/_icons/arrow_right50.png' alt='Next topic' />
				</div>
				<div class='ifc w25pc col mr20 hh'><div class='topic-label'>Next topic:</div><div class='topic-title'>".$_next_title."</div></div>";
		}else{
			$tmp .= "<div class='ifc w15pc'></div><div class='ifc w25pc mr10 hh'></div>";
		}
		$tmp .="</div>";


		return $tmp;
	}



	private function _build_tab_bar(){
		$_tab = new _tabs();
		$_tab->_set_tab_nav_id('top-tabs');

		$_tab_arr[] = 'Introduction';
		$_lnk_arr[] = 'intro-1';
		$_hlp_arr[] = '';
		$_txt_arr[] = $this->_topic_intro;


		if ($this->_show_eg_tab){
			$_tab_arr[] = 'Worked examples';
			$_lnk_arr[] = 'example-2';
			$_hlp_arr[] = '';
			$_txt_arr[] = $this->_topic_eg;
		}

		if ($this->_show_ex_tab){
			$_tab_arr[] = 'Exercises';
			$_lnk_arr[] = 'exercise-3';
			$_hlp_arr[] = '';
			$_txt_arr[] = $this->_topic_ex;
		}

		if ($this->_show_pz_tab){
			$_tab_arr[] = 'Puzzles';
			$_lnk_arr[] = 'puzzles-4';
			$_hlp_arr[] = '';
			$_txt_arr[] = $this->_topic_pz;
		}

		if ($this->_show_act_tab){
			$_tab_arr[] = 'Activities';
			$_lnk_arr[] = 'activ-5';
			$_hlp_arr[] = '';
			$_txt_arr[] = $this->_topic_act;
		}

		if ($this->_show_geo_tab){
			$_tab_arr[] = 'Geogebra';
			$_lnk_arr[] = 'geogebra-6';
			$_hlp_arr[] = '';
			$_txt_arr[] = $this->_topic_geo;
		}


		$_tab->_set_tab_labels($_tab_arr);
		$_tab->_set_tab_links($_lnk_arr);
		$_tab->_set_tab_help($_hlp_arr);
		$_tab->_set_tab_pages($_txt_arr);
		return $_tab->_build_all();
	}

	private function _build_activity(){
		$_act = new _activity($this->_topic_id);
		$this->_topic_act = $_act->_fetch_activity();
		$this->_show_act_tab = $_act->_get_make_act_tab();
	}

	private function _build_puzzles(){
		$_pz = new _puzzle($this->_topic_id);
		$this->_topic_pz = $_pz->_fetch_topic_puzzles();
		$this->_show_pz_tab = $_pz->_get_make_pz_tab();
	}

	private function _build_exercises(){
		$_ex = new _exercise($this->_topic_id);
		$this->_topic_ex = $_ex->_fetch_exercises();
		$this->_show_ex_tab = $_ex->_get_make_ex_tab();
	}

	private function _build_examples(){
		$_eg = new _example($this->_topic_id);
		$this->_topic_eg = $_eg->_fetch_examples();
		$this->_show_eg_tab = $_eg->_get_make_eg_tab();
	}

	private function _build_geogebra(){
		$_geo = new _geogebra($this->_topic_id);
		$this->_topic_geo = $_geo->_fetch_geogebra();
		$this->_show_geo_tab = $_geo->_get_make_geo_tab();
	}

	private function _build_intro_text(){
		$_intro = new _intro($this->_topic_id);
		$this->_topic_intro = $_intro->_fetch_intro_text();
		$this->_show_intro_tab = $_intro->_get_make_intro_tab();
	}

	private function _fetch_topic_id_from_route_id($id){
		$_sql = 'select id from _app_topic where route_id = :route_id';
		$_d = array('route_id' => $id);
		$_f = array('i');
		return $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
	}

	private function _get_next($array, $key) {
		$currentKey = key($array);
		while ($currentKey !== null && $currentKey != $key) {
			next($array);
			$currentKey = key($array);
		}
		return next($array);
	}

	private function _get_prev($array, $key) {
		$currentKey = key($array);
		while ($currentKey !== null && $currentKey != $key) {
			prev($array);
			$currentKey = key($array);
		}
		return prev($array);
	}

	public function _get_topic_id() { return $this->_topic_id; }
	public function _get_topic_route_id() { return $this->_topic_route_id; }
	public function _get_topic_tab_bar() { return $this->_topic_tab_bar; }
	public function _get_topic_title_bar_img() { return $this->_topic_title_bar_img; }
	public function _get_topic_title() { return $this->_topic_title; }
	public function _get_topic_intro() { return $this->_topic_intro; }
	public function _get_topic_ex() { return $this->_topic_ex; }
	public function _get_topic_eg() { return $this->_topic_eg; }
	public function _get_topic_pz() { return $this->_topic_pz; }
	public function _get_topic_act() { return $this->_topic_act; }
	public function _get_topic_geo() { return $this->_topic_geo; }
	public function _get_ex_count() { return $this->_ex_count; }
	public function _get_ex_title() { return $this->_ex_title; }
	public function _get_ex_instructions() { return $this->_ex_instructions; }
	public function _get_show_intro_tab() { return $this->_show_intro_tab; }
	public function _get_show_eg_tab() { return $this->_show_eg_tab; }
	public function _get_show_ex_tab() { return $this->_show_ex_tab; }
	public function _get_show_pz_tab() { return $this->_show_pz_tab; }
	public function _get_show_act_tab() { return $this->_show_act_tab; }
	public function _get_show_geo_tab() { return $this->_show_geo_tab; }

	public function _set_topic_id($_t) { $this->_topic_id = $_t; }
	public function _set_topic_route_id($_t) { $this->_topic_route_id = $_t; }
	public function _set_topic_tab_bar($_t) { $this->_topic_tab_bar = $_t; }
	public function _set_topic_title_bar_img($_t) { $this->_topic_title_bar_img = $_t; }
	public function _set_topic_title($_t) { $this->_topic_title = $_t; }
	public function _set_topic_intro($_t) { $this->_topic_intro = $_t; }
	public function _set_topic_ex($_t) { $this->_topic_ex = $_t; }
	public function _set_topic_eg($_t) { $this->_topic_eg = $_t; }
	public function _set_topic_pz($_t) { $this->_topic_pz = $_t; }
	public function _set_topic_order_array($_t){$this->_topic_order_array = $_t;}
	public function _set_ex_count($_t) { $this->_ex_count = $_t; }
	public function _set_ex_title($_t) { $this->_ex_title = $_t; }
	public function _set_ex_instructions($_t) { $this->_ex_instructions = $_t; }
	public function _set_show_intro_tab($_t) { $this->_show_intro_tab = $_t; }
	public function _set_show_eg_tab($_t) { $this->_show_eg_tab = $_t; }
	public function _set_show_ex_tab($_t) { $this->_show_ex_tab = $_t; }
	public function _set_show_pz_tab($_t) { $this->_show_pz_tab = $_t; }
}
?>