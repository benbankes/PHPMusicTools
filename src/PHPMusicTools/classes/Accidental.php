<?php

namespace ianring;

require_once 'PMTObject.php';

class Accidental extends PMTObject {

	public static $properties = array(
		'type', 'size', 'parentheses', 'bracket', 'editorial', 'courtesy'
	);

	public static $types = array(
		'sharp',
		'flat',
		'natural',
		'double-sharp',
		'double-flat',
		'sharp-sharp',
		'flat-flat',
		'natural-sharp',
		'natural-flat',
		'quarter-flat',
		'quarter-sharp',
		'three-quarters-flat',
		'three-quarters-sharp'
	);

	public function __construct($type = 'natural', $size = false, $parentheses = false, $bracket = false, $editorial = null, $courtesy = false) {
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

	function getUnicode() {
		$codes = array(
			'sharp' => '266F',
			'flat' => '266D',
			'natural' => '266E',
			'double-sharp' => '1D12A',
			'double-flat' => '1D12B',
			'sharp-sharp' => '1D130',
			'flat-flat' => '1D12D',
			'natural-sharp' => '1D12E',
			'natural-flat' => '1D12F',
			'quarter-flat' => '1D133',
			'quarter-sharp' => '1D132',
			'three-quarters-flat' => '1D12D',
			'three-quarters-sharp' => '1D130',
		);
		return $codes[$this->type];
	}

	function toXml() {
		$out .= '<accidental';
		$out .= '/>';
	}

}
