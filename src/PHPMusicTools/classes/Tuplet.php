<?php
namespace ianring;
require_once 'PMTObject.php';

/**
 * Tuplet is the visual glyph that represents a time modification.
 */
class Tuplet extends PMTObject
{

    public function __construct($bracket, $number, $placement, $type, $lineShape, $showNumber, $showType) {
        $this->bracket   = $bracket;
        $this->number    = $number;
        $this->placement = $placement;
        $this->type      = $type;
        $this->lineShape = $lineShape;
        $this->showNumber = $showNumber;
        $this->showType = $showType;
    }


    /**
     * accepts the object in the form of an array structure
     *
     * @param  [type] $scale [description]
     * @return [type]        [description]
     */
    public static function constructFromArray($props) {
        $bracket   = $props['bracket'];
        $number    = $props['number'];
        $placement = $props['placement'];
        $type      = $props['type'];
        $lineShape = $props['lineShape'];
        $showNumber = $props['showNumber'];
        $showType   = $props['showType'];
        return new Tuplet($bracket, $number, $placement, $type, $lineShape, $showNumber, $showType);
    }


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML() {
        $out  = '';
        $out .= '<tuplet';
        $out .= ' bracket="'.$this->bracket.'"';
        $out .= ' number="'.$this->number.'"';
        $out .= ' placement="'.$this->placement.'"';
        $out .= ' type="'.$this->type.'"';
        $out .= '/>';
        return $out;
    }



}
