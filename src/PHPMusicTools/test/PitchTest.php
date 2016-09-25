<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Pitch.php';

class PitchTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function testConstructFromArray(){
		$input = array(
			'step' => 'C',
			'alter' => 1,
			'octave' => 4,
		);

		$pitch = \ianring\Pitch::constructFromArray($input);

		$this->assertInstanceOf(\ianring\Pitch::class, $pitch);
		$this->assertObjectHasAttribute('step', $pitch);
		$this->assertObjectHasAttribute('alter', $pitch);
		$this->assertObjectHasAttribute('octave', $pitch);
		$this->assertEquals('C', $pitch->step);
		$this->assertEquals(1, $pitch->alter);
		$this->assertEquals(4, $pitch->octave);
	}

	/**
	 * [testPitchConstruction description]
	 * @return [type] [description]
	 * @dataProvider pitchConstructionProvider
	 */
	public function testConstructFromString($string, $expected) {
		$this->markTestSkipped();
		$pitch = \ianring\Pitch::constructFromString($string);
		$this->assertEquals($expected, $pitch);
	}
	function pitchConstructionProvider() {
		return array(
			array(
				'args' => 'C4',
				'expected' => new \ianring\Pitch('C', 0, 4)
			),
			array(
				'args' => 'C-4',
				'expected' => new \ianring\Pitch('C', -1, 4)
			),
			array(
				'args' => 'Cb4',
				'expected' => new \ianring\Pitch('C', -1, 4)
			),
			array(
				'args' => 'Bbb5',
				'expected' => new \ianring\Pitch('B', -2, 5)
			),
			array(
				'args' => 'G#2',
				'expected' => new \ianring\Pitch('G', 1, 2)
			),
			array(
				'args' => 'A+3',
				'expected' => new \ianring\Pitch('A', 1, 3)
			),
			array(
				'args' => 'G##2',
				'expected' => new \ianring\Pitch('G', 2, 2)
			),
			array(
				'args' => 'A++3',
				'expected' => new \ianring\Pitch('A', 2, 3)
			),
			array(
				'args' => 'A--3',
				'expected' => new \ianring\Pitch('A', -2, 3)
			),
		);
	}

	/**
	 * tests that the isHigherThan and isLowerThan functions both work OK
	 * @dataProvider isHigherThanProvider
	 */
	public function testIsHigherThan($pitch1, $pitch2, $expected) {
		$actual = $pitch1->isHigherThan($pitch2);
		$this->assertEquals($actual, $expected);

		// let's test isLowerThan at the same time
		$actual = $pitch2->isLowerthan($pitch1);
		$this->assertEquals($actual, $expected);
	}
	function isHigherThanProvider() {
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
	 * [testTranspose description]
	 * @dataProvider transposeProvider
	 */
	public function testTranspose($pitch, $interval, $preferredAlteration, $expected) {
		$pitch->transpose($interval, $preferredAlteration);
		$this->assertTrue($pitch->equals($expected));
	}
	function transposeProvider() {
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
	 * tests that the isHigherThan and isLowerThan functions both work OK
	 * @dataProvider isEnharmonicProvider
	 */
	public function testIsEnharmonic($pitch1, $pitch2, $expected) {
		$actual = $pitch1->isEnharmonic($pitch2);
		$this->assertEquals($actual, $expected);
	}
	function isEnharmonicProvider() {
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
	 * tests that the isHigherThan and isLowerThan functions both work OK
	 * @dataProvider closestUpProvider
	 */
	public function testClosestUp($pitch1, $step, $alter, $expected) {
		$actual = $pitch1->closestUp($step, $alter);
		$this->assertTrue($actual->equals($expected));
	}
	function closestUpProvider() {
		return array(
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'step' => 'E',
				'alter' => 0,
				'expected' => new ianring\Pitch('E', 0, 4),
			),
			array(
				'pitch1' => new ianring\Pitch('C', 0, 4),
				'step' => 'C',
				'alter' => 1,
				'expected' => new ianring\Pitch('C', 1, 4),
			),
			array(
				'pitch1' => new ianring\Pitch('C', 1, 4),
				'step' => 'C',
				'alter' => 0,
				'expected' => new ianring\Pitch('C', 0, 5),
			),
			array(
				'pitch1' => new ianring\Pitch('B', 0, 4),
				'step' => 'C',
				'alter' => 0,
				'expected' => new ianring\Pitch('C', 0, 5),
			),

			array(
				'pitch1' => new ianring\Pitch('B', 1, 4), // b sharp 4 to the nearest C...
				'step' => 'C',
				'alter' => 0,
				'expected' => new ianring\Pitch('C', 0, 4),
			),
			array(
				'pitch1' => new ianring\Pitch('B', 1, 4), // b sharp 4 to the C flat
				'step' => 'C',
				'alter' => -1,
				'expected' => new ianring\Pitch('B', 1, 4),
			)
		);
	}

}
