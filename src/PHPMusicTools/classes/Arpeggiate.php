<?php
/**
 * Arpeggiate Class
 *
 * Arpeggiate is a symbol that indicates for the performer to play notes in quick sequence, instead of simultaneous.
 *
 * @package      PHPMusicTools
 * @author       Ian Ring <httpwebwitch@email.com>
 */

namespace ianring;
require_once 'PMTObject.php';

class Arpeggiate extends PMTObject {


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML() {
        $out .= '<arpeggiate';
        $out .= ' default-x="'.$this->defaultX.'"';
        $out .= ' number="'.$this->number.'"';
        $out .= '/>';

    }


}
