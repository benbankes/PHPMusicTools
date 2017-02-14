<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Note.php';

class NoteTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function test_constructFromArray(){

		$input = array(
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
		);

		$note = \ianring\Note::constructFromArray($input);

		$this->assertInstanceOf(\ianring\Note::class, $note);
			$this->assertObjectHasAttribute('pitch', $note);
			$this->assertInstanceOf(\ianring\Pitch::class, $note->pitch);
			$this->assertEquals(false, $note->rest);
			$this->assertEquals(4, $note->pitch->octave);

			$this->assertInstanceOf(\ianring\TimeModification::class, $note->timeModification);

			$this->assertInstanceOf(\ianring\Articulation::class, $note->articulations[0]);
			$this->assertInstanceOf(\ianring\Articulation::class, $note->articulations[1]);

			$this->assertInstanceOf(\ianring\NoteBeam::class, $note->beams[0]);
			$this->assertEquals('begin', $note->beams[0]->type);
			$this->assertEquals(1, $note->beams[0]->number);
			$this->assertInstanceOf(\ianring\NoteBeam::class, $note->beams[1]);
			$this->assertEquals(2, $note->beams[1]->number);

			$this->assertEquals(false, $note->rest);



	}



}

