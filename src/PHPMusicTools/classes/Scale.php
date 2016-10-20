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
		$pitches = $this->_normalizeScalePitches($pitches);

		return $pitches;
	}


	/**
	 * What this function has got to do is make sure that the C sharp major scale uses an E sharp, not
	 * an F natural. I'm not even sure what is the right way to do that. It should look at the scale
	 * and recognize stepwise movements, and put those on sequential note names (steps) when possible.
	 *
	 * But it should handle complex scales like bebop properly.
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
	 * @return boolean [description]
	 */
	public function isPalindromic() {
		for ($i=1; $i<=5; $i++) {
			if ( (bool)($this->scale & (1 << $i)) !== (bool)($this->scale & (1 << (12 - $i))) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * counts the number of tones in a scale
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
		$radius = floor( ($size - ($smallrad*2) - ($stroke*4)) / 2 );
		$s .= '<svg xmlns="http://www.w3.org/2000/svg" height="'. ($size + 3).'" width="'.($size + 3) .'">';
		$s .= '<circle r="'.$radius.'" cx="'.$centerx.'" cy="'.$centery.'" stroke-width="'.$stroke.'" fill="white" stroke="black"/>';
		$symmetryshape = array();
		for ($i=0; $i<12; $i++) {

			$deg = $i * 30 - 90;
			$x1 = floor($centerx + ($radius * cos( deg2rad($deg))));
			$y1 = floor($centery + ($radius * sin( deg2rad($deg))));

			$innerx1 = floor($centerx + (($radius - $smallrad) * cos( deg2rad($deg))));
			$innery1 = floor($centery + (($radius - $smallrad) * sin( deg2rad($deg))));

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
