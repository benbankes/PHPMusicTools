<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Barline.php';

class BarlineTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function test_constructFromArray(){

		$input = array(
			'location' => 1,
			'barStyle' => 'double',
			'footnote' => 'wtf',
			'ending' => array(
				'number' => 12,
				'type' => 'final'
			),
			'repeat' => array(
				'direction' => 'left',
				'winged' => true
			)
		);

		$barline = \ianring\Barline::constructFromArray($input);

		$this->assertObjectHasAttribute('location', $barline);
		$this->assertObjectHasAttribute('barStyle', $barline);
		$this->assertObjectHasAttribute('footnote', $barline);
		$this->assertObjectHasAttribute('ending', $barline);
		$this->assertObjectHasAttribute('repeat', $barline);

		$this->assertEquals(1, $barline->location);
		$this->assertEquals('double', $barline->barStyle);
		$this->assertEquals('wtf', $barline->footnote);

		$this->assertInstanceOf(\ianring\BarlineEnding::class, $barline->ending);
		$this->assertEquals(12, $barline->ending->number);
		$this->assertEquals('final', $barline->ending->type);

		$this->assertInstanceOf(\ianring\BarlineRepeat::class, $barline->repeat);
		$this->assertEquals('left', $barline->repeat->direction);
		$this->assertEquals(true, $barline->repeat->winged);

	}

}
