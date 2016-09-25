\version "2.19.45"
\pointAndClickOff
\header {
    encodingdate =  "2016-08-06"
    composer =  "Ian Ring"
    copyright =  "© 2016"
    title = "Major Scales"
}
\include "paperlayout.ily"


upper = \absolute c' {
	\autoBeamOn
    \clef "bass" 
    \key c 
    \major 
    \numericTimeSignature
    \time 4/4 | % 1

c8 d8 e8 f8 g8 a8 b8 \clef "treble" c'8 d'8 e'8 f'8 g'8 a'8 b'8 c''8 d''8 e''8 f''8 g''8 a''8 b''8 c'''8 d'''8 e'''8 f'''8 g'''8 a'''8 b'''8 }

lower = \absolute c' {
    \autoBeamOn
    \clef "bass" 
    \key c 
    \major 
    \numericTimeSignature
    \time 4/4 | % 1

c,8 d,8 e,8 f,8 g,8 a,8 b,8 c8 d8 e8 f8 g8 a8 b8 \clef "treble" c'8 d'8 e'8 f'8 g'8 a'8 b'8 c''8 d''8 e''8 f''8 g''8 a''8 b''8 }

\score {
    \new GrandStaff <<
        \new Staff \upper
        \new Staff \lower    
    >>
    \layout { }
}