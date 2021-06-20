<?php
class _tips extends _setup{
	private $_return_txt;
	private $_ttip_content;
	private $_stuffing = '####';
	private $_exclude = '!!';


	public function __construct($_txt){
		parent::__construct();
		$this->_return_txt = $_txt;

		$_words = $this->_fetch_keywords();
		$this->_parse_text($_words);
		//Now remove the stuffing
		$this->_return_txt = str_replace($this->_stuffing, '', $this->_return_txt);
		$this->_return_txt = str_replace($this->_exclude, '', $this->_return_txt);
		//_cl($this->_return_txt);
	}

	private function _fetch_keywords(){
		$_words = array();
		$_sql = 'select keywords from _app_tips';
		$_rows = $this->_dbh->_fetch_db_rows($_sql);
		foreach ($_rows as $_r){
			if (instr('#', $_r['keywords'])){
				$_wa = explode('#', $_r['keywords']);
				foreach ($_wa as $_w){
					$_w = trim($_w,'"'); // double quotes
					$_w = trim($_w ,'\'"'); // any combination of ' and "
					$_words[] = $_w;
				}
			}else if (!empty($_r)){
				$_r['keywords'] = trim($_r['keywords'],'"'); // double quotes
				$_r['keywords'] = trim($_r['keywords'] ,'\'"'); // any combination of ' and "
				$_words[] = $_r['keywords'];
			}
		}
		$_words = array_filter($_words);// Remove empty elements
		$_words = array_map('trim', $_words);//Trim all elements
		$_words = array_values($_words);// Renumber the array
		return $_words;
	}

	private function _parse_text($_words){
		foreach ($_words as $_w){
			$_wl = strtolower($_w);
			$_wu = ucwords($_w);// For names etc.
			$_wf = ucfirst($_w);
			if (instr($_wl, $this->_return_txt) || instr($_wu, $this->_return_txt) || instr($_wf, $this->_return_txt)){
				$_sql = 'select * from _app_tips where lower(keywords) LIKE :keywords';
				$_d = array('keywords' => "%$_wl%");
				$_f = array('s');
				$_row = $this->_dbh->_fetch_db_row_p($_sql, $_d, $_f);
				$_txt = $_row['body'];
				if ($_txt){
					$_ta = explode(' ', $_txt);
					$_txt = implode(' ', substr_replace($_ta, $this->_stuffing, 1, 0));
					$this->_set_tooltips($_row, $_w);
				}
			}
		}

	}

	private function _set_tooltips($_row, $_word){
		$_w = $_word;
		for ($_i = 0; $_i < 3; $_i++){
			if ($_i == 0){$_word = strtolower($_w);}
			if ($_i == 1){$_word = ucfirst($_w);}
			if ($_i == 2){$_word = ucwords($_w);}// Cycle thrice changing upper and lower case initial letters

			//_cl($_word);
			$_ttip_span = "<span class = 'ttip' title = 'tt' data-ttcontent = 'tt".$_row['id']."'>".$_word."</span>";
			$this->_return_txt = str_replace($_word, $_ttip_span, $this->_return_txt);
		}
	}



	public function _get_return_txt() { return $this->_return_txt; }

	public function _set_return_txt($_t) { $this->_return_txt = $_t; }

}