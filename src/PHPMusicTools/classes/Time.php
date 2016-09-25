<?php
namespace ianring;

require_once 'PMTObject.php';

class Time extends PMTObject {

	public static $properties = array(
		'symbol', 'beats', 'beatType'
	);

	public function __construct($symbol, $beats, $beatType) {
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

		return new Time($symbol, $beats, $beatType);
	}

	function toXml(){
		$out = '';

		$out .= '<time';
		if (isset($this->symbol)) {
			$out .= ' symbol="' . $this->symbol . '"';
		}
		$out .= '>';
		if (isset($this->beats)) {
			$out .= '<beats>' . $this->beats . '</beats>';
		}
		if (isset($this->beatType)) {
			$out .= '<beat-type>' . $this->beatType . '</beat-type>';
		}
		$out .= '</time>';

		return $out;
	}
}
