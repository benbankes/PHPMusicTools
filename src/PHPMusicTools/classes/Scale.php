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
 *
 * In order for this class to be useful and flexible, we should not impose limitations on our definition of a 
 * scale; e.g. it is not necessary for a Scale to have the root bit (1) on, nor will we mind if the scale has
 * leaps greater than 4 semitones. We could have a Scale object (which identifies the set of tones), and then
 * use its methods to determine if it is a "scale" according to the definition we desire.
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
		661 => array('chinese mongolian','diatonic'),
		677 => 'pentatonic major',
		819 => 'six tone symmetrical',
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
		1186 => 'insen',
		1189 => 'egyptian',
		1193 => 'pentatonic minor',
		1235 => 'tritone scale',
		1257 => 'blues',
		1365 => array('auxiliary augmented','whole tone'),
		1371 => array('altered', 'diminished whole tone', 'super locrian'),
		1387 => array('half diminished (locrian)', 'locrian'),
		1389 => array('half diminished', 'locrian #2'),
		1395 => 'oriental (a)',
		1397 => array('arabian b', 'major locrian'),
		1403 => 'eight tone spanish',
		1447 => 'mela ratnangi (2)',
		1451 => array('bhairavi theta', 'mela hanumattodi (8)', 'neopolitan minor', 'phrygian'),
		1453 => array('aeolian', 'natural minor', 'asavari theta', 'ethiopian (geez & ezel)', 'mela natabhairavi (20)', 'pure minor'),
		1459 => array('mela vakulabharanam (14)', 'spanish gypsy','phrygian dominant', 'spanish romani', 'jewish (ahaba rabba)'),
		1461 => array('hindu','hindustan','mela charukesi (26)'),
		1465 => 'mela ragavardhani (32)',
		1479 => 'mela jalarnavam (38)',
		1483 => 'mela bhavapriya (44)',
		1485 => array('lydian diminished','mela sanmukhapriya (56)','aolian #4', 'romani scale'),
		1491 => 'mela namanarayani (50)',
		1493 => array('lydian minor','mela risabhapriya (62)'),
		1497 => 'mela jyotisvarupini (68)',
		1619 => 'prometheus neopolitan',
		1621 => 'prometheus',
		1651 => 'oriental (b)',
		1701 => 'dominant 7th',
		1703 => 'mela vanaspati (4)',
		1707 => array('javanese (pelog)','mela natakapriya (10)','pelog (javanese)'),
		1709 => array('dorian','kafi theta','mela kharaharapriya (22)'),
		1711 => 'jewish (adonai malakh)',
		1715 => 'mela chakravakam (16)',
		1717 => array('mixolydian', 'khamaj theta', 'mela harikambhoji (28)'),
		1721 => 'mela vagadhisvari (34)',
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
		2475 => array('mela dhenuka (9)','minor neapolitan','neopolitan'),
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
		2777 => 'mela kosalam (71)',
		2805 => 'japanese (ichikosucho)',
		2901 => 'lydian augmented',
		2925 => 'auxiliary diminished',
		2925 => array('diminished','arabian a'),
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
		3669 => 'prometheus',
		3765 => 'bebop dominant',
		3829 => 'japanese (taishikicho)',
		4095 => 'chromatic',
	);

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
	public static function constructFromString($string, $root, $direction = self::ASCENDING) {
		$scale = self::resolveScaleFromString($string);
		return new Scale($scale, $root, $direction);
	}

	/**
	 * Scales are sometimes expressed as a stack of intervals ascending.
	 * accept a structure like "2122122" and figure out what scale that is.
	 * @param  string $structureString 
	 * @return [type]                  [description]
	 */
	public static function constructFromStructure($structureString) {
		$scale = self::resolveScaleFromStructure($structureString);
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
	 * accept a structure like "2122122" and figure out what scale that is.
	 */
	public static function resolveScaleFromStructure($string) {
		$intervals = str_split($string);
		if (array_sum($intervals) != 12) {
			throw new Exception('invalid structure - intervals do not sum to an octave');
		}
		// remove the last interval
		array_pop($intervals);
		$i = 0;
		$scalebits = 1; // turn on the root bit
		foreach($intervals as $interval) {
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
			$spectrum[$i] = $this->countOnBits($this->scale & $rotateme);
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
	function modes() {
		$rotateme = $this->scale;
		$modes = array();
		for ($i = 0; $i < 12; $i++) {
			$rotateme = $this->rotateBitmask($rotateme);
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
	 * counts the number of tones in a scale
	 *
	 * @return [type] [description]
	 */
	public function countTones() {
		return $this->countOnBits($this->scale);
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


	/**
	 * counts how many bits are on. Distinct from the Scale::countTones() method, in that this one
	 * accepts a scale argument so you can check the on bits of any scale, not just this one.
	 * ... so should this be a static method?
	 */
	public function countOnBits($bits) {
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
	 * generates an SVG representation of a scale bracelet. tries to make it look decent at various sizes.
	 * 
	 * @param  integer $scale             the scale being represented, ie a bitmask integer
	 * @param  integer $size              size in pixels
	 * @param  string  $text              if present, puts text in the middle of the bracelet
	 * @param  boolean $showImperfections if true, puts an "i" on imperfect notes in the scale
	 * @return string                     SVG as a string that you can insert into an HTML page
	 */
	function drawSVGBracelet($scale, $size = 200, $text = null, $showImperfections = false) {
		if ($showImperfections) {
			$imperfections = $this->findImperfections($scale);
			$symmetries = $this->symmetries($scale);
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
