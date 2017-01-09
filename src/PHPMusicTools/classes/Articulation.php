<?php
namespace ianring;
require_once 'PMTObject.php';

class Articulation extends PMTObject {
	
	// has a type, like "staccato"
	public function __construct($articulationType) {
		$this->articulationType = $articulationType;
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [winged] $scale [description]
	 * @return [winged]        [description]
	 */
	public static function constructFromArray($props) {
		$articulationType = $props['articulationType'];
		return new Articulation($articulationType);
	}


	/**
	 * renders this object as MusicXML
	 * @return string MusicXML representation of the object
	 */
	function toMusicXML() {
		$out = '';
		switch ($this->articulationType) {
			// todo move this into the class
			case 'staccato':
				$out .= '<staccato/>';
		}
		return $out;
	}

}
