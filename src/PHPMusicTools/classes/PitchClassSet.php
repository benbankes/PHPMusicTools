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

	/**
	 * returns the prime form of this PCS according to Forte's algorithm. Inversions are excluded as duplicates!
	 */
	public function primeFormForte() {

		// shortcut
		if ($this->bits == 0) {
			return 0;
		}

		$rotations = array();

		$intervals = $this->getInteriorIntervals($this->bits);

		// create rotations of this set of intervals
		$count = count($intervals);
		for($i=0; $i<$count; $i++) {
			$rotations[] = $intervals;
			array_push($intervals, array_shift($intervals));
		}

		// invert, and get those ones too
		$inversion = \ianring\BitmaskUtils::reflectBitmask($this->bits);
		$iIntervals = $this->getInteriorIntervals($inversion);
		$count = count($iIntervals);
		for($i=0; $i<$count; $i++) {
			$rotations[] = $iIntervals;
			array_push($iIntervals, array_shift($iIntervals));
		}

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

	/**
	 * returns the prime form of this PCS according to Rahn's algorithm
	 */
	private function primeFormRahn() {

	}

	/**
	 * returns the prime form of this PCS according to Ring's criteria (bitmask with the lowest value)
	 */
	private function primeFormRing() {		
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
		return count(\ianring\BitmaskUtils::countOnBits($this->bits));
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
	 * (by inversion or transposition), they are said to be "Z-Related". 
	 */
	public function zRelations() {
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
	 *
	 * @return PitchClassSetTransformation
	 */
	public static function getTransformation($set1, $set2) {

	}

	/**
	 * returns the PCS that is the inverse of this one
	 *
	 * @return PitchClassSet
	 */
	public function invert() {

	}

	/**
	 * The complement of a PCS is one where all the off bits are on, and the on bits are off.
	 *
	 * @return PitchClassSet
	 */
	public function complement() {
	}

	/**
	 * A scale whose interval vector has six unique digits is said to have the "deep scale" property.
	 * @return bool 
	 */
	public function isDeepScale() {

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

}
