<?php
include('app_config.php');
$_t = '<a href="">General</a>General2<h4>General3</h4>';
$_word = '\bsine\b';
$_regex = '#\b'.$_word.'\b(?![^\[$<]*[\]$>])|(?![^<h[1-6].*>.*</h[1-6]>])#';


$_regex = "#(?<!<h.*)".$_word."#";
$_fp = fopen('./data.txt', 'r');
$_html = fread($_fp, filesize("data.txt"));


preg_match_all($_regex, $_html, $_matches, PREG_PATTERN_ORDER);
_cl($_matches);


function _is_between_tags(){

}
?>