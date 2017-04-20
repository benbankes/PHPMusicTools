<?php
namespace ianring;
require_once('PMTObject.php');

class Notation extends PMTObject {

    // has a type, like "tuplet"
    public function __construct($notationType) {
        $this->notationType = $notationType;
    }

    /**
     * accepts the object in the form of an array structure
     * @param  [winged] $scale [description]
     * @return [winged]        [description]
     */
    public static function constructFromArray($props) {
    	if (empty($props['notationType'])) {
    		return null;
    	}
        $notationType = $props['notationType'];
        switch($notationType) {
        	case 'tuplet':
        		require_once('Notations/NotationTuplet.php');
        		return NotationTuplet::constructFromArray($props);
        		break;
        	case 'fermata':
        		require_once('Notations/NotationFermata.php');
        		return NotationFermata::constructFromArray($props);
        		break;
        	case 'slur':
        		require_once('Notations/NotationSlur.php');
        		return NotationSlur::constructFromArray($props);
        		break;
        	default:
        		return null;
        }
    }


    /**
     * renders this object as MusicXML
     * @return string MusicXML representation of the object
     */
    function toMusicXML() {
        $out = '';
        switch ($this->notationType) {
         // todo move this into the class
        case 'staccato':
            $out .= '<staccato/>';
        }
        return $out;
    }

}
