<?php
/*
 Be aware that this script is really, really insecure. Having it available
 on your web server is a giant security vulnerability that could cause
 untold damage if used maliciously.

 For the purposes of a public repo, I'm going to put a die() in here, so
 it won't end up on someone's server accidentally as a giant
 security vulnerability. If you want to use the image generator, remove the
 die(), then run the script, then put the die() back again.
 */
die();



	define('UPLOAD_DIR', 'images/');

	if (!file_exists(UPLOAD_DIR)) {
		mkdir(UPLOAD_DIR);
	}

	$img = $_POST['canvas'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR . $_POST['filename'] . '.png';
	$success = file_put_contents($file, $data);
	print $success ? $file : 'Unable to save the file.';
