<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Scale.php';
require_once '../classes/Pitch.php';

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
	 * @dataProvider providerLevenshtein
	 */
	public function testLevenshteinScale($scale1, $scale2, $expected) {
		$actual = ianring\Scale::levenshtein_scale($scale1, $scale2);
		$this->assertEquals($actual, $expected);
	}
	public function providerLevenshtein() {
		return array(
			// all the scales that are a distance of 1 from the major scale, 2741
			array('scale1' => 2741, 'scale2' => 2743, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2739, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2745, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2737, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2749, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2733, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2725, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2773, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2709, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2805, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2677, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2869, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2613, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2997, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2485, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 3253, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2229, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 3765, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 1717, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 693, 'expected' => 1),

			// same in reverse
			array('scale1' => 2743, 'scale2' => 2741, 'expected' => 1),

			array('scale1' => 325, 'scale2' => 4095, 'expected' => 8),
			array('scale1' => 273, 'scale2' => 4095, 'expected' => 9),
			array('scale1' => 3549, 'scale2' => 4095, 'expected' => 3),
			array('scale1' => 3549, 'scale2' => 3003, 'expected' => 3),
			array('scale1' => 585, 'scale2' => 273, 'expected' => 3),
			array('scale1' => 273, 'scale2' => 585, 'expected' => 3),

			array('scale1' => 3935, 'scale2' => 4031, 'expected' => 2),
			array('scale1' => 3935, 'scale2' => 3871, 'expected' => 1),

		);
	}

	/**
	 * @dataProvider providerNormalizeScalePitches
	 */
	public function testNormalizeScalePitches($scale, $pitches, $expected) {
		$scale = new \ianring\Scale($scale, $pitches[0]);
		$actual = $scale->_normalizeScalePitches($pitches);
		$this->assertEquals($expected, $actual);
	}
	public function providerNormalizeScalePitches() {
		return array(
			array(
				'scale' => 2741,
				'pitches' => array(
					new \ianring\Pitch('C', 0, 3),
					new \ianring\Pitch('D', 0, 3),
					new \ianring\Pitch('F', -1, 3), // this one should change to an E natural
					new \ianring\Pitch('F', 0, 3),
					new \ianring\Pitch('G', 0, 3),
					new \ianring\Pitch('A', 0, 3),
					new \ianring\Pitch('B', 0, 3),
				),
				'expected' => array(
					new \ianring\Pitch('C', 0, 3),
					new \ianring\Pitch('D', 0, 3),
					new \ianring\Pitch('E', 0, 3),
					new \ianring\Pitch('F', 0, 3),
					new \ianring\Pitch('G', 0, 3),
					new \ianring\Pitch('A', 0, 3),
					new \ianring\Pitch('B', 0, 3),
				)
			),
			array(
				'scale' => 2741,
				'pitches' => array(
					new \ianring\Pitch('C', 1, 3),
					new \ianring\Pitch('D', 1, 3),
					new \ianring\Pitch('F', 0, 3), // this one should change to an E sharp
					new \ianring\Pitch('F', 1, 3),
					new \ianring\Pitch('G', 1, 3),
					new \ianring\Pitch('A', 1, 3),
					new \ianring\Pitch('C', 0, 4), // this should become a B sharp
				),
				'expected' => array(
					new \ianring\Pitch('C', 1, 3),
					new \ianring\Pitch('D', 1, 3),
					new \ianring\Pitch('E', 1, 3),
					new \ianring\Pitch('F', 1, 3),
					new \ianring\Pitch('G', 1, 3),
					new \ianring\Pitch('A', 1, 3),
					new \ianring\Pitch('B', 1, 4),
				)
			)
		);
	}


}
