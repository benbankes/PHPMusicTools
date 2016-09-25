<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$json = file_get_contents('scales.json');
$allscales = json_decode($json, true);

$json = file_get_contents('modes.json');
$modes = json_decode($json, true);

$notecount_stats = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0);
$imperfection_stats = array(
	1 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	2 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	3 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	4 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	5 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	6 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	7 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	8 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	9 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	10 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	11 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	12 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
);
$symmetry_stats = array(
	1 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	2 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	3 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	4 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	5 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	6 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	7 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	8 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	9 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	10 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	11 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	12 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
);
$symmetry_sets = array(
	1 => array(),
	2 => array(),
	3 => array(),
	4 => array(),
	6 => array(),
);


?>
<html>
<head>
	<style>
	.red {color:red;}
	td{font-family: monospace;white-space: pre;}
	body {
		width:800px;
		margin:0 auto;
		font-family: helvetica;
	}
	.scale-player{
		color:blue;
		text-decoration: underline;
		cursor: pointer;
	}
	</style>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<!-- polyfill -->
	<script src="../demo/MIDI.js/inc/shim/Base64.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/inc/shim/Base64binary.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/inc/shim/WebAudioAPI.js" type="text/javascript"></script>
	<!-- midi.js package -->
	<script src="../demo/MIDI.js/js/midi/audioDetect.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/gm.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/loader.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/plugin.audiotag.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/plugin.webaudio.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/plugin.webmidi.js" type="text/javascript"></script>
	<!-- utils -->
	<script src="../demo/MIDI.js/js/util/dom_request_xhr.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/util/dom_request_script.js" type="text/javascript"></script>


    <script src="../demo/vexflow/vexflow-debug.js"></script>


</head>
<script type="text/javascript">

window.onload = function () {
	MIDI.loadPlugin({
		soundfontUrl: "../demo/MIDI.js/examples/soundfont/",
		instrument: "acoustic_grand_piano",
		onprogress: function(state, progress) {
			// console.log(state, progress);
		},
		onsuccess: function() {
			// var delay = 0; // play one note every quarter second
			// var note = 50; // the MIDI note
			// var velocity = 127; // how hard the note hits
			// // play the note
			// MIDI.setVolume(0, 127);
			// MIDI.noteOn(0, note, velocity, delay);
			// MIDI.noteOff(0, note, delay + 0.75);
		}
	});
};

</script>
<script>
jQuery(document).ready(function($){

	var notes = [50,51,52,53,54,55,56,57,58,59,60,61];
	var velocity = 127; // how hard the note hits

	$('body').on('click', '.scale-player', function(){
		scale = $(this).data('scale');

		MIDI.setVolume(0, 127);

		var d = 0;
		for (var i = 0; i <= 11; i++) {
			if (scale & (1 << (i))) {
				MIDI.noteOn(0, notes[i], velocity, d);
				MIDI.noteOff(0, notes[i], d + 0.4);
				d = d + 0.5;
			}
		}
		MIDI.noteOn(0, 62, velocity, d);
		MIDI.noteOff(0, 62, d + 0.75);
		d = d + 0.5;
		for (var i = 11; i >= 0; i--) {
			if (scale & (1 << (i))) {
				MIDI.noteOn(0, notes[i], velocity, d);
				MIDI.noteOff(0, notes[i], d + 0.4);
				d = d + 0.5;
			}
		}
	});
});
</script>


</head>
<body>
	<h1>Calculating Scales</h1>

	<p>This exploration of scales is based on work by William Zeitler, as published at <a href="http://allthescales.org/">http://allthescales.org/</a>. In fact much of the material on this page merely repeats Zeitler's findings, presented here along with the source code to generate the scales.</p>

	<h2>What is a scale?</h2>

	<p>The definition of a scale that I will use in this study makes some assumptions:</p>
	<ul>
		<li>
			<p>
				<b>Octave Equivalence</b>
				<br/>We assume that for the purpose of defining a scale, a pitch is functionally equivalent to another pitch separated by an octave. So it follows that if you're playing a scale in one octave, if you continue the pattern into the next octave you will play pitches with the same name.
			</p>
		</li>
		<li>
			<p>
				<b>12 tone equal temperament</b>
				<br/>We're using the 12 tones of an equally-tempered tuning system, as you'd find on a piano. Equal temperament asserts that the perceptual (or functional) relationship between two pitches is the same as the relationship between two other pitches with the same chromatic interval distance. Other tuning systems have the notion of scales, but they're not being considered in this study.
			</p>
		</li>
	</ul>

	<p>The total number of all possible sets of tones that meet the above criteria is the "power set" of the twelve-tone chromatic scale. The number of sets in a power set of size <em>n</em> is (2^n).</p>
	<code>2 ^ 12 = 4096</code>
	<p>so there are 4096 different possible subsets of 12 tones.</p>

	<p>Thanks to the magic of binary math, we can represent these scales by a decimal number, from 0 to 4095. Converted to binary, 0 -> 000000000000 and 4095 -> 111111111111. When represented as bits it reads from right to left - the lowest bit is the root, and each bit going from right to left ascends by one semitone.</p>
	<table class="table" border="1">
		<tr><th>decimal</th><th>binary</th><th></th></tr>
		<tr>
			<td>0</td>
			<td>000000000000</td>
			<td>no notes in the scale</td>
		</tr>
		<tr>
			<td>1</td>
			<td>000000000001</td>
			<td>just the root tone</td>
		</tr>
		<tr>
			<td>1365</td>
			<td>010101010101</td>
			<td>whole tone scale</td>
		</tr>
		<tr>
			<td>2741</td>
			<td>101010110101</td>
			<td>major scale</td>
		</tr>
		<tr>
			<td>4095</td>
			<td>111111111111</td>
			<td>chromatic scale</td>
		</tr>
	</table>

	<p>An aside for number theorists: The important concept here is that any set of tones can be represented by a number. This number is not "ordinal" - it's not merely describing the position of the set in an indexed scheme; it's also not "cardinal" because it's not describing an amount of something. This is a <b>nominal</b> number because it definitively identifies something. But it's more than that - the number IS the scale. Convert it to binary, and the number gives you a mask of tones that are on and off. Given a scale number, you can ascertain everything about that scale from examining the number itself.</p>


<p>Now that we have all the possible subsets of tones, we can whittle down the power set to exclude ones that we don't consider to be a legitimate "scale". We can do this easily with just two rules.</p>
<ul>
	<li>
		<p>
			<b>A scale starts on the root tone.</b>
			<br/>This means any set of notes that doesn't have that first bit turned on is not eligible. This cuts our power set in exactly half, leaving 2048 sets.
		</p>
	</li>

	<li>
		<p>
			<b>A scale does not have any leaps greater than <em>n</em> semitones</b>.
			<br/>For the purposes of this exercise we are saying n = 4, a.k.a. a major third. Any collection of tones that has an interval greater than a major third is not considered a "scale". This configuration is consistent with Zeitler's constant used to generate his comprehensive list of scales.
		</p>
	</li>
</ul>

<table border="1" class="table">
	<thead>
		<tr>
			<th>number of tones</th>
			<th>how many scales</th>
		</tr>
	</thead>
	<tbody>
		<tr><td>1</td><td>0</td></tr><tr><td>2</td><td>0</td></tr><tr><td>3</td><td>1</td></tr><tr><td>4</td><td>31</td></tr><tr><td>5</td><td>155</td></tr><tr><td>6</td><td>336</td></tr><tr><td>7</td><td>413</td></tr><tr><td>8</td><td>322</td></tr><tr><td>9</td><td>165</td></tr><tr><td>10</td><td>55</td></tr><tr><td>11</td><td>11</td></tr><tr><td>12</td><td>1</td></tr>	</tbody>
</table>

<hr/>



<h2>Modes</h2>
<p>To compute a mode of the current scale, we shift all the notes down one semitone. If the result starts on the root tone (meaning, it is a scale), then it is a mode of the original scale.</p>
<pre>
<span class="red">1</span>01010110101 - major scale, "ionian" mode
1<span class="red">1</span>0101011010 - rotated down 1 semitone - not a scale
01<span class="red">1</span>010101101 - rotated down 2 semitones - "dorian"
101<span class="red">1</span>01010110 - rotated down 3 semitones - not a scale
0101<span class="red">1</span>0101011 - rotated down 4 semitones - "phrygian"
10101<span class="red">1</span>010101 - rotated down 5 semitones - "lydian"
110101<span class="red">1</span>01010 - rotated down 6 semitones - not a scale
0110101<span class="red">1</span>0101 - rotated down 7 semitones - "mixolydian"
10110101<span class="red">1</span>010 - rotated down 8 semitones - not a scale
010110101<span class="red">1</span>01 - rotated down 9 semitones - "aeolian"
1010110101<span class="red">1</span>0 - rotated down 10 semitones - not a scale
01010110101<span class="red">1</span> - rotated down 11 semitones - "locrian"
</pre>
<p>When we do this to every scale, we see modal relationships between scales, and we also discover symmetries when a scale is a mode of itself on another degree.</p>

<?php
// aggregate stats

foreach ($allscales as $index => $set) {
	$note_count = count($set['tones']);

	$notecount_stats[$note_count]++;
	$imperfection_stats[$note_count][count($set['imperfections'])]++;
	if (!empty($set['symmetries'])) {
		foreach ($set['symmetries'] as $symmetry) {
			$symmetry_stats[$note_count][$symmetry]++;
		}
	}
}

?>
<h3>Modal Siblings</h3>
<p>We can present the list of all modes grouped in sets of modal siblings</p>

<table border="1">
	<thead>
		<tr>
			<th>Number of tones in the scale</th>
			<th>Number of modal families</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($modes as $num => $list) {
			echo '<tr>';
			echo '<td>'.$num.'</td>';
			echo '<td>'.count($list).'</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>


<?php
foreach ($modes as $num => $list) {
	if (count($list) == 0) {
		continue;
	}
	echo '<h4>'.$num.' tones</h4>';
	echo '<table class="table" border="1">';
	foreach ($list as $group) {
		echo '<tr>';
		echo '<td>';
		foreach ($group as $mode) {
			echo '<a class="scale-player" data-scale="'.$mode.'">';
			echo $mode;
			echo '</a>';
			$name = $allscales[$mode]['names'];
			if (!empty($name)) {
				echo ' (<b>' . implode(', ',$name) . '</b>)';
			}
			echo ', ';
		}
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
}


?>




<hr/>



<h2>Symmetry</h2>

<p>There are two kinds of symmetry of interest to scale analysis. They are <i>rotational symmetry</i> and <i>reflective symmetry</i>.</p>

<p>The set of 12 tones has 5 axes of symmetry. The twelve can be divided by 1, 2, 3, 4, and 6.</p>
<p>Any scale containing symmetry can reproduce its own tones by transposition, and is also called a "mode of limited transposition" (Messaien)</p>
<table class="table" border="1">
	<tr><th>axes of symmetry</th><th>interval of repetition</th><th>scales</th></tr>
	<tr>
		<td>1,2,3,4,5,6,7,8,9,10,11</td>
		<td>semitone</td>
		<td>
		<?php 
		echo implode(', ', array_map(function($scale){
			return '<a class="scale-player" data-scale="'.$scale.'">'.$scale.'</a>';
		}, $symmetry_sets[1]));
		?>
		</td>
	</tr>
	<tr>
		<td>2,4,6,8,10</td>
		<td>whole tone</td>
		<td>
		<?php 
		echo implode(', ', array_map(function($scale){
			return '<a class="scale-player" data-scale="'.$scale.'">'.$scale.'</a>';
		}, $symmetry_sets[2]));
		?>
		</td>
	</tr>
	<tr>
		<td>3,6,9</td>
		<td>minor thirds</td>
		<td>
		<?php 
		echo implode(', ', array_map(function($scale){
			return '<a class="scale-player" data-scale="'.$scale.'">'.$scale.'</a>';
		}, $symmetry_sets[3]));
		?>
		</td>
	</tr>
	<tr>
		<td>4,8</td>
		<td>major thirds</td>
		<td>
		<?php 
		echo implode(', ', array_map(function($scale){
			return '<a class="scale-player" data-scale="'.$scale.'">'.$scale.'</a>';
		}, $symmetry_sets[4]));
		?>
		</td>
	</tr>
	<tr>
		<td>6</td>
		<td>tritones</td>
		<td>
		<?php 
		echo implode(', ', array_map(function($scale){
			return '<a class="scale-player" data-scale="'.$scale.'">'.$scale.'</a>';
		}, $symmetry_sets[6]));
		?>
		</td>
	</tr>
</table>
<br/>

<table class="table" border="1"><thead><tr><th>number of notes in scale</th><th align="center" colspan="12"> Placement of Symmetries </th></tr>
  <tr>
 <?php
  	echo '<th></th>';
 	for ($i = 0; $i < 12; $i++) {
 		echo '<th>'.$i.'</th>';
 	}
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

 for ($r = 1; $r <= 12; $r++) {
 	echo '<tr>';
 	echo '<td';
 	echo '>'.$r.'</td>';
 	for ($i = 0; $i < 12; $i++) {
 		echo '<td';
	 	if ($symmetry_stats[$r][$i] == 0) {
	 		echo ' style="color:#ccc;"';
	 	}
 		echo '>'.$symmetry_stats[$r][$i].'</td>';
 	}
 	echo '</tr>';
 }
?>
</tbody>
</table>



<hr/>




<h2>Imperfection</h2>
<p>Imperfection is a concept invented (so far as I can tell) by William Zeitler, to describe the presence or absense of perfect fifths in the scale tones. Any tone in the scale that does not have the perfect fifth above it represented in the scale is an "imperfect" tone. The number of imperfections is a metric that plausibly correlates with the perception of dissonance in a sonority.</p>
<p>The only scale that has no imperfections is the 12-tone chromatic scale.</p>

<p>This table differs from the one at <a href="allthescales.org">allthescales.org</a>, because this script does not de-duplicate modes. If an imperfection exists in a scale, it will also exist in all the sibling modes of that scale. Hence the single imperfect tone in the 11-tone scale is found 11 times (in 11 scales that are all modally related), whereas Zeitler only counts it as one.</p>
<table class="table" border="1">
  <thead>
  <tr>
	<th>number of notes in scale</th>
    <th align="center" colspan="11"> # of Imperfections </th>
  </tr>
  <tr>
<?php
  	echo '<th></th>';
 	for ($i = 0; $i < 7; $i++) {
 		echo '<th>'.$i.'</th>';
 	}
	echo '</tr>';

	for ($r = 1; $r <= 12; $r++) {
		echo '<tr>';
		echo '<td>'.$r.'</td>';
		for ($i = 0; $i < 7; $i++) {
			echo '<td';
			if ($imperfection_stats[$r][$i] == 0) {
				echo ' style="color:#ccc;"';
			}
			echo '>'.$imperfection_stats[$r][$i].'</td>';
		}
		echo '</tr>';
	}
?>
</table>




<hr/>



<h3>Truncation</h3>
<p>A subset of a scale produced by removing notes is known as a "truncation". The term is usually used in the context of Messaien's Modes of Limited Transposition, where a truncation involves removing notes in a way that preserves its symmetry.</p>
<p>(todo: find truncated relationships between modes)</p>


<hr/>



<h3>Intrascale Distance</h3>
<p>Another interesting relationship between two scales is tonal similarity. You can measure how similar two scales are by using their "edit distance"; determining how many additions, subtractions, or alterations would be needed to transform one into the other.</p>
<p>The operations that constitute a unit of distance would be:
	<ol>
		<li>Removing one tone from the scale</li>
		<li>Adding one tone to the scale</li>
		<li>Transposing one tone up or down a single semitone</li>
	</ol>
</p>
<p>We might guess that two scales that are similar will have a close feeling of sonority, but that's perceptually untrue; the "perceptual colour" of a scale depends very much upon which degrees of the scale are being altered, added, or omitted. Our ears are very sensitive to the difference between major and minor modes, which involve only a semitone shift of the third interval. Similarly, the consonance of a perfect fifth is very different from the dissonance of a diminished fifth, just one semitone below. The same power is weilded by the seventh interval of a scale, which can create quite a different sonority when flattened, because it puts the scale into a Dominant position awaiting resolution.</p>
<p>By contrast, we can alter the second, fourth and sixth degrees of a scale (often voiced as the 9th, 11th and 13th) with less effect on the listener's expectations.</p>
<p>Edit distance is bidirectional. If the distance between A -> B is x, the distance from B -> A will also be x.</p>
<p>The distance between every scale and every other scale, omitting itself, will produce a graph with (((1490)^2) / 2 - 1490) values... that's 1108560 distances.</p>
<p>Given a scale, we can generate all the scales with Levenshtein distance <i>n</i> by performing three operations on it: remove a note, add a note, or modify a note by one semitone, done to all the 12 tones, omitting any that don't apply or that produce duplicates or non-scales. If n > 1, then we recurse. The number of scales with L()=1 for any scale will be different depending on the number of tones in the scale and their placement - and whether operations produce duplicates. Precomputing the graph of L() for distance <i>n</i> will be easier than scanning the scales and measuring the L() distance between every scale and every other scale, though since it's a deterministic operation we only need to do that computation once and the results will be static forever.</p>
<p>It's plausible that it would be easy to modulate from one rooted scale to another that has a small distance, just as it is to modulate to a mode of the current scale. A "comfortable modulation distance" would be the shortest path between one rooted scale and another, using scale modification (edit distance) and modal relationship.</p>
<p>An interesting data set would be to calculate all the proximate scales to a given scale, say at L distance 1 or 2. Also interesting is finding out the distance between named scales, such as the ecclesiatstical and jazz scales.</p>
<p>So, let's do that <a href="levenshtein.php">here</a>.</p>
<hr/>



<?php
echo '<h3>All Scales</h3>';
echo '<table class="table" border="1">';
echo '<tr>';
echo '<th>Count</th>';
echo '<th>Index</th>';
echo '<th>Name</th>';
echo '<th>Tones</th>';
echo '<th>Bitmask</th>';
echo '<th>Notation</th>';
echo '<th>Modes</th>';
echo '<th>Symmetry Axes</th>';
echo '<th>Imperfections</th>';
echo '</tr>';

$num = 1;
foreach ($allscales as $index => $set) {
	echo '<tr>';
	echo '<td>'.$num.'</td>';
	echo '<td><a class="scale-player" data-scale="'.$index.'">' . str_pad($index, 4, '0', STR_PAD_LEFT) . '</a></td>';
	echo '<td>' . implode(', ', $set['names']) . '</td>';

	echo '<td>';
	echo implode(',', $set['tones']);
	echo '</td>';

	echo '<td>' . str_pad(decbin($index), 12, '0', STR_PAD_LEFT).'</td>';

	echo '<td><img src="images/' . $index . '.png" style="width:300px;height:60px;" /></td>';

	echo '<td>';
	echo implode(', ', $set['modes']);
	echo '</td>';
	echo '<td>' . implode(',', $set['symmetries']) . '</td>';
	echo '<td>' . implode(',', $set['imperfections']) . '</td>';
	echo '</tr>';
	$num++;
}
echo '</table>';
echo '<br/><br/>';
?>
</body>
</html>











<?php
function find_imperfections($set) {
	$imperfections = array();
	foreach ($set as $pitch) {
		$fifthabove = ($pitch + 7) % 12;
		if (!in_array($fifthabove, $set)) {
			$imperfections[] = $pitch;
		}
	}
	return $imperfections;
}

function find_modes_and_symmetries($index) {
	$rotateme = $index;
	$modes = array();
//	$modes[] = $index;
	$symmetries = array();
	for ($i = 0; $i < 12; $i++) {
		$rotateme = rotate_bitmask($rotateme);
		if (($rotateme & 1) == 0) {
			continue;
		}
		$modes[] = $rotateme;
		if ($rotateme == $index) {
			if ($i != 11) {
				$symmetries[] = $i+1;
			}
		}
	}
	$output = array('modes' => $modes, 'symmetries' => $symmetries);
	return $output;
}

function rotate_bitmask($bits, $direction = 1) {
	if ($direction == 1) {
		$firstbit = $bits & 1;
		$bits = $bits >> 1;
		$bits = $bits | ($firstbit << 11);
		return $bits;
	} else {
		$firstbit = $bits & (1 << 11);
		$bits = $bits << 1;
		$bits = $bits & ~(1 << 12);
		$bits = $bits | ($firstbit >> 11);
		return $bits;

	}
}


