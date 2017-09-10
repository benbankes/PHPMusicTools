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
require_once 'Chord.php';
require_once 'Pitch.php';

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
            'symbol' => '{r}9'
        )
    );

    // has a type, like "staccato"
    public function __construct($num) {
        $this->chord = $num;
    }

    public static function constructFromName($name) {
        // look up the number...
        $num = 273;
        return new ChordName($num);
    }

    public static function constructFromNumber($num) {
        return new ChordName($num);
    }

    public function getTones() {
        return 273;
    }

    public function getName() {
        return 'augmented triad';
    }

    /**
     * return a string like "I", "ii", "iii" or "vii°"
     * majors are in capital roman letters, minors are in lower case.
     *
     * examples:
     *                         10010001,     (root tonic triad)                 ==> I
     *                       1000100100,     (minor triad on the second degree) ==> ii
     *                     100010010000,     (minor triad on the third degree)  ==> iii
     *                   1 001000100000,     (major triad on the fourth degree) ==> IV
     *                 100 100010000000,     (major triad on the fifth degree)  ==> V
     *               10001 001000000000,     (minor triad on the sixth degree)  ==> vi
     *              100100 100000000000,     (dim triad on the seventh degree)  ==> vii°
     *
     */

    /**
     * moves all the tones down into one octave
     */
    public function compress() {
        $output = 0;
        $n = $this->chord;
        while ($n > 0) {
            $temp = $n & 4095;
            $output = $output | $temp;
            $n = $n >> 12;
        }
        return $output;
    }

    /**
     * the Chord is a set of notes with pitches.
     * the tonic is a Pitch, which could be heightless.
     * This method should first figure out the scale degree (I, II, III, IV)
     * then analyze the chord to see if it is maj, min, dim or aug,
     * then output the appropriate string representation, like "vii°" or "V"
     *
     * presently this method assumes the chord is in root inversion formation
     * @todo make this understand inverted chords!
     */
    public static function harmonyTriadName($chord, $tonic) {
        // what is the distance in steps netween the chord and the tonic?
        $lowest = $chord->lowestMember();
        $degree = $lowest->pitch->stepDownDistance($tonic->step);

        $romans = array('i','ii','iii','iv','v','vi','vii');
        $degree = $romans[$degree];
        $symbol = '';

        // what kind of triad is it?
        $type = $chord->analyzeTriad();
        switch ($type) {
            case 'diminished flat 3':
                $degree = strtolower($degree);
                $symbol = '°♭3';
                break;
            case 'diminished':
                $degree = strtolower($degree);
                $symbol = '°';
                break;
            case 'minor':
                $degree = strtolower($degree);
                break;
            case 'major':
                $degree = strtoupper($degree);
                break;
            case 'augmented':
                $degree = strtoupper($degree);
                $symbol = '+';
                break;
            case 'major flat 5':
                $degree = strtoupper($degree);
                $symbol = '♭5';
                break;
            default:
                return '';
        }
        return $degree . $symbol;
    }


}
