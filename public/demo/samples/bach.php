<?php

$score = array(
  'parts' => array(
    'name' => 'Piano',
    'abbreviation' => 'Pno.',
    'measures' => array(
      0 => array(
        'divisions' => 8,
        'number' => 1,
        'staves' => 2,
        'time' => array(
          'symbol' => 'common',
          'beats' => 4,
          'beat-type' => 4
        ),
        'key' => array(
          'fifths' => 3,
        ),
        'clefs' => array(
          array
            'sign' => 'G',
            'line' => 2
          ),
          array(
            'sign' => 'F',
            'line' => 4
          )
        )
        'layers' => array(
          0 => array(
            'chords' => array(
              'notes' => array(
                0 => array(
                  'rest' => true,
                  'duration' => 4,
                  'voice' => 1,
                  'type' => 'eighth',
                  'staff' => 1
                ),
                1 => array(
                  'pitch' => array( // Pitch
                    'step' => 'C',
                    'octave' => 5
                  ),
                  'duration' => 2,
                  'voice' => 1,
                  'type' => 'sixteenth',
                  'staff' => 1,
                  'beams' => array(
                    0 => array( // NoteBeam
                      'number' => 1,
                      'type' => 'begin'
                    ),
                    1 => array( // NoteBeam
                      'number' => 2,
                      'type' => 'begin'
                    )
                  )
                ),
                2 => array(
                  'pitch' => array( // Pitch
                    'step' => 'B',
                    'octave' => 4,
                    'alter' => 0,
                  ),
                  'accidental' => 'natural',
                  'stem' => 'down',
                  'duration' => 2,
                  'voice' => 1,
                  'type' => 'sixteenth',
                  'staff' => 1,
                  'beams' => array(
                    0 => array( // NoteBeam
                      'number' => 1,
                      'type' => 'end'
                    ),
                    1 => array( // NoteBeam
                      'number' => 2,
                      'type' => 'end'
                    )
                  )
                ),
                3 => array(
                  'pitch' => array( // Pitch
                    'step' => 'C',
                    'octave' => 5
                  ),
                  'duration' => 2,
                  'voice' => 1,
                  'type' => 'sixteenth',
                  'staff' => 1,
                  'beams' => array(
                    0 => array( // NoteBeam
                      'number' => 1,
                      'type' => 'begin'
                    ),
                    1 => array( // NoteBeam
                      'number' => 2,
                      'type' => 'begin'
                    )
                  )
                ),
                4 => array(
                  'pitch' => array( // Pitch
                    'step' => 'D',
                    'octave' => 5
                  ),
                  'duration' => 2,
                  'voice' => 1,
                  'type' => 'sixteenth',
                  'staff' => 1,
                  'beams' => array(
                    0 => array( // NoteBeam
                      'number' => 1,
                      'type' => 'continue'
                    ),
                    1 => array( // NoteBeam
                      'number' => 2,
                      'type' => 'continue'
                    )
                  )
                ),

              )
            )
          )
        )
      )
    )
  )
)


    <measure number="1" width="590.92">
      <note>
        <pitch>
          <step>C</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>G</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>A</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>B</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <accidental>flat</accidental>
        <stem>up</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>A</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <backup>
        <duration>32</duration>
        </backup>
      <note>
        <rest/>
        <duration>32</duration>
        <voice>5</voice>
        <staff>2</staff>
        </note>
      </measure>



    <measure number="2" width="498.69">
      <note>
        <pitch>
          <step>B</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <accidental>natural</accidental>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>A</step>
          <alter>-1</alter>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>G</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>B</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>6</duration>
        <voice>1</voice>
        <type>eighth</type>
        <dot/>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <notations>
          <ornaments>
            <inverted-mordent long="yes" approach="above"/>
            </ornaments>
          </notations>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>5</octave>
          </pitch>
        <duration>1</duration>
        <voice>1</voice>
        <type>32nd</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">begin</beam>
        <beam number="3">begin</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>1</duration>
        <voice>1</voice>
        <type>32nd</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        <beam number="3">end</beam>
        </note>
      <backup>
        <duration>32</duration>
        </backup>
      <note>
        <rest/>
        <duration>32</duration>
        <voice>5</voice>
        <staff>2</staff>
        </note>
      </measure>
    <measure number="3" width="586.28">
      <print new-system="yes">
        <system-layout>
          <system-margins>
            <left-margin>21.00</left-margin>
            <right-margin>0.00</right-margin>
            </system-margins>
          <system-distance>150.00</system-distance>
          </system-layout>
        <staff-layout number="2">
          <staff-distance>65.00</staff-distance>
          </staff-layout>
        </print>
      <attributes>
        <clef number="2">
          <sign>G</sign>
          <line>2</line>
          </clef>
        </attributes>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>5</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>G</step>
          <octave>5</octave>
          </pitch>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        </note>
      <note>
        <pitch>
          <step>G</step>
          <octave>5</octave>
          </pitch>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <notations>
          <ornaments>
            <inverted-mordent/>
            </ornaments>
          </notations>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>5</octave>
          </pitch>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        </note>
      <note>
        <rest/>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <staff>1</staff>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>5</octave>
          </pitch>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <stem>down</stem>
        <staff>1</staff>
        </note>
      <backup>
        <duration>32</duration>
        </backup>
      <note>
        <rest/>
        <duration>4</duration>
        <voice>5</voice>
        <type>eighth</type>
        <staff>2</staff>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>B</step>
          <octave>3</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <accidental>natural</accidental>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>G</step>
          <octave>3</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>A</step>
          <alter>-1</alter>
          <octave>3</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>B</step>
          <alter>-1</alter>
          <octave>3</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <accidental>flat</accidental>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>A</step>
          <alter>-1</alter>
          <octave>3</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>3</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      </measure>
    <measure number="4" width="503.34">
      <note>
        <pitch>
          <step>F</step>
          <octave>5</octave>
          </pitch>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <notations>
          <ornaments>
            <inverted-mordent/>
            </ornaments>
          </notations>
        </note>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>5</octave>
          </pitch>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        </note>
      <note>
        <rest/>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <staff>1</staff>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>5</octave>
          </pitch>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <stem>down</stem>
        <staff>1</staff>
        </note>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>5</octave>
          </pitch>
        <duration>4</duration>
        <voice>1</voice>
        <type>eighth</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        </note>
      <note>
        <pitch>
          <step>A</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>4</duration>
        <tie type="start"/>
        <voice>1</voice>
        <type>eighth</type>
        <stem>down</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <notations>
          <tied type="start"/>
          </notations>
        </note>
      <note>
        <pitch>
          <step>A</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <tie type="stop"/>
        <voice>1</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>1</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        <notations>
          <tied type="stop"/>
          </notations>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>B</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>1</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>1</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>1</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <backup>
        <duration>32</duration>
        </backup>
      <note>
        <pitch>
          <step>B</step>
          <octave>3</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <accidental>natural</accidental>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>A</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>G</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>F</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>E</step>
          <alter>-1</alter>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>B</step>
          <octave>3</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">begin</beam>
        <beam number="2">begin</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">continue</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>4</octave>
          </pitch>
        <duration>2</duration>
        <voice>5</voice>
        <type>16th</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>4</octave>
          </pitch>
        <duration>6</duration>
        <voice>5</voice>
        <type>eighth</type>
        <dot/>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">begin</beam>
        <notations>
          <ornaments>
            <inverted-mordent long="yes" approach="above"/>
            </ornaments>
          </notations>
        </note>
      <note>
        <pitch>
          <step>C</step>
          <octave>4</octave>
          </pitch>
        <duration>1</duration>
        <voice>5</voice>
        <type>32nd</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">continue</beam>
        <beam number="2">begin</beam>
        <beam number="3">begin</beam>
        </note>
      <note>
        <pitch>
          <step>D</step>
          <octave>4</octave>
          </pitch>
        <duration>1</duration>
        <voice>5</voice>
        <type>32nd</type>
        <stem>up</stem>
        <staff>2</staff>
        <beam number="1">end</beam>
        <beam number="2">end</beam>
        <beam number="3">end</beam>
        </note>
      <barline location="right">
        <bar-style>light-heavy</bar-style>
        </barline>
      </measure>
    </part>
  </score-partwise>
