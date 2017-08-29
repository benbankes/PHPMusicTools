<?php
/**
 * This class is fascinating because it extends the idea of representing scales as a binary number into 
 * tertiary chords! 
 * for example, a root tone is 1,
 * a root plus a minor third is 1001,
 * a root plus a major third is 10001,
 * a major triad is 10010001
 * 
 * In this way, we can name all kinds of named chords in the same way we name our scales, right up to
 * giant numbers for interesting colourized thirteenths
 *
 * We will also make this class capable of figuring out what chords are compatible
 * with scales
 *
 * There are some interesting possibilities here with chords that don't contain a root tone.
 * For instance, we could define elements of harmonic analysis by identifying the tones of a chord
 * that aren't the "I" - e.g. 100010010 is the "bii" and 100100010000 is a "III", and we could
 * also identify French Neopolitan sixths and stuff like that
 */

namespace ianring;
require_once 'PMTObject.php';

class ChordName extends PMTObject {

    public static $chordNames = array(
        273 => array(
            'name' => array('augmented triad'),
            'symbol' => array('{r}aug5')
        ),
        1169 => array(
            'name' => 'dominant seventh',
            'symbol' => '{r}v7'
        ),
        2193 => array(
            'name' => 'major seventh',
            'symbol' => '{r}maj7'
        ),
        17553 => array(
            'name' => 'dominant ninth',
            'symbol' => '{r}9';
        )
    );

    // has a type, like "staccato"
    public function __construct($num) {
        $this->chord = $num;
    }

    public static function constructFromName($name){
        // look up the number...
        $num = 273;
        return new ChordName($num);
    }

    public static function constructFromNumber($num){
        return new ChordName($num);
    }

    public function getTones() {
        return 273;
    }

    public function getName() {
        return 'augmented triad';
    }

}
