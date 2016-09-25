<?php
namespace ianring;
require_once 'PMTObject.php';

class Slur extends PMTObject {
	
	public function __construct($number, $placement, $type, $bezierX, $bezierY, $defaultX, $defaultY) {
		$this->number = $number;
		$this->placement = $placement;
		$this->type = $type;
		$this->bezierX = $bezierX;
		$this->bezierY = $bezierY;
		$this->defaultX = $defaultX;
		$this->defaultY = $defaultY;
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public static function constructFromArray($props) {
		$number = $props['number'];
		$placement = $props['placement'];
		$type = $props['type'];
		$bezierX = $props['bezierX'];
		$bezierY = $props['bezierY'];
		$defaultX = $props['defaultX'];
		$defaultY = $props['defaultY'];
		return new Slur($number, $placement, $type, $bezierX, $bezierY, $defaultX, $defaultY);
	}


	function toXml() {
		$out = '';
		$out .= '<slur';
		$out .= ' number="' . $this->number . '"';
		$out .= ' placement="' . $this->placement . '"';
		$out .= ' type="' . $this->type . '"';
		$out .= ' bezier-x="' . $this->bezierX . '"';
		$out .= ' bezier-y="' . $this->bezierY . '"';
		$out .= ' default-x="' . $this->defaultX . '"';
		$out .= ' default-y="' . $this->defaultY . '"';
		$out .= '/>';
		return $out;
	}

}
