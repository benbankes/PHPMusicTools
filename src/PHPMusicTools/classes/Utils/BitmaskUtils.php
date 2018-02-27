<?php
namespace ianring;

class BitmaskUtils
{

    /**
     * required because PHP doesn't do modulo correctly with negative numbers.
     */
	public static function truemod($num, $mod = 12) {
		return (($mod + ($num % $mod)) % $mod);
	}


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
    	if (empty($tones)) {return 0;}
    	foreach ($tones as $tone) {
    		$bits += pow(2, $tone);
    	}
    	return $bits;
    }

    public static function nthOnBit($bits, $n) {
    	$countOnBits = self::countOnBits($bits);
    	$tones = self::bits2Tones($bits);
    	$n = self::truemod($n, $countOnBits);
    	return $tones[$n];
    }

	/**
	 * distance clockwise around a 12-tone circle, from one tone to another.
	 * eg the distance between 4 and 3 is not -1, it's 11
	 * @param  [type] $tone1 [description]
	 * @param  [type] $tone2 [description]
	 * @return [type]        [description]
	 */
	public static function circularDistance($tone1, $tone2, $clockwise = true) {
		return self::truemod($tone2 - $tone1);
	}


	/**
	 * Accepts a number to use as a bitmask, and "rotates" it. e.g.
	 * 100000000000 -> 000000000001 -> 00000000010 -> 000000000100
	 *
	 * @param  integer $bits     the bitmask being rotated
	 * @param  integer $direction 1 = rotate up, 0 = rotate down
	 * @param  integer $amount    the number of places to rotate by
	 * @return integer            the result after rotation
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


	/**
	 * returns interval vector as a 6-member array, eg [1,0,1,0,1,0,1]
	 * @param  $bits
	 * @return array
	 */
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


	/**
	 * returns true if $b1 is a rotation of $b2
	 * @param  $b1 
	 * @param  $b2
	 * @return boolean
	 */
	public static function isRotationOf($b1, $b2) {
		for ($i = 0; $i < 12; $i++) {
			$b1 = self::rotateBitmask($b1, 1, 1);
			if ($b1 === $b2) {
				return true;
			}
		}
		return false;
	}

	/**
	 * returns true if all the tones in $subset are present in $set.
	 * NOTE this will return true if the two sets are the same!
	 * @param  $subset
	 * @param  $set
	 * @return boolean 
	 */
	public static function isSubsetOf($subset, $set) {
		return 0 == ($subset & (~$set));
	}


	/**
	 * rotates the bitmask so it is rooted on 0. e.g. 000010011000 [3,4,7] becomes 000000010011 [0,1,4]
	 * @param  $bits
	 * @return [type]
	 */
	public static function moveDownToRootForm($bits) {
		$tones = self::bits2Tones($bits);
		$rootedbits = self::rotateBitmask($bits, 1, $tones[0]);
		return $rootedbits;
	}


}