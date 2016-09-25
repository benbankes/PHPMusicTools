<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Chord.php';

class ChordTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function testConstructFromArray(){

		$input = array(
			'notes' => array(
				0 => array(
					'pitch' => array(
						'step' => 'C',
						'alter' => 1,
						'octave' => 4,
					),
					'rest' => false,
					'duration' => 4,
					'voice' => 1,
					'type' => 'eighth',
					'accidental' => 'sharp',
					'dot' => false,
					'tie' => true,
					'timeModification' => array(
						'actualNotes' => 3,
						'normalNotes' => 2,
					),
					'defaultX' => 3,
					'defaultY' => 1,
					'chord' => true,
					'notations' => array(
						0 => array(
							'notationType' => 'tuplet',
							'number' => 1,
							'type' => 'stop',
						),
						1 => array(
							'notationType' => 'slur',
							'type' => 'start',
							'number' => 1,
						)
					),
					'articulations' => array(
						0 => array('articulationType' => 'staccato'),
						1 => array(
							'articulationType' => 'accent',
							'defaultX' => -1,
							'defaultY' => -71,
							'placement' => 'below',
						),
					),
					'staff' => 1,
					'beams' => array(
						0 => array(
							'number' => 1,
							'type' => 'begin',
						),
						1 => array(
							'number' => 2,
							'type' => 'begin',
						)
					),
					'stem' => array(
						'defaultX' => 2,
						'defaultY' => 3,
						'direction' => 'up',
					)
				),
				1 => array(
					'pitch' => array(
						'step' => 'C',
						'alter' => 1,
						'octave' => 4,
					),
					'rest' => false,
					'duration' => 4,
					'voice' => 1,
					'type' => 'eighth',
					'staff' => 1,
				)			
			)
		);

		$chord = \ianring\Chord::constructFromArray($input);

        $this->assertObjectHasAttribute('notes', $chord);
        $this->assertObjectHasAttribute('pitch', $chord->notes[0]);
		$this->assertInstanceOf(\ianring\Pitch::class, $chord->notes[0]->pitch);
		$this->assertEquals(false, $chord->notes[0]->rest);
		$this->assertEquals(4, $chord->notes[0]->pitch->octave);

		$this->assertInstanceOf(\ianring\TimeModification::class, $chord->notes[0]->timeModification);

		$this->assertInstanceOf(\ianring\Articulation::class, $chord->notes[0]->articulations[0]);
		$this->assertInstanceOf(\ianring\Articulation::class, $chord->notes[0]->articulations[1]);

		$this->assertInstanceOf(\ianring\NoteBeam::class, $chord->notes[0]->beams[0]);
		$this->assertEquals('begin', $chord->notes[0]->beams[0]->type);
		$this->assertEquals(1, $chord->notes[0]->beams[0]->number);
		$this->assertInstanceOf(\ianring\NoteBeam::class, $chord->notes[0]->beams[1]);
		$this->assertEquals(2, $chord->notes[0]->beams[1]->number);

		$this->assertEquals(false, $chord->notes[0]->rest);


	}



}
