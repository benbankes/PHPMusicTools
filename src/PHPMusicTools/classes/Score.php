<?php
namespace ianring;
require_once 'PMTObject.php';
require_once 'Part.php';

/**
 * Score is a collection of parts; it is the highest level root object of a music document
 */
class Score extends PMTObject
{

    public $parts = array();


    function __construct($name='', $parts=array()) {
        $this->name  = $name;
        $this->parts = $parts;

    }


    public static function constructFromArray($props) {
        $name  = $props['name'];
        $parts = array();
        if (isset($props['parts'])) {
            foreach ($props['parts'] as $part) {
                if ($part instanceof Part) {
                    $parts[] = $part;
                } else {
                    $parts[] = Part::constructFromArray($part);
                }
            }
        }

        return new Score($name, $parts);

    }

    public static function constructFromXML($xml) {
        // todo
    }


    function setAttribute($property) {

    }


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML($wise='partwise') {
        $out  = '';
        $out .= '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
        $out .= '<!DOCTYPE score-partwise PUBLIC "-//Recordare//DTD MusicXML 3.0 Partwise//EN" "http://www.musicxml.org/dtds/partwise.dtd">';

        $out .= '<score-partwise version="3.0">';

        $out .= '<part-list>';
        foreach ($this->parts as $key => $part) {
            $out .= '<score-part id="P'.$key.'">';
            $out .= '<part-name>'.$part->name.'</part-name>';
            $out .= '</score-part>';
        }

        $out .= '</part-list>';

        foreach ($this->parts as $key => $part) {
            $out .= $part->toMusicXML($key);
        }

        $out .= '</score-partwise>';
        return $out;
    }

    public static function parseFromXMLObject($obj) {
        $score = new Score('', array());
        foreach ($obj->part as $part) {
            $score->addPart(Part::parseFromXmlObject($part));
        }
        return $score;

    }

    function toPNG() {

    }


    function toPDF() {

    }


    function addPart($part) {
        $this->parts[] = clone $part;
    }


    function addMeasure($measure) {
        $this->measures[] = clone $measure;

    }


}
