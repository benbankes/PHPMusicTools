<?php
namespace ianring;

class PMTObject {
	
	/**
	 * force deep cloning, so a clone of the measure will contain a clone of all its sub-objects as well
	 * @return [type] [description]
	 */
	public function __clone() {
	    foreach($this as $key => $val) {
	        if (is_object($val) || (is_array($val))) {
	            $this->{$key} = unserialize(serialize($val));
	        }
	    }
	}

}
