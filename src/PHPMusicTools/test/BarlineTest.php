<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/Barline.php';

class BarlineTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}

	public function sample() {
		return array(
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
	}
	
	public function test_constructFromArray(){
		$input = $this->sample();

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


	/**
	 * @dataProvider provider_toMusicXML
	 */
	public function test_toMusicXML($input, $expected) {
		$barline = \ianring\Barline::constructFromArray($input);
		$xml = $barline->toMusicXML();
		$this->assertEquals($xml, $expected);
	}
	public function provider_toMusicXML() {
		return array(
			array(
				'input' => array(
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
				),
				'expected' => '<barline location="1"><bar-style>double</bar-style><footnote>wtf</footnote><ending type="final" number="12">12</ending><repeat direction="left" winged="1"></repeat></barline>'
			),
			array(
				'input' => array(
					'location' => 'right',
					'barStyle' => 'regular',
					'segno' => true,
					'coda' => true,
					'fermata' => true,
					'ending' => array(
						'number' => '1,2',
						'type' => 'discontinue'
					),
					'repeat' => array(
						'direction' => 'backward',
						'winged' => 'double-curved',
						'times' => 5
					)
				),
				'expected' => '<barline location="right"><bar-style>regular</bar-style><segno/><coda/><fermata/><ending type="discontinue" number="1,2">1,2</ending><repeat direction="backward" winged="double-curved" times="5"></repeat></barline>'
			),

		);
	}




}
