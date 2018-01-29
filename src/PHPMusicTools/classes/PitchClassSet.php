<?php
/**
 * PitchClassSet Class
 *
 * PitchClassSet internally represents a PCS the same way that Scale represents a scale; as a bitmask word of 12 bits.
 * Set theory typically calls the first position "0", but in bits that first spot is equal to 1. So we need to be 
 * cognizant of that in our maths.
 *
 * @package      PHPMusicTools
 * @author       Ian Ring <httpwebwitch@email.com>
 */

namespace ianring;
require_once 'PMTObject.php';
require_once 'Utils/BitmaskUtils.php';

/**
 * Accidental Class
 */
class PitchClassSet extends PMTObject {

	/**
	 * Constructor.
	 *
	 */
	public function __construct($bits) {
		$this->bits = $bits;
	}


	/**
	 * constructs the object from an array serialization
	 *
	 * @param  array $props the array of properties
	 * @return Accidental the Accidental object.
	 */
	public static function constructFromTones($tones) {
		$bits = BitmaskUtils::tones2Bits($tones);
		return new PitchClassSet($bits);
	}


	public function tones() {
		return \ianring\BitmaskUtils::bits2Tones($this->bits);
	}

	public function forteNumber() {
	}


	public function carterNumber() {
	}



	private function getInteriorIntervals($bits) {
		$tones = BitmaskUtils::bits2Tones($bits);
		if (count($tones) == 0) {
			return 0;
		}
		sort($tones);
		$lowestTone = $tones[0];
		array_walk($tones, function(&$item, $key, $lowestTone) {
			$item = $item - $lowestTone;
		}, $lowestTone);
		// express it as intervals; add a final interval so it reaches an octave
		$intervals = array();
		for ($i=0; $i<count($tones)-1; $i++) {
			$intervals[] = $tones[$i+1] - $tones[$i];
		}
		$intervals[] = 12 - $tones[count($tones)-1];
		return $intervals;
	}

	private function getIntervalsOfAllInversionsAndRotations($bits) {
		$rotations = array();
		$intervals = $this->getInteriorIntervals($bits);
		// create rotations of this set of intervals
		$count = count($intervals);
		for($i=0; $i<$count; $i++) {
			$rotations[] = $intervals;
			array_push($intervals, array_shift($intervals));
		}
		// invert, and get those ones too
		$inversion = \ianring\BitmaskUtils::reflectBitmask($bits);
		$iIntervals = $this->getInteriorIntervals($inversion);
		$count = count($iIntervals);
		for($i=0; $i<$count; $i++) {
			$rotations[] = $iIntervals;
			array_push($iIntervals, array_shift($iIntervals));
		}
		return $rotations;
	}


	public static function isPrime($bits, $algo = 'forte') {
		$pcs = new PitchClassSet($bits);
		return $pcs->bits == $pcs->primeFormForte();
	}

	/**
	 * returns the prime form of this PCS according to Forte's algorithm. Inversions are excluded as duplicates!
	 * @return int 
	 */
	public function primeFormForte() {

		// shortcut
		if ($this->bits == 0) {return 0;}

		$rotations = $this->getIntervalsOfAllInversionsAndRotations($this->bits);

		// now sort all the PCSs for leftmost density
		usort($rotations, function($a, $b){
			// biggest interval at the end,
			if ($a[count($a)-1] !== $b[count($b)-1]) {
				return $a[count($a)-1] < $b[count($b)-1];
			}
			// smaller interval at the beginning
			for ($i=0; $i<count($a); $i++) {
				if ($a[$i] !== $b[$i]) {
					return $a[$i] > $b[$i];
				}
			}
			return 0;
		});
		$primeRotation = $rotations[0];

		// turn the intervals back into tones
		$p = 0;
		$tones = array(0);
		for ($i=0; $i<count($primeRotation)-1; $i++) {
			$p += $primeRotation[$i]; // add the interval...
			$tones[] = $p;
		}

		return \ianring\BitmaskUtils::tones2Bits($tones);
	}


	private function modintdiff($a, $b) {
		return min($a-$b, $b-$a);
	}


	/**
	 * returns the prime form of this PCS according to Rahn's algorithm
	 * @see "The Interger Model Of Pitch", Basic Atonal Theory, John Rahn p.35 ISBN 0-02-873-160-3
	 */
	public function primeFormRahn() {
		// shortcut
		if ($this->bits == 0) {return 0;}

		$bits = \ianring\BitmaskUtils::moveDownToRootForm($this->bits);

		$rotations = array();

		$count = \ianring\BitmaskUtils::countOnBits($bits);
		if ($count == 1) {
			return $bits;
		}
		for ($i=0; $i<$count; $i++) {
			$rotations[] = $bits;
			$tones = \ianring\BitmaskUtils::bits2Tones($bits);
			$first = $tones[1];
			$bits = \ianring\BitmaskUtils::rotateBitmask($bits, 1, $first);
		}

		$bits = \ianring\BitmaskUtils::moveDownToRootForm(\ianring\BitmaskUtils::reflectBitmask($this->bits));
		for ($i=0; $i<$count; $i++) {
			$rotations[] = $bits;

			$tones = \ianring\BitmaskUtils::bits2Tones($bits);
			$first = $tones[1];

			$bits = \ianring\BitmaskUtils::rotateBitmask($bits, 1, $first);
		}
		usort($rotations, function($a, $b) {
			if ($a == $b) {return 0;}
			$atones = \ianring\BitmaskUtils::bits2Tones($a);
			$btones = \ianring\BitmaskUtils::bits2Tones($b);
			for ($i = count($atones) - 1; $i>0; $i--) {

				$diffa = \ianring\PMTObject::_truemodDiff12($atones[$i], $atones[0]);
				$diffb = \ianring\PMTObject::_truemodDiff12($btones[$i], $btones[0]);
				if ($diffa !== $diffb) {
					return $diffa < $diffb;
				}
			}
			return 1;
		});
		return $rotations[0];
	}

	/**
	 * returns the prime form of this PCS according to Ring's criteria (simply the bitmask with the lowest value)
	 */
	public function primeFormRing() {
		// shortcut
		if ($this->bits == 0) {return 0;}

		$bits = \ianring\BitmaskUtils::moveDownToRootForm($this->bits);

		$rotations = array();

		$count = \ianring\BitmaskUtils::countOnBits($bits);
		if ($count == 1) {
			return $bits;
		}
		for ($i=0; $i<$count; $i++) {
			$rotations[] = $bits;
			$tones = \ianring\BitmaskUtils::bits2Tones($bits);
			$first = $tones[1];
			$bits = \ianring\BitmaskUtils::rotateBitmask($bits, 1, $first);
		}

		$bits = \ianring\BitmaskUtils::moveDownToRootForm(\ianring\BitmaskUtils::reflectBitmask($this->bits));
		for ($i=0; $i<$count; $i++) {
			$rotations[] = $bits;

			$tones = \ianring\BitmaskUtils::bits2Tones($bits);
			$first = $tones[1];

			$bits = \ianring\BitmaskUtils::rotateBitmask($bits, 1, $first);
		}


		usort($rotations, function($a, $b) {
			return $a > $b;
		});
		return $rotations[0];
	}

	/**
	 * returns the interval vector as an array of integers
	 * @return array
	 */
	public function intervalVector() {
		return \ianring\BitmaskUtils::spectrum($this->bits);		
	}


	public function normalMatrix() {
	}

	/**
	 * returns the cardinality of the pitch class set, aka the number of tones in it
	 *
	 * @return int
	 */
	public function cardinality() {
		return \ianring\BitmaskUtils::countOnBits($this->bits);
	}

	/**
	 * @return string
	 */
	public static function cardinalityTerm($card) {
		$terms = array(
			2 => 'dyad',
			3 => 'trichord',
			4 => 'tetrachord',
			5 => 'pentachord',
			6 => 'hexachord'
		);
		if (in_array($card, $terms)) {
			return $terms[$card];
		}
		return null;
	}

	/**
	 * When two prime forms have the same internal vector, and when one can not be reduced to the other 
	 * by inversion or transposition, they are said to be "Z-Related". 
	 * @return int
	 */
	public function zRelations($usecached = true) {
		// this is a fairly expensive calculation, so by default we'll use this cached array that we calculated earlier using the algorithm below
		if ($usecached) {
			include(__DIR__.'/../cache/zmates.cache.php');
			if (array_key_exists($this->bits, $zmates)) {
				return $zmates[$this->bits];
			}
			return null;
		}

		// if not using the cached calculations, we'll do this the serious brute force way with a couple of optimizations
		$intervals = $this->intervalVector();
		$countOnBits = \ianring\BitmaskUtils::countOnBits($this->bits);

		// we can omit any that are too small to have the required number of bits on, for example if the set has a cardinality
		// of 6, the minimum value it can have is 111111 (63). That's always 2^n-1
		$minimum = pow(2, $countOnBits) - 1;

		$same = array();
		$all = range($minimum, 4095);
		foreach($all as $s) {
			// for every set in the power set, we perform some exclusions in the order of least to most complexity

			// a z-mate will have the same pitch cardinality. If this one doesn't, keep looking
			if (\ianring\BitmaskUtils::countOnBits($s) != $countOnBits) {
				continue;
			}

			// a z-mate will not be a rotation,
			if (\ianring\BitmaskUtils::isRotationOf($this->bits, $s)) {
				continue;
			}
			// a z-mate will not be a rotation of an inversion either
			$invertedS = \ianring\BitmaskUtils::reflectBitmask($s);
			if (\ianring\BitmaskUtils::isRotationOf($this->bits, $invertedS)) {
				continue;
			}
			// we only return the prime of the z-mate
			if (!self::isPrime($s)) {
				continue;
			}

			$p = new PitchClassSet($s);
			$i = $p->intervalVector();
			if (json_encode($i) == json_encode($intervals)) {
				$same[] = $s;
			}
		}

		return $same;

	}

	/**
	 * @see http://lulu.esm.rochester.edu/rdm/pdflib/set-class.table.pdf
	 */
	public function invarianceVector() {

	}

	public function tnInvariance() {
	}

	public function tniInvariance() {
	}

	public function applyTransformation($t) {

	}

	/**
	 * Returns the PitchClassSetTransformation for what it would take to transform $set1 into $set2.
	 * For example, a rotation of one semitone is "T1". An inversion and rotation of five semitones is "T5I".
	 * @param int set1 a bitmask PCS
	 * @param int set2 a bitmask PCS
	 * @return PitchClassSetTransformation
	 */
	public static function getTransformation($set1, $set2) {
		$pcs1 = new PitchClassSet($set1);
		$t = $pcs1->getAllTransformations();
		$result = array_search($set2, $t);
		if (false === $result) {
			return null;
		}
		return $result;
	}


	/**
	 * returns all the transformations, with the transformation name as its key e.g. T3, T4, T3I, T4I
	 * @return array
	 */
	public function getAllTransformations() {
		$t = array();
		$bits = $this->bits;
		$t['T0'] = $bits;
		for ($i=1;$i<12;$i++) {
			$bits = \ianring\BitmaskUtils::rotateBitmask($bits, $direction = 1, $amount = 1);
			$t['T' . $i] = $bits;
		}
		
		$bits = \ianring\BitmaskUtils::reflectBitmask($this->bits);
		$t['T0I'] = $bits;
		for ($i=1;$i<12;$i++) {
			$bits = \ianring\BitmaskUtils::rotateBitmask($bits, $direction = 1, $amount = 1);
			$t['T' . $i . 'I'] = $bits;
		}
		return $t;
	}

	/**
	 * returns the PCS that is the inverse of this one, that means it's reversed
	 *
	 * @return PitchClassSet
	 */
	public function invert() {

	}

	/**
	 * The complement of a PCS is one where all the off bits are on, and the on bits are off.
	 * This returns the complement in its prime form, aka an "abstract complement" (as opposed to a 
	 * literal complement)
	 * @see http://composertools.com/Theory/PCSets/PCSets7.htm
	 * @return int
	 */
	public function complement() {
		return 4095 ^ $this->bits;
	}

	/**
	 * returns true if the argument is a complement of this PCS (ie it is a rotation of this set's literal complement)
	 * @return bool
	 */
	public function isComplement($b) {

	}

	/**
	 * returns true if the number passed in is a rotation of this PCS
	 * @return bool
	 */
	public function isRotation($b) {

	}

	/**
	 * A scale whose interval vector has six unique digits is said to have the "deep scale" property.
	 * @return bool 
	 */
	public function isDeepScale() {
		$v = $this->intervalVector();
		return count($v) == count(array_unique($v));
	}

	/**
	 * Myhill's property is the quality of musical scales or collections with exactly two specific intervals for every generic interval
	 * @see  https://en.wikipedia.org/wiki/Generic_and_specific_intervals
	 * @return boolean [description]
	 */
	public function hasMyhillProperty() {
		$spectrum = $this->spectrum();
		foreach($spectrum as $s) {
			if (count($s) != 2) {
				return false;
			}
		}
		return true;
	}


	public function hasMaximalEvenness() {

	}

	/**
	 * A voice leading transform is one where an integer transposition is applied to each member of the PCS. 
	 * For example, consider the PCS of a major triad, [0,4,7]. We can transform that into a minor triad by 
	 * transposing the second tone down a semitone, so the 4 becomes a 3, and the result is [0,3,7].
	 * The "voice leading transformation" in this case is [0,-1,0].
	 * 
	 * @see http://dmitri.mycpanel.princeton.edu/files/publications/fourier.pdf
	 * 
	 */
	public function voiceLeadingTransform($transformation) {

	}

	/**
	 * returns boolean, indicating whether a number appears x times in a set's matrix, where x is the length of the set
	 * @see http://www.jaytomlin.com/music/settheory/help.html#forte
	 */
	public function combinatoriality() {

	}

	/**
	 * returns the spectra of the PCS, as described by Krantz & Douthett.
	 * @see http://archive.bridgesmathart.org/2005/bridges2005-255.pdf
	 * 
	 * the 1th element in the array is the distinct lengths between "neighbours" (dupes removed).
	 * the 2nd element is the distinct specific lengths between "next nearest neighbours" (two tones away)
	 * the 3rd element is the distinct specific lengths between "next next nearest neighbours" (three tones away), and so on. 
	 * So for a PCS with 7 tones, we can measure the distance to the next, next, next, next up to six times to find the 
	 * distance to its farthest neighbour. Thus a 7 tone PCS will have 6 elements in its spectrum.
	 *
	 * In the literature, this is notated like:
	 *     <1> = {1,6}
	 *     <2> = {2,7}
	 * 
	 * for example, the spectrum for the major scale is this:
	 * array(
			1 => array(1,2),
			2 => array(3,4),
			3 => array(5,6),
			4 => array(6,7),
			5 => array(8,9),
			6 => array(10,11),
		)
	 *
	 */
	public function spectrum() {
		// echo "\n";
		$spectrum = array();
		$countOnBits = \ianring\BitmaskUtils::countOnBits($this->bits);
		// echo 'countOnBits = '.$countOnBits."\n";
		$tones = \ianring\BitmaskUtils::bits2Tones($this->bits);

		for ($gd = 1; $gd < $countOnBits; $gd++) {
			// echo 'gd = '.$gd."\n";
			$spectrum[$gd] = array();

			for ($i = 0; $i < $countOnBits; $i++) {
				// echo 'i = '.$i."\n";
				// find the specific distance between each tone and the one that is $d "generic distance" away
				$tone1 = \ianring\BitmaskUtils::nthOnBit($this->bits, $i);
				$tone2 = \ianring\BitmaskUtils::nthOnBit($this->bits, $i+$gd);

				// echo 'tone1 = '.$tone1."\n";
				// echo 'tone2 = '.$tone2."\n";

				$dist = \ianring\BitmaskUtils::circularDistance($tone1, $tone2);
				// echo 'dist = '.$dist."\n";
			 	$spectrum[$gd][$dist] = true;
			}
			$spectrum[$gd] = array_keys($spectrum[$gd]);
			sort($spectrum[$gd]);
		}
		return $spectrum;
	}

	/**
	 * Coherence means that a scale/pcs has an unambiguous relationships between specific intervals and generic distances
	 * @return boolean [description]
	 */
	public function isCoherent() {
		$spectrum = $this->spectrum();
		$maxSpecificInterval = 0;
		foreach($spectrum as $g=>$s) {
			if (min($s) <= $maxSpecificInterval) {
				return false;
			}
			$maxSpecificInterval = max($s);
		}
		return true;
	}

	/**
	 * returns the spectrum widths for a PCS. Spectrum widths are used to calculate the "evenness" of pitch distribution.
	 * The "spectrum width" is the difference between the largest and smallest member of the spectrum I.
	 * 
	 * In the literature, This width is notated as a "delta", ∆
	 * So for example if the spectrum is 
	 *     <1> = {1,3,4}
	 *     <2> = {4,5,6}
	 * Then we would describe the spectrum widths thusly:
	 *     ∆(1) = 3
	 *     ∆(2) = 2
	 * 
	 */
	public function spectrumWidth() {
		$spectrum = $this->spectrum();
		$output = array();
		foreach($spectrum as $len => $spec) {
			$max = max($spec);
			$min = min($spec);
			$output[$len] = $max - $min;
		}
		return $output;
	}

	public function spectraVariation() {
		$countOnBits = \ianring\BitmaskUtils::countOnBits($this->bits);
		if ($countOnBits == 0) {return 0;}
		$totalwidths = array_sum($this->spectrumWidth());
		return $totalwidths / $countOnBits;
	}

	/**
	 * A set is maximally even if every spectrum consists of either one number, or two consecutive numbers.
	 * @return boolean [description]
	 */
	public function isMaximallyEven() {
		$spectrum = $this->spectrumWidth();
		foreach($spectrum as $s) {
			if ($s > 1) {
				return false;
			}
		}
		return true;
	}


}
