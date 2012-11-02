<?php

require __DIR__ . '/config.php';
require __DIR__ . '/SortableDirectoryIterator.php';

session_start();

if (isset($_GET['logout'])) {
	$_SESSION['__bugview'] = FALSE;
	header('Location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']);
}

if ((!isset($_SESSION['__bugview']) || !$_SESSION['__bugview']) && isset($_POST['password']) && (PASSWORD_ALGORITHM === '' ? $_POST['password'] : hash(PASSWORD_ALGORITHM, $_POST['password'])) === PASSWORD_HASH) {
	$_SESSION['__bugview'] = TRUE;
	header('Location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
}

echo '<h1>BugView</h1>';

if ((!isset($_SESSION['__bugview']) || !$_SESSION['__bugview']) && isset($_POST['password'])) {
	if (PASSWORD_HASH === '') {
		echo '<p>Generated hash: ' . hash(PASSWORD_ALGORITHM, $_POST['password']) . '</p>';
	} else {
		echo '<p>Wrong user name or password. Please try again.</p>';
	}
}

if (!is_dir(LOG_DIR)) {
	echo '<p>Log directory not found. Please modify config.php.</p>';
	exit;
}

if (IP_ADDRESS !== '*' && !in_array($_SERVER['REMOTE_ADDR'], explode(',', IP_ADDRESS))) {
	echo '<p>Wrong IP address.</p>';
	exit;
}

if (PASSWORD_HASH === '') {
	$_SESSION['__bugview'] = FALSE;
	echo '<p>Password can\'t be an empty string. Please modify config.php.</p>';
	echo '<p>You can generate the password hash by typing a password into the box below.</p>';
}

if (!isset($_SESSION['__bugview']) || !$_SESSION['__bugview']) {
	echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
	echo '<table>';
	echo '<tr><td><label for="password">Password:</label></td><td><input type="password" name="password" id="password" autofocus></td></tr>';
	echo '<tr><td></td><td><input type="submit" value="Log in"></td></tr>';
	echo '</table>';
	echo '</form>';
	exit;
}

echo '<p><a href="' . $_SERVER['PHP_SELF'] . '?logout">Log out</a></p>';

if (isset($_GET['exception'])) {
	if (is_readable(LOG_DIR . '/' . $_GET['exception'])) {
		readfile(LOG_DIR . '/' . $_GET['exception']);
		exit;
	} else {
		echo '<p>Can\'t read file "' . $_GET['exception'] . '".';
	}
}

$dir = new SortedFileIterator(LOG_DIR);
foreach ($dir as $fileinfo) {
	if (substr($fileinfo->getFilename(), 0, 9) == 'exception') {
		echo '<a href="' . $_SERVER['PHP_SELF'] . '?exception=' . urlencode($fileinfo->getFilename()) . '">' . $fileinfo->getFilename() . '</a><br>';
	}
}