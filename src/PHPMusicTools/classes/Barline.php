<?php
namespace ianring;

require_once 'PMTObject.php';
require_once 'BarlineEnding.php';
require_once 'BarlineRepeat.php';

class Barline extends PMTObject {

	public static $properties = array(
		'location', 'barStyle', 'footnote', 'ending', 'repeat'
	);

	public function __construct($location, $barStyle, $footnote, $ending, $repeat) {
		foreach (self::$properties as $var) {
			$this->$var = $$var;
		}
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public static function constructFromArray($props) {
		$defaults = array_fill_keys(self::$properties, null);
		$props = array_merge($defaults, $props);
		extract($props);

		if (isset($props['ending'])) {
			$ending = BarlineEnding::constructFromArray($props['ending']);
		}
		if (isset($props['repeat'])) {
			$repeat = BarlineRepeat::constructFromArray($props['repeat']);
		}

		return new Barline($location, $barStyle, $footnote, $ending, $repeat);
	}


	function toXML() {
		$out = '';
		$out .= '<barline';
		if (isset($this->location)) {
			$out .= ' location="' . $this->location . '"';
		}
		$out .= '>';
		if (isset($this->barStyle)) {
			$out .= '<bar-style>' . $this->barStyle . '</bar-style>';
		}
		if (isset($this->footnote)) {
			$out .= '<footnote>' . $this->footnote . '</footnote>';
		}
		if (isset($this->ending)) {
			$out .= $this->ending->toXml();
		}
		if (isset($this->repeat)) {
			$out .= $this->repeat->toXml();
		}

		$out .= '</barline>';
		return $out;

	}
}
