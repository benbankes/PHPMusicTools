<?php
namespace ianring;
require_once 'PMTObject.php';

class BarlineRepeat extends PMTObject
{


	public function __construct($direction, $winged, $times) {
		$this->direction = $direction;
		$this->winged    = $winged;
		$this->times = $times;
	}


	/**
	 * accepts the object in the form of an array structure
	 *
	 * @param  [winged] $scale [description]
	 * @return [winged]        [description]
	 */
	public static function constructFromArray($props) {
		$direction = $props['direction']; // forward, backward
		$winged = $props['winged'];
		if (!empty($props['times'])) {
			$times = $props['times'];
		} else {
			$times = null;
		}
		return new BarlineRepeat($direction, $winged, $times);
	}


	/**
	 * renders this object as MusicXML
	 *
	 * @return string MusicXML representation of the object
	 */
	function toMusicXML() {
		$out = '';
		 $out .= '<repeat';
		if (!empty($this->direction)) {
			$out .= ' direction="' . $this->direction . '"';
		}
		if (!empty($this->winged)) {
			$out .= ' winged="' . $this->winged . '"';
		}
		if (!empty($this->times)) {
			$out .= ' times="' . $this->times . '"';
		}

		$out .= '></repeat>';
		return $out;

	}


}
