<?php
namespace ianring;
require_once 'PMTObject.php';

class LilypondObject extends PMTObject {
	
	public $text = '';

	public function set($text) {
		$this->text = $text;
	}

	public function render($format = 'png') {

		// web temp dir with permissions
		$tmpdir = '/var/www/html/ianring.com/lilytemp';

		// put the lily in a temp file
		file_put_contents($tmpdir.'/lilytemp.ly', $this->text);

		exec('lilypond -f png -o '.$tmpdir.' -l DEBUG '.$tmpdir.'/lilytemp.ly 2>&1', $output, $return_var);
		$content = file_get_contents($tmpdir.'/lilytemp.png');

//		var_dump($content);

//		var_dump($return_var);
//echo '<hr/>';
		return $content;
	}
}