<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/Clef.php';

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

	public function test_constructDefaultFromArray(){
		$clef = \ianring\Clef::constructFromArray();

		$this->assertInstanceOf(\ianring\Clef::class, $clef);
		$this->assertObjectHasAttribute('sign', $clef);
		$this->assertObjectHasAttribute('line', $clef);
		$this->assertEquals('G', $clef->sign);
		$this->assertEquals(4, $clef->line);
		$this->assertEquals(0, $clef->octaveChange);
	}


	/**
	 * @dataProvider provider_resolveClefString
	 */
	public function test_resolveClefString($string, $expected) {
		$result = $chord->analyzeTriad();
		$this->assertEquals($expected, $result);
	}
	function provider_resolveClefString() {
		return array(
			array(
				'string' => '',
				'expected' => array(
					'sign' => 'G',
					'line' => 2
				)
			)

		);
	}

}
