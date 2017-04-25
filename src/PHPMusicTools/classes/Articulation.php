<?php
namespace ianring;
require_once 'PMTObject.php';

class Articulation extends PMTObject {

    // has a type, like "staccato"
    public function __construct($articulationType) {
        $this->articulationType = $articulationType;
    }

    /**
     * accepts the object in the form of an array structure
     * @param  [winged] $scale [description]
     * @return [winged]        [description]
     */
    public static function constructFromArray($props) {
        if (empty($props['articulationType'])) {
            return null;
        }
        $articulationType = $props['articulationType'];
        switch($articulationType) {
            case 'accent':
                require_once('Articulations/ArticulationAccent.php');
                return ArticulationAccent::constructFromArray($props);
                break;
            case 'staccato':
                require_once('Articulations/ArticulationStaccato.php');
                return ArticulationStaccato::constructFromArray($props);
                break;
            default:
                return null;
        }

    }

}
