<?php
/**
 * ToneRow Class
 *
 * ToneRow represents a series of pitch class sets in a sequence. Typically, a tone row consists of all 12 tones
 * in shuffled order, but it doesn't necessarily need to have a length of 12. All members of the row are pitch
 * class numbers from 0 to 11. So essentially it's an ordered array of integers.
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
class ToneRow extends PMTObject {

	/**
	 * Constructor.
	 *
	 */
	public function __construct($tones) {
		$this->tones = $tones;
	}

	private static function rotateArray($array, $amount) {
		if ($amount == 0) {
return $array;}
		if ($amount < 0) {
			for ($i=0; $i>$amount; $i--) {
				// off the beginning, put it on the end
				array_push($array, array_shift($array));
			}
		} else {
			for ($i=0; $i<$amount; $i++) {
				// off the end, put it on the beginning
				array_unshift($array, array_pop($array));
			}
		}
		return $array;
	}

	/**
	 * put the last one at the beginning, or vice versa. If $amount is positive, members from the end
	 * are put on the beginning. If $amount is negative, members from the beginning are put on the end.
	 * @return void
	 */
	public function rotateSequence($amount = 1) {
		$this->tones = self::rotateArray($this->tones, $amount);
	}


	/**
	 * divide the tone row into segments (dyads, trichords, tetrachords...), and rotate each one.
	 * e.g. take [0,1,2,3,4,5,6,7,8,9,10,11],
	 * divide into groups of 4: [0,1,2,3],[4,5,6,7],[8,9,10,11]
	 * and rotate those groups: [3,0,1,2],[7,4,5,6],[11,8,9,10]
	 * and put the row back together: [3,0,1,2,7,4,5,6,11,8,9,10]
	 *
	 * If the row has a length of 12, $segmentSize should be coprime with 12, ie must be 1, 2, 3, 4, or 6.
	 * Segment size of 1 is pointless. Negative sizes are meaningless.
	 *
	 * @return [type] [description]
	 */
	public function rotateSequenceSegments($segmentSize, $amount = 1) {
		if (count($this->tones) % $segmentSize != 0) {
			throw new \Exception('row is not evenly divisible into segments of that size');
		}
		if ($segmentSize < 2) {
			throw new \Exception('segment must be greater than 1');
		}
		$chunks = array_chunk($this->tones, $segmentSize);
		foreach ($chunks as $idx => $c) {
			$chunks[$idx] = self::rotateArray($c, $amount);
		}
		$output = array();
		foreach ($chunks as $c) {
			$output = array_merge($output, $c);
		}
		$this->tones = $output;
	}

	/**
	 * Moves pitch classes up or down by a number of semitones. Positive amount means ascending,
	 * negative amount means descending.
	 * @return [type] [description]
	 */
	public function transpose($amount) {
		$func = function($n) use ($amount) {return \ianring\PMTObject::_truemod(($n + $amount), 12);};
		$this->tones = array_map($func, $this->tones);
	}


	/**
	 * inverts the pitches; eg 11 becomes 0, 10 becomes 1 etc.
	 * @return [type] [description]
	 */
	public function invert() {
		$func = function($n){return 11 - $n;};
		$this->tones = array_map($func, $this->tones);
	}

	/**
	 * turns the sequence backwards
	 * @return [type] [description]
	 */
	public function retrograde() {
		$this->tones = array_reverse($this->tones);
	}

	/**
	 * applies multiply mod 12 by a multiplicand to each member of the set. This is the transformation
	 * usually shorthanded as "M" appended with the multiplicand, as in "M7".
	 * "M1" returns the original set, "M11" is the same as inversion.
	 * @return [type] [description]
	 */
	public function multiply($multiplicand) {
		$func = function($n) use ($multiplicand) {return \ianring\PMTObject::_truemod(($n * $multiplicand), 12);};
		$this->tones = array_map($func, $this->tones);
	}


}