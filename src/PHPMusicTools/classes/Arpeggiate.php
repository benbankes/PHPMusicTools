<?php 
namespace ianring;

require_once 'PMTObject.php';

class Arpeggiate extends PMTObject {

	function toXml() {
		$out .= '<arpeggiate';
		$out .= ' default-x="' . $this->defaultX . '"';
		$out .= ' number="' . $this->number . '"';
		$out .= '/>';
	}

}
