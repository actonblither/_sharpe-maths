<?php
class _tips extends _setup{
	private $_return_txt;
	private $_ttip_content;
	private $_stuffing = '####';
	private $_exclude = '!!';
	private $_age_level;
	private $_topic_id;
	private $_adv_content = false;




	public function __construct($_txt, $_topic_id = null){
		parent::__construct();
		$this->_return_txt = $_txt;
		if (!is_null($_topic_id)){
			$this->_topic_id = $_topic_id;
			$this->_fetch_age_level();
			if ($this->_age_level > 3){$this->_adv_content = true;}
		}

		$_words = $this->_fetch_keywords();
		$this->_parse_text($_words);
		//Now remove the stuffing
		$this->_return_txt = str_replace($this->_stuffing, '', $this->_return_txt);
		$this->_return_txt = str_replace($this->_exclude, '', $this->_return_txt);
		$_sb = array('/\s+\, /', '/\s+\. /', '/\s+\; /', '/\s+\: /');
		$_nsb = array(', ', '. ', '; ', ': ');
		$this->_return_txt = preg_replace($_sb, $_nsb, $this->_return_txt);

	}

	private function _fetch_age_level(){
		$_sql = 'select age_levels from _app_topic where id = :id';
		$_d = array('id' => $this->_topic_id);
		$_f = array('i');
		$this->_age_level = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
	}

	private function _fetch_keywords(){
		$_words = array();
		$_sql = 'select id, keywords from _app_tips where display = 1 order by order_num';
		$_rows = $this->_dbh->_fetch_db_rows($_sql);
		foreach ($_rows as $_r){
			if (instr('#', $_r['keywords'])){
				$_id = $_r['id'];
				$_wa = explode('#', $_r['keywords']);
				foreach ($_wa as $_w){
					$_words[] = $_w."_".$_id;
					$_ucfw = ucfirst($_w);
					if ($_w !== $_ucfw){
						$_words[] = $_ucfw."_".$_id;
					}
				}
			}else if (!empty($_r)){
				$_id = $_r['id'];
				$_w = $_r['keywords'];
				$_words[] = $_w."_".$_id;
				$_ucfw = ucfirst($_w);
				if ($_w !== $_ucfw){
					$_words[] = $_ucfw."_".$_id;
				}
			}
		}
		$_words = array_filter($_words);// Remove empty elements
		$_words = array_map('trim', $_words);//Trim all elements
		$_words = array_values($_words);// Renumber the array
		//_cl($_words);
		return $_words;
	}

	private function _parse_text($_words){
		foreach ($_words as $_w){
			$_item = explode('_', $_w);
			if (instr($_item[0], $this->_return_txt)){
				$this->_set_tooltips($_item[0], $_item[1]);
			}
		}
	}

	private function _set_tooltips($_word, $_id){
		$_ws = '/\b'.$_word.'\b/';
		$_ttip_span = " <span class = 'ttip' title = 'tt' data-ttcontent = 'tt".$_id."'>".$_word."</span>";
		$this->_return_txt = preg_replace($_ws, $_ttip_span, $this->_return_txt);
	}



	public function _get_return_txt() { return $this->_return_txt; }

	public function _set_return_txt($_t) { $this->_return_txt = $_t; }

}