<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/Utils/BitmaskUtils.php';

class BitmaskUtilsTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}

	/**
	 * @dataProvider provider_bits2Tones
	 */
	public function test_bits2Tones($bits, $tones) {
		$t = \ianring\BitmaskUtils::bits2tones($bits);
		$this->assertEquals($tones, $t);
		$b = \ianring\BitmaskUtils::tones2bits($tones);
		$this->assertEquals($bits, $b);
	}
	public function provider_bits2Tones() {
		return array(
			array('bits' => 0, 'tones' => array()),
			array('bits' => 1, 'tones' => array(0)),
			array('bits' => 2, 'tones' => array(1)),
			array('bits' => 4, 'tones' => array(2)),
			array('bits' => 8, 'tones' => array(3)),
			array('bits' => 16, 'tones' => array(4)),
			array('bits' => 32, 'tones' => array(5)),
			array('bits' => 33, 'tones' => array(0,5)),
			array('bits' => 37, 'tones' => array(0,2,5)),
			array('bits' => 4095, 'tones' => array(0,1,2,3,4,5,6,7,8,9,10,11)),
		);
	}


	/**
	 * @dataProvider provider_nthOnBit
	 */
	public function test_nthOnBit($tones, $n, $expected) {
		$b = \ianring\BitmaskUtils::tones2bits($tones);
		$actual = \ianring\BitmaskUtils::nthOnBit($b, $n);
		$this->assertEquals($expected, $actual);
	}
	public function provider_nthOnBit() {
		return array(
			array('tones' => array(0,1,2,3,4,5,6,7,8,9,10,11), 'n' => 0, 'expected' => 0),
			array('tones' => array(0,1,2,3,4,5,6,7,8,9,10,11), 'n' => 11, 'expected' => 11),
			array('tones' => array(0,1,2,3,4,5,6,7,8,9,10,11), 'n' => 12, 'expected' => 0),
			array('tones' => array(0,1,2,3,4,5,6,7,8,9,10,11), 'n' => 16, 'expected' => 4),
			array('tones' => array(0,1,2,3,4,5,6,7,8,9,10,11), 'n' => -1, 'expected' => 11),
			array('tones' => array(0,1,2,3,4,5,6,7,8,9,10,11), 'n' => -8, 'expected' => 4),
			array('tones' => array(0,1,2,3,4,5,6,7,8,9,10,11), 'n' => -100, 'expected' => 8),
			array('tones' => array(0,2,4,6,8,10), 'n' => 1, 'expected' => 2),
			array('tones' => array(0,2,4,6,8,10), 'n' => 4, 'expected' => 8),
			array('tones' => array(0,2,4,6,8,10), 'n' => 6, 'expected' => 0),
			array('tones' => array(0,2,4,6,8,10), 'n' => 7, 'expected' => 2),
			array('tones' => array(0,2,4,6,8,10), 'n' => -1, 'expected' => 10),
			array('tones' => array(0,2,4,6,8,10), 'n' => -8, 'expected' => 8),
		);
	}


	/**
	 * @dataProvider provider_circularDistance
	 */
	public function test_circularDistance($tone1, $tone2, $expected) {
		$actual = \ianring\BitmaskUtils::circularDistance($tone1, $tone2);
		$this->assertEquals($expected, $actual);
	}
	public function provider_circularDistance() {
		return array(
			array('tone1' => 0, 'tone2' => 1, 'expected' => 1),
			array('tone1' => 3, 'tone2' => 7, 'expected' => 4),
			array('tone1' => 11, 'tone2' => 0, 'expected' => 1),
			array('tone1' => 9, 'tone2' => 2, 'expected' => 5),
		);
	}

	/**
	 * @dataProvider provider_isSubSetOf
	 */
	public function test_isSubSetOf($subset, $set, $expected) {
		$actual = \ianring\BitmaskUtils::isSubSetOf($subset, $set);
		$this->assertEquals($actual, $expected);
	}
	public function provider_isSubSetOf() {
		return array(
			array(
				'subset' => 33,
				'set' => 44,
				'expected' => false
			),
			array(
				'subset' => 2741,
				'set' => 3765,
				'expected' => true
			),
			array(
				'subset' => 2741,
				'set' => 3773,
				'expected' => true
			),
			array(
				'subset' => 3765,
				'set' => 3773,
				'expected' => true
			),
			array(
				'subset' => 2741,
				'set' => 3253,
				'expected' => false
			),
			array(
				'subset' => 0,
				'set' => 1,
				'expected' => true
			),
			array(
				'subset' => 1,
				'set' => 0,
				'expected' => false
			),
			array(
				'subset' => 1,
				'set' => 1,
				'expected' => true
			),
		);
	}

	/**
	 * @dataProvider provider_rotateBitmask
	 */
	public function test_rotateBitmask($bits, $direction, $amount, $base, $expected) {
		$actual = \ianring\BitmaskUtils::rotateBitmask($bits, $direction, $amount, $base);
		$this->assertEquals($expected, $actual);
	}
	public function provider_rotateBitmask() {
		return array(
			array(
				'bits' => 33,
				'direction' => 1,
				'amount' => 1,
				'base' => 12,
				'expected' => 2064
			),
			array(
				'bits' => 3055,
				'direction' => 1,
				'amount' => 1,
				'base' => 12,
				'expected' => 3575
			),
			array(
				'bits' => 3055,
				'direction' => 1,
				'amount' => 2,
				'base' => 12,
				'expected' => 3835
			),
			array(
				'bits' => 3055,
				'direction' => 1,
				'amount' => 3,
				'base' => 12,
				'expected' => 3965
			),
			array(
				'bits' => 13,
				'direction' => 1,
				'amount' => 1,
				'base' => 5,
				'expected' => 22
			),
			array(
				'bits' => 22,
				'direction' => -1,
				'amount' => 1,
				'base' => 5,
				'expected' => 13
			),
			array(
				'bits' => 13,
				'direction' => 1,
				'amount' => 2,
				'base' => 5,
				'expected' => 11
			),
		);
	}

	/**
	 * @dataProvider provider_allRotationsOf
	 */
	public function test_allRotationsOf($set, $base, $expected) {
		$actual = \ianring\BitmaskUtils::allRotationsOf($set, $base);
		$this->assertEquals($actual, $expected);
	}
	public function provider_allRotationsOf() {
		return array(
			array(
				'set' => 33,
				'base' => 12,
				'expected' => array(33, 2064, 1032, 516, 258, 129, 2112, 1056, 528, 264, 132, 66)
			),
			array(
				'set' => 13,
				'base' => 5,
				'expected' => array(13, 22, 11, 21, 26)
			),
			array(
				'set' => 107,
				'base' => 7,
				'expected' => array(107, 117, 122, 61, 94, 47, 87)
			),
		);
	}


	/**
	 * @dataProvider provider_mapTo
	 */
	public function test_mapTo($tones, $set, $expected) {
		$actual = \ianring\BitmaskUtils::mapTo($tones, $set);
		$this->assertEquals($actual, $expected);
	}
	public function provider_mapTo() {
		return array(
			array(
				'tones' => 7, // just the lowest three tones
				'set' => 2741,
				'expected' => 21
			),
			array(
				'tones' => 21,
				'set' => 2741,
				'expected' => 145
			),
			'too many steps for set' => array(
				'tones' => 127,
				'set' => 181,
				'expected' => false
			),
		);
	}


	/**
	 * @dataProvider provider_isRotationOf
	 */
	public function test_isRotationOf($a, $b, $expected) {
		$actual = \ianring\BitmaskUtils::isRotationOf($a, $b);
		$this->assertEquals($actual, $expected);
	}
	public function provider_isRotationOf() {
		return array(
			array(
				'a' => 127,
				'b' => 3103,
				'expected' => true
			),
			array(
				'a' => 3103,
				'b' => 127,
				'expected' => true
			),
			array(
				'a' => 127,
				'b' => 127,
				'expected' => true
			),
			array(
				'a' => 3103,
				'b' => 2591,
				'expected' => false
			),
		);
	}




}
