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
		$p = new \ianring\PMTObject();
		$actual = $p->_truemod($num, $mod);
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

}
