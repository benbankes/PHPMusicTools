<?php
/**
 * PitchClassSetTransformation Class
 *
 * This is a thing that you can apply to a PitchClassSet
 *
 * @package      PHPMusicTools
 * @author       Ian Ring <httpwebwitch@email.com>
 */

namespace ianring;
require_once 'PMTObject.php';
require_once 'Utils/BitmaskUtils.php';

/**
 * Accidental Class
 */
class PitchClassSetTransformation extends PMTObject {


	/**
	 * Constructor.
	 * Default new PitchClassSetTransformation will make no changes, ie its rotation is 0 and the invert is false.
	 */
	public function __construct($r = 0, $i = false) {
		$this->rotation = $r;
		$this->invert = $i;
	}

	/**
	 * constructs the object from a string descriptor, eg "T3" or "T10I"
	 *
	 * @return PitchClassSetTransformation
	 */
	public static function constructFromString($str) {
		$str = strtoupper($str);
		$i = substr($str, -1) == 'I';
		
		preg_match('/^T[0-9]{1,}/', $str, $matches);
//		print_r($matches);
		$r = $matches[0];

		return new PitchClassSetTransformation($r, $i);
	}


	public function toString() {
		return 'T' . $this->rotation . ($this->invert ? 'I' : '');
	}



}

