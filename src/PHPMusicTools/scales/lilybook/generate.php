<?php
namespace ianring;
error_reporting(E_ALL);

require_once '../../classes/Scale.php';

$instruments = array(
    'E Flat Alto Saxophone' => array(
        'low' => new Pitch('D', -1, 3),
        'high' => new Pitch('A', 0, 5),
        'transpose' => 8
    )
);


foreach ($instruments as $instrumentName => $instrument) {
    echo $instrumentName . "\n";
    // loop all keys
    for($i=0; $i<12; $i++) {
        echo $i . "\n";
        print_r($instrument['low']);
        // find the lowest pitch in this instrument's range
        $low = $instrument['low']->closestUp('C', 0);
        print_r($low);
        
    }


}


die();

// in parallel motion
// separated by octaves
// separated by a third
// separated by a sixth
// separated by a tenth
// in double thirds
// contrary motion starting from a unison




$middleC = new Pitch();



$initialnote = 0; // middle C
$root = new Pitch('C', 0, 3);
$clef = ($root->interval($middleC) > 0) ? 'bass' : 'treble';
$righthand = <<<EEE
upper = \\absolute c' {
	\\autoBeamOn
    \\clef "$clef" 
    \\key c 
    \\major 
    \\numericTimeSignature
    \\time 4/4 | % 1
\n
EEE;
//$righthand .= '\\clef "' . $clef . '" ' . "\n";
for ($o=0; $o<4; $o++) {
    $scale = new Scale(2741, $root, 'ascending');
    $pitches = $scale->getPitches();
    foreach ($pitches as $pitch) {
        $newclef = ($root->interval($middleC) > 0) ? 'bass' : 'treble';
        if ($newclef != $clef) {
            $clef = $newclef;
            $righthand .= '\\clef "' . $clef . '" ';
        }
        $righthand .= $pitch->toLilypond() . '8 ';
    }
    $root->transpose(12);
}
$righthand .= '}'. "\n\n";








$definition = <<<EEE
\\score {
    \\new GrandStaff <<
        \\new Staff \\upper
        \\new Staff \\lower    
    >>
    \\layout { }
}
EEE;

$document = getHeader() . $righthand . $lefthand . $definition;

file_put_contents('scales.ly', $document);

shell_exec('lilypond scales.ly');



function getHeader() {
    $header = <<<EEE
\\version "2.19.45"
\\pointAndClickOff
\\header {
    encodingdate =  "2016-08-06"
    composer =  "Ian Ring"
    copyright =  "© 2016"
    title = "Major Scales"
}
\\include "paperlayout.ily"
\n\n
EEE;

return $header;
}
