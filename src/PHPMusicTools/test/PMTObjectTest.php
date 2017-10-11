<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/PMTObject.php';

class PMTObjectTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	/**
	 * @dataProvider provider_truemod
	 */
	public function test_truemod($num, $mod, $expected) {
		$actual = \ianring\PMTObject::_truemod($num, $mod);
		$this->assertEquals($expected, $actual);
	}
	function provider_truemod() {
		return array(
			array('num' => 5, 'mod' => 5, 'expected' => 0),
			array('num' => 0, 'mod' => 5, 'expected' => 0),
			array('num' => 1, 'mod' => 5, 'expected' => 1),
			array('num' => -1, 'mod' => 5, 'expected' => 4),
			array('num' => -5, 'mod' => 5, 'expected' => 0),
			array('num' => -50000, 'mod' => 5, 'expected' => 0),
			array('num' => -50001, 'mod' => 5, 'expected' => 4),
			array('num' => 19, 'mod' => 12, 'expected' => 7),
			array('num' => -3, 'mod' => 12, 'expected' => 9),
		);
	}


	/**
	 * @dataProvider provider_truemodDiff12
	 */
	public function test_truemodDiff12($a, $b, $expected) {
		$actual = \ianring\PMTObject::_truemodDiff12($a, $b);
		$this->assertEquals($expected, $actual);
	}
	function provider_truemodDiff12() {
		return array(
			array('a' => 5, 'b' => 5, 'expected' => 0),
			array('a' => 1, 'b' => 3, 'expected' => 2),
			array('a' => 0, 'b' => 6, 'expected' => 6),
			array('a' => 0, 'b' => 7, 'expected' => 7),
			array('a' => 2, 'b' => 10, 'expected' => 8),
			array('a' => 10, 'b' => 2, 'expected' => 4),
			array('a' => 10, 'b' => 1, 'expected' => 3),
		);
	}

}
