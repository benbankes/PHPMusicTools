<?php
namespace ianring;
require_once 'Direction.php';

class DirectionDynamics extends Direction
{


    function __construct($placement, $staff, $text)
    {
        foreach (array('placement', 'staff', 'text') as $var) {
            $this->$var = $$var;
        }

    }//end __construct()


    public static function constructFromArray($props)
    {
        extract($props);
        return new DirectionDynamics($placement, $staff, $text);

    }//end constructFromArray()


}//end class
