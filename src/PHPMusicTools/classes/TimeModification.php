<?php
namespace ianring;

class TimeModification {

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


	function toXml() {
		$out = '';
		$out .= '<time-modification>';
          $out .= '<actual-notes>' . $this->actualNotes . '</actual-notes>';
          $out .= '<normal-notes>' . $this->normalNotes . '</normal-notes>';
        $out .= '</time-modification>';
		return $out;
	}
	
}
