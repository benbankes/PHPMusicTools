<?php
namespace ianring;

require_once 'PMTObject.php';
require_once 'Pitch.php';
require_once 'Articulation.php';
require_once 'TimeModification.php';
require_once 'NoteBeam.php';
require_once 'NoteStem.php';
require_once 'Pitch.php';
require_once 'Accidental.php';

/**
 * Note is the representation of a sound having a pitch and duration
 */
class Note extends PMTObject
{

    public static $properties = array(
        'pitch',
        'rest',
        'duration',
        'voice',
        'type',
        'accidental',
        'dot',
        'tie',
        'timeModification',
        'beams',
        'stem',
        'defaultX',
        'defaultY',
        'chord',
        'notations',
        'articulations',
        'staff',
    );

    public static $subObjects = array(
        'pitch'            => 'Pitch',
        'beams'            => array('NoteBeam'),
        'stem'             => 'NoteStem',
        'timeModification' => 'TimeModification',
        'accidental'       => 'Accidental',
        'articulations'    => array('Articulation'),
    );


    function __construct(
        $pitch,
        $rest=false,
        $duration,
        $voice,
        $type,
        $accidental,
        $dot=false,
        $tie=false,
        $timeModification,
        $beams=null,
        $stem=null,
        $defaultX=null,
        $defaultY=null,
        $chord=false,
        $notations=array(),
        $articulations=array(),
        $staff=1
    ) {
        foreach (self::$properties as $var) {
            $this->$var = $$var;
        }

    }


    public static function parseFromXmlObject($obj) {

/**
 *                                             [pitch] => SimpleXMLElement Object
                                                (
                                                    [step] => C
                                                    [octave] => 5
                                                )

                                            [duration] => 2
                                            [voice] => 1
                                            [type] => 16th
                                            [stem] => down
                                            [staff] => 1
                                            [beam] => Array
                                                (
                                                    [0] => begin
                                                    [1] => begin
                                                )

                                        )
 */
        $pitch = \ianring\Pitch::parseFromXmlObject($obj->pitch);
        $rest = $obj->rest;
        $duration = $obj->duration;
        $voice = $obj->voice;
        $type = $obj->type;
        $accidental = $obj->accidental;
        $dot = $obj->dot;
        $tie = $obj->tie;
        $timeModification = $obj->timeModification;
        $beams = $obj->beams;
        $stem = $obj->stem;
        $defaultX = $obj->defaultX;
        $defaultY = $obj->defaultY;
        $chord = $obj->chord;
        $notations = $obj->notations;
        $articulations = $obj->articulations;
        $staff = $obj->staff;

        return new Note(
            $pitch,
            $rest,
            $duration,
            $voice,
            $type,
            $accidental,
            $dot,
            $tie,
            $timeModification,
            $beams,
            $stem,
            $defaultX,
            $defaultY,
            $chord,
            $notations,
            $articulations,
            $staff
        );

    }

    public static function constructFromArray($props) {
        $defaults = array_fill_keys(self::$properties, null);
        $props    = array_merge($defaults, $props);
        extract($props);

        foreach (self::$subObjects as $objName => $subObject) {
            if (is_array($subObject)) {
                $className = '\ianring\\'.$subObject[0];
                // this is an array where each member of the array must be
                // an instance of the class
                $$objName = array();
                if (isset($props[$objName])) {
                    foreach ($props[$objName] as $p) {
                        $reflection = new \ReflectionMethod($className, 'constructFromArray');
                        $o          = $reflection->invoke(null, $p);
                        array_push($$objName, $o);
                    }
                }
            } else {
                // this is a simple sub-object
                $className  = '\ianring\\'.$subObject;
                $reflection = new \ReflectionMethod($className, 'constructFromArray');
                $o          = $reflection->invoke(null, $props[$objName]);
                $$objName   = $o;
            }
        }//end foreach

        return new Note(
            $pitch,
            $rest,
            $duration,
            $voice,
            $type,
            $accidental,
            $dot,
            $tie,
            $timeModification,
            $beams,
            $stem,
            $defaultX,
            $defaultY,
            $chord,
            $notations,
            $articulations,
            $staff
        );

    }


    /**
     * converts this Note into a Rest, which basically just nulls out the pitch and changes some properties
     *
     * @return Rest
     */
    function convertToRest() {
        // todo

    }


    /**
     * transposes the Pitch of a Note up or down by $interval semitones
     *
     * @param  integer $interval            a signed integer telling how many semitones to transpose up or down
     * @param  integer $preferredAlteration either 1, or -1 to indicate whether the transposition should prefer sharps or flats.
     * @return null
     */
    function transpose($interval, $preferredAlteration=1) {
        $pitch = $this->pitch;
        $pitch->transpose($interval, $preferredAlteration = 1);
        $this->pitch = $pitch;

    }


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML() {
        $out = '';

        $out .= '<note';
        if (isset($this->defaultX)) {
            $out .= ' default-x="'.$this->defaultX.'"';
        }

        if (isset($this->defaultY)) {
            $out .= ' default-y="'.$this->defaultY.'"';
        }

        $out .= '>';

        if (!empty($this->rest)) {
            $out .= '<rest/>';
        }

        if (!empty($this->chord)) {
            $out .= '<chord/>';
        }

        if (!empty($this->pitch)) {
            if ($this->pitch instanceof Pitch) {
                $pitch = $this->pitch;
            } else {
                // we'll presume it's a string then
                $pitch = new Pitch($this->pitch);
            }

            $out .= $pitch->toMusicXML();
        }

        if (!empty($this->duration)) {
            $out .= '<duration>'.$this->duration.'</duration>';
        }

        if (!empty($this->voice)) {
            $out .= '<voice>'.$this->voice.'</voice>';
        }

        if (!empty($this->type)) {
            $out .= '<type>'.$this->type.'</type>';
        }

        if (!empty($this->dot)) {
            $out .= '<dot/>';
        }

        if (!empty($this->tie)) {
            $out                   .= '<tie style="'.$this->tie.'">';
            $this->notations['tie'] = $this->tie;
        }

        if (!empty($this->staccato)) {
            $this->notations['staccato'] = $this->properties['staccato'];
        }

        if (!empty($this->stem)) {
            $out .= $this->stem->toMusicXML();
        }

        if (!empty($this->staff)) {
            $out .= '<staff>'.$this->staff.'</staff>';
        }

        if (!empty($this->beam)) {
            if (!is_array($this->beam)) {
                $this->beam = array($this->beam);
            }

            foreach ($this->beam as $beam) {
                $out .= $beam->toMusicXML();
            }
        }

        if (!empty($this->notations) || !empty($this->articulations)) {
            $out .= '<notations>';

            // Slur, Tie, Tuplet, Arpeggiate
            foreach ($this->notations as $notation) {
                $out .= $notation->toMusicXML();
            }

            // staccato
            if (!empty($this->articulations)) {
                $out .= '<articulations>';
                foreach ($this->articulations as $articulation) {
                    $out .= $articulation->toMusicXML();
                }

                $out .= '</articulations>';
            }

            $out .= '</notations>';
        }

        $out .= '</note>';
        return $out;
    }



}
