<?php
class _contact{
	private $_page_title;
	private $_page_body;

	public function __construct(){
		$this->_build_page_title();
		$this->_build_page_body();
	}

	public function _build_contact_form(){
		return $this->_page_title.$this->_page_body;
	}

	private function _build_page_body(){
		$tmp = "<section class = 'p10 border mt4 sb'>";
		$tmp .= '<p>Please send me a tweet if you wish to make suggestions and/or follow my maths twitter account.</p>';
		$tmp .= '<p><a href="https://twitter.com/intent/tweet?screen_name=MathsSharpe&ref_src=twsrc%5Etfw" class="twitter-mention-button" data-show-count="false">Tweet to @MathsSharpe</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></p>';
		$tmp .= '<p><a href="https://twitter.com/MathsSharpe?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-count="false">Follow @MathsSharpe</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></p>';
		$tmp .= '</section>';
		$this->_page_body = $tmp;
	}

	private function _build_page_title(){
		$_title = new _title_bar();
		$_title->_set_title('Make contact');
		$_title->_set_img('contact32.png');
		$_title->_set_img_alt('Contact');
		$this->_page_title = $_title->_build_title_bar();
	}




}