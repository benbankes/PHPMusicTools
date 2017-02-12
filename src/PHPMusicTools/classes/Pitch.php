<?php
namespace ianring;
require_once 'PMTObject.php';

/**
 * Pitch is the conceptual represtation of sound frequency. It's the property of a note that describes its pitch.
 *
 * An octave begins and ends with C.
 * Therefore C4 is the note immediately above B3.
 * The octave does not belong to the step. It is a measurement of what height range the tone is
 * in, not dependent on its spelling.
 * Therefore the enharmonic of C4 is not B#3, it is B#4.
 * C-3 is not in the same octave as the C natural beside it.
 *
 * LILYPOND disagrees with this - it attaches the octave to its step. So in lilypond,
 * bis'' is the note above b''
 *
 * Chromas are numbered 0 to 11
 *
 * For some calculations, this class defines a "note number", which is an integer line with its arbitrary
 * origin on middle C. Middle C is zero.
 * going up, C#4 is 1, D4 is 2...
 * going down, B3 is -1, A#3 is -2...
 */
class Pitch extends PMTObject
{

    public function __construct($step = 'C', $alter = 0, $octave = 4) {
        $this->step = $step;
        $this->alter = $alter;
        $this->octave = $octave;
    }

    /**
     * accepts the object in the form of an array structure
     *
     * @param  [winged] $scale [description]
     * @return [winged]        [description]
     */
    public static function constructFromArray($props) {
        $step = $props['step'];
        $alter = $props['alter'];
        $octave = $props['octave'];
        return new Pitch($step, $alter, $octave);
    }

    /**
     * accepts the object in the form of a string
     *
     * @param  [winged] $scale [description]
     * @return [winged]        [description]
     */
    public static function constructFromString($string) {
        $props = self::_resolvePitchString($string);
        return self::constructFromArray($props);
    }

    /**
     * gives the interval between this pitch and the given one.
     * 0 means the pitches are enharmonic.
     * &lt; 0 means the interval is downward,
     * &gt; 0 means the interval is upward
     *
     * @param  Pitch $pitch the pitch being checked
     * @return integer the interval in semitones
     */
    public function interval($pitch) {
        return $pitch->toNoteNumber() - $this->toNoteNumber();
    }

    /**
     * returns true if $this is lower than the provided pitch
     *
     * @param  Pitch $pitch the pitch being checked
     * @return boolean       true if $this is lower than $pitch
     */
    public function isLowerThan($pitch) {
        return $this->interval($pitch) > 0;
    }

    /**
     * returns true if $this is higher than the provided pitch
     *
     * @param  Pitch $pitch the pitch being checked
     * @return boolean       true if $this is higher than $pitch
     */
    public function isHigherThan($pitch) {
        return $this->interval($pitch) < 0;
    }
    /**
     * returns true if $this is enharmonic with the provided pitch
     *
     * @param  Pitch $pitch [description]
     * @return boolean        [description]
     */
    public function isEnharmonic($pitch) {
        return $this->interval($pitch) == 0;
    }

    /**
     * note the difference between this and isEnharmonic - in this one, spelling counts.
     *
     * @param  Pitch $pitch The pitch being compared to $this
     * @return boolean true if the step, alter, and octave are all the same.
     */
    public function equals($pitch) {
        return $pitch->step == $this->step && $pitch->alter == $this->alter && $pitch->octave == $this->octave;
    }

    /**
     * returns a pitch's chroma as an integer. e.g. C = 0, C# = 1, D = 2, up to B = 11
     *
     * @return int The chrome of the pitch as an integer from 0 to 11
     */
    public function chroma() {
        $steps = array(
         'C' => 0,
         'D' => 2,
         'E' => 4,
         'F' => 5,
         'G' => 7,
         'A' => 9,
         'B' => 11,
        );
        $chroma = $steps[$this->step];
        $chroma += $this->alter;
        $chroma = $this->_truemod($chroma, 12);
        return $chroma;
    }

    /**
     * gives the step above the current one. e.g. given D, returns E. Given G, returns A.
     *
     * @param  [type] $step [description]
     * @return [type]       [description]
     */
    public static function stepUp($step) {
        $steps = 'CDEFGABC';
        $pos = strpos($steps, $step);
        return substr($steps, $pos+1, 1);
    }
    /**
     * gives the step below the current one. e.g. given E, returns D. Given A, returns G.
     *
     * @param  [type] $step [description]
     * @return [type]       [description]
     */
    public static function stepDown($step) {
        $steps = 'CBAGFEDC';
        $pos = strpos($steps, $step);
        return substr($steps, $pos+1, 1);
    }

    /**
     * will change a pitch so it is spelled enharmonically using the provided step; in other words it will
     * change the step but adjust the alter. This is basically designed to turn an F natural into an E sharp when
     * in the context of a C# major scale.
     *
     * The enharmonic should always convert to the nearest tone, whether that's up or down. It would be
     * weird to enharmonicize a C sharp to an F quadruple-flat, but theoretically that's what this function would do.
     *
     * For example. For a pitch of F#, enharmonicizing to step "G" will produce G flat.
     * For a pitch D natural, enharmonisizing to step "C" produces a C double-sharp.
     *
     * @param  [type] $pitch [description]
     * @param  [type] $step  [description]
     * @return [type]        [description]
     */
    function enharmonicizeToStep($step) {
        if ($this->step == $step) {
            return $this;
        }
        $up = $this->closestUp($step, 0, true);
        $down = $this->closestDown($step, 0, true);
        $intervalup = $this->interval($up);
        $intervaldown = $this->interval($down);
        $newpitch = abs($intervalup) > abs($intervaldown) ? $down : $up;
        $interval = $this->interval($newpitch);
        $this->step = $step;
        $this->alter = ($interval * -1);
        return $this;
    }

    public static $chromas = array(
    0 => 'C',
    1 => array(
    1 => array('step' => 'C', 'alter' => 1),
    -1 => array('step' => 'D', 'alter' => -1)
    ),
    2 => 'D',
    3 => array(
    1 => array('step' => 'D', 'alter' => 1),
    -1 => array('step' => 'E', 'alter' => -1),
    ),
    4 => 'E',
    5 => 'F',
    6 => array(
    1 => array('step' => 'F', 'alter' => 1),
    -1 => array('step' => 'G', 'alter' => -1),
    ),
    7 => 'G',
    8 => array(
    1 => array('step' => 'G', 'alter' => 1),
    -1 => array('step' => 'A', 'alter' => -1),
    ),
    9 => 'A',
    10 => array(
    1 => array('step' => 'A', 'alter' => 1),
    -1 => array('step' => 'B', 'alter' => -1),
    ),
    11 => 'B'
    );


    public function isHeightless() {
        return is_null($this->octave);
    }

    /**
     * renders this object as MusicXML
     *
     * @return string MusicXML representation of the object
     */
    public function toMusicXML() {
        if ($this->octave == null) {
            throw new Exception('heightless pitches can not be rendered as XML. Provide an "octave" property. ' . print_r($this->properties, true));
        }

        $out = '<pitch>';

        $out .= '<step>' . $this->step . '</step>';
        $out .= '<alter>' . $this->alter . '</alter>';
        $out .= '<octave>' . $this->octave . '</octave>';

        $out .= '</pitch>';

        return $out;
    }

    /**
     * interprets a string like "C#4" or "Gbb"
     *
     * @param  [type] $string [description]
     * @return [type]        [description]
     */
    private static function _resolvePitchString($string) {
        if (is_array($string)) {
            return $string;
        }

        $properties = array();

        preg_match('/([A-Ga-g+#-b]+?)(\d+)/', $string, $matches);
        $chroma = $matches[1];

        // there might be no octave part, if we're creating a heightless pitch, like "D#". Default to octave 4.
        $octave = null;
        if (!empty($matches[2])) {
            $octave = $matches[2];
        }

        preg_match('/([A-Ga-g]+?)(.*)/', $chroma, $matches);
        $step = $matches[1];
        switch ($matches[2]) {
        case '##':
        case '++':
            $alter = 2;
            break;
        case '#':
        case '+':
            $alter = 1;
            break;
        case 'b':
        case '-':
            $alter = -1;
            break;
        case 'bb':
        case '--':
            $alter = -2;
            break;
        default:
            $alter = 0;
        }
        $step = $step;
        $alter = $alter;
        $octave = $octave;

        return $properties;
    }


    /**
     * transposes a Pitch up or down by $interval semitones.
     *
     * @param  integer $interval            a signed integer telling how many semitones to transpose up or down
     * @param  integer $preferredAlteration either 1, or -1 to indicate whether the transposition should prefer sharps or flats.
     * @return null
     */
    public function transpose($interval, $preferredAlteration = 1) {
        $isHeightless = $this->isHeightless();
        if (!in_array($preferredAlteration, array(1, -1))) {
            $preferredAlteration = 1; // default to prefer sharps.
        }
        if ($isHeightless) {
            $this->octave = 4; // just choose some arbitrary octave to use for calculations
        }
        $num = self::toNoteNumber($this);
        $num += $interval;
        $this->noteNumberToPitch($num, $preferredAlteration);

        if ($isHeightless) {
            // set it back the way it was
            $this->octave = null;
        }
        // return the pitch so we can do chaining
        return $this;
    }

    /**
     * Inverts this pitch around an axis.
     * For example, if I invert C4 around E4, the result is G#4.
     * When I invert G4 around E4, the result is C#4. 
     * @param  Pitch $axis the axis around which to invert
     * @return [type]        [description]
     * @todo 
     */
    public function invert($axis) {
        // to do
    }

    /**
     * renders a canonical string description of the pitch. Uses "#" and "-" for accidentals.
     *
     * @return [type] [description]
     */
    public function toString() {
        $str = '';
        $str .= $this->step;
        switch ($this->alter) {
        case 1:
            $str .= '#';
            break;
        case -1:
            $str .= '-';
            break;
        default:
            break;
        }
        $str .= $this->octave;
        return $str;
    }

    /**
     * translates a pitch properties into a signed integer, arbitrarily centered with zero on middle C
     *
     * @param  Pitch $pitch
     * @return int pitch number, useful for doing calculcations
     */
    public function toNoteNumber() {
        $chromas = array('C' => 0, 'D' => 2, 'E' => 4, 'F' => 5, 'G' => 7, 'A' => 9, 'B' => 11);
        $num = ($this->octave - 4) * 12;
        $num += $chromas[$this->step];
        $num += $this->alter; // adds a sharp or flat, e.g. 1 = sharp, -1 = flat, -2 = double-flat...
        return $num;
    }

    /**
     * accepts a note number and sets the pitch properties
     *
     * @param  integer $noteNumber          signed integer with origin zero as middle C4
     * @param  integer $preferredAlteration 1 for sharp or -1 for flats
     * @return array                       returns a pitch array, containing step and alter elements.
     */
    public function noteNumberToPitch($noteNumber, $preferredAlteration = 1) {
        $chroma = $this->_truemod($noteNumber, 12); // chroma is the note pitch independent of octave, eg C = 0, Eb/D# = 3, E = 4
        $octave = (($noteNumber - $chroma) / 12) + 4;
        $this->octave = $octave;

        if (is_array(self::$chromas[$chroma])) {
            if ($preferredAlteration === 1) {
                $this->step = self::$chromas[$chroma][1]['step'];
                $this->alter = self::$chromas[$chroma][1]['alter'];
            } else {
                $this->step = self::$chromas[$chroma][-1]['step'];
                $this->alter = self::$chromas[$chroma][-1]['alter'];
            }
        } else {
            $this->step = self::$chromas[$chroma];
            $this->alter = 0;
        }
    }

    /**
     * returns an absolute pitch string suitable for using in Lilypond
     *
     * @return [type] [description]
     */
    public function toLilypond() {
        $out = strtolower($this->step);
        if ($this->alter == 1) {
            $out .= 'is';
        }
        if ($this->alter == -1) {
            $out .= 'es';
        }

        // contrary to the standards in this class, lilypond thinks that B sharp belongs in the same
        // octave as the neighbouring B natural. So we have to adjust those specifically
        $octave = $this->octave;
        if ($this->step == 'B' && $this->alter > 0) {
            $octave--;
        }
        // the same must be done for C flats
        if ($this->step == 'C' && $this->alter < 0) {
            $octave++;
        }

        if ($octave > 3) {
            $out .= str_repeat("'", $octave - 3);
        } else {
            $out .= str_repeat(",", 3 - $octave);
        }
        return $out;
    }

    /**
     * returns the MIDI note number of a pitch.
     * MIDI notes are numbered from 0 (C-1) to 127 (G9)
     *
     * @return int
     * @todo
     */
    public function toMidiKeyNumber() {
        $o = new Pitch('C', 0, -1);
        return $o->interval($this);
    }

    /**
     * returns the frequency of a pitch in Hz
     *
     * @param  string  $tuning presumably we would want to be able to do this for more than just Equal Temperament
     * @param  integer $a      the frequency of "A", in case someone wants to use something other than the standard 440Hz
     * @return number           the frequency in Hz
     */
    public function toFrequency($tuning = 'equal', $a = 440, $precision = 2) {
        $ap = new Pitch('A', 0, 4);
        $n = $ap->interval($this); // get the interval between our note and A
        $s = pow(2, (1/12)); // the twelfth root of 2
        $p = pow($s, $n); // raised to the power of the interval in semitones
        $f = $p * $a; // multiplied by the frequency of "A"
        $f = round($f, $precision, PHP_ROUND_HALF_UP);
        return $f;
    }

    /**
     * Finds the nearest pitch that is higher than (or equal), with a step and alter.
     * May be called with only one argument which is a heightless pitch.
     *
     * @param  string $step       The step we are moving to
     * @param  int    $alter      the alteration we are moving to
     * @param  bool   $allowEqual when the pitch matches the step and alter, (if true) return the same pitch or (if false) go up one octave
     * @return [type] [description]
     */
    public function closestUp($step = 'C', $alter = 0, $allowEqual = true) {
        // special case: if the first and only argument is a heightless Pitch.
        if (count(func_get_args()) == 1 && $step instanceof Pitch && $step->isHeightless()) {
            $alter = $step->alter;
            $step = $step->step;
        }

        $base = new Pitch($step, $alter, -6);
        for ($i=0; $i<25; $i++) {
            $base->transpose(12, $alter);
            if ($base->isHigherThan($this)) {
                return $base;
            }
            if ($allowEqual && $base->isEnharmonic($this)) {
                return $base;
            }
        }
        // this may happen if the pitch is really high or low
        return null;
    }

    /**
     * finds the nearest pitch that is lower than or equal, with a step and alter.
     * May be called with only one argument which is a heightless pitch.
     *
     * @param  string $step       The step we are moving to
     * @param  int    $alter      the alteration we are moving to
     * @param  bool   $allowEqual when the pitch matches the step and alter, (if true) return the same pitch or (if false) go down one octave
     * @return [type] [description]
     */
    public function closestDown($step = 'C', $alter = 0, $allowEqual = true) {
        if (count(func_get_args()) == 1 && $step instanceof Pitch && $step->isHeightless()) {
            $alter = $step->alter;
            $step = $step->step;
        }
        $base = new Pitch($step, $alter, 20);
        for ($i=0; $i<25; $i++) {
            $base->transpose(-12, $alter);
            if ($base->isLowerThan($this)) {
                return $base;
            }
            if ($allowEqual && $base->isEnharmonic($this)) {
                return $base;
            }
        }
        // this may happen if the pitch is really high or low
        return null;
    }


}
