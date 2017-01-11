<?php
namespace ianring;
require_once 'Direction.php';

class DirectionMetronome extends Direction
{


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
