<?php
namespace ianring;
require_once 'PMTObject.php';

/**
 * Direction is extra information added to a score to instruct the performer how to interpret the score
 */
class Direction extends PMTObject
{

    public $properties = array();


    function __construct($placement, $staff, $direction) {
        $this->placement = $placement;
        $this->staff     = $staff;
        $this->direction = $direction;

    }


    /**
     * accepts the object in the form of an array structure
     *
     * @param  [winged] $scale [description]
     * @return [winged]        [description]
     */
    public static function constructFromArray($props) {
        switch ($props['directionType']) {
            case 'metronome':
            return DirectionMetronome::constructFromArray($props);

            break;
            case 'dynamics':
            return DirectionDynamics::constructFromArray($props);

            break;
        }

    }


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML() {
          $out  = '<direction placement="'.$this->placement.'">';
          $out .= '<direction-type>';

          $out .= $this->direction->toMusicXML();

          $out .= '</direction-type>';
          $out .= '<staff>'.$this->staff.'</staff>';
          // TODO put the sound elements in here too
          $out .= '</direction>';

        /*
            <direction placement="above">
            <direction-type>
            <metronome parentheses="no">
            <beat-unit>quarter</beat-unit>
            <per-minute>125</per-minute>
            </metronome>
            </direction-type>
            <staff>1</staff>
            <sound tempo="125"/>
            </direction>

            <direction placement="below">
            <direction-type>
            <dynamics>
            <p/>
            </dynamics>
            </direction-type>
            <staff>1</staff>
            <sound dynamics="54.44"/>
            </direction>


        */
        return 'it works!';
}



}
