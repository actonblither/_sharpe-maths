<?php
class _tips extends _setup{
	private $_original_txt;
	private $_return_txt;
	private $_search_txt;
	private $_tips = array();
	private $_excluded_words = array('the', 'a', 'an', 'another', 'to', 'at', 'before', 'after', 'in', 'how', 'and', 'but', 'however', 'something', 'they', 'them', 'there', 'their', 'is', 'be', 'almost', 'can', 'you', 'this', 'that', 'i', 'will', 'am', 'are', 'with', 'which', 'when', 'him', 'her', 'his', 'hers', 'its', 'on', 'of', 'other', 'as', 'we', 'learn', 'it', 'simplest', 'amazingly', 'useful', 'put', 'find', 'now', 'have', 'only', 'fairly', "i'll", "if", '-', 'uk', 'covered', 'previously', 'straightforward', 'found', 'look', 'fun', 'about', 'by', '16th', '17th', '18th', '19th', '20th', 'also', 'great', 'behind', 'one', 'day', 'get', 'round', 'allow', 'allows', 'thing', 'things', 'out', 'us', 'just', 'than', 'working', 'help', 'helpful', 'place', 'start', 'next', 'few', 'back', 'forth', 'forward', 'between', 'our', 'practical', 'all', 'any', 'was', );


	public function __construct($_text){
		parent::__construct();
		$this->_original_txt = $_text;
		$this->_return_txt = $_text;
		$this->_search_txt = strip_tags($_text);

		$this->_parse_text();
	}

	private function _parse_text(){
		$this->_search_txt = trim(strtolower($this->_search_txt));
		$this->_search_txt = preg_replace( "/\r|\n/", "#", $this->_search_txt);
		$_words = explode(" ", $this->_search_txt);
		$_words = str_replace(",", "", $_words);
		$_words = str_replace(".", "", $_words);
		$_words = str_replace("&", "", $_words);
		$_words = str_replace("(", "", $_words);
		$_words = str_replace(")", "", $_words);
		foreach ($this->_excluded_words as $_e){
			$_words = array_diff($_words, [$_e]);
		}
		$_words = array_filter($_words);// Remove empty elements
		$_words = array_map('trim', $_words);//Trim all elements
		$_words = array_values($_words);// Renumber the array
		//_cl($_words);
		if (!empty($_words)){
			for ($_i = 0; $_i < count($_words);$_i++){
				if (instr('#', $_words[$_i])){
					$_wa = explode('#', $_words[$_i]);
					$_words[$_i] = $_wa[0];
				}
				$_sql = 'select * from _app_tips where keywords LIKE :keywords';
				$_d = array('keywords' => "%$_words[$_i]%");
				$_f = array('s');
				$_row = $this->_dbh->_fetch_db_row_p($_sql, $_d, $_f);
				if ($_row){
					$this->_set_tooltips($_row, $_words[$_i]);
				}
			}
			_cl($_words);
			_cl($this->_get_return_txt());
		}

	}

	private function _set_tooltips($_row, $_word){
		$_ttip_span = "<span class = 'ttip' title = '".addslashes($_row['body'])."' >".$_word."</span>";
		$this->_return_txt = str_replace($_word, $_ttip_span, $this->_return_txt);
	}



	public function _get_original_txt() { return $this->_original_txt; }
	public function _get_return_txt() { return $this->_return_txt; }
	public function _get_search_txt() { return $this->_search_txt; }
	public function _get_tips() { return $this->_tips; }

	public function _set_original_txt($_t) { $this->_original_txt = $_t; }
	public function _set_return_txt($_t) { $this->_return_txt = $_t; }
	public function _set_search_txt($_t) { $this->_search_txt = $_t; }
	public function _set_tips($_t) { $this->_tips = $_t; }
}