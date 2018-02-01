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

	// Data array for fast lookup. Keys are the bits for a forte prime. Values are the Forte number, ie the digit that appears
	// after the hyphen in a name like "8-3". Only Forte primes are in this table; if it's not a forte prime then it shouldn't
	// be here, because let's leave some of the grunt work to calculation 
	// 
	// IRONICALLY, the keys in this data are Primes according to Ring/Rahn, not Forte primes!
	// Rahn(355) = Forte(395)
	// Rahn(755) = Forte(815)
	// Rahn(691) = Forte(811)
	// Rahn(717) = Forte(843)
	// Rahn(743) = Forte(919)
	// Rahn(1467) = Forte(1719)
	// 
	// One question is why have we multiple keys with the same value? look into this because it might be a clue for refactoring and optimizing this 
	// 
	public static $fortePrimes = array(
		0 => 1, 		1 => 1, 		

		// 2 tones
		3 => 1, 		5 => 2, 		9 => 3, 		17 => 4, 		33 => 5, 		65 => 6, 		

		// 3 tones
		7 => 1, 		11 => 2, 		13 => 2, 		19 => 3, 		25 => 3, 		35 => 4, 		49 => 4, 		67 => 5, 		97 => 5, 		21 => 6, 		
		37 => 7, 		41 => 7, 		69 => 8, 		81 => 8, 		133 => 9, 		73 => 10, 		137 => 11, 		145 => 11, 		273 => 12, 		

		// 4 tones
		15 => 1, 		23 => 2, 		29 => 2, 		27 => 3, 		39 => 4, 		57 => 4, 		71 => 5, 		
		113 => 5, 		135 => 6, 		51 => 7, 		99 => 8, 		195 => 9, 		45 => 10, 		43 => 11, 		53 => 11, 		77 => 12, 		89 => 12, 		75 => 13, 		105 => 13, 		141 => 14, 		177 => 14, 		83 => 15, 		
		101 => 15, 		163 => 16, 		197 => 16, 		153 => 17, 		147 => 18, 		201 => 18, 		275 => 19, 		281 => 19, 		291 => 20, 		85 => 21, 		149 => 22, 		169 => 22, 		165 => 23, 		277 => 24, 		
		325 => 25, 		297 => 26, 		293 => 27, 		329 => 27, 		585 => 28, 		139 => 29, 		209 => 29, 		

		// 5 tones
		31 => 1, 		47 => 2, 		61 => 2, 		55 => 3, 		59 => 3, 		79 => 4, 		121 => 4, 		143 => 5, 		
		241 => 5, 		103 => 6, 		115 => 6, 		199 => 7, 		227 => 7, 		93 => 8, 		87 => 9, 		117 => 9, 		91 => 10, 		109 => 10, 		157 => 11, 		185 => 11, 		107 => 12, 		279 => 13, 		285 => 13, 		
		167 => 14, 		229 => 14, 		327 => 15, 		155 => 16, 		217 => 16, 		283 => 17, 		179 => 18, 		205 => 18, 		203 => 19, 		211 => 19, 		355 => 20, 		419 => 20, 		307 => 21, 		409 => 21, 		
		403 => 22, 		173 => 23, 		181 => 23, 		171 => 24, 		213 => 24, 		301 => 25, 		361 => 23, 		309 => 26, 		345 => 26, 		299 => 27, 		425 => 27, 		333 => 28, 		357 => 28, 		331 => 29, 		
		421 => 29, 		339 => 30, 		405 => 30, 		587 => 31, 		589 => 31, 		595 => 32, 		659 => 32, 		341 => 33, 		597 => 34, 		661 => 35, 		151 => 36, 		233 => 36, 		313 => 37, 		295 => 38, 		
		457 => 38, 		

		// 6 tones
		63 => 1, 		95 => 2, 		125 => 2, 		111 => 3, 		123 => 3, 		119 => 4, 		207 => 5, 		243 => 5, 		231 => 6, 		455 => 7, 		189 => 8, 		175 => 9, 		245 => 9, 		187 => 10, 		
		221 => 10, 		183 => 11, 		237 => 11, 		215 => 12, 		235 => 12, 		219 => 13, 		315 => 14, 		441 => 14, 		311 => 15, 		473 => 15, 		371 => 16, 		413 => 16, 		407 => 17, 		467 => 17, 		
		423 => 18, 		459 => 18, 		411 => 19, 		435 => 19, 		819 => 20, 		349 => 21, 		373 => 21, 		343 => 22, 		469 => 22, 		365 => 23, 		347 => 24, 		437 => 24, 		363 => 25, 		429 => 25, 		
		427 => 26, 		603 => 27, 		621 => 27, 		619 => 28, 		717 => 29, 		715 => 30, 		845 => 30, 		691 => 31, 		851 => 31, 		693 => 32, 		685 => 33, 		725 => 33, 		683 => 34, 		853 => 34, 		
		1365 => 35, 	159 => 36, 		249 => 36, 		287 => 37, 		399 => 38, 		317 => 39, 		377 => 39, 		303 => 40, 		489 => 40, 		335 => 41, 		485 => 41, 		591 => 42, 		359 => 43, 		461 => 43, 		
		615 => 44, 		807 => 44, 		605 => 45, 		599 => 46, 		629 => 46, 		663 => 47, 		669 => 47, 		679 => 48, 		667 => 49, 		723 => 50, 		

		// 7 tones
		127 => 1, 		191 => 2, 		253 => 2, 		319 => 3, 		
		505 => 3, 		223 => 4, 		251 => 4, 		239 => 5, 		247 => 5, 		415 => 6, 		499 => 6, 		463 => 7, 		487 => 7, 		381 => 8, 		351 => 9, 		501 => 9, 		607 => 10, 		637 => 10, 		379 => 11, 		
		445 => 11, 		671 => 12, 		375 => 13, 		477 => 13, 		431 => 14, 		491 => 14, 		471 => 15, 		623 => 16, 		635 => 16, 		631 => 17, 		755 => 18, 		979 => 18, 		719 => 19, 		847 => 19, 		
		743 => 20, 		935 => 20, 		823 => 21, 		827 => 21, 		871 => 22, 		701 => 23, 		757 => 23, 		687 => 24, 		981 => 24, 		733 => 25, 		749 => 25, 		699 => 26, 		885 => 26, 		695 => 27, 		
		949 => 27, 		747 => 28, 		861 => 28, 		727 => 29, 		941 => 29, 		855 => 30, 		939 => 30, 		731 => 31, 		877 => 31, 		859 => 32, 		875 => 32, 		1367 => 33, 	1371 => 34, 	1387 => 35, 		
		367 => 36, 		493 => 36, 		443 => 37, 		439 => 38, 		475 => 38, 		

		// 8 tones
		255 => 1, 		383 => 2, 		509 => 2, 		639 => 3, 		447 => 4, 		507 => 4, 		479 => 5, 		503 => 5, 		495 => 6, 		831 => 7, 		
		927 => 8, 		975 => 9, 		765 => 10, 		703 => 11, 		1013 => 11, 	763 => 12, 		893 => 12, 		735 => 13, 		1005 => 13, 	759 => 14, 		957 => 14, 		863 => 15, 		1003 => 15, 		943 => 16, 		
		983 => 16, 		891 => 17, 		879 => 18, 		987 => 18, 		887 => 19, 		955 => 19, 		951 => 20, 		1375 => 21, 	1391 => 22, 	1711 => 22, 	1455 => 23, 	1399 => 24, 	1495 => 25, 		
		1467 => 26, 	1463 => 27, 	1751 => 27, 	1755 => 28, 	751 => 29, 		989 => 29, 		

		// 9 tones
		511 => 1, 		767 => 2, 		1021 => 2, 		895 => 3, 		1019 => 3, 		959 => 4, 		1015 => 4, 		991 => 5, 		
		1007 => 5, 		1407 => 6, 		1471 => 7, 		1727 => 7, 		1503 => 8, 		1887 => 8, 		1519 => 9, 		1759 => 10, 	1775 => 11, 	1903 => 11, 	1911 => 12, 		

		// 10 tones
		1023 => 1, 		1535 => 2, 		1791 => 3, 		1919 => 4, 		1983 => 5, 		2015 => 6, 		

		// 11 tones
		2047 => 1, 		

		// 12 tones
		4095 => 1
	);

	// these are the RING PRIMES (not the forte primes!) that have a Z.
	public static $forteZeds = array(83,101,139,209,107,283,179,205,151,233,313,295,457,111,123,119,231,187,221,183,237,215,235,219,407,467,411,435,365,347,437,363,429,427,619,717,159,249,287,399,317,377,303,489,335,485,591,359,461,615,807,605,599,629,663,669,679,667,723,671,631,755,979,367,493,443,439,475,863,1003,751,989);

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
		// declares the lookup arrays in the scope of this function

		$prime = $this->primeFormRing($this->bits);

		$output = '';
		$cardinality =\ianring\BitmaskUtils::countOnBits($prime);
		$output .= $cardinality . '-';

		$output .= in_array($prime, self::$forteZeds) ? 'Z' : '';

		$output .= self::$fortePrimes[$prime];

		return $output;
	}

	/**
	 * Mirror is a pc set that is symmetric by reflection around a pc axis. A minor-seventh chord is a mirror because if its intervals are projected in the opposite direction the same chord results. In Solomon's Table of Set Classes all mirror sets are indicated with an asterisk next to the set name.
	 * @return boolean [description]
	 */
	public function isMirror() {

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


	/**
	 * Drawing a line in the electrons here, declaring that our "isPrime" method will use the Ring method, which 
	 * produces identical results as the Rahn formula. Apologies to Forte.
	 * @param  [type]  $bits [description]
	 * @param  string  $algo [description]
	 * @return boolean       [description]
	 */
	public static function isPrime($bits, $algo = 'forte') {
		$pcs = new PitchClassSet($bits);
		return $pcs->bits == $pcs->primeFormRing();
	}

	/**
	 * returns the prime form of this PCS according to Forte's algorithm. Inversions are excluded as duplicates!
	 * @return int 
	 */
	public function primeFormForte() {
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


	/**
	 * what is this for?
	 */
	private function modintdiff($a, $b) {
		return min($a-$b, $b-$a);
	}


	private function getAllInversionsAndRotations($bits) {
		$rotations = array();
		$count = \ianring\BitmaskUtils::countOnBits($bits);
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
		return $rotations;
	}

	/**
	 * returns the prime form of this PCS according to Rahn's algorithm
	 * It has been proven by brute force that this algorithm produces the same results as primeFormRing()
	 * 
	 * @see "The Interger Model Of Pitch", Basic Atonal Theory, John Rahn p.35 ISBN 0-02-873-160-3
	 */
	public function primeFormRahn() {
		if ($this->bits == 0) {return 0;}

		$bits = \ianring\BitmaskUtils::moveDownToRootForm($this->bits);

		$rotations = array();
		$count = \ianring\BitmaskUtils::countOnBits($bits);

		// another shortcut
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
	 * returns the prime form of this PCS according to Ian Ring's algorithm.
	 * Invented by Ian Ring, 2016
	 * Proven in 2016 to produce idential results as the Rahn algorithm, but the method is less complicated.
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
	 *
	 * This function will accept any set, whether it is prime or not. But it will only return the prime z-mate.
	 * 
	 * Z-related sets are always in pairs. Isn't that interesting? That is a consequence of the 12-TET system. In other tuning systems,
	 * Z-mates can occur in different groupings.
	 *
	 * @see  https://en.wikipedia.org/wiki/Interval_vector#Z-relation
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
