<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Time.php';

class TimeTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function test_constructFromArray(){
		$input = array(
			'symbol' => 'C',
			'beats' => 4,
			'beatType' => 4
		);

		$time = \ianring\Time::constructFromArray($input);

		$this->assertInstanceOf(\ianring\Time::class, $time);
		$this->assertObjectHasAttribute('symbol', $time);
		$this->assertObjectHasAttribute('beats', $time);
		$this->assertObjectHasAttribute('beatType', $time);

		$this->assertEquals('C', $time->symbol);
		$this->assertEquals(4, $time->beats);
		$this->assertEquals(4, $time->beatType);
	}

}
