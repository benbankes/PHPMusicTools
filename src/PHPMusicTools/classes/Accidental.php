<?php

namespace ianring;

require_once 'PMTObject.php';

class Accidental extends PMTObject {

	public static $properties = array(
		'type', 'size', 'parentheses', 'bracket', 'editorial', 'courtesy'
	);

	public function __construct($type = 'natural', $size = false, $parentheses = false, $bracket = false, $editorial = null, $courtesy = false) {
		// "type" can be 'sharp', 'flat', natural, double-sharp, sharp-sharp, flat-flat, natural-sharp, natural-flat, quarter-flat, quarter-sharp, three- quarters-flat, and three-quarters-sharp
		foreach (self::$properties as $var) {
			$this->$var = $$var;
		}
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [winged] $scale [description]
	 * @return [winged]        [description]
	 */
	public static function constructFromArray($props) {
		if (!is_array($props)) {
			$props = array('type' => $props);
		}
		$defaults = array_fill_keys(self::$properties, null);
		$props = array_merge($defaults, $props);
		extract($props);

		return new Accidental($type, $size, $parentheses, $bracket, $editorial, $courtesy);
	}

	function toXml() {
		$out .= '<accidental';
		$out .= '/>';
	}

}
