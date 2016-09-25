<?php
ini_set('memory_limit', '128M');
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', true);

// todo
// figure out a way to calculate the "edit distance" between two scales.
// https://en.wikipedia.org/wiki/Edit_distance
/*
e.g.

    110111010010 - 7 tones
    110010110010 - 6 tones
       ^ ^^     
    seems like three changes needed.
	however, we have a 0->1 change beside a 1->0 change. That's
	equivalent to "moving" one of the scale degrees by a semitone,
	so it counts as 1 operation, not two. 
	the distance here is 2

	101011000110 - 6 tones
	101011001001 - 6 tones
            ^^^^
            two moves

    110100101001 - 6 tones
    110101011001 - 7 tones
         ^^^ 
         one addition, one move

    110100101001 - 6 tones
    110101011001 - 7 tones
XOR
	000001110000 - an XOR shows which bits changed

	*/

// $stats = array();

// $allscales = range(0, 4095);
$allscales = range(0, 700);
foreach ($allscales as $index => $set) {
	if ((1 & $index) == 0) {
		unset($allscales[$index]);
	}
}
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
$maxinterval = 4;
foreach ($allscales as $index => $set) {
	$setsize = count($set['tones']);
	for ($i = 0; $i < $setsize-1; $i++) {
		if ($set['tones'][$i+1] - $set['tones'][$i] > $maxinterval) {
			unset($allscales[$index]);
		}
	}
	if (12 - $set['tones'][$setsize-1] > $maxinterval) {
		unset($allscales[$index]);
	}
}



$numscales = count($allscales);
echo $numscales.' scales';
$gd = imagecreatetruecolor($numscales, $numscales) or die('Cannot Initialize new GD image stream');
imagecolorallocate($gd, 100,100,100);	

$colors = array(
	0 => imagecolorallocate($gd, 255, 255, 255),
	1 => imagecolorallocate($gd, 0, 0, 255),
	2 => imagecolorallocate($gd, 0, 255, 255),
	3 => imagecolorallocate($gd, 0, 255, 0),
	4 => imagecolorallocate($gd, 255, 150, 0),
	5 => imagecolorallocate($gd, 255, 255, 0),
	6 => imagecolorallocate($gd, 255, 0, 0),
);
print_r($colors);

$x = 0;
$y = 0;
foreach ($allscales as $i => $s) {
	echo '.';
	flush();
	$y++;
	$x = 0;
	foreach ($allscales as $j => $q) {
		$x++;
		$dist = edit_distance($i, $j);
		imagesetpixel($gd, $x, $y, $colors[$dist]);
// 		// $stats[$dist]++;
// 		// if ($y > 100) {break;}
// 		// echo $x;
	}
// 	// echo '<br/>'.$y;
// 	// if ($y > 100) { break;}
}

imagetruecolortopalette($gd, false, 255);
$success = imagepng($gd, "./file.png"); 
var_dump($success);
imagedestroy($gd);

echo '<img src="./file.png" />';

// header('Content-Type: image/png');
// imagepng($gd);

// echo '<table>';
// foreach($stats as $num => $stat) {
// 	echo '<tr><td>'.$num.'</td><td>'.$stat.'</td></tr>';
// }
// echo '</table>';


function edit_distance($scale1, $scale2) {

	return rand(0,6);

	$changes = 0;
	$array1 = bits2array($scale1);
	$array2 = bits2array($scale2);
	for ($i=0; $i<count($array1); $i++) {
		if ($array2[$i] != $array1[$i]) {
			if (($array1[$i+1] != $array2[$i+1]) && ($array1[$i] != $array1[$i+1])) {
				$array2[$i+1] = $array1[$i+1];
			}
			$changes++;
		}
	}
	return $changes;
}

function nbit($number, $n) {
	return ($number >> $n-1) & 1;
}

/**
 * converts a bitmask into an array
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function bits2array($value) {
	$array = array();
	$n = 0;
    while ($value) {
        $array[$n] += ($value & 1);
        $value = $value >> 1;
        $n++;
    }
    return $array;
}

/**
 * counts how many bits are on
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function getBitCount($value) {
    $count = 0;
    while($value) {
        $count += ($value & 1);
        $value = $value >> 1;
    }
    return $count;
}
