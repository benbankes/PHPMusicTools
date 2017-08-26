<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../PHPMusicTools.php';

$measureOptions = array(
	'divisions' => 4,
	'key' => new Key('D major'),
	'time' => array(
		'symbol' => 'common', // omit this to represent a normal signature
		'beats' => 4,
		'beat-type' => 4
	),
	'clef' => array(
		new Clef('treble'),
		new Clef('bass')
	),
	'barline' => array(
		new Barline(
			array(
				'location' => 'left',
				'bar-style' => 'light-heavy',
				'repeat' => array(
					'direction' => 'forward',
					'winged' => 'none'
				),
				'ending' => array(
					'type' => 'stop',
					'number' => 1
				)
			)
		),
		new Barline(
			array(
				'location' => 'right',
				'bar-style' => 'heavy-light',
				'repeat' => array(
					'direction' => 'backward'
				),
				'ending' => array(
					'type' => 'stop',
					'number' => 2
				)
			)
		)
	),
	'implicit' => false,
	'non-controlling' => false,
	'number' => 1,
	'width' => 180
);


$pitches = array(
	array('C4','E4','G4'),
	array('C4','F4','A4'),
	'F4','F#4','F##4','G4','A-4','B4'
);


$score = new Score();
$part = new Part();
$measure = new Measure($measureOptions);
$layer = new Layer();

foreach ($pitches as $pitch) {
	if (is_array($pitch)) {
		$chord = new Chord();
		foreach ($pitch as $p) {
			$note = new Note(
				array(
					'pitch' => $p,
					'duration' => 4,
					'type' => 'quarter'
				)
			);
			$chord->addNote($note);
		}
		$layer->addChord($chord);
	} else {
		$note = new Note(
			array(
				'pitch' => $pitch,
				'duration' => 4,
				'type' => 'quarter'
			)
		);		
		$layer->addNote($note); // this essentially adds a chord with only one note in it.
	}
}
$measure->addLayer($layer);
$part->addMeasure($measure);



// 2nd measure

$measure = new Measure($measureOptions);

$layer = new Layer();
foreach ($pitches as $pitch) {
	if (is_array($pitch)) {
		$chord = new Chord();
		foreach ($pitch as $p) {

			$pitch = new Pitch($p);
			$pitch->transpose(-12);

			$note = new Note(
				array(
					'pitch' => $p,
					'duration' => 4,
					'type' => 'quarter'
				)
			);
			$chord->addNote($note);
		}
		$layer->addChord($chord);
	} else {
		$pitch = new Pitch($pitch);
		$pitch->transpose(-12);

		$note = new Note(
			array(
				'pitch' => $pitch,
				'duration' => 4,
				'type' => 'quarter'
			)
		);		
		$layer->addNote($note);
	}
}
$measure->addLayer($layer);

$part->addMeasure($measure);

// 3nd measure

$measure = new Measure($measureOptions);

$layer = new Layer();
$note = new Note(
	array(
		'pitch' => new Pitch('F2'),
		'duration' => 8,
		'type' => 'half'
	)
);
$layer->addNote($note);
$note = new Note(
	array(
		'pitch' => new Pitch('G2'),
		'duration' => 8,
		'type' => 'half'
	)
);
$layer->addNote($note);

$measure->addLayer($layer);

$layer = new Layer();
$note = new Note(
	array(
		'pitch' => new Pitch('G3'),
		'duration' => 8,
		'type' => 'half'
	)
);
$layer->addNote($note);
$note = new Note(
	array(
		'pitch' => new Pitch('C#3'),
		'duration' => 8,
		'type' => 'half'
	)
);
$layer->addNote($note);

$measure->addLayer($layer);


$part->addMeasure($measure);



$score->addPart($part);

$xml2 = $score->toMusicXML();

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


<pre>
<?php 
var_dump($score);
?>
</pre>

  </body>
</html>
