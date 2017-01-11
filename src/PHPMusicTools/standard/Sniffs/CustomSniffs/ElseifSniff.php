<?php
/**
 * PMT_Sniffs_ElseifSniff.
 *
 * Looks for "else if", where we should use "elseif".
 *
 * @category  PHP
 * @package	  PHP_CodeSniffer
 * @author	  Ian Ring <httpwebwitch@gmail.com>
 */
class standard_Sniffs_CustomSniffs_ElseifSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(T_ELSE);
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		if ($tokens[($stackPtr + 1)]['code'] == T_WHITESPACE && $tokens[($stackPtr + 2)]['code'] == T_IF) {
			$message = 'Detected else if. Use elseif instead.';
			$phpcsFile->addError($message, $stackPtr, 'Missing');
		}

	}

}
