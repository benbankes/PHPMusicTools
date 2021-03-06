<?php
namespace ianring;
require_once 'PMTObject.php';

/**
 * Time is aka a "time signature", incidating number of beats in a bar, and the value of one beat.
 */
class Time extends PMTObject
{

    public static $properties = array(
        'symbol',
        'beats',
        'beatType',
    );


    public function __construct($symbol, $beats, $beatType) {
        foreach (self::$properties as $var) {
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
        if (is_null($props)) {
            return null;
        }
        if (!is_array($props)) {
            throw new Exception('invalid argument to construct Time');
        }
        $defaults = array_fill_keys(self::$properties, null);
        $props    = array_merge($defaults, $props);
        extract($props);

        return new Time($symbol, $beats, $beatType);

    }


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML() {
        $out = '';

        $out .= '<time';
        if (isset($this->symbol)) {
            $out .= ' symbol="'.$this->symbol.'"';
        }

        $out .= '>';
        if (isset($this->beats)) {
            $out .= '<beats>'.$this->beats.'</beats>';
        }

        if (isset($this->beatType)) {
            $out .= '<beat-type>'.$this->beatType.'</beat-type>';
        }

        $out .= '</time>';

        return $out;
    }



}
