<?php
namespace ianring;

require_once 'PMTObject.php';

/**
 * Tie is a symbol which indicates that the duration of one note should be extended by the duration of another
 */
class Tie extends PMTObject {

	public function __construct($type) {
		$this->type = $type;
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public static function constructFromArray($props) {
		$type = $props['type'];
		return new Tie($type);
	}

	/**
	 * renders this object as MusicXML
	 * @return string MusicXML representation of the object
	 */
	function toMusicXML() {
		return '<tied type="' . $this->type . '"/>';
	}

}
