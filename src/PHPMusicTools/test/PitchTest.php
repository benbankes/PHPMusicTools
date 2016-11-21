<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Pitch.php';

class PitchTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function testConstructFromArray(){		
		$pitch = ianring\Pitch::constructFromArray(array(
			'step' => 'C',
			'alter' => 1,
			'octave' => 4,
		));

		$this->assertInstanceOf(\ianring\Pitch::class, $pitch);
		$this->assertObjectHasAttribute('step', $pitch);
		$this->assertObjectHasAttribute('alter', $pitch);
		$this->assertObjectHasAttribute('octave', $pitch);
		$this->assertEquals('C', $pitch->step);
		$this->assertEquals(1, $pitch->alter);
		$this->assertEquals(4, $pitch->octave);
	}

	/**
	 * @dataProvider providerConstructFromString
	 */
	public function testConstructFromString($string, $expected) {
		$this->markTestSkipped();
		$pitch = \ianring\Pitch::constructFromString($string);
		$this->assertEquals($expected, $pitch);
	}
	function providerConstructFromString() {
		return array(
			array(
				'args' => 'C4',
				'expected' => new ianring\Pitch('C', 0, 4)
			),
			array(
				'args' => 'C-4',
				'expected' => new ianring\Pitch('C', -1, 4)
			),
			array(
				'args' => 'Cb4',
				'expected' => new ianring\Pitch('C', -1, 4)
			),
			array(
				'args' => 'Bbb5',
				'expected' => new ianring\Pitch('B', -2, 5)
			),
			array(
				'args' => 'G#2',
				'expected' => new ianring\Pitch('G', 1, 2)
			),
			array(
				'args' => 'A+3',
				'expected' => new ianring\Pitch('A', 1, 3)
			),
			array(
				'args' => 'G##2',
				'expected' => new ianring\Pitch('G', 2, 2)
			),
			array(
				'args' => 'A++3',
				'expected' => new ianring\Pitch('A', 2, 3)
			),
			array(
				'args' => 'A--3',
				'expected' => new ianring\Pitch('A', -2, 3)
			),
		);
	}

	/**
	 * @dataProvider providerIsHigherThan
	 */
	public function testIsHigherThan($pitch1, $pitch2, $expected) {
		$actual = $pitch1->isHigherThan($pitch2);
		$this->assertEquals($actual, $expected);

		// let's test isLowerThan at the same time
		$actual = $pitch2->isLowerthan($pitch1);
		$this->assertEquals($actual, $expected);
	}
	function providerIsHigherThan() {
		return array(
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'pitch2' => new ianring\Pitch('C', 0, 3),
				'expected' => true
			),
			array(
				'pitch1' => new ianring\Pitch('C', 1, 4), // C sharp
				'pitch2' => new ianring\Pitch('D', -2, 4), // D double flat
				'expected' => true
			),
			array(
				'pitch1' => new ianring\Pitch('C', 1, 4), // C sharp
				'pitch2' => new ianring\Pitch('D', -1, 4), // D flat
				'expected' => false
			),
		);
	}

	/**
	 * @dataProvider providerEquals
	 */
	public function testEquals($pitch1, $pitch2, $expected) {
		$actual = $pitch1->equals($pitch2);
		$this->assertEquals($actual, $expected);
	}
	function providerEquals() {
		return array(
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'pitch2' => new ianring\Pitch('C', 0, 4),
				'expected' => true
			),
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'pitch2' => new ianring\Pitch('C', 1, 4),
				'expected' => false
			),
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'pitch2' => new ianring\Pitch('C', 0, 3),
				'expected' => false
			),
			'enharmonic is not the same as equal' => array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'pitch2' => new ianring\Pitch('B', 1, 3),
				'expected' => false
			),
		);
	}

	/**
	 * @dataProvider providerTranspose
	 */
	public function testTranspose($pitch, $interval, $preferredAlteration, $expected) {
		$pitch->transpose($interval, $preferredAlteration);
		$this->assertTrue($pitch->equals($expected));
	}
	function providerTranspose() {
		return array(
			array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'interval' => 0,
				'preferredAlteration' => 1,
				'expected' => new ianring\Pitch('C', 0, 4)
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'interval' => 1,
				'preferredAlteration' => 1,
				'expected' => new ianring\Pitch('C', 1, 4)
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'interval' => 1,
				'preferredAlteration' => -1,
				'expected' => new ianring\Pitch('D', -1, 4)
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 0),
				'interval' => 1,
				'preferredAlteration' => -1,
				'expected' => new ianring\Pitch('D', -1, 0)
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 0),
				'interval' => 12,
				'preferredAlteration' => 1,
				'expected' => new ianring\Pitch('C', 0, 1)
			),
		);
	}


	/**
	 * @dataProvider providerIsEnharmonic
	 */
	public function testIsEnharmonic($pitch1, $pitch2, $expected) {
		$actual = $pitch1->isEnharmonic($pitch2);
		$this->assertEquals($actual, $expected);
	}
	function providerIsEnharmonic() {
		return array(
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'pitch2' => new ianring\Pitch('C', 0, 4),
				'expected' => true
			),
			array(
				'pitch1' => new ianring\Pitch('C', 1, 4), // C sharp
				'pitch2' => new ianring\Pitch('D', -1, 4), // D flat
				'expected' => true
			),
			array(
				'pitch1' => new ianring\Pitch('C', 2, 4), // C double sharp
				'pitch2' => new ianring\Pitch('E', -2, 4), // E double flat
				'expected' => true
			),
			array(
				'pitch1' => new ianring\Pitch('C', 2, 4),
				'pitch2' => new ianring\Pitch('D', 0, 4),
				'expected' => true
			),
			array(
				'pitch1' => new ianring\Pitch('C', 1, 4), // C sharp
				'pitch2' => new ianring\Pitch('D', -2, 4), // D double flat
				'expected' => false
			),
			array(
				'pitch1' => new ianring\Pitch('C', 1, 4), // C sharp
				'pitch2' => new ianring\Pitch('C', 0, 4), // C natural
				'expected' => false
			),
		);
	}

	/**
	 * @dataProvider providerClosestUp
	 */
	public function testClosestUp($pitch1, $step, $alter, $allowEqual, $expected) {
		$actual = $pitch1->closestUp($step, $alter, $allowEqual);
		$this->assertTrue($actual->equals($expected));
	}
	function providerClosestUp() {
		return array(
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'step' => 'E',
				'alter' => 0,
				'allowEqual' => true,
				'expected' => new ianring\Pitch('E', 0, 4),
			),
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'step' => 'C',
				'alter' => 1,
				'allowEqual' => true,
				'expected' => new ianring\Pitch('C', 1, 4),
			),
			array(
				'pitch1' => new ianring\Pitch('C', 1, 4),
				'step' => 'C',
				'alter' => 0,
				'allowEqual' => true,
				'expected' => new ianring\Pitch('C', 0, 5),
			),
			array(
				'pitch1' => new ianring\Pitch('B', 0, 4),
				'step' => 'C',
				'alter' => 0,
				'allowEqual' => true,
				'expected' => new ianring\Pitch('C', 0, 5),
			),
			array(
				'pitch1' => new ianring\Pitch('B', 1, 4), // b sharp 4 is the same as C natural 5
				'step' => 'C',
				'alter' => 0,
				'allowEqual' => true,
				'expected' => new ianring\Pitch('C', 0, 5),
			),
			array(
				'pitch1' => new ianring\Pitch('B', 1, 4), // b sharp 4 to the C flat above
				'step' => 'C',
				'alter' => -1,
				'allowEqual' => true,
				'expected' => new ianring\Pitch('B', 0, 5),
			),
			array(
				'pitch1' => new ianring\Pitch('D', 0, -2),
				'step' => 'E',
				'alter' => 0,
				'allowEqual' => true,
				'expected' => new ianring\Pitch('E', 0, -2),
			),
			array(
				'pitch1' => new ianring\Pitch('D', 0, -2),
				'step' => 'E',
				'alter' => 1,
				'allowEqual' => true,
				'expected' => new ianring\Pitch('F', 0, -2),
			),
			'allow equal, return same pitch' => array(
				'pitch1' => new ianring\Pitch('D', 0, 3),
				'step' => 'D',
				'alter' => 0,
				'allowEqual' => true,
				'expected' => new ianring\Pitch('D', 0, 3),
			),
			'no allow equal, go to octave above' => array(
				'pitch1' => new ianring\Pitch('D', 0, 3),
				'step' => 'D',
				'alter' => 0,
				'allowEqual' => false,
				'expected' => new ianring\Pitch('D', 0, 4),
			),
			'call it with a heightless pitch' => array(
				'pitch1' => new ianring\Pitch('D', 0, null),
				'step' => 'E',
				'alter' => 0,
				'allowEqual' => null,
				'expected' => new ianring\Pitch('E', 0, null),
			)
		);
	}

	/**
	 * @dataProvider providerClosestDown
	 */
	public function testClosestDown($pitch1, $step, $alter, $expected) {
		$actual = $pitch1->closestDown($step, $alter);
		$this->assertTrue($actual->equals($expected));
	}
	function providerClosestDown() {
		return array(
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'step' => 'E',
				'alter' => 0,
				'expected' => new ianring\Pitch('E', 0, 3),
			),
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'step' => 'C',
				'alter' => 0,
				'expected' => new ianring\Pitch('C', 0, 4),
			),
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'step' => 'C',
				'alter' => 1,
				'expected' => new ianring\Pitch('C', 1, 3),
			),
			array(
				'pitch1' => new ianring\Pitch('C', 1, 4),
				'step' => 'C',
				'alter' => 0,
				'expected' => new ianring\Pitch('C', 0, 4),
			),
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'step' => 'C',
				'alter' => -1,
				'expected' => new ianring\Pitch('B', 0, 3),
			),

		);
	}


	/**
	 * @dataProvider providerToNoteNumber
	 */
	public function testToNoteNumber($pitch, $expected) {
		$actual = $pitch->toNoteNumber();
		$this->assertEquals($expected, $actual);

	}
	function providerToNoteNumber() {
		return array(
			array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'expected' => 0
			),
			array(
				'pitch' => new ianring\Pitch('C', 1, 4),
				'expected' => 1
			),
			array(
				'pitch' => new ianring\Pitch('C', -1, 4),
				'expected' => -1
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 3),
				'expected' => -12
			),
			array(
				'pitch' => new ianring\Pitch('B', 0, 2),
				'expected' => -13
			),
		);
	}

	/**
	 * @dataProvider providerInterval
	 */
	public function testInterval($pitch1, $pitch2, $expected) {
		$actual = $pitch1->interval($pitch2);
		$this->assertEquals($expected, $actual);
	}
	function providerInterval() {
		return array(
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'pitch2' => new ianring\Pitch('E', 0, 4),
				'expected' => 4
			),
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'pitch2' => new ianring\Pitch('E', -1, 4),
				'expected' => 3
			),
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'pitch2' => new ianring\Pitch('E', 1, 4),
				'expected' => 5
			)
		);
	}


	/**
	 * @dataProvider providerEnharmonicizeToStep
	 */
	public function testEnharmonicizeToStep($pitch, $step, $expected) {
		$pitch->enharmonicizeToStep($step);
		$this->assertEquals($expected, $pitch);
	}
	function providerEnharmonicizeToStep() {
		return array(
			array(
				'pitch' => new ianring\Pitch('C', 1, 4),
				'step' => 'D',
				'expected' => new ianring\Pitch('D', -1, 4)
			),
			array(
				'pitch' => new ianring\Pitch('D', -1, 4),
				'step' => 'C',
				'expected' => new ianring\Pitch('C', 1, 4)
			),
			array(
				'pitch' => new ianring\Pitch('F', 0, 2),
				'step' => 'E',
				'expected' => new ianring\Pitch('E', 1, 2)
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 3),
				'step' => 'B',
				'expected' => new ianring\Pitch('B', 1, 3)
			),
			array(
				'pitch' => new ianring\Pitch('C', -1, 3),
				'step' => 'B',
				'expected' => new ianring\Pitch('B', 0, 3)
			),
		);
	}


	/**
	 * @dataProvider providerStepUp
	 */
	public function testStepUp($step, $expected) {
		$result = ianring\Pitch::stepUp($step);
		$this->assertEquals($result, $expected);
	}
	function providerStepUp() {
		return array(
			array(
				'step' => 'D',
				'expected' => 'E'
			),
			array(
				'step' => 'G',
				'expected' => 'A'
			),
			array(
				'step' => 'C',
				'expected' => 'D'
			),
		);
	}


	/**
	 * @dataProvider providerStepDown
	 */
	public function testStepDown($step, $expected) {
		$result = ianring\Pitch::stepDown($step);
		$this->assertEquals($result, $expected);
	}
	function providerStepDown() {
		return array(
			array(
				'step' => 'E',
				'expected' => 'D'
			),
			array(
				'step' => 'A',
				'expected' => 'G'
			),
			array(
				'step' => 'D',
				'expected' => 'C'
			),
		);
	}


	/**
	 * @dataProvider providerChroma
	 */
	public function testChroma($pitch, $expected) {
		$result = $pitch->chroma();
		$this->assertEquals($result, $expected);
	}
	function providerChroma() {
		return array(
			array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'expected' => '0'
			),
			array(
				'pitch' => new ianring\Pitch('C', 1, 4),
				'expected' => '1'
			),
			array(
				'pitch' => new ianring\Pitch('C', -1, 4),
				'expected' => '11'
			),
			array(
				'pitch' => new ianring\Pitch('B', 0, 4),
				'expected' => '11'
			),
			array(
				'pitch' => new ianring\Pitch('A', 2, 4),
				'expected' => '11'
			),
		);
	}


	/**
	 * @dataProvider providerToFrequency
	 */
	public function testToFrequency($pitch, $expected) {
		$result = $pitch->toFrequency();
		$this->assertEquals($result, $expected);
	}
	function providerToFrequency() {
		return array(
			array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'expected' => 261.63
			),
			array(
				'pitch' => new ianring\Pitch('C', 1, 4),
				'expected' => 277.18
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 0),
				'expected' => 16.35
			),
			array(
				'pitch' => new ianring\Pitch('B', 0, 2),
				'expected' => 123.47
			),
			array(
				'pitch' => new ianring\Pitch('G', 0, 9),
				'expected' => 12543.85
			),
			array(
				'pitch' => new ianring\Pitch('A', 0, 4),
				'expected' => 440
			),
			array(
				'pitch' => new ianring\Pitch('A', 1, 4),
				'expected' => 466.16
			),
			array(
				'pitch' => new ianring\Pitch('A', -1, 4),
				'expected' => 415.30
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 2),
				'expected' => 65.41
			),
			array(
				'pitch' => new ianring\Pitch('B', 0, 5),
				'expected' => 987.77
			),
			array(
				'pitch' => new ianring\Pitch('C', 1, 5),
				'expected' => 554.37
			),
		);
	}



	/**
	 * @dataProvider providerToMidiKeyNumber
	 */
	public function testToMidiKeyNumber($pitch, $expected) {
		$result = $pitch->toMidiKeyNumber();
		$this->assertEquals($result, $expected);
	}
	function providerToMidiKeyNumber() {
		return array(
			array(
				'pitch' => new ianring\Pitch('C', 0, -1),
				'expected' => 0
			),
			array(
				'pitch' => new ianring\Pitch('C', 1, -1),
				'expected' => 1
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'expected' => 60
			),
			array(
				'pitch' => new ianring\Pitch('A', 0, 4),
				'expected' => 69
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 7),
				'expected' => 96
			),
			array(
				'pitch' => new ianring\Pitch('G', 0, 9),
				'expected' => 127
			),
		);
	}


}
