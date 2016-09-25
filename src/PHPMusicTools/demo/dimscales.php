<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

?><html>
<head>
    <meta name="viewport" content="initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 1.0, user-scalable = no">

<?php

require_once '../PHPMusicTools.php';

$scale = new Scale(array(
	'root' => new Pitch('C4'),
	'mode' => 'prometheus'
));

$measureOptions = array(
	'divisions' => 4,
	'key' => new Key('C major'),
	'time' => array(
//		'symbol' => 'common', // omit this to represent a normal signature
		'beats' => 6,
		'beat-type' => 8
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


$pitches = $scale->getPitches();
//var_dump($pitches);


$score = new Score();
$part = new Part('Piano');
$measure = new Measure($measureOptions);
$layer = new Layer();

foreach ($pitches as $pitch) {
	$note = new Note(
		array(
			'pitch' => $pitch,
			'duration' => 4,
			'type' => 'quarter'
		)
	);		
	$layer->addNote($note); // this essentially adds a chord with only one note in it.
}

$measure->addLayer($layer);

$newlayer = clone $layer;
$newlayer->transpose(-12);
$measure->addLayer($newlayer);

// puts this layer in staff two
$newlayer->setStaff(2);


for($i=0; $i<12; $i++) {
	$newmeasure = clone $measure;
	$newmeasure->transpose($i, -1);
	$part->addMeasure($newmeasure);
}

$score->addPart($part);

$xml2 = $score->toXML();

?>
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

<textarea style="width:800px;height:400px;"><?php echo htmlspecialchars($xml2); ?></textarea>

  </body>
</html>
