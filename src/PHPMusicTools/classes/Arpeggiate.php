<?php
namespace ianring;
require_once 'PMTObject.php';

class Arpeggiate extends PMTObject
{


    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    function toMusicXML()
    {
        $out .= '<arpeggiate';
        $out .= ' default-x="'.$this->defaultX.'"';
        $out .= ' number="'.$this->number.'"';
        $out .= '/>';

    }//end toMusicXML()


}//end class
