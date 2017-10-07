<?php

namespace ianring;

class BitmaskUtils
{

	/**
	 * for example, 432 => [0,3,4]
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
	 * for example, [0,3,4] => 432
	 */
    public static function tones2Bits($tones) {
    	$bits = 0;
    	foreach ($tones as $tone) {
    		$bits += pow(2, $tone);
    	}
    	return $bits;
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
	public static function rotateBitmask($bits, $direction = 1, $amount = 1) {
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
	 * Produces the reflection of a bitmask, e.g.
	 * 011100110001 -> 100011001110
	 * see enantiomorph()
	 */
	public static function reflectBitmask($scale) {
		$output = 0;
		for ($i = 0; $i < 12; $i++) {
			if ($scale & (1 << $i)) {
				$output = $output | (1 << (11 - $i));
			}
		}
		return $output;
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


	public static function spectrum($bits) {
		$spectrum = array();
		$rotateme = $bits;
		for ($i=0; $i<6; $i++) {
			$rotateme = self::rotateBitmask($rotateme, $direction = 1, $amount = 1);
			$spectrum[$i] = self::countOnBits($bits & $rotateme);
		}
		// special rule: if there is a tritone in the sonority, it will show up twice, so we divide by 2
		$spectrum[5] = $spectrum[5] / 2;
		return $spectrum;
	}



}