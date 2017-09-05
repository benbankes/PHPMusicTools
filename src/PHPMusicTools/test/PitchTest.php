<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/Pitch.php';

class PitchTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function test_constructFromArray(){		
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
	 * @dataProvider provider_constructFromString
	 */
	public function test_constructFromString($string, $expected) {
		$this->markTestSkipped();
		$pitch = \ianring\Pitch::constructFromString($string);
		$this->assertEquals($expected, $pitch);
	}
	function provider_constructFromString() {
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
	 * @dataProvider provider_isHigherThan
	 */
	public function test_isHigherThan($pitch1, $pitch2, $expected) {
		$actual = $pitch1->isHigherThan($pitch2);
		$this->assertEquals($actual, $expected);

		// let's test isLowerThan at the same time
		$actual = $pitch2->isLowerthan($pitch1);
		$this->assertEquals($actual, $expected);
	}
	function provider_isHigherThan() {
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
	 * @dataProvider provider_equals
	 */
	public function test_equals($pitch1, $pitch2, $expected) {
		$actual = $pitch1->equals($pitch2);
		$this->assertEquals($actual, $expected);
	}
	function provider_equals() {
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
	 * @dataProvider provider_transpose
	 */
	public function test_transpose($pitch, $interval, $preferredAlteration, $expected) {
		$pitch->transpose($interval, $preferredAlteration);
		$this->assertTrue($pitch->equals($expected));
	}
	function provider_transpose() {
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
	 * @dataProvider provider_isEnharmonic
	 */
	public function test_isEnharmonic($pitch1, $pitch2, $expected) {
		$actual = $pitch1->isEnharmonic($pitch2);
		$this->assertEquals($actual, $expected);
	}
	function provider_isEnharmonic() {
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
	 * @dataProvider provider_closestUp
	 */
	public function test_closestUp($pitch1, $step, $alter, $allowEqual, $expected) {
		$actual = $pitch1->closestUp($step, $alter, $allowEqual);
		$this->assertTrue($actual->equals($expected));
	}
	function provider_closestUp() {
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
	 * @dataProvider provider_closestDown
	 */
	public function test_closestDown($pitch1, $step, $alter, $expected) {
		$actual = $pitch1->closestDown($step, $alter);
		$this->assertTrue($actual->equals($expected));
	}
	function provider_closestDown() {
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
	 * @dataProvider provider_toNoteNumber
	 */
	public function test_toNoteNumber($pitch, $expected) {
		$actual = $pitch->toNoteNumber();
		$this->assertEquals($expected, $actual);

	}
	function provider_toNoteNumber() {
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
	 * @dataProvider provider_interval
	 */
	public function test_interval($pitch1, $pitch2, $expected) {
		$actual = $pitch1->interval($pitch2);
		$this->assertEquals($expected, $actual);
	}
	function provider_interval() {
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
	 * @dataProvider provider_enharmonicizeToStep
	 */
	public function test_enharmonicizeToStep($pitch, $step, $expected) {
		$pitch->enharmonicizeToStep($step);
		$this->assertEquals($expected, $pitch);
	}
	function provider_enharmonicizeToStep() {
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
	 * @dataProvider provider_stepUp
	 */
	public function test_stepUp($step, $distance, $expected) {
		$result = ianring\Pitch::stepUp($step, $distance);
		$this->assertEquals($expected, $result);
	}
	function provider_stepUp() {
		return array(
			array(
				'step' => 'D',
				'distance' => 1, 
				'expected' => 'E'
			),
			array(
				'step' => 'G',
				'distance' => 1,
				'expected' => 'A'
			),
			array(
				'step' => 'C',
				'distance' => 1,
				'expected' => 'D'
			),
			array(
				'step' => 'C',
				'distance' => 2,
				'expected' => 'E'
			),
			array(
				'step' => 'C',
				'distance' => 3,
				'expected' => 'F'
			),
			array(
				'step' => 'B',
				'distance' => 3,
				'expected' => 'E'
			),
			array(
				'step' => 'B',
				'distance' => 10,
				'expected' => 'E'
			),
			array(
				'step' => 'B',
				'distance' => 17,
				'expected' => 'E'
			),
		);
	}


	/**
	 * @dataProvider provider_stepDown
	 */
	public function test_stepDown($step, $distance, $expected) {
		$result = ianring\Pitch::stepDown($step, $distance);
		$this->assertEquals($result, $expected);
	}
	function provider_stepDown() {
		return array(
			array(
				'step' => 'E',
				'distance' => 1,
				'expected' => 'D'
			),
			array(
				'step' => 'A',
				'distance' => 1,
				'expected' => 'G'
			),
			array(
				'step' => 'D',
				'distance' => 1,
				'expected' => 'C'
			),
			array(
				'step' => 'D',
				'distance' => 2,
				'expected' => 'B'
			),
			array(
				'step' => 'D',
				'distance' => 5,
				'expected' => 'F'
			),
			array(
				'step' => 'D',
				'distance' => 12,
				'expected' => 'F'
			),
			array(
				'step' => 'D',
				'distance' => 19,
				'expected' => 'F'
			),
		);
	}


	/**
	 * @dataProvider provider_chroma
	 */
	public function test_chroma($pitch, $expected) {
		$result = $pitch->chroma();
		$this->assertEquals($result, $expected);
	}
	function provider_chroma() {
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
	 * @dataProvider provider_toFrequency
	 */
	public function test_toFrequency($pitch, $expected) {
		$result = $pitch->toFrequency();
		$this->assertEquals($result, $expected);
	}
	function provider_toFrequency() {
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
	 * @dataProvider provider_toMidiKeyNumber
	 */
	public function test_toMidiKeyNumber($pitch, $expected) {
		$result = $pitch->toMidiKeyNumber();
		$this->assertEquals($result, $expected);
	}
	function provider_toMidiKeyNumber() {
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

	/**
	 * @dataProvider provider_invert
	 */
	public function test_invert($pitch, $axis, $expected) {
		$result = $pitch->invert($axis);
		$this->assertEquals($expected, $result);
	}
	function provider_invert() {
		return array(
			'C inverted on E becomes G sharp' => array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'axis' => new ianring\Pitch('E', 0, 4),
				'expected' => new ianring\Pitch('G', 1, 4),
			),
			'C sharp inverted on E becomes G' => array(
				'pitch' => new ianring\Pitch('C', 1, 4),
				'axis' => new ianring\Pitch('E', 0, 4),
				'expected' => new ianring\Pitch('G', 0, 4),
			),
			'G sharp inverted on E becomes C' => array(
				'pitch' => new ianring\Pitch('G', 1, 4),
				'axis' => new ianring\Pitch('E', 0, 4),
				'expected' => new ianring\Pitch('C', 0, 4),
			),
			'G inverted on E becomes C sharp' => array(
				'pitch' => new ianring\Pitch('G', 0, 4),
				'axis' => new ianring\Pitch('E', 0, 4),
				'expected' => new ianring\Pitch('C', 1, 4),
			),
			'G inverted on E flat becomes C flat' => array(
				'pitch' => new ianring\Pitch('G', 0, 4),
				'axis' => new ianring\Pitch('E', -1, 4),
				'expected' => new ianring\Pitch('C', -1, 3),
			),
			'B inverted on E flat becomes A double-flat' => array(
				'pitch' => new ianring\Pitch('B', 0, 3),
				'axis' => new ianring\Pitch('E', -1, 4),
				'expected' => new ianring\Pitch('A', -2, 4),
			),
			'pitch inverted upward on an octave is two octaves away' => array(
				'pitch' => new ianring\Pitch('G', 1, 4),
				'axis' => new ianring\Pitch('G', 1, 5),
				'expected' => new ianring\Pitch('G', 1, 6),
			),
			'pitch inverted downward on an octave is two octaves away' => array(
				'pitch' => new ianring\Pitch('G', -1, 6),
				'axis' => new ianring\Pitch('G', -1, 5),
				'expected' => new ianring\Pitch('G', -1, 4),
			),
			'pitch inverted on itself is unchanged' => array(
				'pitch' => new ianring\Pitch('D', 0, 4),
				'axis' => new ianring\Pitch('D', 0, 4),
				'expected' => new ianring\Pitch('D', 0, 4),
			),
			'heightless pitch is unchanged' => array(
				'pitch' => new ianring\Pitch('C', 0, null),
				'axis' => new ianring\Pitch('E', 0, 4),
				'expected' => new ianring\Pitch('C', 0, null),
			),
		);
	}

	/**
	 * @dataProvider provider_stepUpDistance
	 */
	public function test_stepUpDistance($pitch, $step, $expected) {
		$result = $pitch->stepUpDistance($step);
		$this->assertEquals($expected, $result);
	}
	function provider_stepUpDistance() {
		return array(
			'C to G' => array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'step' => 'G',
				'expected' => 4
			),
			'C sharp to G' => array(
				'pitch' => new ianring\Pitch('C', 1, 4),
				'step' => 'G',
				'expected' => 4
			),
			'C flat to G' => array(
				'pitch' => new ianring\Pitch('C', -1, 4),
				'step' => 'G',
				'expected' => 4
			),
			'D up to C' => array(
				'pitch' => new ianring\Pitch('D', 0, 4),
				'step' => 'C',
				'expected' => 6
			),
			'B up to A' => array(
				'pitch' => new ianring\Pitch('B', 0, 4),
				'step' => 'A',
				'expected' => 6
			),
			'B up to D' => array(
				'pitch' => new ianring\Pitch('B', 0, 4),
				'step' => 'D',
				'expected' => 2
			),
			'unison' => array(
				'pitch' => new ianring\Pitch('D', 0, 4),
				'step' => 'D',
				'expected' => 0
			),
			'wtf' => array(
				'pitch' => new ianring\Pitch('G', -1, 4),
				'step' => 'E',
				'expected' => 5
			)

		);
	}


	/**
	 * @dataProvider provider_stepDownDistance
	 */
	public function test_stepDownDistance($pitch, $step, $expected) {
		$result = $pitch->stepDownDistance($step);
		$this->assertEquals($expected, $result);
	}
	function provider_stepDownDistance() {
		return array(
			'G to C' => array(
				'pitch' => new ianring\Pitch('G', 0, 4),
				'step' => 'C',
				'expected' => 4
			),
			'G sharp to C' => array(
				'pitch' => new ianring\Pitch('G', 1, 4),
				'step' => 'C',
				'expected' => 4
			),
			'G flat to C' => array(
				'pitch' => new ianring\Pitch('G', -1, 4),
				'step' => 'C',
				'expected' => 4
			),
			'C down to D' => array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'step' => 'D',
				'expected' => 6
			),
			'A down to B' => array(
				'pitch' => new ianring\Pitch('A', 0, 4),
				'step' => 'B',
				'expected' => 6
			),
			'D down to B' => array(
				'pitch' => new ianring\Pitch('D', 0, 4),
				'step' => 'B',
				'expected' => 2
			),
			'unison' => array(
				'pitch' => new ianring\Pitch('D', 0, 4),
				'step' => 'D',
				'expected' => 0
			),

		);
	}



	/**
	 * @dataProvider provider_toHemholtz
	 */
	public function test_toHemholtz($pitch, $expected) {
		$result = $pitch->toHemholtz();
		$this->assertEquals($expected, $result);
	}
	function provider_toHemholtz() {
		return array(
			'G' => array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'expected' => 'C'
			),
		);
	}


	/**
	 * @dataProvider provider_toVexFlowKey
	 */
	public function test_toVexFlowKey($pitch, $expected) {
		$result = $pitch->toVexFlowKey();
		$this->assertEquals($expected, $result);
	}
	function provider_toVexFlowKey() {
		return array(
			array(
				'pitch' => new ianring\Pitch('C', -2, 4),
				'expected' => 'cbb/4'
			),
			array(
				'pitch' => new ianring\Pitch('C', -1, 4),
				'expected' => 'cb/4'
			),
			array(
				'pitch' => new ianring\Pitch('C', 0, 4),
				'expected' => 'cn/4'
			),
			array(
				'pitch' => new ianring\Pitch('C', 1, 4),
				'expected' => 'c#/4'
			),
			array(
				'pitch' => new ianring\Pitch('C', 2, 4),
				'expected' => 'c##/4'
			),
			array(
				'pitch' => new ianring\Pitch('A', 1, 2),
				'expected' => 'a#/2'
			),
		);
	}


}
