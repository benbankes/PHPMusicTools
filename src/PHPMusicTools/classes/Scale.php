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

class Scale extends PMTObject {

	const ASCENDING = 'ascending';
	const DESCENDING = 'descending';

	public static $scaleNames = array(
		273 => 'augmented triad',
		585 => 'diminished seventh',
		1123 => 'iwato',
		1186 => 'insen',
		1365 => 'whole tone',
		1371 => 'altered',
		1387 => 'locrian',
		1389 => 'half diminished',
		1451 => 'phrygian',
		1453 => array('aeolian', 'natural minor'),
		1459 => array('phrygian dominant', 'spanish romani'),
		1485 => array('aolian #4', 'romani scale'),
		1709 => 'dorian',
		1717 => 'mixolydian',
		1741 => array('ukranian dorian','romanian scale','altered dorian'),
		1749 => array('acoustic', 'lydian dominant'),
		2257 => 'hirajoshi',
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
	);

	/**
	 * [__construct description]
	 * @param int|string         $scale     [description]
	 * @param Pitch|string|null  $root      [description]
	 * @param string             $direction [description]
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
	public static function calculateScaleFromPitches($pitches, $root = null) {

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
		return $pitches;
	}

	/**
	 * return the levenshtein distance between two scales (a measure of similarity)
	 * @param  Scale  $scale1  the first scale
	 * @param  Scale  $scale2  the second scale
	 * @return int    Levenshtein distance between the two scales
	 */
	static function levenshtein_scale($scale1, $scale2) {
		// todo
	}

	/**
	 * Static function: pass in a note, measure, layer etc and get back an array of scales that all the pitches conform to.
	 * @param  Note, Chord, Layer, Measure   $obj  the thing that has pitches in it, however deep they may be
	 * @return array of Scales
	 */
	public static function getScales($obj) {
		$pitches = $obj->getAllPitches();
		// todo figure out how to do this efficiently
	}

	public function imperfections() {

	}

	public function modes() {

	}

	public function symmetries() {
		$rotateme = $this->scale;
		$symmetries = array();
		for ($i = 0; $i < 12; $i++) {
			$rotateme = $this->rotate_bitmask($rotateme);
			if (($rotateme & 1) == 0) {
				continue;
			}
			if ($rotateme == $this->scale) {
				if ($i != 11) {
					$symmetries[] = $i+1;
				}
			}
		}
		return $symmetries;
	}

	public function isPalindromic() {
		for ($i=1; $i<=5; $i++) {
			if ( (bool)($this->scale & (1 << $i)) !== (bool)($this->scale & (1 << (12 - $i))) ) {
				return false;
			}
		}
		return true;
	}

	public function countTones() {
		$tones = 0;
		for ($i = 0; $i < 12; $i++) {
			if (($this->scale & (1 << $i)) == 0) {
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

	private function rotate_bitmask($bits, $direction = 1, $howfar = 1) {
		for ($i=0; $i<$howfar; $i++) {
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

}
