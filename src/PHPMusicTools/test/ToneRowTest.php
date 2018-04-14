<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/ToneRow.php';
require_once __DIR__.'/../classes/Utils/BitmaskUtils.php';

class ToneRowTest extends PHPMusicToolsTest
{
	
    public static function setUpBeforeClass()
    {
    }

	protected function setUp(){
	}
	protected function tearDown(){
	}


	/**
	 * @dataProvider provider_rotateSequence
	 */
	public function test_rotateSequence($row, $amount, $expected) {
		$row = new \ianring\ToneRow($row);
		$row->rotateSequence($amount);
		$this->assertEquals($row->tones, $expected);
	}
	public function provider_rotateSequence() {
		return array(
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'amount' => 1,
				'expected' => array(11,0,1,2,3,4,5,6,7,8,9,10)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'amount' => -1,
				'expected' => array(1,2,3,4,5,6,7,8,9,10,11,0)
			),
			array(
				'row' => array(0,1,2,3,4,5),
				'amount' => 1,
				'expected' => array(5,0,1,2,3,4)
			),
			array(
				'row' => array(0,1,2,3,4,5),
				'amount' => -1,
				'expected' => array(1,2,3,4,5,0)
			),
			array(
				'row' => array(6),
				'amount' => -1,
				'expected' => array(6)
			),
			array(
				'row' => array(3,6),
				'amount' => 2,
				'expected' => array(3,6)
			),
			array(
				'row' => array(3,6,9),
				'amount' => -3,
				'expected' => array(3,6,9)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'amount' => 0,
				'expected' => array(0,1,2,3,4,5,6,7,8,9,10,11)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'amount' => 11,
				'expected' => array(1,2,3,4,5,6,7,8,9,10,11,0)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'amount' => 6,
				'expected' => array(6,7,8,9,10,11,0,1,2,3,4,5)
			),
		);
	}




	/**
	 * @dataProvider provider_rotateSequenceSegmentsExceptions
	 */
	public function test_rotateSequenceSegmentsExceptions($row, $segmentSize, $amount, $expected) {
		$row = new \ianring\ToneRow($row);
		$this->expectException(\Exception::class);
		$row->rotateSequenceSegments($segmentSize, $amount);
	}
	public function provider_rotateSequenceSegmentsExceptions() {
		return array(
			'should throw exception for indivisible chunk size' => array(
				'row' => array(0,1,2,3,4,5,6,7,8,9),
				'segmentSize' => 6,
				'amount' => 1,
				'exception' => true
			),
			'throw exception for negative chunk size' => array(
				'row' => array(0,1,2,3,4,5,6,7,8,9),
				'segmentSize' => -1,
				'amount' => 1,
				'exception' => true
			)
		);
	}


	/**
	 * @dataProvider provider_rotateSequenceSegments
	 */
	public function test_rotateSequenceSegments($row, $segmentSize, $amount, $expected) {
		$row = new \ianring\ToneRow($row);
		// if (!empty($expected['exception'])) {
		// 	$this->expectException(\Exception::class);
		// }
		$row->rotateSequenceSegments($segmentSize, $amount);
		$this->assertEquals($row->tones, $expected);
	}
	public function provider_rotateSequenceSegments() {
		return array(
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'segmentSize' => 3,
				'amount' => 1,
				'expected' => array(2,0,1, 5,3,4, 8,6,7, 11,9,10)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'segmentSize' => 4,
				'amount' => 1,
				'expected' => array(3,0,1,2, 7,4,5,6, 11,8,9,10)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'segmentSize' => 6,
				'amount' => 1,
				'expected' => array(5,0,1,2,3,4, 11,6,7,8,9,10)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7),
				'segmentSize' => 4,
				'amount' => 2,
				'expected' => array(2,3,0,1, 6,7,4,5)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9),
				'segmentSize' => 5,
				'amount' => -1,
				'expected' => array(1,2,3,4,0, 6,7,8,9,5)
			),
		);
	}


	/**
	 * @dataProvider provider_transpose
	 */
	public function test_transpose($row, $amount, $expected) {
		$row = new \ianring\ToneRow($row);
		$row->transpose($amount);
		$this->assertEquals($row->tones, $expected);
	}
	public function provider_transpose() {
		return array(
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'amount' => 0,
				'expected' => array(0,1,2,3,4,5,6,7,8,9,10,11)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'amount' => 1,
				'expected' => array(1,2,3,4,5,6,7,8,9,10,11,0)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'amount' => -1,
				'expected' => array(11,0,1,2,3,4,5,6,7,8,9,10)
			),
			array(
				'row' => array(6,7,8,9,10),
				'amount' => 4,
				'expected' => array(10,11,0,1,2)
			),
			array(
				'row' => array(6,7,8,9,10),
				'amount' => 16,
				'expected' => array(10,11,0,1,2)
			),
			array(
				'row' => array(6,7,8,9,10),
				'amount' => -7,
				'expected' => array(11,0,1,2,3)
			),
			array(
				'row' => array(6,7,8,9,10),
				'amount' => -19,
				'expected' => array(11,0,1,2,3)
			),
		);
	}

	/**
	 * @dataProvider provider_invert
	 */
	public function test_invert($row, $expected) {
		$row = new \ianring\ToneRow($row);
		$row->invert();
		$this->assertEquals($row->tones, $expected);
	}
	public function provider_invert() {
		return array(
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'expected' => array(11,10,9,8,7,6,5,4,3,2,1,0)
			),
			array(
				'row' => array(2,4,6,8),
				'expected' => array(9,7,5,3)
			)
		);
	}

	/**
	 * @dataProvider provider_retrograde
	 */
	public function test_retrograde($row, $expected) {
		$row = new \ianring\ToneRow($row);
		$row->retrograde();
		$this->assertEquals($row->tones, $expected);
	}
	public function provider_retrograde() {
		return array(
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'expected' => array(11,10,9,8,7,6,5,4,3,2,1,0)
			),
			array(
				'row' => array(2,4,6,8),
				'expected' => array(8,6,4,2)
			),
		);
	}


	/**
	 * @dataProvider provider_multiply
	 */
	public function test_multiply($row, $multiplicand, $expected) {
		$row = new \ianring\ToneRow($row);
		$row->multiply($multiplicand);
		$this->assertEquals($row->tones, $expected);
	}
	public function provider_multiply() {
		return array(
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'multiplicand' => 1,
				'expected' => array(0,1,2,3,4,5,6,7,8,9,10,11)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'multiplicand' => 11,
				'expected' => array(0,11,10,9,8,7,6,5,4,3,2,1)
			),
			array(
				'row' => array(0,1,2,3,4,5,6,7,8,9,10,11),
				'multiplicand' => 7,
				'expected' => array(0,7,2,9,4,11,6,1,8,3,10,5)
			),
		);
	}

}

