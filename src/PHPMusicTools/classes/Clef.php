<?php
/**
 * Clef Class
 *
 * Clef is a symbol which defines which pitches are mapped to the five staff lines.
 *
 * @package      PHPMusicTools
 * @author       Ian Ring <httpwebwitch@email.com>
 */

namespace ianring;
require_once 'PMTObject.php';

class Clef extends PMTObject
{

    public static $properties = array(
        'sign',
        'line',
        'octaveChange'
    );
    public static $defaults = array(
        'sign' => 'G',
        'line' => 4,
        'octaveChange' => 0 // see https://usermanuals.musicxml.com/MusicXML/Content/EL-MusicXML-clef-octave-change.htm
    );


    public function __construct($sign, $line, $octaveChange) {
        foreach (self::$properties as $var) {
            $this->$var = $$var;
        }
    }


    /**
     * accepts the object in the form of an array structure
     *
     * @param  [winged] $scale [description]
     * @return [winged]        [description]
     */
    public static function constructFromArray($props = null) {
        if (is_null($props)) {
            $props = self::$defaults;
        }
        $defaults = array_fill_keys(self::$properties, null);
        $props    = array_merge($defaults, $props);
        extract($props);

        return new Clef($sign, $line, $octaveChange);

    }


    private function _resolveClefString($string) {
        $string = strtolower($string);
        switch ($string) {
            case 'treble':
                $this->sign = 'G';
                $this->line = 2;
            break;

            case 'bass':
                $this->sign = 'F';
                $this->line = 4;
            break;

            // todo: add more clefs here
            default:
                // todo: throw an exception here instead
                $this->sign = 'G';
                $this->line = 2;
            break;
        }

    }

    /**
     * adds an integer to the changeOctave property.
     */
    public function changeOctave($by) {

    }

    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML($num) {
        $out = '';

        $out .= '<clef number="'.$num.'">';
        $out .= '<sign>'.$this->sign.'</sign>';
        $out .= '<line>'.$this->line.'</line>';
        if ($this->octaveChange != 0) {
            $out .= '<clef-octaveChange>'.$this->octave-change.'</clef-octaveChange>';
        }
        $out  .= '</clef>';

        return $out;
    }



}
