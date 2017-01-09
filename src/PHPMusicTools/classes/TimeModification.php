<?php
namespace ianring;
require_once 'PMTObject.php';

/**
 * TimeModification is an indication that notes should occupy a time different from their notated duration. Used to render tuplets. Is the actual time modification, not the visual glyph that represents it.
 */
class TimeModification extends PMTObject{

	public function __construct($actualNotes, $normalNotes) {
		$this->actualNotes = $actualNotes;
		$this->normalNotes = $normalNotes;
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public static function constructFromArray($props) {
		$actualNotes = $props['actualNotes'];
		$normalNotes = $props['normalNotes'];
		return new TimeModification($actualNotes, $normalNotes);
	}


	/**
	 * renders this object as MusicXML
	 * @return string MusicXML representation of the object
	 */
	function toMusicXML() {
		$out = '';
		$out .= '<time-modification>';
          $out .= '<actual-notes>' . $this->actualNotes . '</actual-notes>';
          $out .= '<normal-notes>' . $this->normalNotes . '</normal-notes>';
        $out .= '</time-modification>';
		return $out;
	}
	
}
