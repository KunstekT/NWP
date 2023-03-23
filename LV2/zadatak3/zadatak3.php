<?php
	function handle_open_element($p, $element, $attributes) {
		switch($element) {
			case 'RECORD':
				echo '<div style="border: 1px solid #0000FF; padding: 5px">';
				break;
			case 'SLIKA':
				echo '<h2>' . $element . ': </h2>';
				echo '<div><img src="' . $element . '"/></div>';
				break;		
			case 'IME':
			case 'PREZIME':
			case 'EMAIL':
			case 'ZIVOTOPIS':
			case 'SPOL':
				echo '<h2>' . $element . ': </h2>';
				break;
		}
	}
	
	function handle_close_element($p, $element) {
		switch($element) {
			case 'RECORD':
				echo '</div>';
				break;
			case 'IME':
			case 'PREZIME':
			case 'EMAIL':
			case 'ZIVOTOPIS':
				echo '<br>';
				break;
		}
	}
	
	function handle_character_data($p, $cdata) {
		echo $cdata;
	}
	
	$p = xml_parser_create();
	xml_set_element_handler($p, 'handle_open_element', 'handle_close_element');
	xml_set_character_data_handler($p, 'handle_character_data');
	
	$file = 'LV2.xml';
	$fp = @fopen($file, 'r') or die("<p>Ne možemo otvoriti datoteku '$file'.</p></body></html>");
	while ($data = fread($fp, 4096)) {
		xml_parse($p, $data, feof($fp));
	}
	xml_parser_free($p);
?>