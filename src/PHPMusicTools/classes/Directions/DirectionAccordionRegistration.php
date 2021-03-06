<?php
namespace ianring;
require_once(__DIR__.'/../Direction.php');

// https://usermanuals.musicxml.com/MusicXML/Content/EL-MusicXML-accordion-registration.htm

/**
 * defines accordion registration. Can have the usual inherited attributes like default-x and font-family, but also
 * has its own "registration" which may be "high", "middle", or "low"
 */

class DirectionAccordionRegistration extends Direction
{

	function __construct($registration) {
		foreach (array('registration') as $var) {
			$this->$var = $$var;
		}

	}


	public static function constructFromArray($props) {
		extract($props);
		return new DirectionAccordionRegistration($registration);

	}

}
