<?php
namespace ianring;
require_once 'PMTObject.php';

class Tuplet extends PMTObject {

	public function __construct($bracket, $number, $placement, $type) {
		$this->bracket = $bracket;
		$this->number = $number;
		$this->placement = $placement;
		$this->type = $type;
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public static function constructFromArray($props) {
		$bracket = $props['bracket'];
		$number = $props['number'];
		$placement = $props['placement'];
		$type = $props['type'];
		return new Tuplet($bracket, $number, $placement, $type);
	}	

	function toXml() {
		$out = '';
		$out .= '<tuplet';
		$out .= ' bracket="' . $this->bracket . '"';
		$out .= ' number="' . $this->number . '"';
		$out .= ' placement="' . $this->placement . '"';
		$out .= ' type="' . $this->type . '"';
		$out .= '/>';
		return $out;
	}


}
