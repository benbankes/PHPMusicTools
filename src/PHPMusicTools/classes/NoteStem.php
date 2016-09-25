<?php
namespace ianring;

require_once 'PMTObject.php';

class NoteStem extends PMTObject {
	// default-x
	// default-y
	// direction

	public function __construct($defaultX, $defaultY, $direction) {
		$this->defaultX = $defaultX;
		$this->defaultY = $defaultY;
		$this->direction = $direction;
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [defaultY] $scale [description]
	 * @return [defaultY]        [description]
	 */
	public static function constructFromArray($props) {
		$defaultX = $props['defaultX'];
		$defaultY = $props['defaultY'];
		$direction = $props['direction'];
		return new NoteStem($defaultX, $defaultY, $direction);
	}



	function toXml() {
		$out = '';
		$out .= '<stem';
		if (isset($this->defaultX)) {
			$out .= ' default-x="' . $this->defaultX . '"';
		}
		if (isset($this->defaultY)) {
			$out .= ' default-y="' . $this->defaultY . '"';
		}
		$out .= '>';
		if (isset($this->direction)) {
			$out .= $this->direction;
		}
		$out .= '</stem>';
		return $out;
	}
}
