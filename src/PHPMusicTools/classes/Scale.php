<?php
namespace ianring;
require_once 'PMTObject.php';
require_once 'Pitch.php';
require_once 'Chord.php';

/**
 * This class operates on the understanding that all scales are made from the set of 12 chromatic tempered
 * pitches, and that there is a limited number of possible combinations of those pitches. The "power set"
 * of all possible scales is a set of 4096 scales, and each one can be represented by a decimal number from
 * 0 (no notes) to 4095 (all 12 notes). The index is deterministic. Simply by converting the decimal number
 * to a binary number, it becomes a bitmask defining what pitches are present in the scale, where bit 1 is the root,
 * bit 2 is up one semitone, bit 4 is a major second, bit 8 is the minor third, etc.
 *
 * In order for this class to be useful and flexible, we should not impose limitations on our definition of a
 * scale; e.g. it is not necessary for a Scale to have the root bit (1) on, nor will we mind if the scale has
 * leaps greater than 4 semitones. We could have a Scale object (which identifies the set of tones), and then
 * use its methods to determine if it is a "scale" according to the definition we desire.
 *
 * Take notice that a Scale is not a collection of Notes, nor a collection of Pitches. The scale is an abstract
 * pattern that can be applied to a root to generate pitches in a particular octave.
 *
 */

/**
 * Scale is a series of notes all conforming to a set, moving stepwise ascending or descending
 */
class Scale extends PMTObject
{

	const ASCENDING = 'ascending';
	const DESCENDING = 'descending';

	// ref: http://www.pdmusic.org/text/027.txt
	// ref: Benjamin Robert Tubb brtubb@pdmusic.org http://www.pdmusic.org/theory.html
	public static $scaleNames = array(
		273 => 'augmented triad',
		395 => 'balinese',
		397 => 'hirajoshi',
		419 => 'japanese (a)',
		421 => 'japenese (b)',
		585 => 'diminished seventh',
		653 => 'kumoi',
		661 => 'pentatonic major',
		819 => array('augmented inverse', 'six tone symmetrical'),
		859 => array('ultralocrian', 'superlocrian bb7', 'diminished'),
		935 => 'mela kanakangi (1)',
		939 => 'mela senavati (7)',
		941 => 'mela jhankaradhvani (19)',
		947 => 'mela gayakapriya (13)',
		949 => 'mela mararanjani (25)',
		953 => 'mela yagapriya (31)',
		967 => 'mela salagam (37)',
		971 => 'mela gavambodhi (43)',
		973 => 'mela syamalangi (55)',
		979 => 'mela dhavalambari (49)',
		981 => 'mela kantamani (61)',
		985 => 'mela sucharitra (67)',
		1123 => 'iwato',
		1187 => 'insen',
		1189 => 'egyptian',
		1193 => 'pentatonic minor',
		1235 => 'tritone scale',
		1257 => 'blues',
		1365 => array('whole tone', 'auxiliary augmented'),
		1371 => array('altered', 'diminished whole tone', 'super locrian'),
		1387 => array('locrian', 'half diminished (locrian)'),
		1389 => array('half diminished', 'locrian #2'),
		1395 => 'oriental (a)',
		1397 => array('arabian b', 'major locrian'),
		1403 => 'eight tone spanish',
		1447 => 'mela ratnangi (2)',
		1451 => array('phrygian', 'bhairavi theta', 'mela hanumattodi (8)', 'neopolitan minor'),
		1453 => array('aeolian', 'natural minor', 'asavari theta', 'ethiopian (geez & ezel)', 'mela natabhairavi (20)', 'pure minor'),
		1459 => array('phrygian dominant', 'mela vakulabharanam (14)', 'spanish gypsy', 'spanish romani', 'jewish (ahaba rabba)'),
		1461 => array('hindu','hindustan','mela charukesi (26)'),
		1465 => 'mela ragavardhani (32)',
		1479 => 'mela jalarnavam (38)',
		1483 => 'mela bhavapriya (44)',
		1485 => array('lydian diminished','mela sanmukhapriya (56)','aolian #4', 'romani scale'),
		1491 => 'mela namanarayani (50)',
		1493 => array('lydian minor','mela risabhapriya (62)'),
		1497 => 'mela jyotisvarupini (68)',
		1499 => 'bebop locrian',
		1619 => 'prometheus neopolitan',
		1621 => 'prometheus',
		1643 => array('locrian natural 6', 'locrian sharp 6'),
		1651 => 'oriental (b)',
		1701 => 'dominant 7th',
		1703 => 'mela vanaspati (4)',
		1707 => array('javanese (pelog)','mela natakapriya (10)','pelog (javanese)'),
		1709 => array('dorian','kafi theta','mela kharaharapriya (22)'),
		1711 => 'jewish (adonai malakh)',
		1715 => 'mela chakravakam (16)',
		1717 => array('mixolydian', 'khamaj theta', 'mela harikambhoji (28)'),
		1721 => 'mela vagadhisvari (34)',
		1725 => 'bebop dorian',
		1735 => 'mela navanitam (40)',
		1739 => 'mela sadvidhamargini (46)' 	,
		1741 => array('ukranian dorian','romanian scale','altered dorian','roumanian minor','mela hemavati (58)'),
		1747 => 'mela ramapriya (52)',
		1749 => array('acoustic', 'lydian dominant','mela vaschaspati (64)','overtone dominant','overtone',),
		1753 => array('hungarian major','mela nasikabhusani (70)'),
		1755 => array('octatonic', 'second mode of limited transposition','auxiliary diminished blues'),
		2257 => array('hirajoshi', 'chinese', 'augmented'),
		2275 => 'fifth mode of limited transposition',
		2419 => 'persian',
		2457 => 'augmented',
		2471 => 'mela ganamurti (3)',
		2475 => array('minor neapolitan','mela dhenuka (9)','neopolitan'),
		2477 => array('harmonic minor','mela kiravani (21)','mohammedan'),
		2483 => array('enigmatic', 'flamenco mode', 'double harmonic', 'bhairav theta', 'byzantine','double harmonic','hungarian gypsy persian','mela mayamalavagaula (15)'),
		2485 => 'mela sarasangi (27)',
		2489 => 'mela gangeyabhusani (33)',
		2503 => 'mela jhalavarali (39)',
		2507 => array('mela subhapantuvarali (45)','todi theta'),
		2509 => array('hungarian minor','hungarian gypsy','mela simhendramadhyama (57)'),
		2515 => array('mela kamavarardhani (51)','purvi theta'),
		2517 => 'mela latangi (63)',
		2521 => 'mela dhatuvardhani (69)',
		2535 => 'fourth mode of limited transposition',
		2541 => 'algerian',
		2727 => 'mela manavati (5)',
		2731 => array('major neapolitan','mela kokilapriya (11)','neoploitan major'),
		2733 => array('heptatonia seconda', 'melodic minor','ascending melodic minor', 'jazz minor', 'hawaiian', 'mela gaurimanohari (23)'),
		2739 => 'mela suryakantam (17)',
		2741 => array('major' ,'ionian', 'bilaval theta', 'ethiopian (a raray)', 'mela dhirasankarabharana'),
		2745 => 'mela sulini (35)',
		2759 => 'mela pavani (41)',
		2763 => 'mela suvarnangi (47)',
		2765 => 'mela dharmavati (59)' 			,
		2771 => array('marva theta', 'mela gamanasrama (53)'),
		2773 => array('lydian', 'kalyan theta','mela mechakalyani (65)'),
		2777 => array('lydian #2', 'mela kosalam (71)'),
		2805 => 'japanese (ichikosucho)',
		2869 => array('ionian augmented', 'ionian #5'),
		2901 => 'lydian augmented',
		2925 => 'auxiliary diminished',
		2925 => array('diminished','arabian a'),
		2989 => 'bebop minor',
		2997 => array('bebop major'),
		3037 => 'nine tone scale',
		3055 => 'seventh mode of limited transposition',
		3239 => 'mela tanarupi (6)',
		3243 => 'mela rupavati (12)',
		3245 => 'mela varunapriya (24)',
		3251 => 'mela hatakambari (18)',
		3253 => 'mela naganandini (30)',
		3257 => 'mela chalanata (36)',
		3271 => 'mela raghupriya (42)',
		3275 => 'mela divyamani (48)',
		3277 => 'mela nitimati (60)',
		3283 => 'mela visvambari (54)',
		3285 => 'mela chitrambari (66)',
		3289 => 'mela rasikapriya (72)',
		3411 => 'enigmatic',
		3413 => 'leading whole tone',
		3419 => 'jewish (magan abot)',
		3445 => 'sixth mode of limited transposition',
		3549 => 'third mode of limited transposition',
		3765 => 'bebop dominant',
		3829 => 'japanese (taishikicho)',
		4095 => 'chromatic',
	);

	public static function justThePopularOnes() {
		$a = array(273, 585, 661, 859, 1193, 1257, 1365, 1371, 1387, 1389, 1397, 1451, 1453, 1459, 1485, 1493, 1499, 1621, 1643, 1709, 1717, 1725, 1741, 1749, 1753, 1755, 2257, 2275, 2457, 2475, 2477, 2483, 2509, 2535, 2731, 2733, 2741, 2773, 2777, 2869, 2901, 2925, 2925, 2989, 2997, 3055, 3411, 3445, 3549, 3669, 3765, 4095);
		return array_intersect_key(self::$scaleNames, array_fill_keys($a, true));
	}

	/**
	 * Constructor.
	 *
	 * @param int|string        $scale     [description]
	 * @param Pitch|string|null $root      [description]
	 * @param string            $direction [description]
	 */
	public function __construct($scale, $root = null, $direction = self::ASCENDING) {
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
	 * accepts the scale object in the form of an array
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
	public static function constructFromString($string, $root, $direction = self::ASCENDING) {
		$scale = self::resolveScaleFromString($string);
		return new Scale($scale, $root, $direction);
	}

	/**
	 * Scales are sometimes expressed as a stack of intervals ascending.
	 * accept an interval pattern like "2122122" and figure out what scale that is.
	 * @param  string $structureString
	 * @return [type]                  [description]
	 */
	public static function constructFromIntervalPattern($patternString) {
		$scale = self::resolveScaleFromIntervalPattern($patternString);
		return new Scale($scale, $root, $direction);
	}

	/**
	 * accepts an array of pitches, and will tell you what scale it is. If root is not provided,
	 * tries to figure out what the tonic is based on note distribution.
	 * @param  [type] $pitches [description]
	 * @return [type]          [description]
	 */
	public static function constructFromPitches($pitches, $root = null) {
		$scale = self::resolveScaleFromPitches($pitches);
		return new Scale($scale, $root, $direction);
	}

	/**
	 * accepts a string like "C sharp mixolydian" or "Ab"
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 * @todo
	 */
	public static function resolveScaleFromString($string) {

	}

	/*
	 * accept an interval pattern like "2122122" as a string, figures out what scale that is.
	 */
	public static function resolveScaleFromIntervalPattern($string) {
		$intervals = str_split($string);
		if (array_sum($intervals) != 12) {
			throw new \Exception('invalid interval pattern - intervals do not sum to an octave');
		}
		// remove the last interval
		array_pop($intervals);
		$i = 0;
		$scalebits = 1; // turn on the root bit
		foreach ($intervals as $interval) {
			$i += $interval;
			$scalebits = ($scalebits | (1 << ($i)));
		}
		return $scalebits;
	}

	/**
	 * if root is null, assume that the lowest pitch is the root.
	 * root doesn't need to be one of the pitches
	 * @param  [type] $pitches [description]
	 * @param  [type] $root    [description]
	 * @return [type]          [description]
	 */
	public static function resolveScaleFromPitches($pitches, $root = null) {

	}

	/**
	 * gets pitches in sequence for the scale, of one octave
	 * todo: make this better
	 */
	function getPitches() {
		if (empty($this->root)) {
			throw new \Exception('Can not get pitches for a rootless scale');
		}
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
		$pitches = $this->normalizeScalePitches($pitches);

		return $pitches;
	}

	function type() {
		$types = array(
			3 => 'tritonic',
			4 => 'tetratonic',
			5 => 'pentatonic',
			6 => 'hexatonic',
			7 => 'heptatonic',
			8 => 'octatonic',
			9 => 'nonatonic',
			10 => 'decatonic',
			11 => 'ginantonic',
			12 => 'dodecatonic',
		);
		$num = $this->countTones();
		if (isset($types[$num])) {
			return $types[$num];
		}
		return null;
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
	public function normalizeScalePitches($pitches) {
		if ($this->isHeliotonic()) {
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


	public function isTrueScale() {
		return $this->hasRootTone() && $this->doesNotHaveFourConsecutiveOffBits();
	}

	/**
	 * We will conveniently use the definition that a heliotonic scale is a heptatonic scale that
	 * can be written with one tone on each step; so each tone gets its own letter name. This is useful when
	 * we are figuring out the enharmonic spelling of an altered note
	 */
	public function isHeliotonic() {
		// let's at least make an attempt to do this programatically
		if (!$this->isHeptatonic()) {
			return false;
		}

		// are any of the scale degrees more than 2 alterations away from the major scale?
		$major = array(0,2,4,5,7,9,11);
		$tones = $this->getTones();
		foreach ($tones as $index => $tone) {
			if (abs($tone - $major[$index]) > 2) {
				return false;
			}
		}
		return true;

		// here's our backup list in case the above doesn't work
		return in_array($this->scale, array(1387,1451,1709,1717,2773,2477,2741,1453,2777,1741,1395,2745));
	}

	public function isHeptatonic() {
		return $this->countTones() == 7;
	}

	/**
	 * return the levenshtein distance between two scales (a measure of similarity)
	 * accepts scale numbers, not Scale objects!
	 *
	 * @param  Scale $scale1 the first scale number
	 * @param  Scale $scale2 the second scale number
	 * @return int    Levenshtein distance between the two scales
	 */
	public static function levenshteinScale($scale1, $scale2) {
		$distance = 0;
		$d = $scale1 ^ $scale2;
		for ($i=0; $i<12; $i++) {
			if (
				($d & (1 << ($i))) && ($d & (1 << ($i+1)))
				&&
				($scale1 & (1 << ($i))) != ($scale1 & (1 << ($i+1)))
			) {
				$distance++;
				$d = $d & ( ~ (1 << ($i)));
				$d = $d & ( ~ (1 << ($i+1)));
			}
		}
		for ($i=0; $i<12; $i++) {
			if (($d & (1 << ($i)))) {
				$distance++;
			}
		}
		return $distance;
	}


	/**
	 * Static function: pass in a note, measure, layer etc and get back an array of scales that all the pitches conform to.
	 *
	 * @param  PMTObject   Note, Chord, Layer, Measure $obj the thing that has pitches in it, however deep they may be
	 * @param  bool $namedOnly  only return scales that have names
	 * @return array of Scales
	 */
	public static function getScales($obj, $namedOnly = true) {
		$pitches = $obj->getAllPitches();
		// todo figure out how to do this efficiently
	}


	/**
	 *
	 * @todo  I think this could be done using a rotation and XOR bitwise logic... investigate that
	 */
	public function imperfections() {
		$imperfections = array();
		for ($i = 0; $i<12; $i++) {
			$fifthAbove = ($i + 7) % 12;
			if ($this->scale & (1 << $i) && !($this->scale & (1 << $fifthAbove))) {
				$imperfections[] = $i;
			}
		}
		return $imperfections;
	}


	public function name($all = false) {
		if (isset(self::$scaleNames[$this->scale])) {
			$names = self::$scaleNames[$this->scale];
			if (!is_array($names)) {
				$names = array($names);
			}
			if ($all==true) {
				return $names;
			}
			return $names[0];
		}
		return null;
	}


	/**
	 * returns the spectrum of a scale, ie how many of every type of interval exists between all the tones.
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public function spectrum() {
		$spectrum = array();
		$rotateme = $this->scale;
		for ($i=0; $i<6; $i++) {
			$rotateme = $this->rotateBitmask($rotateme, $direction = 1, $amount = 1);
			$spectrum[$i] = self::countOnBits($this->scale & $rotateme);
		}
		// special rule: if there is a tritone in the sonority, it will show up twice, so we divide by 2
		$spectrum[5] = $spectrum[5] / 2;
		return $spectrum;
	}



	/**
	 * a special rule that some people think defines what a scale is.
	 * returns true if the first bit is not a zero.
	 * Useful for filtering a set of numbers to weed out non-scales
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public function hasRootTone($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
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
	public function doesNotHaveFourConsecutiveOffBits($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
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
	function modes($includeSelf = false) {
		$rotateme = $this->scale;
		$modes = array();
		for ($i = 0; $i < 12; $i++) {
			$rotateme = $this->rotateBitmask($rotateme);
			if (($rotateme & 1) == 0) {
				continue;
			}
			$modes[] = $rotateme;
		}
		if ($includeSelf) {
			$modes[] = $this->scale;
		}
		return $modes;
	}

	/**
	 * finds the notes of a scale that are symmetry axes, ie the roots of modes that are identical the original
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	function symmetries() {
		$rotateme = $this->scale;
		$symmetries = array();
		for ($i = 0; $i < 12; $i++) {
			$rotateme = $this->rotateBitmask($rotateme);
			if ($rotateme == $this->scale) {
				if ($i != 11) {
					$symmetries[] = $i+1;
				}
			}
		}
		return $symmetries;
	}

	/**
	 * returns true if a scale is palindromic.
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
	 * returns true if the scale is chiral.
	 * Chirality means that something is distinguishable from its reflection, and can't be transformed into its reflection by rotation.
	 * see: https://en.wikipedia.org/wiki/Chirality_(mathematics)
	 * and http://ianring.com/scales
	 */
	public function isChiral() {
		$reflected = $this->reflectBitmask($this->scale);
		for ($i = 0; $i < 12; $i++) {
			$reflected = $this->rotateBitmask($reflected, 1, 1);
			if ($reflected == $this->scale) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Returns a new Scale, which is the enantiomorph of this one.
	 * The enantiomorph of a scale is its mirror image. In the case of a palindromic scale, the enantiomorph is itself.
	 * @todo
	 */
	public function enantiomorph() {
		$scale = $this->reflectBitmask($this->scale);
		$scale = $this->rotateBitmask($scale, -1, 1);
		return new \ianring\Scale($scale, $this->root, $this->direction);
	}

	/**
	 * Returns all the scales that this one can be transformed into by one addition, deletion, or
	 * having one tone shifted up or down by one semitone. In other words, it returns all the scales
	 * with a levenshtein distance of 1.
	 */
	function neighbours() {
		$near = array();
		// start at one, because we leave the root alone
		for ($i=1; $i<12; $i++) {
			if ($this->scale & (1 << ($i))) {
				// if this tone is on,
				$copy = $this->scale;

				// turn this tone off,
				$off = $copy ^ (1 << ($i));
				$near[] = $off;

				// move this tone down one semitone
				$copy = $off | (1 << ($i - 1));
				$near[] = $copy;

				// move this tone up one semitone, but be careful not to create an octave
				if ($i != 11) {
					$copy = $off | (1 << ($i + 1));
					$near[] = $copy;
				}
			} else {
				// if this tone is off, then try turning it on
				$copy = $this->scale;
				$copy = $copy | (1 << ($i));
				$near[] = $copy;
			}
		}
		return array_unique($near);
	}


	/**
	 * Returns named chords that contain only notes that are included in this scale
	 *
	 */
	function chordNames() {

	}

    /**
     * This method constructs tertiary triads built on each member of the scale.
     * For example when given a major scale, this should return

     * scale: 101010110101
     * [
     *                         10010001,     (root tonic triad)
     *                       1000100100,     (minor triad on the second degree)
     *                     100010010000,     (minor triad on the third degree)
     *                   1 001000100000,     (major triad on the fourth degree)
     *                 100 100010000000,     (major triad on the fifth degree)
     *               10001 001000000000,     (minor triad on the sixth degree)
     *              100100 100000000000,     (dim triad on the seventh degree)
     * ]
     *
     */
    public function getChordBitMasks() {
    	$chords = array();
    	$tones = $this->getTones();
    	$doubledTones = array_merge(
    		$tones,
	    	array_map(
	    		function($n){return $n + 12;},
	    		$this->getTones()
	    	)
	    );
    	for ($i=0; $i<count($tones); $i++) {
    		// build a triad on the ith degree
    		$triad = 0;
    		$triad = $triad | (1 << $doubledTones[$i]);
    		$triad = $triad | (1 << $doubledTones[$i + 2]);
    		$triad = $triad | (1 << $doubledTones[$i + 4]);
    		$chords[] = $triad;
    	}
    	return $chords;
    }

    /**
     * Returns triads built on each step of a scale. Only works for diatonic scales, and should always
     * render the chords using the right enharmonic spellings.
     */
    public function getTriads() {
    	if (!$this->isHeliotonic()) {
			return null;
		}
    	$pitches = $this->getPitches(); // this step already does the proper enharmonization for spelling
    	$count = count($pitches);

    	// now get the same pitches up an octave
    	$raised = array();
    	foreach ($pitches as $pitch) {
    		$raised[] = new Pitch($pitch->step, $pitch->alter, $pitch->octave + 1);
    	}

    	$pitches = array_merge($pitches, $raised);
    	// var_dump($pitches);

    	$chords = array();
    	for ($i=0; $i<$count; $i++) {
    		// build a triad on the ith degree
    		$triad = Chord::constructFromArray(
    			array(
    				'notes' => array(
    					array(
    						'pitch' => array(
    							'step' => $pitches[$i]->step,
    							'alter' => $pitches[$i]->alter,
    							'octave' => $pitches[$i]->octave
    						)
    					),
    					array(
    						'pitch' => array(
    							'step' => $pitches[$i+2]->step,
    							'alter' => $pitches[$i+2]->alter,
    							'octave' => $pitches[$i+2]->octave
    						)
    					),
    					array(
    						'pitch' => array(
    							'step' => $pitches[$i+4]->step,
    							'alter' => $pitches[$i+4]->alter,
    							'octave' => $pitches[$i+4]->octave
    						)
    					)
    				)
    			)
    		);
    		$chords[] = $triad;
    	}
    	return $chords;
    }

    /**
     * returns something that resembles a pitch class set, with "on" bits as members of an array, like [0,2,4,5,7,9,11]
     */
    public static function bits2Tones($bits) {
    	$tones = array();
    	$n = $bits;
    	$i = 0;
    	while ($n > 0) {
    		if ($bits & (1 << $i)) {
    			$tones[] = $i;
	    		$n = $n & ~(1 << $i); // turn the bit off
    		}
    		$i++;
    	}
    	return $tones;
    }

    /**
     * This returns the *places* where bits are on, as a 0-based set. For example, the
     * binary 101010010001 should return [0, 4, 7, 9, 11]
     * This set of tones is used to construct chords and other useful things
     * unlike some of the other methods, this one should recognize places higher than 12
     */
    public function getTones() {
    	return self::bits2Tones($this->scale);
    }


	/**
	 * counts the number of tones in a scale
	 *
	 * @return [type] [description]
	 */
	public function countTones() {
		return self::countOnBits($this->scale);
	}

	public function scaletype() {
		$types = array(
			4 => 'tetratonic',
			5 => 'pentatonic',
			6 => 'hexatonic',
			7 => 'heptatonic',
			8 => 'octatonic',
			9 => 'nonatonic',
			10 => 'decatonic'
		);
		$numTones = $this->countTones();
		if (isset($types[$numTones])) {
			return $types[$numTones];
		}
		return null;
	}


	/**
	 * counts how many bits are on. Distinct from the Scale::countTones() method, in that this one
	 * accepts a scale argument so you can check the on bits of any scale, not just this one.
	 * ... so should this be a static method?
	 */
	public static function countOnBits($bits) {
		$tones = 0;
		for ($i = 0; $i < 12; $i++) {
			if (($bits & (1 << $i)) > 0) {
				$tones++;
			}
		}
		return $tones;
	}

	/**
	 * Produces the reflection of a bitmask, e.g.
	 * 011100110001 -> 100011001110
	 * see enantiomorph()
	 * ... should this be a static method?
	 */
	function reflectBitmask($scale) {
		$output = 0;
		for ($i = 0; $i < 12; $i++) {
			if ($scale & (1 << $i)) {
				$output = $output | (1 << (11 - $i));
			}
		}
		return $output;
	}

	/**
	 * Accepts a number to use as a bitmask, and "rotates" it. e.g.
	 * 100000000000 -> 000000000001 -> 00000000010 -> 000000000100
	 *
	 * @param  integer $bits     the bitmask being rotated
	 * @param  integer $direction 1 = rotate up, 0 = rotate down
	 * @param  integer $amount    the number of places to rotate by
	 * @return integer            the result after rotation
	 *
	 * ... should this be a static method?
	 */
	function rotateBitmask($bits, $direction = 1, $amount = 1) {
		if ($amount < 0) {
			$amount = $amount * -1;
			$direction = $direction * -1;
		}

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

	/**
	 * returns the interval pattern of a scale. eg a major scale has the pattern [2,2,1,2,2,2]
	 */
	public function intervalPattern() {
		if (!$this->hasRootTone()) {
			throw new \Exception('we do not make patterns for scales with no root tone');
		}
		$tones = $this->getTones();
		$tones[] = 12;
		$pattern = array();
		for ($i=0; $i<(count($tones) - 1); $i++) {
			$pattern[] = $tones[$i+1] - $tones[$i];
		}
		return $pattern;
	}

	public function hemitonicTones() {
		return self::bits2Tones($this->hemitonics());
	}
	public function tritonicTones() {
		return self::bits2Tones($this->tritonics());
	}
	public function cohemitonicTones() {
		return self::bits2Tones($this->cohemitonics());
	}

	/**
	 * returns the bits that have a semitone above them
	 */
	function hemitonics($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		return $this->findIntervalics($scale, 1);
	}

	/**
	 * returns the bits that have a tritone above them
	 */
	function tritonics($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		return $this->findIntervalics($scale, 7);
	}

	/**
	 * returns the bits that have a semitone above them, and a semitone above those two.
	 * how elegant is it that we just call hemitonics() twice recursively? booyah.
	 */
	function cohemitonics($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		return $this->hemitonics($this->hemitonics($this->scale));
	}

	public function isHemitonic() {
		return count($this->hemitonicTones()) > 0;
	}

	public function isCohemitonic() {
		return count($this->cohemitonicTones()) > 0;
	}

	public function isTritonic() {
		return count($this->tritonicTones()) > 0;
	}

	/**
	 * finds tones that have some interval above them, e.g. hemitonics and tritonics
	 */
	private function findIntervalics($scale = null, $interval) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		$rotateme = $scale; // make a copy
		return $scale & ($this->rotateBitmask($rotateme, $direction = 1, $amount = $interval));
	}

	function hemitonia() {
		$hemi = $this->hemitonicTones();
		if (count($hemi) == 0) {
			return 'anhemitonic';
		}
		if (count($hemi) == 1) {
			return 'unhemitonic';
		}
		if (count($hemi) == 2) {
			return 'dihemitonic';
		}
		if (count($hemi) == 3) {
			return 'trihemitonic';
		}
		return 'multihemitonic';
	}

	function cohemitonia() {
		$cohemi = $this->cohemitonicTones();
		if (count($cohemi) == 0) {
			return 'ancohemitonic';
		}
		if (count($cohemi) == 1) {
			return 'uncohemitonic';
		}
		if (count($cohemi) == 2) {
			return 'dicohemitonic';
		}
		if (count($cohemi) == 3) {
			return 'tricohemitonic';
		}
		return 'multicohemitonic';
	}

	/**
	 * returns the polar negative of this scale
	 * that's the scale where all the on bits are off, and the off bits are on. Beware that this produces a non-scale
	 */
	public function negative() {
		$negative = 4095 ^ $this->scale;
		return new Scale($negative);
	}


	/**
	 * returns the inverse of this scale, reflected across the root.
	 * Note the similarities bewtixt this and Pitch::invert() - the main difference being that this
	 * inversion is modulo-12 and works on bits (members of a pitch class set), not actual pitches. As a consequence,
	 */
	public function invert($axis = 0) {
		$inverse = $this->reflectBitmask($this->scale);
		$rotateBy = ($axis * 2) - 11;
		$inverse = $this->rotateBitmask($inverse, $direction = -1, $rotateBy);
		$this->scale = $inverse;
	}



	/**
	 *
	 * see section 3.3 of http://composertools.com/Theory/PCSets.pdf
	 */
	public function primeForm() {

	}

	/**
	 * returns an array of all modal families. ie for each set of scales that are modes of each other, only a single
	 * representative is present.
	 */
	public static function getFamilies($justTrueScales = true) {
		$allscales = range(0, 4095);
		$modelist= array();
		while (count($allscales) > 0) {
			$s = array_pop($allscales);
			$scale = new Scale($s);
			$modes = $scale->modes();
			if ($scale->isTrueScale()) {
				$modelist[] = $s;
			}
			foreach ($modes as $mode) {
				unset($allscales[$mode]);
			}
		}
		return $modelist;
	}


}
