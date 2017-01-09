<?php
namespace ianring;
require_once 'PMTObject.php';

/**
 * Part is a collection of measures, such as would be performed by an instrument
 */
class Part extends PMTObject
{

    var $measures = array();


    function __construct($name=null, $measures=array())
    {
        $this->name     = $name;
        $this->measures = $measures;

    }//end __construct()


    public static function constructFromArray($props)
    {
        $name     = $props['name'];
        $measures = array();
        if (isset($props['measures'])) {
            foreach ($props['measures'] as $measure) {
                if ($measure instanceof Measure) {
                    $measures[] = $measure;
                } else {
                    $measures[] = Measure::constructFromArray($measure);
                }
            }
        }

        return new Part($name, $measures);

    }//end constructFromArray()


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML($num=1)
    {
        $out = '<part id="P'.$num.'">';

        if (!empty($this->measures)) {
            foreach ($this->measures as $key => $measure) {
                $out .= $measure->toMusicXML($key);
            }
        }

        $out .= '</part>';

        return $out;

    }//end toMusicXML()


    function addMeasure($measure)
    {
        $newmeasure       = clone $measure;
        $this->measures[] = $newmeasure;

    }//end addMeasure()


}//end class
