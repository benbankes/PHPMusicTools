\version "2.19.45"
\pointAndClickOff
\header {
    encodingdate =  "2016-08-06"
    composer =  "Ian Ring"
    copyright =  "© 2016"
    title = "Major Scales"
}
\include "paperlayout.ily"




upper = {
            \autoBeamOn
            \clef "treble" 
            \key cis
            \major 
            \numericTimeSignature
            \time 4/4 | % 1
        
 % going up 
\clef "bass" cis8-2 dis8-3 eis8-1 fis8-2 gis8-3 ais8-4 bis8-1 \clef "treble" cis'8-2 dis'8-3 eis'8-1 fis'8-2 gis'8-3 ais'8-4 bis'8-1 cis''8-2 dis''8-3 eis''8-1 fis''8-2 gis''8-3 ais''8-4 bis''8-1 cis'''8-2 dis'''8-3 eis'''8-1 fis'''8-2 gis'''8-3 ais'''8-4 bis'''8-1 
 % top note 
cis''''8-2
 % going down 
\clef "treble" bis'''8-1 ais'''8-4 gis'''8-3 fis'''8-2 eis'''8-1 dis'''8-3 cis'''8-2 bis''8-1 ais''8-4 gis''8-3 fis''8-2 eis''8-1 dis''8-3 cis''8-2 bis'8-1 ais'8-4 gis'8-3 fis'8-2 eis'8-1 dis'8-3 cis'8-2 \clef "bass" bis8-1 ais8-4 gis8-3 fis8-2 eis8-1 dis8-3 cis8-2 \bar "|."}

lower = {
            \autoBeamOn
            \clef "bass" 
            \key cis,
            \major 
            \numericTimeSignature
            \time 4/4 | % 1
        
 % going up 
\clef "bass" cis,8-2 dis,8-3 eis,8-1 fis,8-2 gis,8-3 ais,8-4 bis,8-1 cis8-2 dis8-3 eis8-1 fis8-2 gis8-3 ais8-4 bis8-1 \clef "treble" cis'8-2 dis'8-3 eis'8-1 fis'8-2 gis'8-3 ais'8-4 bis'8-1 cis''8-2 dis''8-3 eis''8-1 fis''8-2 gis''8-3 ais''8-4 bis''8-1 
 % top note 
cis'''8-2
 % going down 
\clef "treble" bis''8-1 ais''8-4 gis''8-3 fis''8-2 eis''8-1 dis''8-3 cis''8-2 bis'8-1 ais'8-4 gis'8-3 fis'8-2 eis'8-1 dis'8-3 cis'8-2 \clef "bass" bis8-1 ais8-4 gis8-3 fis8-2 eis8-1 dis8-3 cis8-2 bis,8-1 ais,8-4 gis,8-3 fis,8-2 eis,8-1 dis,8-3 cis,8-2 \bar "|."}\score {
    \new PianoStaff <<
    \set PianoStaff.instrumentName = #"Piano  "
    \new Staff = "upper" \upper
    \new Staff = "lower" \lower
  >>
  \layout { }
}