<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Accidental.php';

class AccidentalTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	

	/**
	 * @dataProvider provider_alterToVexFlow
	 */
	public function test_alterToVexFlow($alter, $expected) {
		$result = \ianring\Accidental::alterToVexFlow($alter);
		$this->assertEquals($expected, $result);
	}
	function provider_alterToVexFlow() {
		return array(
			array(
				'alter' => -2,
				'expected' => 'bb'
			),
			array(
				'alter' => -1,
				'expected' => 'b'
			),
			array(
				'alter' => 0,
				'expected' => 'n'
			),
			array(
				'alter' => 1,
				'expected' => '#'
			),
			array(
				'alter' => 2,
				'expected' => '##'
			),
		);
	}

}
