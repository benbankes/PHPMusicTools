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



}
