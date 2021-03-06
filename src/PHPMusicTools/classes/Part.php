<?php
namespace ianring;
require_once 'PMTObject.php';
require_once 'Measure.php';

/**
 * Part is a collection of measures, such as would be performed by an instrument
 */
class Part extends PMTObject
{

    var $measures = array();

    public static $properties = array(
        'name',
        'measures'
    );

    function __construct($name=null, $measures=array()) {
        $this->name     = $name;
        $this->measures = $measures;

    }


    public static function constructFromArray($props) {
        $name = null;
        if (!empty($props['name'])) {
            $name = $props['name'];
        }
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

    }


    public static function parseFromXmlObject($obj) {
        $part = new Part();
        foreach ($obj->measure as $measure) {
            $part->addMeasure(Measure::parseFromXmlObject($measure));
        }
        return $part;
    }

    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML($num=1) {
        $out = '<part id="P'.$num.'">';

        if (!empty($this->measures)) {
            foreach ($this->measures as $key => $measure) {
                $out .= $measure->toMusicXML($key);
            }
        }

        $out .= '</part>';

        return $out;
    }



    function addMeasure($measure) {
        $newmeasure       = clone $measure;
        $this->measures[] = $newmeasure;

    }


    public function transpose($interval, $preferredAlteration = 1) {
        foreach ($this->measures as &$measure) {
            $measure->transpose($interval, $preferredAlteration);
        }

    }

}
