<?php
namespace ianring;
require_once 'PMTObject.php';
require_once 'Pitch.php';
require_once 'Chord.php';
require_once __DIR__.'/Utils/BitmaskUtils.php';

/**
 * This class operates on the understanding that all scales are made from the set of 12 chromatic tempered
 * pitches, and that there is a limited number of possible combinations of those pitches. The "power set"
 * of all possible scales is a set of 4096 scales, and each one can be represented by a decimal number from
 * 0 (no notes) to 4095 (all 12 notes). The index is deterministic. Simply by converting the decimal number
 * to a binary number, it becomes a bitmask defining what pitches are present in the scale, where bit 1 is the root,
 * bit 2 is up one semitone, bit 4 is a major second, bit 8 is the minor third, etc.
 *
 * In order for this class to be useful and flexible, we should not impose limitations on our definition of a
 * scale; e.g. it is not necessary for a Scale to have the root bit (1) on, nor will we mind if the scale has
 * leaps greater than 4 semitones. We could have a Scale object (which identifies the set of tones), and then
 * use its methods to determine if it is a "scale" according to the definition we desire.
 *
 * Take notice that a Scale is not a collection of Notes, nor a collection of Pitches. The scale is an abstract
 * pattern that can be applied to a root to generate pitches in a particular octave.
 *
 */

/**
 * Scale is a series of notes all conforming to a set, moving stepwise ascending or descending
 */
class Scale extends PMTObject
{

	const ASCENDING = 'ascending';
	const DESCENDING = 'descending';

	// ref: http://www.pdmusic.org/text/027.txt
	// ref: Benjamin Robert Tubb brtubb@pdmusic.org http://www.pdmusic.org/theory.html
	// ref: Clint Goss, Comprehensive Scale Catalog, version March 30, 2017, available at http://www.Flutopedia.com/scale_catalog.htm retrieved Jan 15, 2018
	// ref: http://www.flutopedia.com/xls/Flutopedia_Scales.txt
	// ref: http://www.huygens-fokker.org/docs/modename.html

	public static $scaleNames = array(
		5 => array("Vietnamese ditonic"),
		33 => array("Honchoshi"),
		41 => array("Vietnamese tritonic"),
		129 => array("Niagari"),
		137 => array("Ute tritonic","Peruvian tritonic 2"),
		145 => array("Raga Malasri","Peruvian tritonic 1"),
		149 => array("Eskimo tetratonic"),
		161 => array("Raga Sarvasri","Warao tritonic"),
		165 => array("Genus Primum"),
		169 => array("Vietnamese tetratonic"),
		173 => array("Raga Purnalalita","Chad Gadyo","Ghana Pentatonic 1","Nando-kyemyonjo"),
		181 => array("Raga Budhamanohari"),
		193 => array("Raga Ongkari"),
		195 => array("Messiaen truncated mode 5"),
		199 => array("Raga Nabhomani"),
		219 => array("Istrian"),
		245 => array("Raga Dipak"),
		273 => array('Augmented Triad'),
		291 => array("Raga Lavangi","Gowleeswari"),
		293 => array("Raga Haripriya"),
		299 => array("Raga Chitthakarshini"),
		301 => array("Raga Audav Tukhari"),
		307 => array("Raga Megharanjani","Syrian Pentatonic"),
		325 => array("Messiaen truncated mode 6"),
		331 => array("Raga Chhaya Todi","Locrian Pentatonic 1"),
		395 => array("Phrygian Pentatonic","Balinese Pelog","Madenda Modern","Raga Bhupalam","Bhupala Todi","Bibhas"),
		395 => array('Balinese'),
		397 => array("Aeolian Pentatonic","Hira-joshi","Kata-kumoi","Yona Nuki Minor","Tizita Minor (Half tizita)"),
		397 => array('Hirajoshi'),
		403 => array("Raga Reva","Revagupti","Ramkali","Vibhas (Bhairava)"),
		405 => array("Raga Bhupeshwari","Janasammodini"),
		419 => array("Hon-kumoi-joshi","Sakura","Akebono II","Olympos Enharmonic","Raga Salanganata","Saveri","Gunakri (Gunakali)","Latantapriya","Ambassel",'japanese (a)'),
		421 => array("Han-kumoi","Raga Shobhavari","Sutradhari",'japenese (b)'),
		425 => array("Raga Kokil Pancham"),
		427 => array("Raga Suddha Simantini"),
		433 => array("Raga Zilaf"),
		435 => array("Raga Purna Pancama","Malahari","Geyahejjajji","Kannadabangala"),
		451 => array("Raga Saugandhini","Yashranjani"),
		455 => array("Messiaen mode 5","Two-semitone Tritone scale"),
		461 => array("Raga Syamalam"),
		467 => array("Raga Dhavalangam"),
		529 => array("Raga Bilwadala"),
		549 => array("Raga Bhavani"),
		557 => array("Raga Abhogi"),
		585 => array('Diminished Seventh'),
		597 => array("Kung"),
		619 => array("Double-Phrygian Hexatonic"),
		621 => array("Pyramid Hexatonic"),
		637 => array("Debussy's Heptatonic"),
		653 => array("Dorian Pentatonic","Raga Sivaranjini","Raga Shivranjani","Akebono I",'Kumoi'),
		659 => array("Raga Rasika Ranjani","Vibhas (Marva)","Scriabin"),
		661 => array("Major Pentatonic","Pentatonic Major","Ryosen","Yona Nuki Major","Man Jue","Gong","Raga Bhopali","Raga Bhup","Mohanam","Deskar","Bilahari","Kokila","Jait Kalyan","Peruvian Pentatonic 1","Ghana Pentatonic 2","Tizita Major"),
		665 => array("Raga Mohanangi"),
		675 => array("Altered Pentatonic","Raga Manaranjani II"),
		677 => array("Scottish Pentatonic","Blues Major","Ritusen","Ritsu","Gagaku","Zhi","Zheng","Ujo","P'yongjo","Bac","Lai Soutsanaen","Lai Po Sai","Lai Soi","Raga Devakriya","Durga","Suddha Saveri","Arabhi","Major complement"),
		681 => array("Kyemyonjo","Minor added sixth Pentatonic"),
		685 => array("Raga Suddha Bangala","Gauri Velavali"),
		689 => array("Raga Nagasvaravali","Raga Mand"),
		691 => array("Raga Kalavati","Ragamalini"),
		693 => array("Arezzo Major Diatonic Hexachord","Raga Kambhoji","Devarangini","Sama","Syama","Scottish Hexatonic"),
		709 => array("Raga Shri Kalyan"),
		711 => array("Raga Chandrajyoti"),
		715 => array("Messiaen truncated mode 2"),
		717 => array("Raga Vijayanagari"),
		721 => array("Raga Dhavalashri"),
		725 => array("Raga Yamuna Kalyani","Kalyani Keseri","Airavati","Ancient Chinese"),
		743 => array("Chromatic Hypophrygian inverse"),
		775 => array("Raga Putrika"),
		807 => array("Raga Suddha Mukhari"),
		819 => array("Augmented Inverse","Messiaen truncated mode 3","Prometheus (Liszt)","Six Tone Symmetrical"),
		845 => array("Raga Neelangi"),
		851 => array("Raga Hejjajji"),
		859 => array("Ultralocrian","Superlocrian Diminished","Superlocrian Double-Flat 7","Mixolydian sharp 1","Diminished"),
		871 => array('Locrian Double-flat 3 Double-flat 7'),
		875 => array("Locrian Double-flat 7"),
		877 => array("Moravian Pistalkova","Hungarian Major inverse"),
		915 => array("Raga Kalagada","Raga Kalgada"),
		919 => array("Chromatic Phrygian Inverse"),
		923 => array('Ultraphrygian'),
		925 => array("Chromatic Hypodorian","Relative Blues scale","Raga Dvigandharabushini"),
		931 => array("Raga Kalakanthi"),
		935 => array("Chromatic Dorian","Mela Kanakangi","Raga Kanakambari"),
		939 => array("Mela Senavati","Raga Senagrani","Malini"),
		941 => array("Mela Jhankaradhvani","Raga Jhankara Bhramavi"),
		945 => array("Raga Saravati","Raga Sharavati"),
		947 => array("Mela Gayakapriya","Raga Kalakanti","Gipsy Hexatonic"),
		949 => array("Mela Mararanjani","Raga Keseri","Major Bebop Heptatonic"),
		953 => array("Mela Yagapriya","Raga Kalahamsa"),
		967 => array("Mela Salaga","Mela Salagam"),
		971 => array("Mela Gavambodhi","Raga Girvani"),
		973 => array("Mela Syamalangi","Raga Shyamalam"),
		975 => array("Messiaen mode 4","Tcherepnin Octatonic mode 3"),
		979 => array("Mela Dhavalambari","Foulds' Mantra of Will scale"),
		981 => array("Mela Kantamani","Raga Kuntala","Srutiranjani"),
		985 => array("Mela Sucaritra","Raga Santanamanjari"),
		1025 => array("Warao ditonic"),
		1037 => array("Warao tetratonic"),
		1057 => array("Sansagari"),
		1105 => array("Messiaen truncated mode 6 inverse"),
		1113 => array("Locrian Pentatonic 2"),
		1115 => array("Superlocrian Hexamirror"),
		1123 => array("Iwato"),
		1129 => array("Raga Jayakauns"),
		1131 => array("Honchoshi plagal form"),
		1161 => array("Bi Yu"),
		1163 => array("Raga Rukmangi"),
		1169 => array("Raga Mahathi","Antara Kaishiaki"),
		1171 => array("Raga Manaranjani I"),
		1173 => array("Dominant Pentatonic"),
		1185 => array("Genus Primum Inverse"),
		1187 => array("Kokin-joshi","Miyakobushi","Han-Iwato","In Sen","Raga Vibhavari","Bairagi","Lasaki"),
		1189 => array("Suspended Pentatonic","Raga Madhyamavati","Madhmat Sarang","Megh","Egyptian","Shang","Rui Bin","Jin Yu","Qing Yu","Yo","Ngu Cung Dao","Yematebela wofe","Egyptian"),
		1193 => array("Minor Pentatonic","Pentatonic Minor","Blues Pentatonic","Raga Dhani","Abheri","Udhayaravi Chandrika","Qing Shang","Gu Xian","Jia Zhong","Yu","P'yongjo-kyemyonjo","Minyo","Lai Yai","Lai Noi","Nam","Northern Sa mac","Peruvian Pentatonic 2","Batti Minor"),
		1195 => array("Raga Gandharavam","Sabai silt"),
		1197 => array("Minor Hexatonic","Raga Manirangu","Nayaki","Palasi","Pushpalithika","Puspalatika","Suha Sughrai","Yo","Eskimo Hexatonic 1"),
		1201 => array("Mixolydian Pentatonic","Nam ai","Oan","Raga Savethri"),
		1205 => array("Raga Siva Kambhoji","Vivardhini","Andhali"),
		1209 => array("Raga Bhanumanjari","Jog"),
		1225 => array("Raga Samudhra Priya","Madhukauns"),
		1229 => array("Raga Simharava","Raga Sinharavam","Gopikatilaka"),
		1235 => array("Messiaen truncated mode 2","Raga Indupriya","Tritone scale"),
		1255 => array("Chromatic Mixolydian"),
		1257 => array("Blues scale","Blues","Raga Nileshwari"),
		1261 => array("Modified Blues"),
		1317 => array("Chaio"),
		1321 => array("Blues Minor","Raga Malkauns","Raga Malakosh","Raga Hindola","Man Gong","Quan Ming","Yi Ze","Jiao","Shegaye"),
		1323 => array("Ritsu","Raga Suddha Todi)"),
		1331 => array("Raga Vasantabhairavi"),
		1353 => array("Raga Harikauns","Chin"),
		1355 => array("Raga Bhavani"),
		1357 => array("Takemitsu Tree Line mode 2"),
		1365 => array("Whole-tone","Messiaen mode 1","Raga Gopriya","Anhemitonic Hexatonic",'auxiliary augmented'),
		1367 => array("Leading Whole-Tone inverse"),
		1371 => array("Superlocrian","Altered Dominant","Diminished Whole-tone","Locrian flat 4","Pomeroy","Ravel","Dominant Whole-tone Combo","Altered"),
		1387 => array("Locrian","Half Diminished Locrian","Greek Mixolydian","Greek Hyperdorian","Medieval Hypophrygian","Medieval Locrian","Greek Medieval Hyperaeolian","Rut biscale descending","Pien chih","Makam Lami","Yishtabach"),
		1389 => array("Minor Locrian","Half Diminished","Locrian Sharp 2","Minor Flat 5"),
		1395 => array('Oriental (a)'),
		1397 => array("Major Locrian",'Arabian b'),
		1403 => array("Espla's scale","Eight-tone Spanish"),
		1417 => array("Raga Shailaja","Varini"),
		1419 => array("Raga Kashyapi"),
		1421 => array("Raga Trimurti"),
		1435 => array("Makam Huzzam","Maqam Saba Zamzam","Phrygian Flat 4"),
		1437 => array("Sabach ascending"),
		1443 => array("Raga Phenadyuti","Insen","Honchoshi","Niagari"),
		1445 => array("Raga Navamanohari"),
		1447 => array("Mela Ratnangi","Raga Phenadyuti"),
		1449 => array("Raga Gopikavasantam","Desya Todi","Jayantasri","Phrygian Hexatonic"),
		1451 => array("Phrygian","Greek Dorian","Medieval Phrygian","Greek Medieval Hypoaeolian","Neopolitan Minor","Bhairavi That","Bhairavi Theta","Mela Hanumatodi","Raga Asavari","Raga Asaveri","Bilashkhani Todi","Ghanta","Makam Kurd","Gregorian nr.3","In","Zokuso","Ousak","Major inverse"),
		1453 => array("Aeolian","Greek Medieval Hypodorian","Greek Medieval Aeolian","Greek Hyperphrygian","Natural Minor","Pure Minor","Melodic Minor descending","Asavari That","Asavari Theta","Mela Natabhairavi","Raga Jaunpuri","Adana","Darbari","Dhanyasi","Jingla","Gregorian nr.2","Makam Buselik","Nihavend","Peruvian Minor","Se","Chiao","Geez/Ezel","Kiourdi descending","Cushak"),
		1455 => array("Phrygian/Aeolian mixed"),
		1457 => array("Raga Kamalamanohari"),
		1459 => array("Phrygian Dominant","Phrygian Major","Spanish Gypsy","Spanish Gipsy","Spanish Romani","Mela Vakulabharanam","Raga Jogiya","Ahiri","Vativasantabhairavi","Zilof","Ahava Rabba","Freygish","Maqam Hijaz-Nahawand","Humayun","Dorico Flamenco","Hitzaz","Harmonic Major inverse","Altered Hungarian"),
		1461 => array("Major-Minor","Melodic Major","Mischung 6","Mixolydian flat 6","Mela Carukesi","Raga Charukeshi","Tarangini","Hindu","Hindustan","Altered Mixolydian"),
		1465 => array("Mela Ragavardhani","Raga Cudamani"),
		1467 => array("Spanish Phrygian",'altered dominant a'),
		1477 => array("Raga Jaganmohanam"),
		1479 => array("Mela Jalarnava", 'mela jalarnavam'),
		1483 => array("Mela Bhavapriya","Raga Bhavani","Kalamurti","Neveseri ascending"),
		1485 => array("Minor Gypsy","Minor Gipsy","Ukrainian Dorian","Mela Sanmukhapriya","Raga Camara","Chinthamani","Lydian Diminished","Aolian Sharp 4","Romani Scale"),
		1489 => array("Raga Jyoti"),
		1491 => array("Mela Namanarayani","Raga Narmada","Pratapa","Harsh Major-Minor"),
		1493 => array("Lydian Minor","Minor Lydian","Mela Risabhapriya","Raga Ratipriya"),
		1495 => array("Messiaen mode 6"),
		1497 => array("Mela Jyotisvarupini","Raga Jotismatti"),
		1499 => array('Bebop Locrian', 'Altered Dominant b'),
		1515 => array("Phrygian/Locrian mixed"),
		1519 => array("Locrian/Aeolian mixed"),
		1573 => array("Raga Guhamanohari"),
		1577 => array("Raga Chandrakauns (Kafi)","Surya","Varamu"),
		1581 => array("Raga Bagesri","Sriranjani","Kapijingla","Jayamanohari"),
		1585 => array("Raga Khamaji Durga"),
		1587 => array("Raga Rudra Pancama"),
		1589 => array("Raga Rageshri","Raga Rageshwari","Nattaikurinji","Natakuranji"),
		1619 => array("Prometheus Neapolitan"),
		1621 => array("Prometheus (Scriabin)","Mystic","Raga Barbara)"),
		1643 => array("Locrian natural 6","Maqam Tarznauyn",'Locrian Sharp 6'),
		1645 => array("Dorian flat 5","Blues Heptatonic","Makam Karcigar","Maqam Nahawand Murassah","Kiourdi ascending","Kartzihiar"),
		1651 => array("Asian","Oriental","Oriental (b)","Raga Ahira-Lalita","Hungarian Minor inverse","Tsinganikos"),
		1653 => array("Minor Gipsy inverse"),
		1659 => array("Maqam Shadd'araban"),
		1669 => array("Raga Matha Kokila","Raga Matkokil"),
		1675 => array("Raga Salagavarali"),
		1677 => array("Raga Manavi"),
		1681 => array("Raga Valaji"),
		1683 => array("Raga Malayamarutam"),
		1697 => array("Raga Kuntvarali","Raga Kuntalavarali"),
		1699 => array("Raga Rasavali"),
		1701 => array("Dominant Seventh","Mixolydian Hexatonic","P'yongjo","Yosen","Raga Darbar","Narayani","Suposhini","Andolika","Gorakh Kalyan"),
		1703 => array("Mela Vanaspati","Raga Bhanumati"),
		1705 => array("Raga Manohari","Malavasri"),
		1707 => array("Mela Natakapriya","Jazz Minor inverse","Phrygian-Mixolydian","Dorian flat 2","Raga Natabharanam","Ahiri Todi","Javanese Pelog","Pelog"),
		1709 => array("Dorian","Greek Phrygian","Medieval Dorian","Medieval Hypomixolydian","Kafi That","Kafi Theta","Mela Kharaharapriya","Raga Bageshri","Bhimpalasi","Nayaki Kanada","Sriraga","Ritigaula","Huseni","Kanara","Mischung 5","Gregorian nr.8","Eskimo Heptatonic","Yu","Hyojo","Oshikicho","Banshikicho","Nam"),
		1711 => array("Adonai Malakh","Jewish"),
		1713 => array("Raga Khamas","Desya Khamas","Bahudari"),
		1715 => array("Harmonic Minor inverse","Mela Cakravaka","Mela Chakravakam","Raga Ahir Bhairav","Bindumalini","Vegavahini","Makam Hicaz","Zanjaran"),		
		1717 => array("Mixolydian","Greek Hypophrygian","Greek Ionian","Greek Iastian","Medieval Mixolydian","Greek Medieval Hypoionian","Hypermixolydian","Khamaj That","Khamaj Theta","Mela Harikambhoji","Raga Harini","Janjhuti","Khambhavati","Surati","Balahamsa","Devamanohari","Mischung 3","Gregorian nr.7","Enharmonic Byzantine Liturgical","Rast descending","Ching","Shang"),
		1721 => array("Mela Vagadhisvari","Raga Bhogachayanata","Nandkauns","Ganavaridhi","Chayanata","Bluesy Rock 'n Roll"),
		1723 => array("JG Octatonic"),
		1725 => array("Minor Bebop","Dorian Bebop","Bebop Dorian","Raga Zilla","Mixolydian/Dorian mixed"),
		1733 => array("Raga Sarasvati"),
		1735 => array("Mela Navanitam","Raga Nabhomani"),
		1737 => array("Raga Madhukauns"),
		1739 => array("Mela Sadvidhamargini","Raga Sthavarajam","Tivravahini"),
		1741 => array("Altered Dorian","Mela Hemavati","Raga Desisimharavam","Maqam Nakriz","Tunisian","Ukranian Dorian","Romanian Scale","Rumanian Minor","Dorian sharp 4","Misheberekh","Souzinak Minor","Peiraiotikos Minor","Nigriz","Pimenikos","Ukrainian Minor","Kaffa","Gnossiennes"),
		1745 => array("Raga Vutari"),
		1747 => array("Mela Ramapriya","Raga Ramamanohari","Romanian Major","Petrushka chord"),
		1749 => array("Acoustic","Lydian Dominant","Mela Vacaspati","Raga Bhusavati","Bhusavali","Overtone","Overtone Dominant","Lydian-Mixolydian","Lydian-Mixolydian Combo","Bartok"),
		1753 => array("Hungarian Major","Mela Nasikabhusani","Raga Nasamani"),
		1755 => array("Octatonic","Messiaen mode 2","Dominant Diminished","Diminished Blues","Half-Whole step scale",'second mode of limited transposition','auxiliary diminished blues', 'dominant'),
		1769 => array("Blues Heptatonic II"),
		1773 => array("Blues scale II"),
		1783 => array("Youlan scale"),
		1789 => array("Blues Enneatonic II"),
		1849 => array("Chromatic Hypodorian inverse"),
		1911 => array("Messiaen mode 3","Tcherepnin Nonatonic mode 3"),
		1965 => array("Raga Mukhari","Anandabhairavi","Deshi","Manji","Gregorian nr.1","Dorian/Aeolian mixed"),
		1967 => array("Diatonic Dorian mixed"),
		1981 => array("Houseini","Modes of Major Pentatonic mixed"),
		1997 => array("Raga Cintamani"),
		2015 => array("Messiaen mode 7"),
		2029 => array("Kiourdi"),
		2043 => array("Maqam Tarzanuyn"),
		2099 => array("Raga Megharanji"),
		2117 => array("Raga Sumukam"),
		2129 => array("Raga Nigamagamini"),
		2133 => array("Raga Kumurdaki","Raga Kumudki"),
		2145 => array("Messiaen truncated mode 5 inverse"),
		2197 => array("Raga Hamsadhvani","Raga Hansdhwani","Raga Haunsadhwani"),
		2211 => array("Raga Gauri"),
		2213 => array("Raga Desh","Tcherepnin Major Pentatonic","Nam xuan"),
		2217 => array("Raga Nata","Udayaravicandrika","Madhuranjani"),
		2221 => array("Raga Sindhura Kafi"),
		2225 => array("Ionian Pentatonic","Raga Gambhiranata","Pelog Degung Modern","Ryukyu","Vong co","Batti Major"),
		2227 => array("Raga Gaula"),
		2229 => array("Raga Nalinakanti","Kedaram","Vilasini"),
		2245 => array("Raga Vaijayanti","Hamsanada"),
		2247 => array("Raga Vijayasri"),
		2249 => array("Raga Multani","Batti mineur"),
		2253 => array("Raga Amarasenapriya"),
		2257 => array("Lydian Pentatonic","Raga Amritavarshini","Malashri","Shilangi","Batti Lydian",'Hirajoshi',"Chinese","Augmented"),
		2259 => array("Raga Mandari","Gamakakriya","Hamsanarayani"),
		2261 => array("Raga Caturangini","Ratnakanthi"),
		2265 => array("Raga Rasamanjari"),
		2275 => array("Messiaen Mode 5","Fifth Mode of Limited Transposition"),
		2311 => array("Raga Kumarapriya"),
		2339 => array("Raga Kshanika"),
		2341 => array("Raga Priyadharshini"),
		2345 => array("Raga Chandrakauns","Kiravani"),
		2347 => array("Raga Viyogavarali"),
		2349 => array("Raga Ghantana","Kaushiranjani","Kaishikiranjani"),
		2353 => array("Raga Girija","Bacovia","Batti Major sharp 5"),
		2355 => array("Raga Lalita","Sohini","Hamsanandi","Lalit Bhairav"),
		2357 => array("Raga Sarasanana"),
		2379 => array("Raga Gurjari Todi"),
		2381 => array("Takemitsu Tree Line mode 1"),
		2389 => array("Eskimo Hexatonic 2"),
		2413 => array("Locrian nr.2"),
		2419 => array("Raga Lalita","Persian","Chromatic Hypolydian inverse","Raga Suddha Pancama","Persian"),
		2435 => array("Raga Deshgaur"),
		2451 => array("Raga Bauli"),
		2453 => array("Raga Latika"),
		2457 => array("Major Augmented","Messiaen truncated mode 3 inverse","Genus Tertium","Raga Devamani","Augmented"),
		2465 => array("Raga Devaranjani","Raga Devaranji"),
		2467 => array("Raga Padi"),
		2469 => array("Raga Bhinna Pancama"),
		2471 => array("Mela Ganamurti","Raga Ganasamavarali"),
		2473 => array("Raga Takka"),
		2475 => array("Neapolitan Minor","Minor Neapolitan","Mela Dhenuka","Raga Bhinnasadjam","Dhunibinnashadjam","Kirvanti","Takka","Maqam Shahnaz Kurdi","Hungarian Gipsy"),
		2477 => array("Harmonic Minor","Mischung 4","Pilu That","Mela Kiravani","Raga Kiranavali","Kirvani","Kalyana Vasantha","Deshi(3)","Maqam Bayat-e-Esfahan","Sultani Yakah","Zhalibny Minor","Armoniko minore","Mohammedan"),
//		2479 => array("Harmonic and Neapolitan Minor mixed"),
		2481 => array("Raga Paraju","Raga Paraz","Raga Pharas","Ramamanohari","Sindhu Ramakriya","Kamalamanohari"),
		2483 => array("Double Harmonic","Major Gipsy","Major Gypsy","Hungarian Gypsy Persian","Double Harmonic Major","Enigmatic","Byzantine","Flamenco Mode","Bhairav That","Bhairav Theta","Mela Mayamalavagaula","Raga Paraj","Kalingada","Gaulipantu","Lalitapancamam","Chromatic 2nd Byzantine Liturgical","Hitzazkiar","Maqam Zengule","Hijaz Kar","Suzidil"),
		2485 => array("Harmonic Major","Mela Sarasangi","Raga Haripriya","Simhavahini","Mischung 2","Ethiopian","Tabahaniotikos"),
		2489 => array("Mela Gangeyabhusani","Raga Gangatarangini","Sengiach","Sengah","Gipsy Hexatonic inverse"),
		2503 => array("Mela Jhalavarali","Raga Varali","Jinavali"),
		2507 => array("Todi That","Todi Theta","Mela Shubhapantuvarali","Raga Multani","Gamakasamantam","Harsh Minor","Chromatic Lydian inverse","Maqam Athar Kurd"),
		2509 => array("Double Harmonic Minor","Hungarian Minor","Egyptian Heptatonic","Mela Simhendramadhyama","Raga Madhava Manohari","Maqam Nawa Athar","Hisar","Flamenco Mode","Niavent"),
		2515 => array("Chromatic Hypolydian","Purvi That","Purvi Theta","Mela Kamavardhani","Raga Shri","Pantuvarali","Basant","Kasiramakriya","Suddharamakriya","Puriya Dhanashri","Dhipaka","Pireotikos"),
		2517 => array("Harmonic Lydian","Mela Latangi","Raga Gitapriya","Hamsalata"),
		2521 => array("Mela Dhatuvardhani","Raga Dhauta Pancama","Devarashtra"),
		2535 => array("Messiaen mode 4","Tcherepnin Octatonic mode 2",'Fourth mode of limited transposition'),
		2539 => array("Half-diminished Bebop"),
		2541 => array("Algerian","Sabiren"),
		2547 => array("Raga Ramkali","Raga Ramakri)"),
		2581 => array("Raga Neroshta"),
		2597 => array("Raga Rasranjani"),
		2601 => array("Raga Chandrakauns","Marga Hindola","Rajeshwari"),
		2609 => array("Raga Bhinna Shadja","Kaushikdhvani","Hindolita"),
		2611 => array("Raga Vasanta","Chayavati"),
		2613 => array("Raga Hamsa Vinodini"),
		2629 => array("Raga Shubravarni"),
		2637 => array("Raga Ranjani","Rangini"),
		2641 => array("Raga Hindol","Sunada Vinodini","Sanjh ka Hindol"),
		2643 => array("Raga Hamsanandi","Marva","Pancama","Puriya","Sohni"),
		2645 => array("Raga Mruganandana"),
		2669 => array("Jeths' mode"),
		2675 => array("Chromatic Lydian","Raga Lalit","Bhankar"),
		2701 => array("Hawaiian"),
		2705 => array("Raga Mamata"),
		2709 => array("Raga Kumud","Sankara","Shankara","Prabhati","Lydian Hexatonic"),
		2721 => array("Raga Puruhutika","Purvaholika"),
		2723 => array("Raga Jivantika"),
		2725 => array("Raga Nagagandhari"),
		2727 => array("Mela Manavati","Raga Manoranjani"),
		2731 => array("Neapolitan Major","Major Meapolitan","Lydian Major","Mela Kokilapriya","Raga Kokilaravam"),
		2733 => array("Melodic Minor ascending","Melodic Minor",'Heptatonia Seconda', "Jazz Minor","Minor-Major","Mela Gaurimanohari","Raga Patdip","Velavali","Deshi(2)","Mischung 1","Hawaiian"),
		2737 => array("Raga Hari Nata","Genus Secundum"),
		2739 => array("Mela Suryakanta","Mela Suryakantam","Bhairubahar That","Raga Supradhipam","Sowrashtram","Jaganmohini","Major-Melodic Phrygian","Hungarian Gipsy inverse"),
		2741 => array("Major","Ionian","Greek Lydian","Medieval Ionian","Medieval Hypolydian","Major","Bilaval That","Bilaval Theta","Mela Shankarabharanam","Mela Dhirasankarabharana","Raga Atana","Ghana Heptatonic","Peruvian Major","Matzore","Rast ascending","4th plagal Byzantine","Ararai","Makam Cargah","Ajam Ashiran","Dastgah-e Mahur","Dastgah-e Rast Panjgah","Xin","DS2"),
		2745 => array("Mela Sulini","Raga Sailadesakshi","Raga Trishuli","Houzam"),
		2757 => array("Raga Nishadi"),
		2759 => array("Mela Pavani","Raga Kumbhini"),
		2763 => array("Mela Suvarnangi","Raga Sauviram"),
		2765 => array("Lydian Diminished","Mela Dharmavati","Raga Arunajualita","Dumyaraga","Madhuvanti","Ambika"),
		2771 => array("Marva That","Marva Theta","Mela Gamanasrama","Raga Partiravam","Puriya","Puriya Kalyan","Purvikalyani","Sohani","Bairari","Harsh-intense Major","Peiraiotikos"),
		2773 => array("Lydian","Greek Hypolydian","Medieval Lydian","Greek Medieval Hypolocrian","Rut biscale ascending","Kalyan That","Kalyan Theta","Mela Mecakalyani","Raga Shuddh Kalyan","Ping","Kung","Gu"),
		2777 => array("Aeolian Harmonic","Lydian sharp 2","Mela Kosalam","Raga Kusumakaram"),
		2779 => array("Shostakovich"),
		2795 => array("Van der Horst Octatonic"),
		2803 => array("Raga Bhatiyar"),
		2805 => array("Ishikotsucho","Raga Yaman Kalyan","Chayanat","Bihag","Hamir Kalyani","Kedar","Gaud Sarang","Genus Diatonicum Veterum Correctum","Gregorian nr.5","Kubilai's Mongol scale","Major/Lydian mixed"),
		2869 => array("Ionian Augmented","Ionian sharp 5"),
		2873 => array('Ionian Augmented Sharp 2'),
		2901 => array("Lydian Augmented","Lydian sharp 5"),
		2905 => array("Aeolian flat 1"),
		2907 => array("Magen Abot"),
		2917 => array("Nohkan Flute scale"),
		2925 => array("Diminished","Modus conjunctus","Messiaen mode 2 inverse","Whole-Half step scale","Auxiliary Diminished","Arabian A"),
		2987 => array("Neapolitan Major and Minor mixed"),
		2989 => array("Bebop Minor","Zirafkend","Melodic Minor Bebop","Minor Bebop"),
		2995 => array("Raga Saurashtra"),
		2997 => array("Major Bebop","Altered Mixolydian","Bebop Major"),
		2999 => array("Chromatic and Permuted Diatonic Dorian mixed"),
		3003 => array("Genus Chromaticum","Tcherepnin Nonatonic mode 1","Augmented-9"),
		3037 => array("nine tone scale"),
		3055 => array("Messiaen mode 7",'seventh mode of limited transposition'),
		3069 => array("Maqam Shawq Afza"),
		3185 => array("Messiaen mode 5 inverse"),
		3237 => array("Raga Brindabani Sarang","Megh","Megh Malhar"),
		3239 => array("Mela Tanarupi","Raga Tanukirti"),
		3243 => array("Mela Rupavati"),
		3245 => array("Mela Varunapriya","Viravasantham"),
		3249 => array("Raga Tilang","Savitri","Brindabani Tilang"),
		3251 => array("Mela Hatakambari","Raga Jeyasuddhamalavi"),
		3253 => array("Mela Naganandini","Raga Nagabharanam","Samanta"),
		3257 => array("Mela Calanata","Raga None","Chromatic Dorian inverse"),
		3269 => array("Raga Malarani","Hamsanada"),
		3271 => array("Mela Raghupriya","Raga Ravikriya","Ghandarva"),
		3273 => array("Raga Jivantini","Gaurikriya"),
		3275 => array("Mela Divyamani"),
		3277 => array("Mela Nitimati","Raga Nisada","Kaikavasi"),
		3281 => array("Raga Vijayavasanta"),
		3283 => array("Mela Visvambhari","Raga Vamsavathi"),
		3285 => array("Mela Citrambari","Raga Chaturangini"),
		3289 => array("Lydian Sharp 2 Sharp 6","Mela Rasikapriya","Raga Rasamanjari","Hamsagiri"),
		3301 => array("Chromatic Mixolydian inverse"),
		3305 => array("Chromatic Hypophrygian","Blues scale III"),
		3315 => array("Tcherepnin Octatonic mode 1"),
		3379 => array("Verdi's Scala enigmatica descending"),
		3385 => array("Chromatic Phrygian"),
		3411 => array("Enigmatic","Verdi's Scala enigmatica ascending"),
		3413 => array("Leading Whole-tone"),
		3419 => array("Magan Abot"),
		3435 => array("Prokofiev"),
		3443 => array("Verdi's Scala enigmatica"),
		3445 => array("Messiaen mode 6 inverse",'sixth mode of limited transposition'),
		3485 => array("Sabach","Sambah"),
		3499 => array("Hamel"),
		3501 => array("Maqam Nahawand","Farahfaza","Raga Suha","Raga Suha Kanada","Gregorian nr.4","Utility Minor"),
		3507 => array("Maqam Hijaz","Maqam Hedjaz"),
		3515 => array("Moorish Phrygian","Phrygian/Double Harmonic Major mixed"),
		3519 => array("Raga Sindhi-Bhairavi"),
		3531 => array("Neveseri"),
		3549 => array("Messiaen mode 3 inverse","Tcherepnin Nonatonic mode 2",'third mode of limited transposition'),
		3575 => array("Symmetrical Decatonic"),
		3637 => array("Raga Rageshri"),
		3705 => array("Messiaen mode 4 inverse","Tcherepnin Octatonic mode 4"),
		3749 => array("Raga Sorati","Sur Malhar"),
		3757 => array("Raga Mian Ki Malhar","Bahar","Sindhura"),
		3761 => array("Raga Madhuri"),
		3765 => array("Dominant Bebop","Genus Diatonicum","Bebop Dominant","Raga Khamaj","Desh Malhar","Alhaiya Bilaval","Devagandhari","Bihagara","Maqam Shawq Awir","Gregorian nr.6","Major/Mixolydian mixed","Chinese Eight-Tone","Rast"),
		3767 => array("Chromatic Bebop"),
		3773 => array("Raga Malgunji","Ramdasi Malhar","Major/Dorian mixed","Blues Enneatonic"),
		3829 => array("Taishikicho","Ryo","Raga Chayanat","Lydian/Mixolydian mixed"),
		3837 => array("Minor Pentatonic with leading tones"),
		3965 => array("Messiaen mode 7 inverse"),
		4013 => array("Raga Pilu","Full Minor"),
		4021 => array("Raga Pahadi"),
		4029 => array("Major/Minor mixed"),
		4095 => array("Chromatic","Twelve-tone Chromatic"),
	);




	public static function justThePopularOnes() {
		$a = array(273, 585, 661, 859, 1193, 1257, 1365, 1371, 1387, 1389, 1397, 1451, 1453, 1459, 1485, 1493, 1499, 1621, 1643, 1709, 1717, 1725, 1741, 1749, 1753, 1755, 2257, 2275, 2457, 2475, 2477, 2483, 2509, 2535, 2731, 2733, 2741, 2773, 2777, 2869, 2901, 2925, 2925, 2989, 2997, 3055, 3411, 3445, 3549, 3669, 3765, 4095);
		return array_intersect_key(self::$scaleNames, array_fill_keys($a, true));
	}

	/**
	 * Constructor.
	 *
	 * @param int|string        $scale     [description]
	 * @param Pitch|string|null $root      [description]
	 * @param string            $direction [description]
	 */
	public function __construct($scale, $root = null, $direction = self::ASCENDING) {
		if ($root instanceof Pitch) {
			$this->root = $root;
		} elseif (is_null($root)) {
			$this->root = null; // because a scale can be a rootless, abstract thing
		} else {
			$this->root = new Pitch($root);
		}
		if (is_numeric($scale)) {
			$this->scale = $scale;
		} else {
			$this->scale = $this->_resolveScaleFromString($scale);
		}
		$this->direction = $direction;
	}

	/**
	 * accepts the scale object in the form of an array
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public static function constructFromArray($props) {
		if (isset($props['root'])) {
			if ($props['root'] instanceof Pitch) {
				$root = $props['root'];
			} else {
				$root = Pitch::constructFromArray($props['root']);
			}
		}

		return new Scale($props['scale'], $root, $props['direction']);
	}

	/**
	 * accept a string, like "C# major ascending" or "D# minor",
	 * "E4 aolian ascending" or "dorian"
	 * leaving ambiguities intact to be filled in with setProperty
	 *
	 * @param  [type] $string [description]
	 * @return int         the scale number
	 */
	public static function constructFromString($string, $root, $direction = self::ASCENDING) {
		$scale = self::resolveScaleFromString($string);
		return new Scale($scale, $root, $direction);
	}

	/**
	 * Scales are sometimes expressed as a stack of intervals ascending.
	 * accept an interval pattern like "2122122" and figure out what scale that is.
	 * @param  string $structureString
	 * @return [type]                  [description]
	 */
	public static function constructFromIntervalPattern($patternString) {
		$scale = self::resolveScaleFromIntervalPattern($patternString);
		return new Scale($scale, $root, $direction);
	}

	/**
	 * accepts an array of pitches, and will tell you what scale it is. If root is not provided,
	 * tries to figure out what the tonic is based on note distribution.
	 * @param  [type] $pitches [description]
	 * @return [type]          [description]
	 */
	public static function constructFromPitches($pitches, $root = null) {
		$scale = self::resolveScaleFromPitches($pitches);
		return new Scale($scale, $root, $direction);
	}

	/**
	 * accepts a string like "C sharp mixolydian" or "Ab"
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 * @todo
	 */
	public static function resolveScaleFromString($string) {

	}

	/*
	 * accept an interval pattern like "2122122" as a string, figures out what scale that is.
	 */
	public static function resolveScaleFromIntervalPattern($string) {
		$intervals = str_split($string);
		if (array_sum($intervals) != 12) {
			throw new \Exception('invalid interval pattern - intervals do not sum to an octave');
		}
		// remove the last interval
		array_pop($intervals);
		$i = 0;
		$scalebits = 1; // turn on the root bit
		foreach ($intervals as $interval) {
			$i += $interval;
			$scalebits = ($scalebits | (1 << ($i)));
		}
		return $scalebits;
	}

	/**
	 * if root is null, assume that the lowest pitch is the root.
	 * root doesn't need to be one of the pitches
	 * @param  [type] $pitches [description]
	 * @param  [type] $root    [description]
	 * @return [type]          [description]
	 */
	public static function resolveScaleFromPitches($pitches, $root = null) {

	}

	/**
	 * gets pitches in sequence for the scale, of one octave
	 * todo: make this better
	 */
	function getPitches() {
		if (empty($this->root)) {
			throw new \Exception('Can not get pitches for a rootless scale');
		}
		$pitches = array();
		for ($i = 0; $i < 12; $i++) {
			if ($this->direction == self::ASCENDING) {
				$offset = $i;
			} else {
				$offset = 12 - $i;
			}
			if ($this->scale & (1 << $offset)) {
				$newroot = clone $this->root;
				$newroot->transpose($i);
				$pitches[] = $newroot;
			}
		}
		$pitches = $this->normalizeScalePitches($pitches);

		return $pitches;
	}

	/**
	 * returns true if a set of pitches contains a step. Useful for figuring out if a scale
	 * should use an enharmonic spelling.
	 * @param  [type] $step [description]
	 * @return [type]       [description]
	 */
	public static function containsStep($pitches, $step) {
		foreach ($pitches as $pitch) {
			if ($pitch->step == $step) {
				return true;
			}
		}
		return false;
	}

	/**
	 * What this function has got to do is make sure that the C sharp major scale uses an E sharp, not
	 * an F natural. To do that it recognizes scales that are diatonic, and forces each note to be on
	 * consecutive steps. To do that, it assumes that the first note is on the correct step!
	 *
	 * TODO: it should handle complex scales like bebop and octotonic properly.
	 * Good luck!
	 *
	 * @param  Pitch[] $pitches [description]
	 * @return Pitch[]
	 */
	public function normalizeScalePitches($pitches) {
		if ($this->isHeliotonic()) {
			// first priority rule is that a heliotonic scale - which CAN be represented with 
			// a note on every step, MUST be notated that way. Even if there are lots of accidentals.
			$currentStep = $pitches[0]->step;
			for ($i = 1; $i < count($pitches); $i++) {
				$prevstep = $pitches[$i-1]->step;
				$shouldbe = Pitch::stepUp($prevstep);
				if ($pitches[$i] != $shouldbe) {
					$pitches[$i] = $pitches[$i]->enharmonicizeToStep($shouldbe);
				}
			}
		}

		// here we should normalize scales where there is a note and then its sharp on the same step, where
		// the step above is empty. As in scale 1621, 1725, 1755, 2275 etc

		$currentStep = $pitches[0]->step;
		for ($i = 1; $i < (count($pitches)-1); $i++) {

			if ($pitches[$i-1]->step == $pitches[$i]->step) {
				if ($pitches[$i]->alter == 1) {
					$stepup = Pitch::stepUp($pitches[$i]->step);
					if ($pitches[$i+1]->step != $stepup) {
						$pitches[$i] = $pitches[$i]->enharmonicizeToStep($stepup);
					}
				}
			}
		}

		// rule: a minor third should be more favoured than an augmented second
		// 
		// in fact, any major or minor interval should be preferred instead of an augmented or diminished one

		return $pitches;
	}


	public function isTrueScale() {
		return $this->hasRootTone() && $this->doesNotHaveFourConsecutiveOffBits();
	}

	/**
	 * We will conveniently use the definition that a heliotonic scale is a heptatonic scale that
	 * can be written with one tone on each step; so each tone gets its own letter name. This is useful when
	 * we are figuring out the enharmonic spelling of an altered note
	 */
	public function isHeliotonic() {
		// let's at least make an attempt to do this programatically
		if (!$this->isHeptatonic()) {
			return false;
		}

		// are any of the scale degrees more than 2 alterations away from the major scale?
		$major = array(0,2,4,5,7,9,11);
		$tones = $this->getTones();
		foreach ($tones as $index => $tone) {
			if (abs($tone - $major[$index]) > 2) {
				return false;
			}
		}
		return true;

		// here's our backup list in case the above doesn't work
		return in_array($this->scale, array(1387,1451,1709,1717,2773,2477,2741,1453,2777,1741,1395,2745));
	}

	public function isHeptatonic() {
		return $this->countTones() == 7;
	}

	/**
	 * return the levenshtein distance between two scales (a measure of similarity)
	 * accepts scale numbers, not Scale objects!
	 *
	 * @param  Scale $scale1 the first scale number
	 * @param  Scale $scale2 the second scale number
	 * @return int    Levenshtein distance between the two scales
	 */
	public static function levenshteinScale($scale1, $scale2) {
		$distance = 0;
		$d = $scale1 ^ $scale2;
		for ($i=0; $i<12; $i++) {
			if (
				($d & (1 << ($i))) && ($d & (1 << ($i+1)))
				&&
				($scale1 & (1 << ($i))) != ($scale1 & (1 << ($i+1)))
			) {
				$distance++;
				$d = $d & ( ~ (1 << ($i)));
				$d = $d & ( ~ (1 << ($i+1)));
			}
		}
		for ($i=0; $i<12; $i++) {
			if (($d & (1 << ($i)))) {
				$distance++;
			}
		}
		return $distance;
	}


	/**
	 * Static function: pass in a note, measure, layer etc and get back an array of scales that all the pitches conform to.
	 *
	 * @param  PMTObject   Note, Chord, Layer, Measure $obj the thing that has pitches in it, however deep they may be
	 * @param  bool $namedOnly  only return scales that have names
	 * @return array of Scales
	 */
	public static function getScales($obj, $namedOnly = true) {
		$pitches = $obj->getAllPitches();
		// todo figure out how to do this efficiently
	}


	/**
	 *
	 * @todo  I think this could be done using a rotation and XOR bitwise logic... investigate that
	 */
	public function imperfections() {
		$imperfections = array();
		for ($i = 0; $i<12; $i++) {
			$fifthAbove = ($i + 7) % 12;
			if ($this->scale & (1 << $i) && !($this->scale & (1 << $fifthAbove))) {
				$imperfections[] = $i;
			}
		}
		return $imperfections;
	}


	public function name($all = false) {
		if (isset(self::$scaleNames[$this->scale])) {
			$names = self::$scaleNames[$this->scale];
			if (!is_array($names)) {
				$names = array($names);
			}
			if ($all==true) {
				return $names;
			}
			return $names[0];
		}
		return null;
	}


	/**
	 * returns the spectrum of a scale, ie how many of every type of interval exists between all the tones.
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public function spectrum() {
		return \ianring\BitmaskUtils::spectrum($this->scale);
	}



	/**
	 * a special rule that some people think defines what a scale is.
	 * returns true if the first bit is not a zero.
	 * Useful for filtering a set of numbers to weed out non-scales
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public function hasRootTone($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		// returns true if the first bit is not a zero
		return (1 & $scale) != 0;
	}

	/**
	 * a special rule that some people think defines what a scale is, ie not having leaps of more than a major third.
	 * Useful for filtering a set of numbers to weed out non-scales
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public function doesNotHaveFourConsecutiveOffBits($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		$c = 0;
		for ($i=0; $i<12; $i++) {
			if (!($scale & (1 << ($i)))) {
				$c++;
				if ($c >= 4) {
					return false;
				}
			} else {
				$c = 0;
			}
		}
		return true;
	}

	/**
	 * returns an array of all the modes of a scale.
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	function modes($includeSelf = true, $unique = false) {
		$rotateme = $this->scale;
		$modes = array();

		$modes[] = $this->scale;

		for ($i = 0; $i < 11; $i++) {
			$rotateme = BitmaskUtils::rotateBitmask($rotateme);
			if (($rotateme & 1) == 0) {
				continue;
			}
			if ($rotateme == $this->scale) {
				break;
			}

			// if (!$includeSelf && $rotateme != $this->scale) {
				$modes[] = $rotateme;
			// }
		}

		// // take self off the end and put it at the start
		// $end = array_pop($modes);
		// array_unshift($modes, $end);

		// // remove self if it's not wanted
		if (!$includeSelf) {
			array_shift($modes);
		}

		if ($unique) {
			$modes = array_unique($modes);
		}

		return $modes;
	}

	/**
	 * finds the notes of a scale that are symmetry axes, ie the roots of modes that are identical the original
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	function symmetries() {
		$rotateme = $this->scale;
		$symmetries = array();
		for ($i = 0; $i < 12; $i++) {
			$rotateme = BitmaskUtils::rotateBitmask($rotateme);
			if ($rotateme == $this->scale) {
				if ($i != 11) {
					$symmetries[] = $i+1;
				}
			}
		}
		return $symmetries;
	}

	/**
	 * returns true if a scale is palindromic.
	 *
	 * @return boolean [description]
	 */
	public function isPalindromic() {
		for ($i=1; $i<=5; $i++) {
			if ((bool)($this->scale & (1 << $i)) !== (bool)($this->scale & (1 << (12 - $i))) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * returns true if the scale is chiral.
	 * Chirality means that something is distinguishable from its reflection, and can't be transformed into its reflection by rotation.
	 * see: https://en.wikipedia.org/wiki/Chirality_(mathematics)
	 * and http://ianring.com/scales
	 */
	public function isChiral() {
		$reflected = BitmaskUtils::reflectBitmask($this->scale);
		for ($i = 0; $i < 12; $i++) {
			$reflected = BitmaskUtils::rotateBitmask($reflected, 1, 1);
			if ($reflected == $this->scale) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Returns a new Scale, which is the enantiomorph of this one.
	 * The enantiomorph of a scale is its mirror image. In the case of a palindromic scale, the enantiomorph is itself.
	 * @todo
	 */
	public function enantiomorph() {
		$scale = BitmaskUtils::reflectBitmask($this->scale);
		$scale = BitmaskUtils::rotateBitmask($scale, -1, 1);
		return new \ianring\Scale($scale, $this->root, $this->direction);
	}

	/**
	 * Returns all the scales that this one can be transformed into by one addition, deletion, or
	 * having one tone shifted up or down by one semitone. In other words, it returns all the scales
	 * with a levenshtein distance of 1.
	 */
	function neighbours() {
		$near = array();
		// start at one, because we leave the root alone
		for ($i=1; $i<12; $i++) {
			if ($this->scale & (1 << ($i))) {
				// if this tone is on,
				$copy = $this->scale;

				// turn this tone off,
				$off = $copy ^ (1 << ($i));
				$near[] = $off;

				// move this tone down one semitone
				$copy = $off | (1 << ($i - 1));
				$near[] = $copy;

				// move this tone up one semitone, but be careful not to create an octave
				if ($i != 11) {
					$copy = $off | (1 << ($i + 1));
					$near[] = $copy;
				}
			} else {
				// if this tone is off, then try turning it on
				$copy = $this->scale;
				$copy = $copy | (1 << ($i));
				$near[] = $copy;
			}
		}
		return array_unique($near);
	}


	/**
	 * Returns named chords that contain only notes that are included in this scale
	 *
	 */
	function chordNames() {

	}

    /**
     * This method constructs tertiary triads built on each member of the scale.
     * For example when given a major scale, this should return

     * scale: 101010110101
     * [
     *                         10010001,     (root tonic triad)
     *                       1000100100,     (minor triad on the second degree)
     *                     100010010000,     (minor triad on the third degree)
     *                   1 001000100000,     (major triad on the fourth degree)
     *                 100 100010000000,     (major triad on the fifth degree)
     *               10001 001000000000,     (minor triad on the sixth degree)
     *              100100 100000000000,     (dim triad on the seventh degree)
     * ]
     *
     */
    public function getChordBitMasks() {
    	$chords = array();
    	$tones = $this->getTones();
    	$doubledTones = array_merge(
    		$tones,
	    	array_map(
	    		function($n){return $n + 12;},
	    		$this->getTones()
	    	)
	    );
    	for ($i=0; $i<count($tones); $i++) {
    		// build a triad on the ith degree
    		$triad = 0;
    		$triad = $triad | (1 << $doubledTones[$i]);
    		$triad = $triad | (1 << $doubledTones[$i + 2]);
    		$triad = $triad | (1 << $doubledTones[$i + 4]);
    		$chords[] = $triad;
    	}
    	return $chords;
    }

    /**
     * Returns triads built on each step of a scale. Only works for diatonic scales, and should always
     * render the chords using the right enharmonic spellings.
     */
    public function getTriads() {
    	if (!$this->isHeliotonic()) {
			return null;
		}
    	$pitches = $this->getPitches(); // this step already does the proper enharmonization for spelling
    	$count = count($pitches);

    	// now get the same pitches up an octave
    	$raised = array();
    	foreach ($pitches as $pitch) {
    		$raised[] = new Pitch($pitch->step, $pitch->alter, $pitch->octave + 1);
    	}

    	$pitches = array_merge($pitches, $raised);
    	// var_dump($pitches);

    	$chords = array();
    	for ($i=0; $i<$count; $i++) {
    		// build a triad on the ith degree
    		$triad = Chord::constructFromArray(
    			array(
    				'notes' => array(
    					array(
    						'pitch' => array(
    							'step' => $pitches[$i]->step,
    							'alter' => $pitches[$i]->alter,
    							'octave' => $pitches[$i]->octave
    						)
    					),
    					array(
    						'pitch' => array(
    							'step' => $pitches[$i+2]->step,
    							'alter' => $pitches[$i+2]->alter,
    							'octave' => $pitches[$i+2]->octave
    						)
    					),
    					array(
    						'pitch' => array(
    							'step' => $pitches[$i+4]->step,
    							'alter' => $pitches[$i+4]->alter,
    							'octave' => $pitches[$i+4]->octave
    						)
    					)
    				)
    			)
    		);
    		$chords[] = $triad;
    	}
    	return $chords;
    }

    /**
     * This returns the *places* where bits are on, as a 0-based set. For example, the
     * binary 101010010001 should return [0, 4, 7, 9, 11]
     * This set of tones is used to construct chords and other useful things
     * unlike some of the other methods, this one should recognize places higher than 12
     */
    public function getTones() {
    	return BitmaskUtils::bits2Tones($this->scale);
    }


	/**
	 * counts the number of tones in a scale
	 *
	 * @return [type] [description]
	 */
	public function countTones() {
		return BitmaskUtils::countOnBits($this->scale);
	}

	public function scaletype() {
		$types = array(
			3 => 'tritonic',
			4 => 'tetratonic',
			5 => 'pentatonic',
			6 => 'hexatonic',
			7 => 'heptatonic',
			8 => 'octatonic',
			9 => 'nonatonic',
			10 => 'decatonic',
			11 => 'undecatonic',
			12 => 'dodecatonic'
		);
		$numTones = $this->countTones();
		if (isset($types[$numTones])) {
			return $types[$numTones];
		}
		return null;
	}





	/**
	 * returns the interval pattern of a scale. eg a major scale has the pattern [2,2,1,2,2,2]
	 */
	public function intervalPattern() {
		if (!$this->hasRootTone()) {
			throw new \Exception('we do not make patterns for scales with no root tone');
		}
		$tones = $this->getTones();
		$tones[] = 12;
		$pattern = array();
		for ($i=0; $i<(count($tones) - 1); $i++) {
			$pattern[] = $tones[$i+1] - $tones[$i];
		}
		return $pattern;
	}

	public function hemitonicTones() {
		return \ianring\BitmaskUtils::bits2Tones($this->hemitonics());
	}
	public function tritonicTones() {
		return \ianring\BitmaskUtils::bits2Tones($this->tritonics());
	}
	public function cohemitonicTones() {
		return \ianring\BitmaskUtils::bits2Tones($this->cohemitonics());
	}

	/**
	 * returns the bits that have a semitone above them
	 */
	function hemitonics($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		return $this->findIntervalics($scale, 1);
	}

	/**
	 * returns the bits that have a tritone above them
	 */
	function tritonics($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		return $this->findIntervalics($scale, 7);
	}

	/**
	 * returns the bits that have a semitone above them, and a semitone above those two.
	 * how elegant is it that we just call hemitonics() twice recursively? booyah.
	 */
	function cohemitonics($scale = null) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		return $this->hemitonics($this->hemitonics($this->scale));
	}

	public function isHemitonic() {
		return count($this->hemitonicTones()) > 0;
	}

	public function isCohemitonic() {
		return count($this->cohemitonicTones()) > 0;
	}

	public function isTritonic() {
		return count($this->tritonicTones()) > 0;
	}

	/**
	 * finds tones that have some interval above them, e.g. hemitonics and tritonics
	 */
	private function findIntervalics($scale = null, $interval) {
		if (is_null($scale)) {
			$scale = $this->scale;
		}
		$rotateme = $scale; // make a copy
		return $scale & (BitmaskUtils::rotateBitmask($rotateme, $direction = 1, $amount = $interval));
	}

	function hemitonia() {
		$hemi = $this->hemitonicTones();
		if (count($hemi) == 0) {
			return 'anhemitonic';
		}
		if (count($hemi) == 1) {
			return 'unhemitonic';
		}
		if (count($hemi) == 2) {
			return 'dihemitonic';
		}
		if (count($hemi) == 3) {
			return 'trihemitonic';
		}
		return 'multihemitonic';
	}

	function cohemitonia() {
		$cohemi = $this->cohemitonicTones();
		if (count($cohemi) == 0) {
			return 'ancohemitonic';
		}
		if (count($cohemi) == 1) {
			return 'uncohemitonic';
		}
		if (count($cohemi) == 2) {
			return 'dicohemitonic';
		}
		if (count($cohemi) == 3) {
			return 'tricohemitonic';
		}
		return 'multicohemitonic';
	}

	/**
	 * returns the polar negative of this scale
	 * that's the scale where all the on bits are off, and the off bits are on. Beware that this produces a non-scale
	 */
	public function negative() {
		$negative = 4095 ^ $this->scale;
		return new Scale($negative);
	}


	/**
	 * returns the inverse of this scale, reflected across the root.
	 * Note the similarities bewtixt this and Pitch::invert() - the main difference being that this
	 * inversion is modulo-12 and works on bits (members of a pitch class set), not actual pitches. As a consequence,
	 */
	public function invert($axis = 0) {
		$inverse = BitmaskUtils::reflectBitmask($this->scale);
		$rotateBy = ($axis * 2) - 11;
		$inverse = BitmaskUtils::rotateBitmask($inverse, $direction = -1, $rotateBy);
		$this->scale = $inverse;
	}


	/**
	 * see section 3.3 of http://composertools.com/Theory/PCSets.pdf
	 * 
	 * returns the prime form of this scale, as a 
	 */
	public function primeForm() {
		$pcs = new PitchClassSet($this->scale);
		$p = $pcs->primeForm();
		return $p;
	}

	/**
	 * returns true if this scale is in Prime form
	 */
	public function isPrime() {
		return $this->scale == $this->primeForm();
	}

	/**
	 * Scales that share the same interval spectrum but are not transpositionally related nor inversionally related are called Z-related, also 
	 * called isomeric relation. For example, a major triad and minor triad have the same interval content, but you can't
	 * transform one into the other by rotation or inversion. Therefore, they are Z-related. 
	 *
	 * This method should return all the Z-related scales 
	 */
	public function zRelated() {

	}

	/**
	 * returns an array of all modal families. ie for each set of scales that are modes of each other, only a single
	 * representative is present.
	 */
	public static function getFamilies($justTrueScales = true) {
		$allscales = range(0, 4095);
		$modelist= array();
		while (count($allscales) > 0) {
			$s = array_pop($allscales);
			$scale = new Scale($s);
			$modes = $scale->modes();
			if ($scale->isTrueScale()) {
				$modelist[] = $s;
			}
			foreach ($modes as $mode) {
				unset($allscales[$mode]);
			}
		}
		return $modelist;
	}


}
