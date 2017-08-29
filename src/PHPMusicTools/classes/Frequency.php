<?php
/**
 * Whilst a Pitch represents the conceptual notion of a sound's height, the frequency is a 
 * specific scientific property of something's vibration, as measured in Hertz (cycles per second).
 * 
 * The same pitch, for example the A above middle C, might have different frequency in different 
 * tuning systems. This class encapulates methods that let us play with frequencies.
 */

class Frequency extends PMTObject
{
	

	/**
	 *
     * @param  $n the number of a harmonic, e.g. 1 = 1st, 2 = 2nd...
     * @return Frequency  a new Frequency object which represents that harmonic of the root
	 */
	function get_harmonic($n) {
		if (!is_integer($n) || $n < 0) {
			throw new Error('harmonic must be a positive integer');
		}
	}
}