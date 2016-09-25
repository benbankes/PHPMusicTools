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



}
