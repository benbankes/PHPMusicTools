<?php
namespace ianring;
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once '../../classes/Scale.php';


// in parallel motion
// separated by octaves
// separated by a third
// separated by a sixth
// separated by a tenth
// in double thirds
// contrary motion starting from a unison



class PianoExercise {

    public function __construct($scalenum, $root, $style = 'parallel octaves', $octaves = 3) {
        $this->scalenum = $scalenum;
        $this->style = $style;
        $this->octaves = $octaves;

        $this->middleC = new Pitch();
        $this->duration = '8';

        $leftroot = $root;
        $rightroot = $root->transpose(12);

        $str = $this->getHeader();
        $str .= $this->getUpper($this->scalenum, $leftroot, $this->octaves);
        $str .= $this->getLower($this->scalenum, $rightroot, $this->octaves);
        $str .= $this->getDefinition();

        echo $str;

        file_put_contents('scales-piano.ly', $str);

        shell_exec('lilypond scales-piano.ly');
    }

    function getUpper($scalenum, $root, $octaves) {
        $key = 

        $hand = 'R';
        $str = "\n\n" . 'upper = {
            \\autoBeamOn
            \\clef "treble" 
            \\key cis
            \\major 
            \\numericTimeSignature
            \\time 4/4 | % 1
        ';
        $str .= $this->getNotesUp($scalenum, $root, $octaves, $hand);
        $str .= $this->getTopNote($scalenum, $root, $octaves, $hand);
        $str .= $this->getNotesDown($scalenum, $root, $octaves, $hand);
        $str .= "\\bar \"|.\"";
        $str .= '}';
        return $str;
    }


    function getLower($scalenum, $root, $octaves) {
        $hand = 'L';
        $str = "\n\n" . 'lower = {
            \\autoBeamOn
            \\clef "bass" 
            \\key c 
            \\major 
            \\numericTimeSignature
            \\time 4/4 | % 1
        ';
        $str .= $this->getNotesUp($scalenum, $root, $octaves, $hand);
        $str .= $this->getTopNote($scalenum, $root, $octaves, $hand);
        $str .= $this->getNotesDown($scalenum, $root, $octaves, $hand);
        $str .= "\\bar \"|.\"";
        $str .= '}';
        return $str;
    }

    /**
     * starting from the bottom and going up, not including the root again at the top
     * @param  [type] $scalenum [description]
     * @param  [type] $root     [description]
     * @param  [type] $octaves  [description]
     * @return [type]           [description]
     */
    function getNotesUp($scalenum, $root, $octaves, $hand) {
        $initialnote = clone($root);
        $str = "\n % going up \n";
        $clef = ''; // this will force a clef to be defined for the first note
        for ($o=0; $o<$octaves; $o++) {
            $scale = new Scale($scalenum, $initialnote, 'ascending');
            $pitches = $scale->getPitches();
            $degree = 0;
            foreach ($pitches as $pitch) {
                $newclef = ($initialnote->interval($this->middleC) > 0) ? 'bass' : 'treble';
                if ($newclef != $clef) {
                    $clef = $newclef;
                    $str .= '\\clef "' . $clef . '" ';
                }
                $str .= $pitch->toLilypond() . $this->duration;

                $isbottom = $pitch->isEnharmonic($root);
                $finger = $this->getFinger($scalenum, $initialnote, $hand, $degree, false, $isbottom);
                $str .= '-' . $finger;

                $str .= ' ';
                $degree++;
            }
            $initialnote->transpose(12);
        }
        return $str;
    }

    /**
     * starting from the bottom and going up, not including the root again at the top
     * @param  [type] $scalenum [description]
     * @param  [type] $root     [description]
     * @param  [type] $octaves  [description]
     * @return [type]           [description]
     */
    function getNotesDown($scalenum, $root, $octaves, $hand) {
        $initialnote = clone($root);
        $initialnote->transpose(12 * ($octaves - 1));
        $str = "\n % going down \n";
        $clef = '';
        for ($o=0; $o<$octaves; $o++) {
            $scale = new Scale($scalenum, $initialnote, 'ascending');

            $pitches = array_reverse($scale->getPitches());
            $degree = 6;
            foreach ($pitches as $pitch) {
                $newclef = ($initialnote->interval($this->middleC) > 0) ? 'bass' : 'treble';
                if ($newclef != $clef) {
                    $clef = $newclef;
                    $str .= '\\clef "' . $clef . '" ';
                }
                $str .= $pitch->toLilypond() . $this->duration;
                $isbottom = $pitch->isEnharmonic($root);
                $finger = $this->getFinger($scalenum, $initialnote, $hand, $degree, false, $isbottom);
                $str .= '-' . $finger;
                $str .=' ';
                $degree--;
            }
            $initialnote->transpose(-12);
        }
        return $str;
    }

    function getTopNote($scalenum, $root, $octaves, $hand) {
        $topnote = clone($root);
        $str = "\n % top note \n";
        $topnote->transpose(12 * $octaves);
        $str .= $topnote->toLilypond() . $this->duration;
        $finger = $this->getFinger($scalenum, $topnote, $hand, 0, true, false);
        $str .= '-' . $finger;
        return $str;
    }

    /**
     * [getFinger description]
     * @param  [type] $scale    [description]
     * @param  [type] $root     [description]
     * @param  [type] $hand     [description]
     * @param  [type] $degree   zero-based degree of the scale
     * @param  [type] $istop    [description]
     * @param  [type] $isbottom [description]
     * @return [type]           [description]
     */
    private function getFinger($scale, $root, $hand, $degree, $istop = false, $isbottom = false) {
        $fingers = array(
            2741 => array(
                // C
                0 => array(
                    'R' => array(
                        'pattern' => array(1,2,3,1,2,3,4),
                        'top' => 5,
                        'bottom' => 1,
                    ),
                    'L' => array(
                        'pattern' => array(1,4,3,2,1,3,2),
                        'top' => 1,
                        'bottom' => 5,
                    )
                ),
                // C# / Db
                1 => array(
                    'R' => array(
                        'pattern' => array(2,3,1,2,3,4,1),
                        'top' => 2,
                        'bottom' => 2
                    ),
                    'L' => array(
                        'pattern' => array(2,3,1,2,3,4,1),
                        'top' => 2,
                        'bottom' => 2
                    ),
                ),
                2 => array(
                    'R' => array(
                        'pattern' => array(2,3,1,2,3,4,1),
                        'top' => 2,
                        'bottom' => 2
                    ),
                    'L' => array(
                        'pattern' => array(2,3,1,2,3,4,1),
                        'top' => 2,
                        'bottom' => 2
                    ),
                ),
            )
        );

        $set = $fingers[$scale][$root->chroma()][$hand];
        if ($istop) {return $set['top'];}
        if ($isbottom) {return $set['bottom'];}
        return ($set['pattern'][$degree]);

    }

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


    function getDefinition() {
        $definition = <<<EEE
\\score {
    \\new PianoStaff <<
    \\set PianoStaff.instrumentName = #"Piano  "
    \\new Staff = "upper" \\upper
    \\new Staff = "lower" \\lower
  >>
  \\layout { }
}
EEE;
     return $definition;    
    }

}



// $root = new Pitch('C', 0, 3);
// $e = new PianoExercise(2741, $root, 'parallel octaves', 3);
$root = new Pitch('C', 1, 3);
$e = new PianoExercise(2741, $root, 'parallel octaves', 3);
