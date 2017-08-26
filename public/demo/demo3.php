<?php
use ianring\PHPMusicTools;

error_reporting(E_ALL);
ini_set('display_errors', 1);

//require_once SITE_ROOT . '/current/vendor/autoload.php';

require_once '../PHPMusicTools.php';

$score = new Score();
$part = new Part('Viola');

$measure = new Measure(
	array(
		'divisions' => 24,
	)
);
$measure->addNotes(array(
  new Note(array('pitch' => 'C4', 'duration' => 4)),
  new Note(array('pitch' => 'D4', 'duration' => 4)),
  new Note(array('pitch' => 'E4', 'duration' => 4)),
  new Note(array('pitch' => 'F4', 'duration' => 4)),
));
$part->addMeasure($measure);

$measure = new Measure();
$measure->addNotes(array(
  new Note(array('pitch' => 'G4', 'duration' => 4)),
  new Note(array('pitch' => 'A4', 'duration' => 4)),
  new Note(array('pitch' => 'B4', 'duration' => 4)),
  new Note(array('pitch' => 'C5', 'duration' => 4)),  
));

$part->addMeasure($measure);

$score->addPart($part);
$part->addMeasure($measure);
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
