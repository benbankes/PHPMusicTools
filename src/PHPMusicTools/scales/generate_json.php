<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

$maxinterval = 4;

$allscales = range(0, 4095);

// remove any that do not include the root
foreach ($allscales as $index => $set) {
	if ((1 & $index) == 0) {
		unset($allscales[$index]);
	}
}

// for convenience we'll populate the array with the set of tones that are turned on
foreach ($allscales as $index => $set) {
	$allscales[$index] = array();
	$newset = array();
	for ($i = 0; $i < 12; $i++) {
		if ($index & (1 << ($i))) {
			$newset[] = $i;
		}
	}
	$allscales[$index]['tones'] = $newset;
}

foreach ($allscales as $index => $set) {
	$setsize = count($set['tones']);
	for ($i = 0; $i < $setsize-1; $i++) {
		// find the distance between this note and the one above it
		if ($set['tones'][$i+1] - $set['tones'][$i] > $maxinterval) {
			unset($allscales[$index]);
		}
	}
	// and check the last one too
	if (12 - $set['tones'][$setsize-1] > $maxinterval) {
		unset($allscales[$index]);
	}
}

foreach ($allscales as $index => $set) {
	$note_count = count($set['tones']);
	$m = find_modes_and_symmetries($index);

	$allscales[$index]['modes'] = $m['modes'];
	$allscales[$index]['symmetries'] = $m['symmetries'];
	$imperfections = find_imperfections($set['tones']);
	$allscales[$index]['imperfections'] = $imperfections;

}

foreach ($allscales as $index => $set) {
	$allscales[$index]['names'] = name($index);
}

echo '<pre>';
print_r($allscales);

$json = json_encode($allscales);
file_put_contents('scales.json', $json);


// --------
// now generate the file with the modal families
// 

$modeList = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());
$all = $allscales;
while (count($all) > 0) {
	$set = array_pop($all);
	$modes = $set['modes'];
	$notecount = count($set['tones']);
	$modeList[$notecount][] = array_unique($modes);
	foreach ($modes as $mode) {
		unset($all[$mode]);
	}
}

echo '<pre>';
print_r($modeList);

$json = json_encode($modeList);
file_put_contents('modes.json', $json);







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

function name($index) {
	$names = array(
		273 => array('augmented triad'),
		585 => array('diminished seventh'),
		1123 => array('iwato'),
		1186 => array('insen'),
		1365 => array('whole tone'),
		1371 => array('altered', 'altered dominant', 'super locrian'),
		1387 => array('locrian'),
		1389 => array('half diminished', 'Locrian ♮2'),
		1451 => array('phrygian'),
		1453 => array('aeolian', 'natural minor'),
		1459 => array('phrygian dominant', 'spanish romani'),
		1485 => array('aolian #4', 'romani scale'),
		1707 => array('phrygian ♮6', 'dorian ♭2'),
		1709 => array('dorian'),
		1717 => array('mixolydian'),
		1741 => array('ukranian dorian','romanian scale','altered dorian'),
		1749 => array('acoustic', 'lydian dominant', 'lydian ♭7', 'mixolydian ♯4'),
		2257 => array('hirajoshi'),
		2733 => array('heptatonia seconda', 'ascending melodic minor', 'jazz minor'),
		2741 => array('major' ,'ionian'),
		2773 => array('lydian'),
		2483 => array('enigmatic'),
		2457 => array('augmented'),
		2477 => array('harmonic minor'),
		2483 => array('flamenco mode'),
		2509 => array('hungarian minor'),
		2901 => array('lydian augmented', 'lydian ♯5'),
		2731 => array('major neapolitan'),
		2475 => array('minor neapolitan'),
		2483 => array('double harmonic'),
		3669 => array('prometheus'),
		1235 => array('tritone scale'),
		1755 => array('octatonic', 'second mode of limited transposition'),
		3549 => array('third mode of limited transposition'),
		2535 => array('fourth mode of limited transposition'),
		2275 => array('fifth mode of limited transposition'),
		3445 => array('sixth mode of limited transposition'),
		3055 => array('seventh mode of limited transposition'),
		3765 => array('bebop dominant'),
		4095 => array('chromatic 12-tone'),
	);
	if (isset($names[$index])) {
		return $names[$index];
	} else {
		return array();
	}
}
