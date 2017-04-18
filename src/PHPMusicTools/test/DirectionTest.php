<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Direction.php';

class DirectionTest extends PHPMusicToolsTest {

	protected function setUp(){}

	public function test_constructFromArray(){

		$direction = \ianring\Direction::constructFromArray(array(
			'directionType' => 'metronome',
			'placement' => 1, 
			'staff' => 2, 
			'parentheses' => false, 
			'beatUnit' => 4, 
			'perMinute' => 120
		));

		$this->assertInstanceOf(\ianring\DirectionMetronome::class, $direction);

		$direction = \ianring\Direction::constructFromArray(array(
			'directionType' => 'accordion-registration',
			'registration' => 'high',
		));

		$this->assertInstanceOf(\ianring\DirectionAccordionRegistration::class, $direction);
		$this->assertEquals('high', $direction->registration);

	}
}