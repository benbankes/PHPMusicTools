<?php
namespace ianring;

require_once 'PMTObject.php';
require_once 'Direction.php';

class DirectionMetronome extends PMTObject {

    function __construct($placement, $staff, $parentheses, $beatUnit, $perMinute) {
    	foreach (array('placement', 'staff', 'parentheses', 'beatUnit', 'perMinute') as $var) {
    		$this->$var = $$var;
    	}
    }


  public static function constructFromArray($props) {
  	extract($props);
    return new DirectionMetronome($placement, $staff, $parentheses, $beatUnit, $perMinute);
  }


}
