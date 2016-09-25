<?php
namespace ianring;

require_once 'PMTObject.php';

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

	// used in the context of a Note notation
	function toXml() {
		return '<tied type="' . $this->type . '"/>';
	}

}
