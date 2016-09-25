<?php
namespace ianring;

require_once 'PMTObject.php';

class NoteBeam extends PMTObject {

	public function __construct($number, $type) {
		$this->number = $number;
		$this->type = $type; // begin, continue, end
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public static function constructFromArray($props) {
		$number = $props['number'];
		$type = $props['type'];
		return new NoteBeam($number, $type);
	}

	function toXml() {
		$out = '';
		$out .= '<beam';
		if (isset($this->number)) {
			$out .= ' number="' . $this->number . '"';
		}
		$out .= '>';
		if (!empty($this->type)) {
			$out .= $this->type;
		}
		$out .= '</beam>';
		return $out;
	}

}
