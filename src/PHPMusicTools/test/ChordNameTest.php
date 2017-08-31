<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/ChordName.php';

class ChordNameTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}


	/**
	 * @dataProvider provider_getName
	 */
	public function test_getName($input, $expected){
		$chord = \ianring\ChordName::constructFromNumber($input);
		$name = $chord->getName();
		$this->assertEquals($name, $expected);
	}
	public function provider_getName() {
		return array(
			array(
				'input' => 273,
				'expected' => 'augmented triad'
			)
		);
	}

		
	/**
	 * @dataProvider provider_getTones
	 */
	public function test_getTones($input, $expected){
		$chord = \ianring\ChordName::constructFromName($input);
		$number = $chord->getTones();
		$this->assertEquals($number, $expected);
	}
	public function provider_getTones() {
		return array(
			array(
				'input' => 'augmented triad',
				'expected' => '273'

			)
		);
	}


	/**
	 * @dataProvider provider_compress
	 */
	public function test_compress($input, $expected) {
		$chord = new ianring\ChordName($input);
		$actual = $chord->compress();
		$this->assertEquals($actual, $expected);
	}
	public function provider_compress() {
		return array(
			array( // major third interval
				'input' => 9,
				'expected' => 9
			),
			array( // major triad
				'input' => 145,
				'expected' => 145
			),
			array( // mjor seventh
				'input' => 2193,
				'expected' => 2193
			),
			array( // major ninth - the ninth should fold down to a second
				'input' => 18577,
				'expected' => 2197
			),
			array( // major eleventh
				'input' => 280721,
				'expected' => 2261
			),
			array( // major thirteenth
				'input' => 2377873,
				'expected' => 2773
			),
			array( // two superimposed whole tone scales
				'input' => 11183445,
				'expected' => 4095
			),
			array( // two superimposed whole tone scales, with an octave between 
				'input' => 45801801045,
				'expected' => 4095
			),

		);
	}


}
