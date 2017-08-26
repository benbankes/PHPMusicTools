<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once '../../src/PHPMusicTools/classes/LilypondObject.php';


echo 'example 1';

$lilypond = new ianring\LilypondObject();

// nowdoc syntax means this won't parse the contents
$text = <<<'EOD'
\version "2.18.2"

rhMusic = \relative c'' {
  \new Voice {
    r2 c4.\( g8 |
    \once \override Tie.staff-position = #3.5
    bes1~ |
    \bar "||"
    \time 6/4
    bes2. r8
    % Start polyphonic section of four voices
    <<
      { c,8 d fis bes a }  % continuation of main voice
      \new Voice {
        \voiceTwo
        c,8~ c2
      }
      \new Voice {
        \voiceThree
        s8 d2
      }
      \new Voice {
        \voiceFour
        s4 fis4.
      }
    >> |
    g2.\)  % continuation of main voice
  }
}

lhMusic = \relative c' {
  r2 <c g ees>2( |
  <d g, d>1)\arpeggio |
  r2. d,,4 r4 r |
  r4
}

\score {
  \new PianoStaff <<
    \new Staff = "RH"  <<
      \key g \minor
      \rhMusic
    >>
    \new Staff = "LH" <<
      \key g \minor
      \clef "bass"
      \lhMusic
    >>
  >>
}

EOD;

echo '<pre>';
echo htmlspecialchars($text);
// $text = str_replace('<', '\\<', $text);
// $text = str_replace('>', '\\>', $text);

$lilypond->set($text);

$png = $lilypond->render('png');

//var_dump($png);

echo '<img src="data:image/png;base64,'.(base64_encode($png)).'">';
