<?php
/**
 * Whilst a Pitch represents the conceptual notion of a sound's height, the frequency is a
 * specific scientific property of something's vibration, as measured in Hertz (cycles per second).
 *
 * The same pitch, for example the A above middle C, might have different frequency in different
 * tuning systems. This class encapulates methods that let us play with frequencies.
 */
namespace ianring;
require_once 'PMTObject.php';

class Frequency extends PMTObject
{

	/**
	 * constructor
	 * @param int $frequency  in Hertz
	 */
	public function __construct($frequency) {
		$this->frequency = $frequency;
	}

	/**
	 *
     * @param  $n the number of a harmonic, e.g. 1 = 1st, 2 = 2nd...
     * @return Frequency  a new Frequency object which represents that harmonic of the root
	 */
	public function getHarmonic($n) {
		if (!is_integer($n) || $n < 0) {
			throw new Exception('harmonic must be a positive integer');
		}
		return $this->frequency * $n;
	}

	/**
	 * returns a harmonic divisor for a group of frequencies which might be perceived
	 * as a ghost fundamental
	 */
	public static function getFundamental($fz, $max = 30, $fuzz = 0) {
		if (!is_array($fz)) {
			$fz = array($fz);
		}
		sort($fz);

		for ($i = 1; $i < $max; $i++) {
			$F = $fz[0] / $i;
			for ($j = 1; $j < count($fz); $j++) {
				$partial = $fz[$j] / $F;
				if (abs($partial - round($partial)) > $partial * $fuzz) {
					continue 2;
				}
			}
			return $F;
		}
		return null;
	}

}