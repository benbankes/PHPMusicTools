<?php
/**
 * BarlineEnding Class
 *
 * BarlineEnding is a notation for the final bar in a repeated section.
 *
 * @package      PHPMusicTools
 * @author       Ian Ring <httpwebwitch@email.com>
 */

namespace ianring;
require_once 'PMTObject.php';

class BarlineEnding extends PMTObject
{


    public function __construct($number, $type) {
        foreach (array('number', 'type') as $var) {
            $this->$var = $$var;
        }

    }


    /**
     * accepts the object in the form of an array structure
     *
     * @param  [type] $scale [description]
     * @return [type]        [description]
     */
    public static function constructFromArray($props) {
        $number = $props['number'];
        $type   = $props['type'];
        return new BarlineEnding($number, $type);

    }


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML() {
        $out = '';
        $out .= '<ending';
        if (isset($this->type)) {
            $out .= ' type="'.$this->type.'"';
        }
        if (isset($this->number)) {
            $out .= ' number="'.$this->number.'"';
        } else {
            $out .= ' number="1"';
        }
        $out .= '>';
        $out .= $this->number;
        $out .= '</ending>';
        return $out;
    }


}
