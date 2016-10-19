<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Scale.php';

class ScaleTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function testConstructFromArray(){

		$input = array(
			'scale' => 4033,
			'root' => array(
				'step' => 'C',
				'alter' => 1,
				'octave' => 4,
			),
			'direction' => 'ascending'
		);

		$scale = \ianring\Scale::constructFromArray($input);

		$this->assertObjectHasAttribute('scale', $scale);
		$this->assertObjectHasAttribute('root', $scale);
		$this->assertObjectHasAttribute('direction', $scale);

		$this->assertInstanceOf(\ianring\Pitch::class, $scale->root);
		$this->assertEquals('C', $scale->root->step);
		$this->assertEquals(1, $scale->root->alter);
		$this->assertEquals(4, $scale->root->octave);

		$this->assertEquals('ascending', $scale->direction);

	}

	/**
	 * [testPitchConstruction description]
	 * @return [type] [description]
	 * @dataProvider normalizeScalePitchesProvider
	 */
	public function testNormalizeScalePitches($pitches, $expected) {
		$scale = new Scale(2741)
		$actual = _normalizeScalePitches($pitches);
		$this->assertEquals($actual, $expected);
	}
	public function normalizeScalePitchesProvider() {
		return array(
			array(
				'scale' => 2741,
				'pitches' => array(
					new Pitch('C', 0, 3),
					new Pitch('D', 0, 3),
					new Pitch('F', -1, 3), // this one should change to an E natural
					new Pitch('F', 0, 3),
					new Pitch('G', 0, 3),
					new Pitch('A', 0, 3),
					new Pitch('B', 0, 3),
				),
				'expected' => array(
					new Pitch('C', 0, 3),
					new Pitch('D', 0, 3),
					new Pitch('E', 0, 3),
					new Pitch('F', 0, 3),
					new Pitch('G', 0, 3),
					new Pitch('A', 0, 3),
					new Pitch('B', 0, 3),
				)
			),
			array(
				'scale' => 2741,
				'pitches' => array(
					new Pitch('C', 1, 3),
					new Pitch('D', 1, 3),
					new Pitch('F', 0, 3), // this one should change to an E sharp
					new Pitch('F', 1, 3),
					new Pitch('G', 1, 3),
					new Pitch('A', 1, 3),
					new Pitch('C', 0, 4), // this should become a B sharp
				),
				'expected' => array(
					new Pitch('C', 1, 3),
					new Pitch('D', 1, 3),
					new Pitch('E', 1, 3),
					new Pitch('F', 1, 3),
					new Pitch('G', 1, 3),
					new Pitch('A', 1, 3),
					new Pitch('B', 1, 3),
				)
			)
		);
	}


}
