<?php
namespace ianring;
require_once 'PMTObject.php';

/**
 * Clef is a symbol which defines which pitches are mapped to the five staff lines.
 */
class Clef extends PMTObject
{

    public static $properties = array(
                                 'sign',
                                 'line',
                                );


    public function __construct($sign, $line)
    {
        foreach (self::$properties as $var) {
            $this->$var = $$var;
        }

    }//end __construct()


    /**
     * accepts the object in the form of an array structure
     *
     * @param  [winged] $scale [description]
     * @return [winged]        [description]
     */
    public static function constructFromArray($props)
    {
        $defaults = array_fill_keys(self::$properties, null);
        $props    = array_merge($defaults, $props);
        extract($props);

        return new Clef($sign, $line);

    }//end constructFromArray()


    private function _resolveClefString($string)
    {
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

    }//end _resolveClefString()


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML($num)
    {
        $out = '';

        $out  .= '<clef number="'.$num.'">';
         $out .= '<sign>'.$this->sign.'</sign>';
         $out .= '<line>'.$this->line.'</line>';
        $out  .= '</clef>';

        return $out;

    }//end toMusicXML()


}//end class
