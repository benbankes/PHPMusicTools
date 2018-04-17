<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/ChordName.php';

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


	/**
	 * @dataProvider provider_harmonyTriadName
	 */
	public function test_harmonyTriadName($chord, $tonic, $expected) {
		$actual = \ianring\ChordName::harmonyTriadName($chord, $tonic);
		$this->assertEquals($actual, $expected);
	}
	public function provider_harmonyTriadName() {
		return array(
			array( 
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'A', 'alter' => -1, 'octave' => 3)),
						array('pitch' => array('step' => 'C', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'E', 'alter' => -1, 'octave' => 4))
					)
				)),
				'pitch' => new \ianring\Pitch('A',-1,null),
				'expected' => 'I'
			),
			array( 
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'D', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'F', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'A', 'alter' => 0, 'octave' => 4))
					)
				)),
				'pitch' => new \ianring\Pitch('C',0,null),
				'expected' => 'ii'
			),
			array( 
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'D', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'F', 'alter' => 1, 'octave' => 4)),
						array('pitch' => array('step' => 'A', 'alter' => 0, 'octave' => 4))
					)
				)),
				'pitch' => new \ianring\Pitch('C',0,null),
				'expected' => 'II'
			),
			array( 
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'E', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'G', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'B', 'alter' => -1, 'octave' => 4))
					)
				)),
				'pitch' => new \ianring\Pitch('C',0,null),
				'expected' => 'iii°'
			),
			array( 
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'G', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'B', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'D', 'alter' => 1, 'octave' => 5))
					)
				)),
				'pitch' => new \ianring\Pitch('D',0,null),
				'expected' => 'IV+'
			),
			array( 
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'D', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'F', 'alter' => 1, 'octave' => 4)),
						array('pitch' => array('step' => 'A', 'alter' => -1, 'octave' => 4))
					)
				)),
				'pitch' => new \ianring\Pitch('C',0,null),
				'expected' => 'II♭5'
			),
			array( 
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'B', 'alter' => 0, 'octave' => 3)),
						array('pitch' => array('step' => 'D', 'alter' => -1, 'octave' => 4)),
						array('pitch' => array('step' => 'F', 'alter' => 0, 'octave' => 4))
					)
				)),
				'pitch' => new \ianring\Pitch('B',0,null),
				'expected' => 'i°♭3'
			),
			array( 
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'B', 'alter' => 0, 'octave' => 3)),
						array('pitch' => array('step' => 'D', 'alter' => -1, 'octave' => 4)),
						array('pitch' => array('step' => 'F', 'alter' => 0, 'octave' => 4))
					)
				)),
				'pitch' => new \ianring\Pitch('A',0,null),
				'expected' => 'ii°♭3'
			),
			'unidentifiable' => array( 
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'B', 'alter' => 0, 'octave' => 3)),
						array('pitch' => array('step' => 'C', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'F', 'alter' => -2, 'octave' => 4))
					)
				)),
				'pitch' => new \ianring\Pitch('G',0,null),
				'expected' => ''
			),


		);
	}




}
