<?php
namespace ianring;
require_once 'PMTObject.php';

class LilypondObject extends PMTObject {

	public $text = '';
	const LILYPOND_TEMP_FOLDER = '/var/www/html/ianring.com/lilytemp';

	public function set($text) {
		$this->text = $text;
	}

	public function render($format = 'png') {
		file_put_contents(LilypondObject::LILYPOND_TEMP_FOLDER.'/lilytemp.ly', $this->text);
		exec('lilypond -f png -o '.LilypondObject::LILYPOND_TEMP_FOLDER.' -l DEBUG '.LilypondObject::LILYPOND_TEMP_FOLDER.'/lilytemp.ly 2>&1', $output, $return_var);
		$content = file_get_contents(LilypondObject::LILYPOND_TEMP_FOLDER.'/lilytemp.png');
		return $content;
	}
}