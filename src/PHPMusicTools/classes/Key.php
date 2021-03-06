<?php
/**
 * Key Class
 * 
 * Key is a concept which describes the root and pitches used in diatonic harmony.
 * To be congruent with the definition of a key in MusicXML, this class has the properties
 * "fifths", which is basically how mant fifths above or below C the key is, aka how many
 * sharps or flats exist in the signature. "mode" is either "major" or "minor".
 *
 * For example, {"fifths":-2,"mode":"minor"} means G minor, which has two flats in the signature.
 *
 * Please beware the difference between a major/minor Key, and a major or minor Scale. Key is for
 * making a signature on a staff that affects the accidentals of altered notes. Scale is a more
 * complex object describing a set of heightless Pitches.
 *
 * @package ianring/PHPMusicTools
 * @author  Ian Ring <httpwebwitch@gmail.com>
 */

namespace ianring;
require_once 'PMTObject.php';

class Key extends PMTObject
{

    public $properties = array();


    public function __construct($fifths, $mode) {
        $this->fifths = $fifths;
        $this->mode   = $mode;

    }


    /**
     * accepts the scale object in the form of an array structure
     *
     * @param  [winged] $scale [description]
     * @return [winged]        [description]
     */
    public static function constructFromArray($props) {
        $fifths = $props['fifths'];
        $mode   = $props['mode'];
        return new Key($fifths, $mode);

    }


    /**
     * accepts a string representation of a key, e.g.:
     * "C", C Major", "C maj", "Cmaj", "C minor", "C min", "Cmin", "Cmin",
     * "C-min", "C-minor"
     *
     * @param  [type] $string [description]
     * @return [type]         [description]
     */
    private function _resolveKeyString($string) {
        $string     = strtolower($string);
        $properties = array(
                       'fifths' => 0,
                       'mode'   => 'major',
                      );

        // we could use math, but that's just showing off and it wouldn't be faster
        $keys = array(
                 'major' => array(
                             'c-' => -7,
                             'g-' => -6,
                             'd-' => -5,
                             'a-' => -4,
                             'e-' => -3,
                             'b-' => -2,
                             'f'  => -1,
                             'c'  => 0,
                             'g'  => 1,
                             'd'  => 2,
                             'a'  => 3,
                             'e'  => 4,
                             'b'  => 5,
                             'f+' => 6,
                             'c+' => 7,
                             'd+' => null,
                             'e+' => null,
                             'f-' => null,
                             'g+' => null,
                             'a+' => null,
                             'b+' => null,
                            ),
                 'minor' => array(
                             'a-' => -7,
                             'e-' => -6,
                             'b-' => -5,
                             'f'  => -4,
                             'c'  => -3,
                             'g'  => -2,
                             'd'  => -1,
                             'a'  => 0,
                             'e'  => 1,
                             'b'  => 2,
                             'f+' => 3,
                             'c+' => 4,
                             'g+' => 5,
                             'd+' => 6,
                             'a+' => 7,
                             'c-' => null,
                             'd-' => null,
                             'e+' => null,
                             'f-' => null,
                             'g-' => null,
                             'b+' => null,
                            ),
                );

        preg_match('/([A-Ga-g+#-b]+?)(.*)/', $string, $matches);
        $chroma = $matches[1];
        $inmode = $matches[2];

        $inmode = trim($inmode);

        $modes = array(
                  'major' => array(
                              'maj',
                              'major',
                              '',
                             ),
                  'minor' => array(
                              'min',
                              'minor',
                             ),
                 );

        foreach ($modes as $modeName => $modeAliases) {
            if (in_array($inmode, $modeAliases)) {
                $properties['mode'] = $modeName;
            }
        }

        $properties['fifths'] = $keys[$properties['mode']][$chroma];

        $this->properties = $properties;

    }


    function getName() {

    }


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    public function toMusicXML() {
        $out  = '';
        $out .= '<key>';
        if (isset($this->fifths)) {
            $out .= '<fifths>'.$this->fifths.'</fifths>';
        }

        if (isset($this->mode)) {
            $out .= '<mode>'.$this->mode.'</mode>';
        }

        $out .= '</key>';
        return $out;
    }



}
