<?php
namespace ianring;

class PMTObject
{

    const UNICODE_FLAT = 'E260';
    const UNICODE_NATURAL = 'E261';
    const UNICODE_SHARP = 'E262';
    const UNICODE_DOUBLE_SHARP = 'E263';
    const UNICODE_DOUBLE_FLAT = 'E264';
    const UNICODE_DIMINISHED = 'E870';
    const UNICODE_HALF_DIMINISHED = 'E871';
    const UNICODE_PLUS = 'E872';
    const UNICODE_MINUS = 'E874';
    const UNICODE_MAJ7 = 'E873';


    /**
     * force deep cloning, so a clone of the measure will contain a clone of all its sub-objects as well
     *
     * @return [type] [description]
     */
    public function __clone() {
        foreach ($this as $key => $val) {
            if (is_object($val) || (is_array($val))) {
                $this->{$key} = unserialize(serialize($val));
            }
        }

    }


    function setProperty($name, $value) {
        $this->$name = $value;

    }


    /**
     * required because PHP doesn't do modulo correctly with negative numbers.
     */
    public static function _truemod($num, $mod) {
        return (($mod + ($num % $mod)) % $mod);

    }


    /**
     * returns the smaller interval between a and b, in modulo 12 assuming that a is lower than b
     * @return [type]
     */
    public static function _truemodDiff12($a, $b) {
        return self::_truemod($b-$a,12);
    }

}
