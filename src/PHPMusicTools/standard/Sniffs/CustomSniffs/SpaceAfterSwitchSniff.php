<?php
namespace StandardName\Sniffs\Category;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * standard_Sniffs_SpaceAfterSwitchSniff.
 *
 * Makes sure there is a space after a switch statement
 *
 * @category  PHP
 * @package	  PHP_CodeSniffer
 * @author	  Ian Ring <iring@netsuite.com>
 */
class standard_Sniffs_CustomSniffs_SpaceAfterSwitchSniff implements Sniff
{

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(T_SWITCH);
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int				   $stackPtr  The position of the current token in the
	 *										  stack passed in $tokens.
	 * @return void
	 */
	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
			$message = 'Switch Statement must be followed by a space';
			$phpcsFile->addError($message, $stackPtr, 'Missing');
		}
	}

}
