<?php
use ianring\PHPMusicTools;

error_reporting(E_ALL);
ini_set('display_errors', 1);

//require_once SITE_ROOT . '/current/vendor/autoload.php';

require_once('../PHPMusicTools.php');

$score = new ianring\Score();

$measure = ianring\Measure::constructFromArray(
	array(
		'divisions' => 24,
		'direction' => array(
			'placement' => 'below',
			'direction-type' => array(
				'words' => array(
					'default-x' => 0,
					'default-y' => 15,
					'font-size' => 10,
					'font-weight' => 'bold',
					'font-style' => 'italic',
					'text' => 'Andantino'
				)
			),
			'staff' => 1,
			'sound-dynamics' => 40
		),
		'barline' => array(
			array(
				'location' => 'left',
				'bar-style' => 'light-heavy',
				'repeat' => 'forward',
				'ending' => array(
					'type' => 'stop',
					'number' => 1
				)
			),
			array(
				'location' => 'right',
				'bar-style' => 'heavy-light',
				'repeat' => 'backward',
				'ending' => array(
					'type' => 'stop',
					'number' => 1
				)
			)
		),
		'implicit' => true,
		'number' => 1,
		'width' => 180
	)
);

// pitch can be any of the following 
// C4, c+4, C+4, C#4, c#4, c-4, C-4, Cb4, 
// array('step'=>'C','alter'=>-1,'octave'=>4)

$note = ianring\Note::constructFromArray(
	array(
		'pitch' => 'C4',
		'duration' => 4,
		'type' => 'whole'
	)
);

$note = ianring\Note::constructFromArray(
	array(
		'rest' => true,
		'dot' => true,
		'staccato' => true,
		'chord' => false,

		'voice' => 1,
		'staff' => 1, // useful for multistaff parts like piano

		'pitch' => 'C4', // there are many ways to notate this - see above
		'duration' => 4,
		'type' => 'quarter',
		'tied' => 'stop',

		'tuplet' => array(
			'bracket' => 'no',
			'number' => 1,
			'placement' => 'above',
			'type' => 'start'
		),
		'stem' => array( 
			'default-y' => 3, // for up or down stems, measured in 1/10 of an interline space from top of staff
			'direction' => 'up', // up, down, none, or double
		),
		'beams' => array(
			// this can be a single beam, or an array of beams
			array(
				'number' => 1,
				'type' => 'begin'  // begin, continue, end, forward hook, and backward hook
			),
			array(
				'number' => 1,
				'type' => 'begin'  // begin, continue, end, forward hook, and backward hoo
			)
		),
		'accidental' => array(
			'courtesy' => true,
			'editorial' => null,
			'bracket' => false,
			'parentheses' => true,
			'size' => false,
			'type' => 'natural' // sharp, flat, natural, double-sharp, sharp-sharp, flat-flat, natural-sharp, natural-flat, quarter-flat, quarter-sharp, three- quarters-flat, and three-quarters-sharp
		)
	)
);


// a quarter-note triplet
$note = ianring\Note::constructFromArray(
	array(
		'chord' => false, // this indicates that this note is synchonous with the previous one
		'dot' => false,
		'pitch' => 'E4',
		'duration' => 8,
		'type' => 'quarter',
		'time-modification' => array(
			'actual-notes' => 3,
			'normal-notes' => 2,
			'normal-type' => 'eighth' // this tells the parser that it's a quarter note part of an eighth-note triplet
		)
	)
);

$measure->addNote($note);
$note->transpose(2); // transposes the note down 4 semitones
$measure->addNote($note);
$note->transpose(2); // transposes the note down 4 semitones
$measure->addNote($note);


$note = ianring\Note::constructFromArray(
	array(
		'rest' => array(
			'measure' => true // this means the voice is resting for the entire measure
		),
		'duration' => 8,
		'voice' => 1
	)
);


$note = ianring\Note::constructFromArray(
	array(
		'chord' => true, // this indicates that this note is synchonous with the previous one
		'pitch' => 'C4',
		'duration' => 4,
		'type' => 'whole'
	)
);

$direction = ianring\Direction::constructFromArray(
	array(
		'placement' => 'above',
		'direction-type' => array(
			'wedge' => array(
				'default-y' => 20,
				'spread' => 0,
				'type' => 'crescendo' // crescendo, diminuendo, or stop
			)
		),
		'offset' => -8
	)
);

$direction = ianring\Direction::constructFromArray(
	array(
		'placement' => 'above',
		'direction-type' => array(
			'words' => array( // words, dynamics, wedge, segno, coda, rehearsal, dashes, pedal, metronome, and octave-shift
				'default-x' => 15, // units of tenths of interline space
				'default-y' => 15, // units of tenths of interline space
				'font-size' => 9,
				'font-style' => 'italic',
				'words' => 'dolce'
			)
		),
		'offset' => -8
	)
);


// many direction-types can go together into one direction
$direction = ianring\Direction::constructFromArray(
	array(
		'placement' => 'above',
		'direction-type' => array(
			array(
				'words' => array(
					'default-x' => 15, // units of tenths of interline space
					'default-y' => 15, // units of tenths of interline space
					'font-size' => 9,
					'font-style' => 'italic',
					'words' => 'dolce'
				)
			),
			array(
				'wedge' => array(
				)
			),
		),
		'offset' => -8
	)
);

$measure->addNote($note);

$note = ianring\Note::constructFromArray(
	array(
		'pitch' => array(
			'step' => 'C',
			'alter' => -1,
			'octave' => 4
		),
		'duration' => 4,
		'tie' => 'start',
		'type' => 'whole',
		'lyric' => array(
			'syllabic' => 'end',
			'text' => 'meil',
			'extend' => true
		)
	)
);

$note->transpose(4); // transposes the note up 4 semitones
$measure->addNote($note);
$note->transpose(-4); // transposes the note down 4 semitones
$measure->addNote($note);

// backup and forward lets us add "layers" to a measure with independent voicing
// $duration = 16;
// $measure->backup($duration);
// $measure->forward($duration);

$part = new ianring\Part('Viola');
$part->addMeasure($measure);

$score->addPart($part);

$xml2 = $score->toMusicXML('partwise');

?><html>
<head>
    <meta name="viewport" content="initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 1.0, user-scalable = no">

<script src="vexflow/jquery.js"></script>
<script src="vexflow/vexflow-debug.js"></script>

    <script>
	$(document).ready(function() {

		var xml2 = '<?php echo $xml2; ?>';
		var doc = null;
		doc = new Vex.Flow.Document(xml2);
		doc.getFormatter().setWidth(800).draw($("#viewer2")[0]);

	});

    </script>
    <style>
      #viewer {
        width: 100%;
        overflow: hidden;
      }
    </style>
  </head>
  <body>

    <div id="viewer2">
      <p>Please enable JavaScript to use the viewer.</p>
    </div>

    <textarea style="width:800px;height:400px;">
    	<?php echo htmlspecialchars($xml2); ?>
    </textarea>

  </body>
</html>
