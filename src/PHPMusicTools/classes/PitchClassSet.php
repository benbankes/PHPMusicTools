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


	/**
	 * returns the prime form of this PCS according to Forte's algorithm
	 */
	public function primeFormForte() {
		$tones = BitmaskUtils::bits2Tones($this->bits);
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
		$rotations = array();
		$count = count($intervals);
		for($i=0; $i<$count; $i++) {
			$rotations[] = $intervals;
			array_push($intervals, array_shift($intervals));
		}
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


	public function normalForm() {	
	}

	public function internalVector() {
	}

	public function normalMatrix() {
	}

	public function zMate() {
	}

	public function tnInvariance() {
	}

	public function tniInvariance() {
	}

	public function invert() {
	}

	public function complement() {
	}

	/**
	 * A scale whose interval vector has six unique digits is said to have the deep scale property.
	 */
	public function deepScale() {

	}


	/**
	 * returns boolean, indicating whether a number appears x times in a set's matrix, where x is the length of the set
	 * @see http://www.jaytomlin.com/music/settheory/help.html#forte
	 */
	public function combinatoriality() {

	}

}
