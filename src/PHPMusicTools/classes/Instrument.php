<?php
namespace ianring;

require_once 'PMTObject.php';

class Instrument extends PMTObject {


	function __construct($name) {
		$this->getProperties($name);
	}

	function __construct($name, $rangeMin = null, $rangeMax = null, $transpose = 0, $family = null) {
		$this->name = $name;
		$this->rangeMin = $rangeMin;
		$this->rangeMax = $rangeMax;
		$this->transpose = $transpose;
		$this->family = $family;
	}

	public static function constructFromArray($props) {
		$name = $props['name'];
		$rangeMin = $props['rangeMin'];
		$rangeMax = $props['rangeMax'];
		$transpose = $props['transpose'];
		$family = $props['family'];
		return new Instrument($name, $rangeMin, $rangeMax, $transpose, $family);
	}


	function getProperties() {
		if (isset(self::$instruments[$this->name])) {
			$i = $instruments[$this->name];
			$this->rangeMin = $i['rangeMin'];
			$this->rangeMax = $i['rangeMax'];
			$this->transpose = $i['transpose'];
			$this->family = $i['family'];
		} else {
			// todo
			// search for it in otherNames
		}
	}

	public static $instruments = array(
		'Alto Saxophone' => array(
	        'rangeMin' => new Pitch('D', -1, 3),
	        'rangeMax' => new Pitch('A', 0, 5),
	        'transpose' => 8,
	        'family' => 'woodwind',
	        'otherNames' => array(
	        	'E flat Alto Saxophone', 'E flat Alto Sax', 'Alto Sax',
	        ),
	        'abbreviation' => 'Alto Sax'
		),
	);

}
