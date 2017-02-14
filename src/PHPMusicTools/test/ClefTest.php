<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Clef.php';

class ClefTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function test_constructFromArray(){
		$input = array(
			'sign' => 'G',
			'line' => 2
		);

		$clef = \ianring\Clef::constructFromArray($input);

		$this->assertInstanceOf(\ianring\Clef::class, $clef);
		$this->assertObjectHasAttribute('sign', $clef);
		$this->assertObjectHasAttribute('line', $clef);
		$this->assertEquals('G', $clef->sign);
		$this->assertEquals(2, $clef->line);
	}



}
