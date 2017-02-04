<?php
namespace ianring;
require_once 'PMTObject.php';
require_once 'Pitch.php';

/**
 * This class operates on the understanding that all scales are made from the set of 12 chromatic tempered
 * pitches, and that there is a limited number of possible combinations of those pitches. The "power set"
 * of all possible scales is a set of 4096 scales, and each one can be represented by a decimal number from
 * 0 (no notes) to 4095 (all 12 notes). The index is deterministic. Simply by converting the decimal number
 * to a binary number, it becomes a bitmask defining what pitches are present in the scale, where bit 1 is the root,
 * bit 2 is up one semitone, bit 4 is a major second, bit 8 is the minor third, etc.
 */

/**
 * Scale is a series of notes all conforming to a set, moving stepwise ascending or descending
 */
class Scale extends PMTObject
{
    // ref: http://www.pdmusic.org/text/027.txt

    const ASCENDING = 'ascending';
    const DESCENDING = 'descending';

    public static $scaleNames = array(
    273 => 'augmented triad',
    395 => 'balinese',
    585 => 'diminished seventh',
    1123 => 'iwato',
    1186 => 'insen',
    1365 => 'whole tone',
    1371 => 'altered',
    1387 => 'locrian',
    1389 => 'half diminished',
    1397 => 'arabian b',
    1451 => 'phrygian',
    1453 => array('aeolian', 'natural minor', 'asavari theta'),
    1459 => array('phrygian dominant', 'spanish romani'),
    1485 => array('aolian #4', 'romani scale'),
    1709 => 'dorian',
    1717 => 'mixolydian',
    1741 => array('ukranian dorian','romanian scale','altered dorian'),
    1749 => array('acoustic', 'lydian dominant'),
    2257 => 'hirajoshi',
    2541 => 'algerian',
    2733 => array('heptatonia seconda', 'ascending melodic minor', 'jazz minor'),
    2741 => array('major' ,'ionian'),
    2773 => 'lydian',
    2483 => 'enigmatic',
    2457 => 'augmented',
    2477 => 'harmonic minor',
    2483 => 'flamenco mode',
    2509 => 'hungarian minor',
    2901 => 'lydian augmented',
    2731 => 'major neapolitan',
    2475 => 'minor neapolitan',
    2483 => 'double harmonic',
    2925 => 'arabian a',
    3669 => 'prometheus',
    1235 => 'tritone scale',
    1755 => array('octatonic', 'second mode of limited transposition'),
    3549 => 'third mode of limited transposition',
    2535 => 'fourth mode of limited transposition',
    2275 => 'fifth mode of limited transposition',
    3445 => 'sixth mode of limited transposition',
    3055 => 'seventh mode of limited transposition',
    3765 => 'bebop dominant',
    4095 => 'chromatic 12-tone',

/* TODO: add the rest of these, 

      Internationa Scale Names
      Name                            Relative      General
    1 Aeolian                         12b345b6b7    2122122
    2 Algerian                        12b34#45b67   21211131
    3 Arabian (a)                     12b34#4#567   21212121
    4 Arabian (b)                     1234#4#5b7    2211222
    5 Asavari Theta                   12b345b6b7    2122122
    6 Balinese                        1b2b35b6      12414
    7 Bilaval Theta                   1234567       2212221
    8 Bhairav Theta                   1b2345b67     1312131*
    9 Bhairavi Theta                  1b2b345b6b7   1222122
   10 Byzantine                       1b2345b67     1312131*
   11 Chinese                         13#457        42141
   12 Chinese Mongolian               12356         22323
   13 Diminished                      12b34#4#567   21212121
   14 Dorian                          12b3456b7     2122212*
   15 Egyptian                        1245b7        23232*
   16 Ethiopian (A raray)             1234567       2212221
   17 Ethiopian (Geez & ezel)         12b345b6b7    2122122
   18 Harmonic Minor                  12b345b67     2122131
   19 Hawaiian                        12b34567      2122221
   20 Hindustan                       12345b6b7     2212122*
   21 Hungarian Major                 1#23$456b7    3121212
   22 Hungarian Gypsy                 12b3#45b67    2131131
   23 Hungarian Gypsy Persian         1b2345b67     1312131*
   24 Hungarian Minor                 12b3#45b67    2131131
   25 Ionian                          1234567       2212221
   26 Japanese (A)                    1b245b6       14214
   27 Japenese (B)                    1245b6        23214
   28 Japanese (Ichikosucho)          1234#4567     22111221
   29 Japanese (Taishikicho)          1234#456#67   221112111
   30 Javanese (Pelog)                1b2b3456b7    1222212
   31 Jewish (Adonai Malakh)          1b22b3456b7   11122212
   32 Jewish (Ahaba Rabba)            1b2345b6b7    1312122
   33 Jewish (Magan Abot)             1b2#23#4#567  12122211
   34 Kafi Theta                      12b3456b7     2122212*
   35 Kalyan Theta                    123#4567      2221221
   36 Khamaj Theta                    123456b7      2212212
   37 Locrian                         1b2b34#4#5b7  1221222
   38 Lydian                          123#4567      2221221
   39 Major                           1234567       2212221
   40 Marva Theta                     1b23#4567     1321221
   41 Mela Bhavapriya (44)            1b2b3#45#567  1231122
   42 Mela Chakravakam (16)           1b23456b7     1312212
   43 Mela Chalanata (36)             1#2345#67     3112311
   44 Mela Charukesi (26)             12345b6b7     2212122*
   45 Mela Chitrambari (66)           123#45#67     2221311
   46 Mela Dharmavati (59)            12b3#4567     2131221
   47 Mela Dhatuvardhani (69)         1#23#45b67    3121131
   48 Mela Dhavalambari (49)          1b23#45#56    1321113
   49 Mela Dhenuka (9)                1b2b345b67    1222131
   50 Mela Dhirasankarabharana (29)   1234567       2212221
   51 Mela Divyamani (48)             1b2b3#45#67   1231311
   52 Mela Gamanasrama (53)           1b23#4567     1321221
   53 Mela Ganamurti (3)              1b245b67      1132131
   54 Mela Gangeyabhusani (33)        1#2345b67     3112131
   55 Mela Gaurimanohari (23)         12b34567      2122221
   56 Mela Gavambodhi (43)            1b2b3#45#56   1231113
   57 Mela Gayakapriya (13)           1b2345#56     1312113
   58 Mela Hanumattodi (8)            1b2b345b6b7   1222122
   59 Mela Harikambhoji (28)          123456b7      2212212
   60 Mela Hatakambari (18)           1b2345#67     1312311
   61 Mela Hemavati (58)              12b3#456b7    2131212
   62 Mela Jalarnavam (38)            1b22#45b6b7   1141122
   63 Mela Jhalavarali (39)           1b22#45b67    1141131
   64 Mela Jhankaradhvani (19)        12b345#556    2122113
   65 Mela Jyotisvarupini (68)        1#23#45b6b7   3121122
   66 Mela Kamavarardhani (51)        1b23#45b67    1321131
   67 Mela Kanakangi (1)              1b2245#56     1132113
   68 Mela Kantamani (61)             123#45#56     2221113
   69 Mela Kharaharapriya (22)        12b3456b7     2122212*
   70 Mela Kiravani (21)              12b345b67     2122131
   71 Mela Kokilapriya (11)           1b2b34567     1222221*
   72 Mela Kosalam (71)               1#23#4567     3121221
   73 Mela Latangi (63)               123#45b67     2221131
   74 Mela Manavati (5)               1b224567      1132221
   75 Mela Mararanjani (25)           12345#56      2212113
   76 Mela Mayamalavagaula (15)       1b2345#5#67   1312131
   77 Mela Mechakalyani (65)          123#4567      2221221
   78 Mela Naganandini (30)           12345#67      2212311
   79 Mela Namanarayani (50)          1b23#45b6b7   1321122
   80 Mela Nasikabhusani (70)         1#23#456b7    3121212
   81 Mela Natabhairavi (20)          12b345b6b7    2122122
   82 Mela Natakapriya (10)           1b2b3456b7    1222212
   83 Mela Navanitam (40)             1b22#456b7    1141212
   84 Mela Nitimati (60)              12b3#45#67    2131311
   85 Mela Pavani (41)                1b22#4567     1141221
   86 Mela Ragavardhani (32)          1#2345b6b7    3112122
   87 Mela Raghupriya (42)            1b22#45#67    1141311
   88 Mela Ramapriya (52)             1b23#456b7    1321212
   89 Mela Rasikapriya (72)           1#23#45#67    3121311
   90 Mela Ratnangi (2)               1b2245b6b7    1132122
   91 Mela Risabhapriya (62)          123#45b6b7    2221122
   92 Mela Rupavati (12)              1b2b345#67    1222311
   93 Mela Sadvidhamargini (46)       1b2b3#456b7   1231212
   94 Mela Salagam (37)               1b22#45#56    1141113
   95 Mela Sanmukhapriya (56)         12b3$45b6b7   2131122
   96 Mela Sarasangi (27)             12345b67      2212131
   97 Mela Senavati (7)               1b2b345#56    1222113
   98 Mela Simhendramadhyama (57)     12b3#45b67    2131131
   99 Mela Subhapantuvarali (45)      1b2b3#45b67   1231131
  100 Mela Sucharitra (67)            1#23#45#56    3121113
  101 Mela Sulini (35)                1#234567      3112221
  102 Mela Suryakantam (17)           1b234567      1312221
  103 Mela Suvarnangi (47)            1b2b3#4567    1231221
  104 Mela Syamalangi (55)            12b3#45#56    2131113
  105 Mela Tanarupi (6)               1b2245#67     1132311
  106 Mela Vaschaspati (64)           123#456b7     2221212
  107 Mela Vagadhisvari (34)          1#23456b7     3112212
  108 Mela Vakulabharanam (14)        1#2345b6b7    1312122
  109 Mela Vanaspati (4)              1b22456b7     1132212
  110 Mela Varunapriya (24)           12b345#67     2122311
  111 Mela Visvambari (54)            1#23#45#67    1321311
  112 Mela Yagapriya (31)             1#2345#56     3112113*
  113 Melodic Minor                   12b34567      2122221
  114 Mixolydian                      123456b7      2212212
  115 Mohammedan                      12b345b67     2122131
  116 Neopolitan                      1b2b345b67    1222131
  117 Oriental (a)                    1b234b5b6b7   1311222
  118 Overtone Dominant               123#456b7     2221212
  119 Pentatonic Major                12356         23223
  120 Pentatonic Minor                1b345b7       32232
  121 Persian                         1b234b5b67    1311231
  122 Phrygian                        1b2b345b6b7   1222122
  123 Purvi Theta                     1b23#45b67    1321131
  124 Roumanian Minor                 12b3#456b7    2131212
  125 Spanish Gypsy                   1b2345b6b6    1312122
  126 Todi Theta                      1b2b3#45b67   1231131
  127 Whole Tone                      123#4#5b7     222222*
  128 Augmented                       1#235b67      313131
  129 Blues                           1b34#45b7     321132
  130 Diatonic                        12356         22323
  131 Double Harmonic                 1b2345b67     1312131*
  132 Eight Tone Spanish              1b2#234b5b6b7 12111222
  133 Enigmatic                       1b23#4#5#67   1322211
  134 Hirajoshi                       12b35b6       21414
  135 Kumoi                           12b356        21423
  136 Leading Whole Tone              123#4#5#67    2222211
  137 Lydian Augmented                123#4#567     2222121
  138 Neoploitan Major                1b2b34567     1222221*
  139 Neopolitan Minor                1b2b345b6b7   1222122
  140 Oriental (b)                    1b234b56b7    1311312
  141 Pelog (Javanese)                1b2b3456b7    1222212
  142 Prometheus                      123b56b7      222312
  143 Prometheus Neopolitan           1b23b56b7     132312
  144 Six Tone Symmetrical            1b234#56      131313
  145 Super Locrian                   1b2#23#4#5b7  1212222
  146 Lydian Minor                    123#45b6b7    2221122
  147 Lydian Diminished               12b3#4567     2131122
  148 Nine Tone Scale                 12#23#45#567  211211121
  149 Auxiliary Diminished            12b34#4#567   21212121
  150 Auxiliary Augmented             123#4#5#6     222222
  151 Auxiliary Diminished Blues      1b2#23#456b7  12121212
  152 Major Locrian                   1234b5b6b7    2211222
  153 Overtone                        123#456b7     2221212
  154 Hindu                           12345b6b7     2212122
  155 Diminished Whole Tone           1b2#23#4#5b7  1212222
  156 Pure Minor                      12b345b6b7    2122122
  157 Half Diminished (Locrian)       1b2b34b5b6b7  1221222
  158 Half Diminished #2 (Locrian #2) 12b34b5b6b7   2121222
  159 Dominant 7th                    12456b7       232212

      * signify scale structure palindromes (same forward and backward)

      Scale names source refererences:
      "The Lydian Chromatic Concept of Tonal Organization" by George Russell,
      "John McLaughlin and the Mahavishnu Orchestra" (Music Vocabulary) of th
      "Charlie Parker Omnibook"  (Scale Syllabus) solo transcriptions by Jame
      "Encyclopedia of Scales" by Don Schaeffer & Charles Colin, 1965
      "The Ragas of North India" by Walter Kaufmann, 1968
      "The Ragas of South India" by Walter Kaufmann, 1976
      "Twentienth Century Harmony" by Vincent Perischetti, 1961

      Note: The Melas of South India are divided into twelve chakras.
    I.Indu                 (melas:)     1-6          (structure:) 1132
   II.Netra                             7-12                      1222
  III.Agni                             13-18                      1312
   IV.Veda                             19-24                      2122
    V.Bana                             25-30                      2212
   VI.Rutu                             31=36                      3112
  VII.Rishi                            37-42                      1141
 VIII.Vasu                             43-48                      1231
   IX.Brahma                           49-54                      1321
    X.Disi                             55-60                      2131
   XI.Rudra                            61-66                      2221
  XII.Aditya                           67-72                      3121

      Originally completed July 25, 1980 with an addendum dated 4/13/81 & 7/2
      With added sources and more scales added 24 February 1998. Corrections
      made to scale numbers 41, 51, 76 and 103, on 7 and 10 May 2012.

      Benjamin Robert Tubb
      brtubb@pdmusic.org
      http://www.pdmusic.org/theory.html
 */

    );

    /**
     * Constructor.
  *
     * @param int|string        $scale     [description]
     * @param Pitch|string|null $root      [description]
     * @param string            $direction [description]
     */
    public function __construct($scale, $root, $direction = self::ASCENDING) {
        if ($root instanceof Pitch) {
            $this->root = $root;
        } elseif (is_null($root)) {
            $this->root = null; // because a scale can be a rootless, abstract thing
        } else {
            $this->root = new Pitch($root);
        }

        if (is_numeric($scale)) {
            $this->scale = $scale;
        } else {
            $this->scale = $this->_resolveScaleFromString($scale);
        }

        $this->direction = $direction;

    }

    /**
     * accepts the scale object in the form of an array structure
  *
     * @param  [type] $scale [description]
     * @return [type]        [description]
     */
    public static function constructFromArray($props) {
        if (isset($props['root'])) {
            if ($props['root'] instanceof Pitch) {
                $root = $props['root'];
            } else {
                $root = Pitch::constructFromArray($props['root']);
            }
        }

        return new Scale($props['scale'], $root, $props['direction']);
    }

    /**
     * accept a string, like "C# major ascending" or "D# minor",
     * "E4 aolian ascending" or "dorian"
     * leaving ambiguities intact to be filled in with setProperty
     *
     * @param  [type] $string [description]
     * @return int         the scale number
     */
    function _resolveScaleFromString($string) {
        // todo: this
        return 4095;
    }

    /**
     * @todo
     * accepts an array of pitches, and will tell you what scale it is. If root is not provided,
     * tries to figure out what the tonic is based on note distribution.
     * @param  [type] $pitches [description]
     * @return [type]          [description]
     */
    public static function determineScaleFromPitches($pitches, $root = null) {

    }

    // gets pitches in sequence for the scale, of one octave
    // todo: make this better
    function getPitches() {
        $pitches = array();
        for ($i = 0; $i < 12; $i++) {
            if ($this->direction == self::ASCENDING) {
                $offset = $i;
            } else {
                $offset = 12 - $i;
            }
            if ($this->scale & (1 << $offset)) {
                $newroot = clone $this->root;
                $newroot->transpose($i);
                $pitches[] = $newroot;
            }
        }
        $pitches = $this->_normalizeScalePitches($pitches);

        return $pitches;
    }


    /**
     * What this function has got to do is make sure that the C sharp major scale uses an E sharp, not
     * an F natural. To do that it recognizes scales that are diatonic, and forces each note to be on
     * consecutive steps. To do that, it assumes that the first note is on the correct step!
     *
     * TODO: it should handle complex scales like bebop and octotonic properly.
     * Good luck!
     *
     * @param  Pitch[] $pitches [description]
     * @return Pitch[]
     */
    public function _normalizeScalePitches($pitches) {
        if (in_array($this->scale, array(1387,1451,1709,1717,2773,2477,2741,1453))) {
            // this is a scale known to have a note on every step
            $currentStep = $pitches[0]->step;
            for ($i = 1; $i < count($pitches); $i++) {
                $prevstep = $pitches[$i-1]->step;
                $shouldbe = Pitch::stepUp($prevstep);
                if ($pitches[$i] != $shouldbe) {
                    $pitches[$i] = $pitches[$i]->enharmonicizeToStep($shouldbe);
                }
            }
        }

        return $pitches;
    }


    /**
     * return the levenshtein distance between two scales (a measure of similarity)
  *
     * @param  Scale $scale1 the first scale
     * @param  Scale $scale2 the second scale
     * @return int    Levenshtein distance between the two scales
     */
    static function levenshtein_scale($scale1, $scale2) {
        // todo
    }

    /**
     * Static function: pass in a note, measure, layer etc and get back an array of scales that all the pitches conform to.
  *
     * @param  Note, Chord, Layer, Measure $obj the thing that has pitches in it, however deep they may be
     * @return array of Scales
     */
    public static function getScales($obj) {
        $pitches = $obj->getAllPitches();
        // todo figure out how to do this efficiently
    }

    public function imperfections() {

    }

    public static function findImperfections($scale) {
        $imperfections = array();
        for ($i = 0; $i<12; $i++) {
            $fifthAbove = ($i + 7) % 12;
            if ($scale & (1 << $i) && !($scale & (1 << $fifthAbove))) {
                $imperfections[] = $i;
            }
        }
        return $imperfections;
    }

    /**
     * returns the spectrum of a scale, ie how many of every type of interval exists between all the tones.
  *
     * @param  [type] $scale [description]
     * @return [type]        [description]
     */
    public static function findSpectrum($scale) {
        $spectrum = array();
        $rotateme = $scale;
        for ($i=0; $i<6; $i++) {
            $rotateme = rotateBitmask($rotateme, $direction = 1, $amount = 1);
            $spectrum[$i] = countOnBits($scale & $rotateme);
        }
        // special rule: if there is a tritone in the sonority, it will show up twice, so we divide by 2
        $spectrum[5] = $spectrum[5] / 2;
        return $spectrum;
    }

    /**
     * takes a scale spectrum, and renders the "pmn" summmary string, ala Howard Hansen
  *
     * @param  [type] $spectrum [description]
     * @return [type]           [description]
     */
    function renderPmn($spectrum) {
        $string = '';
        // remember these are 0-based, so they're like the number of semitones minus 1
        $classes = array('p' => 4, 'm' => 3, 'n' => 2, 's' => 1, 'd' => 0, 't' => 5);
        foreach ($classes as $class => $interval) {
            if ($spectrum[$interval] > 0) {
                $string .= $class;
            }
            if ($spectrum[$interval] > 1) {
                $string .= '<sup>'.$spectrum[$interval].'</sup>';
            }
        }
        return '<em>' . $string . '</em>';
    }

    /**
     * a special rule that some people think defines what a scale is.
     * returns true if the first bit is not a zero.
     * Useful for filtering a set of numbers to weed out non-scales
  *
     * @param  [type] $scale [description]
     * @return [type]        [description]
     */
    function hasRootTone($scale) {
        // returns true if the first bit is not a zero
        return (1 & $scale) != 0;
    }

    /**
     * a special rule that some people think defines what a scale is, ie not having leaps of more than a major third.
     * Useful for filtering a set of numbers to weed out non-scales
  *
     * @param  [type] $scale [description]
     * @return [type]        [description]
     */
    public static function doesNotHaveFourConsecutiveOffBits($scale) {
        $c = 0;
        for ($i=0; $i<12; $i++) {
            if (!($scale & (1 << ($i)))) {
                $c++;
                if ($c >= 4) {
                    return false;
                }
            } else {
                $c = 0;
            }
        }
        return true;
    }

    /**
     * returns an array of all the modes of a scale.
  *
     * @param  [type] $scale [description]
     * @return [type]        [description]
     */
    function modes($scale) {
        $rotateme = $scale;
        $modes = array();
        for ($i = 0; $i < 12; $i++) {
            $rotateme = rotateBitmask($rotateme);
            if (($rotateme & 1) == 0) {
                continue;
            }
            $modes[] = $rotateme;
        }
        return $modes;
    }

    /**
     * finds the notes of a scale that are symmetry axes, ie the roots of modes that are identical the original
     *
     * @param  [type] $scale [description]
     * @return [type]        [description]
     */
    function symmetries($scale) {
        $rotateme = $scale;
        $symmetries = array();
        for ($i = 0; $i < 12; $i++) {
            $rotateme = rotateBitmask($rotateme);
            if ($rotateme == $scale) {
                if ($i != 11) {
                    $symmetries[] = $i+1;
                }
            }
        }
        return $symmetries;
    }

    /**
     * returns true if a scale is palindromic
     *
     * @return boolean [description]
     */
    public function isPalindromic() {
        for ($i=1; $i<=5; $i++) {
            if ((bool)($this->scale & (1 << $i)) !== (bool)($this->scale & (1 << (12 - $i))) ) {
                return false;
            }
        }
        return true;
    }

    /**
     * returns true if the scale is chiral
     * see: https://en.wikipedia.org/wiki/Chirality_(mathematics) 
     */
    public function isChiral() {

    }

    /**
     * counts the number of tones in a scale
     *
     * @return [type] [description]
     */
    public static function countTones($scale) {
        $tones = 0;
        for ($i = 0; $i < 12; $i++) {
            if (($scale & (1 << $i)) == 0) {
                $tones++;
            }
        }
        return $tones;
    }

    static function scaletype($num) {
        $types = array(
         4 => 'tetratonic',
         5 => 'pentatonic',
         6 => 'hexatonic',
         7 => 'heptatonic',
         8 => 'octatonic',
        );
        if (isset($types[$num])) {
            return $types[$num];
        }
        return null;
    }

    function rotateBitmask($bits, $direction = 1, $amount = 1) {
        for ($i = 0; $i < $amount; $i++) {
            if ($direction == 1) {
                $firstbit = $bits & 1;
                $bits = $bits >> 1;
                $bits = $bits | ($firstbit << 11);
            } else {
                $firstbit = $bits & (1 << 11);
                $bits = $bits << 1;
                $bits = $bits & ~(1 << 12);
                $bits = $bits | ($firstbit >> 11);
            }
        }
        return $bits;
    }

    function drawSVGBracelet($scale, $size = 200, $text = null, $showImperfections = false) {
        if ($showImperfections) {
            $imperfections = findImperfections($scale);
            $symmetries = symmetries($scale);
        }

        $s = '';
        if ($size > 100) {
            $stroke = 3;
        } elseif ($size > 50) {
            $stroke = 2;
        } else {
            $stroke = 1;
        }
        $smallrad = floor(($size / 12));
        $centerx = $size / 2;
        $centery = $size / 2;
        $radius = floor(($size - ($smallrad*2) - ($stroke*4)) / 2);
        $s .= '<svg xmlns="http://www.w3.org/2000/svg" height="'. ($size + 3).'" width="'.($size + 3) .'">';
        $s .= '<circle r="'.$radius.'" cx="'.$centerx.'" cy="'.$centery.'" stroke-width="'.$stroke.'" fill="white" stroke="black"/>';
        $symmetryshape = array();
        for ($i=0; $i<12; $i++) {

            $deg = $i * 30 - 90;
            $x1 = floor($centerx + ($radius * cos(deg2rad($deg))));
            $y1 = floor($centery + ($radius * sin(deg2rad($deg))));

            $innerx1 = floor($centerx + (($radius - $smallrad) * cos(deg2rad($deg))));
            $innery1 = floor($centery + (($radius - $smallrad) * sin(deg2rad($deg))));

            if ($i == 0) {
                $symmetryshape[] = array($innerx1, $innery1);
            }

            $s .= '<circle r="'.$smallrad.'" cx="'.$x1.'" cy="'.$y1.'" stroke="black" stroke-width="'.$stroke.'"';
            if ($scale & (1 << $i)) {
                $s .= ' fill="black"';
            } else {
                $s .= ' fill="white"';
            }
            $s .= '/>';

            if ($showImperfections) {
                if (in_array($i, $imperfections)) {
                    $s .= '<text style="font-family: Times New Roman;font-weight:bold;font-style:italic;font-size:30px;" text-anchor="middle" x="'.$x1.'" y="'. ($y1 + 9) .'" fill="white">i</text>';
                }
                if (in_array($i, $symmetries)) {
                    $symmetryshape[] = array($innerx1, $innery1);
                }
            }
        }
        if (count($symmetryshape) > 1) {
            for ($i = 0; $i < count($symmetryshape) - 1; $i++) {
                $s .= '<line x1="'.$symmetryshape[$i][0].'" y1="'.$symmetryshape[$i][1].'" x2="'.$symmetryshape[$i+1][0].'" y2="'.$symmetryshape[$i+1][1].'" style="stroke:#000;stroke-width:'.$stroke.'" />';
            }
            $s .= '<line x1="'.$symmetryshape[count($symmetryshape)-1][0].'" y1="'.$symmetryshape[count($symmetryshape)-1][1].'" x2="'.$symmetryshape[0][0].'" y2="'.$symmetryshape[0][1].'" style="stroke:#000;stroke-width:'.$stroke.'" />';
        }
        if (!empty($text)) {
            $s .= '<text style="font-weight: bold;" text-anchor="middle" x="'.$centerx.'" y="'. ($centery + 5) .'" fill="black">'.$text.'</text>';
        }
        $s .= '</svg>';
        return $s;
    }

}
