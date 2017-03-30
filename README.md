# PHPMusicTools
A PHP library for music analysis, generation, manipulation, and more

  
This project is a work in progress. Features described here may not exist or may not work, and there may be features that work that aren't documented here yet. This is a public, open-source project so if you see something that isn't right, be a hero: fork it and fix it!

The class structure in PHPMusicTools (PMT) is modelled closely after the markup of MusicXML, and was derived from an older project named PHPMusicXML. One goal of this project is to be able to manipulate musical language programmatically, and export the result as MusicXML, for easy rendering by a 3rd party typesetting tool. In addition to MusicXML, the project also integrates in certain ways to output for LilyPond, MIDI, SVG, CSV, etc.

# Basic Usage

Create a file in your project named ```composer.json```, and put this in it:

```
{
    "require": {
        "ianring/PHPMusicTools": "dev-master"
    }
}
```

Then install composer, using this command:

```
curl -sS https://getcomposer.org/installer | php
```

Then execute this command:
```
php composer.phar install
```

Now you may use PHPMusicTools Classes in your project. For example:

```php
<?php
use ianring\PHPMusicTools;
require 'vendor/autoload.php';

$score = new Score();
// ... lots of things here
echo $score->toXML();
```


# Object construction

Every class in PHPMusicTools has two ways to create an instance. The first is to use the "new" keyword, providing its properties as arguments, like this:

```php
$pitch = new Pitch('C', -1, 4);
```
Each class also has a static constructFromArray() method, which accepts a PHP array for its properties.

```php
$pitch = Pitch::constructFromArray(array(
	'step' => 'C',
	'alter' => 1,
	'octave' => 4,
))
```
The constructFromArray method is recursive, so if your chord array contains a note array, and your note arrays contain a pitch array, they will be transformed into Chord and Note and Pitch objects. This is a convenient way to serialize a complex musical structure into things that are enumerable and fun to manipulate.

```php
$chord = Chord::constructFromArray(
	array(
		'notes' => array(
			0 => array(
				'pitch' => array(
					'step' => 'C',
					'alter' => 1,
					'octave' => 4,
				),
				'rest' => false,
			)
		)
	)
);
```

Some object properties are singular, but some properties are plural, like the notes in a Chord or the measures in a Part. In the object construction these are named with their plural nouns, e.g. "notes" is an array of Note objects, "measures" is the array of Measure objects, and so on.


# Classes



## Pitch
A pitch object has three properties: step, alter, and octave. These are directly mapped to the MusicXML elements that represent the notation of a pitch, so there is a difference between B flat and A sharp.

You can create a pitch like this:

```php
$pitch = new Pitch('C', -1, 4);
```

You can also use this shorthand:

```php
$p = new Pitch('C-4');
```

The tools will also accept Lilypond "absolute" style:

```php
$p = new Pitch('cis,,');
```

Pitches are cool because you can do transposition on them:

```php
$p->transpose(6); // transposes the pitch up 6 semitones
$p->transpose(-3); // transposes the pitch down 3 semitones
```

A transposition might result in an altered note, e.g. if $pitch is 'C4', $pitch->transpose(-4) will result in either a G sharp or an A flat. To resolve this ambiguity, transpose() accepts a second argument for its preferred alteration. If omitted, the alteration will be the same as the original note if there is one.

```php
$p->transpose(6, 1); // will create an F sharp.
$p->transpose(6, -1); // will create a G flat.
```

In MusicXML, pitch is described using "step", "alter", and "octave", whereas in music analysis, pitch is often described using "chroma" and "height". Chroma is the chromatic position in the 12-tone scale, for which we have names like "C sharp". Whereas music notation cares about "step" and "alter" to know the difference between the visual representation of a B flat or A sharp, chroma doesn't carry information about notation, and can be represented as a number from 0 to 12.
"Height" is the octave in which the chroma resides. Two pitches can have the same chroma in different heights (e.g. C#4 and C#5), and notes can of course have the same height and different chromas (e.g. D4 and F4).

The chroma is a number from 0 to 11. 0 is the chroma of C natural, and 11 is the chroma of B.

```php
$pitch = new Pitch('F', 1, 3);
echo $pitch->chroma(); // 6, aka F sharp
$pitch->transpose(-4);
echo $pitch->chroma(); // 2, aka D natural

```

### Heightless pitches
A Pitch might be "heightless", when it represents a Chroma with no "octave" property. Heightlessness is a useful concept in music analysis, because it allows you to examine the use of chromas without regard for their octave. Transposing a heightless pitch will result in another heightless pitch. Heightless pitches can be assigned to Notes (which would be a weird thing to do, but it is possible), but they can not be rendered as XML, so if you're mixing heightless pitches into music that is being rendered as XML, be careful.

## Note

A Note has many properties; the object constructor is a little crazy

```php
$note = new Note(
	$pitch = new Pitch(),
	$rest = false,
	$duration = 4,
	$voice = 1,
	$type = 'quarter',
	$accidental = 'flat',
	$dot = false,
	$tie = false,
	$timeModification = new TimeModification(),
	$beams = new NoteBeam(),
	$defaultX = null,
	$defaultY = null,
	$chord = false,
	$notations = array(),
	$articulations = array(),
	$staff = 1
);
```
It's actually easier and more sensible to use the constructFromArray method, because you can pass in only the properties that aren't default:

```php
$note = Note::constructFromArray(
	array(
		'pitch' => array(
			'step' => 'A',
			'alter' => -1,
			'octave' => 3,
		),
		'duration' => 8
	)
)
```

Here is the complete construction of a Note with everything provided:
```php
$note = Note::constructFromArray(
	array(
		'pitch' => array( // becomes a Pitch object
			'step' => 'C',
			'alter' => 1,
			'octave' => 4,
		),
		'rest' => false,
		'duration' => 4,
		'voice' => 1,
		'type' => 'eighth',
		'accidental' => 'sharp',
		'dot' => false,
		'tie' => true,
		'timeModification' => array( // becomes a TimeModification object
			'actualNotes' => 3,
			'normalNotes' => 2,
		),
		'defaultX' => 3,
		'defaultY' => 1,
		'chord' => true,
		'notations' => array(
			array( // becomes a Tuplet object
				'notationType' => 'tuplet',
				'number' => 1,
				'type' => 'stop',
			),
			array( // becomes a Slur object
				'notationType' => 'slur',
				'type' => 'start',
				'number' => 1,
			)
		),
		'articulations' => array(
			array( // becomes an Articulation object
				'articulationType' => 'accent',
				'defaultX' => -1,
				'defaultY' => -71,
				'placement' => 'below',
			),
		),
		'staff' => 1,
		'beams' => array(
			array( // becomes a NoteBeam object
				'number' => 1,
				'type' => 'begin',
			),
			array( // becomes another NoteBeam object
				'number' => 2,
				'type' => 'begin',
			)
		),
		'stem' => array( // becomes a NoteStem object
			'defaultX' => 2,
			'defaultY' => 3,
			'direction' => 'up',
		)
	)
);

```


A note can be transposed too.

```php
$note->transpose(5, -1); // transpose up 5 semitones, preferring flats.
```

## Chord

A chord is a group of notes that sound simultaneously. Its only properties is $notes, an array of Note objects.

```php
$chord = new Chord(array($note1, $note2, $note3));

$chord = Chord::constructFromArray(
	array(
		'notes' => array($note1, $note2, $note3)
	)
);


$chord->addNote($note4);
```

Chords may be transposed:

```php
$chord->transpose(5, -1); // transpose up 5 semitones, preferring flats.
```


## Layer

A layer allows multiple sequences of chords to share a single measure. This is common for notation of counterpoint, and for polyphonic instruments like the piano. Layers have no special properties of their own.

```php
$layer = new Layer();
```

You can add a chord, or a note.

```php
$layer->addChord($chord);
$layer->addNote($note);
```

You can add a Note directly to a Layer, but internally it's creating a Chord with one Note in it, and adding that.

Layers may be transposed:

```php
$layer->transpose(5, -1); // transpose up 5 semitones, preferring flats.
```

## Measure

A measure is a collection of layers, together describing one discrete duration of music defined by the time signature. 

```php
$measure = new Measure($properties);
$measure->addLayer($layer);
```

A measure has many properties:
<dl>
	<dt>number</dt>
	<dd>the sequential number of this measure, starting from 1</dd>

	<dt>division</dt>
	<dd>the number of divisions of a single beat. If the time signature is 4/4, then one beat is a quarter note. If your measure contains any sixteenth notes, you will need the division to be at least 4. If you also use eighth-note triplets, then the division must be 12. The number of "ticks" (discrete equal time durations, as in a ticking clock) in a measure is beats * divisions, and each note in your measure must be assigned to one of the ticks.</dd>

	<dt>key</dt>
	<dd>Must be a Key object, as described below</dd>

	<dt>time</dt>
	<dd>has properties "beats", and "beat-type", and optionally "symbol".</dd>

	<dt>clef</dt>
	<dd>must be a Clef object, as described below</dd>

	<dt>barlines</dt>
	<dd>
		may be a single barline or an array of barlines, if the measure has multiple staves.	
		each must be a Barline object, as described below
	</dd>

	<dt>implicit</dt>
	<dd>boolean. Defaults to false if omitted. If true, then the measure won't be counted with a measure number, as in pickup measures and the last half of mid-measure repeats.</dd>

	<dt>non-controlling</dt>
	<dd>boolean, defaults to false. If true, the left barline of this measure does not coincide with the left barline of measures in other parts.</dd>

	<dt>width</dt>
	<dd>to explicitly set the width of a measure</dd>

</dl>

### Key

The Key class builds a Key, to use as the 'key' property in your Measure. It is represented using the properties "fifths" and "mode". In major mode, C major is 0 fifths, G is 1, D is 2... F is -1, B flat is -2. The class provides a shorthand for building a key, by constructing it with a string (like "F minor") instead of an array. 

These three ways of creating $key all accomplish the same thing:
```php
$key = new Key(3, 'major');

$key = Key::constructFromArray(
	array(
		'fifths' => 3,
		'mode' => 'major'
	)
);

$key = Key::constructFromString('D major'));
```

### Clef

There is a Clef class which provides a shorthand for creating common clefs. The clef has two properties: sign and line. 

```php
// this creates a treble clef
$clef = new Clef(array('sign' => 'G', 'line' => 4));

$clef = Clef::constructFromArray(
	array(
		'sign' => 'G',
		'line' => 4
	)
);

$clef = Clef::constructFromString('treble');
```

### Staves

A measure may contain multiple staves. Each of these has its own clef. The number of staves is controlled by the number of clefs. To override the number of staves for complex notation situations, put a number in the "staves" property. 

```php
// This measure will have two staves, without having to set the "staves" property
$measure->setProperty('clef', array(
	new Clef('treble'),
	new Clef('bass')
));

// explicitly use a different number of staves
$measure->setProperty('staves', 3);
```

### Barline

The Barline class has four main properties: "location", "bar-style", "repeat", and "ending". "repeat" has sub-properties "direction" and "winged". "ending" has sub-properties "type" and "number".

```php
$barline = Barline::constructFromArray(
	array(
		'location' => 'right',
		'bar-style' => 'heavy-light',
		'repeat' => array( // this becomes a BarlineRepeat object
			'direction' => 'backward',
			'winged' => 'none'
		),
		'ending' => array( // this becomes a BarlineEnding object
			'type' => 'stop',
			'number' => 2
		)
	)
);
```

Barlines are added to a Measure by setting to the "barline" property.
```php
$measure->setProperty('barline', $barline);
$measure->setProperty('barline', array($barline1, $barline2));
$measure->addBarline($barline);
```


## Part

A part is a sequence of measures intended to be played by the same instrument. Parts have a name, and an array of measures.

```php
$part = new Part('Pianoforte', array());
$part->addMeasure($measure);
```
 bv
## Score

A score is the collection of all the parts being played by all the instruments. It is the highest-level element in MusicXML, and so it's usually where you will call the toXML() method.

```php
$score = new Score();
$score->addPart($part);

echo $score->toXML();
```


# Helper Classes

PHPMusicTools contains some interesting helper classes, that have no influence on typesetting scores, but which have application for music analysis or transformation.

## Scale
The scale class is a utility that understands scales. A Scale object has three properties; scale, root, and direction.

The scale property is an integer between 0 and 4095, essentially a bitmask describing which tones are present in the scale. 

You can create a scale using shorthand, 

```php
$scale = new Scale(2741, new Pitch('C', 1, 3), Scale::ASCENDING);

$scale = Scale::constructFromArray(array(
	'scale' => 2741,
	'root' => array(
		'step' => 'C',
		'alter' => 1,
		'octave' => 3,
	),
	'direction' => Scale::ASCENDING
));


```
Note that the root can be a heightless pitch to describe a heightless Scale, or it may be a pitch with an octave to anchor the scale at a certain height.

Scale objects are used for autoTune(), can be returned by functions that do analysis, and can be used to render sequences of Notes.


# What should this library accomplish?

The big picture vision for this library is to be able to answer questions about music, generate representations of music programmatically, and manipulate music constructs. Here are a short list of goals:

 - Count many G's are in Moonlight Sonata
 - What is the pitch range of Chopin's Etudes?
 - Generate a D minor harmonic scale, starting at D3 and ascending for three octaves.
 - Generate a random 16th-note solo over the Giant Steps chord pattern, using no more than a major third leap
 - Take a score in Lilypond format in C, and output it transposed for Alto Saxophone.
 - Given a piece of music, guess what key and scale it's in
 - Create a trie that learns the next probable note given two notes and a body of music to analyze
 - Accept a MusicXML document and output it as MIDI
 - Identify French Sixth chords in a piece of music
 - Measure whether large leaps land on notes with longer durations.
 - Find places where a piano score requires a span of greater than an octave in a single hand
 - Check if a clarinet part has sufficient pauses for breath
 - Check if a flute part goes out of range
 - Find similar phrases in two or more pieces of music
 - Find parts that are out of range for their instrument
 - Locate tone rows in a score, and identify variants

# Developer Info

If you're looking for something to work on, check the most wanted list at https://github.com/ianring/PHPMusicTools/issues
Make sure all your code is good quality and that you've tested it, and run the Unit tests before submitting.

## Unit Tests

All functionality should be unit tested. If you discover functionality that isn't tested yet, the highest priority should be to write tests for it. Though it is not mandated that all development should be test-driven, it is recommended that TDD is a good approach for adding new features or enhancing existing ones.


wget https://phar.phpunit.de/phpunit-6.0.phar
or on mac:
curl "https://phar.phpunit.de/phpunit-6.0.phar" -o "phpunit-6.0.phar"

curl "https://phar.phpunit.de/phpunit-6.0.11.phar" -o "phpunit-6.0.phar"

chmod +x phpunit-6.0.11.phar

sudo mv phpunit-6.0.11.phar /usr/local/bin/phpunit

This version of phpunit requires PHP 7.0 or above. If you're running an older version of PHP, upgrade!



## Documentation
PHPMusicTools uses phpdocumentor for api documentation, so all code must be properly self-documented. 
To generate the documentation in /docs/api, first you need to make sure you've already loaded the vendor extras using composer, then run this command:
```
vendor/bin/phpdoc -d ./src/PHPMusicTools/classes -t ./docs/api
```
If you are submitting a pull request into PHPMusicTools, do not alter any of the auto-generated files because they'll just be overwritten.
Learn more at https://www.phpdoc.org/

## Code Standards

PHPMusicTools has a custom standard sniffer. To check the project for standards compliance, run this command, from the root:
```
vendor/bin/phpcs src/PHPMusicTools/classes/
```
Run the beautifier and fixer:
```
vendor/bin/phpcbf src/PHPMusicTools/classes/
```


# License
GPL. See https://www.gnu.org/licenses/gpl-3.0.en.html .

I can't imagine what sort of trouble you could possibly get into using this code, but the authors and maintainers don't accept any responsibility for losses resulting from its use. It's a work in progress, so this comes with no warranteee whatsoever, and functionality may be incomplete or not exactly as described.

This code is not guaranteed to be safe, either. Use at your own risk.

# Resources
 - http://www.musicxml.com/tutorial/
 - http://www.musicxml.com/UserManuals/MusicXML/MusicXML.htm
 - https://github.com/0xfe/vexflow
 - https://en.wikipedia.org/wiki/MusicXML
 - https://github.com/ringw/vexflow/
 - https://www.soundslice.com/musicxml-viewer/
