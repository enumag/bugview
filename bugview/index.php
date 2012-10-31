<?php

require __DIR__ . '/config.php';

session_start();

echo '<h1>BugView</h1>';

if (PASSWORD === '') {
	echo '<p>Password can\'t be an empty string. Please modify config.php.</p>';
	exit;
}

if (!is_dir(LOG_DIR)) {
	echo '<p>Log directory not found. Please modify config.php.</p>';
	exit;
}

if (isset($_GET['logout'])) {
	$_SESSION['__bugview'] = FALSE;
}

if (!isset($_SESSION['__bugview']) || !$_SESSION['__bugview']) {
	if (isset($_POST['user']) && isset($_POST['password'])) {
		if ($_POST['user'] === USER && $_POST['password'] === PASSWORD) {
			$_SESSION['__bugview'] = TRUE;
		} else {
			echo '<p>Wrong user name or password. Please try again.</p>';
		}
	}
}

if (!isset($_SESSION['__bugview']) || !$_SESSION['__bugview']) {
	echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
	echo '<table>';
	echo '<tr><td><label for="user">User:</label></td><td><input type="text" name="user" id="user"></td></tr>';
	echo '<tr><td><label for="password">Password:</label></td><td><input type="password" name="password" id="password"></td></tr>';
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

$dir = new DirectoryIterator(LOG_DIR);
foreach ($dir as $fileinfo) {
	if (substr($fileinfo->getFilename(), 0, 9) == 'exception') {
		echo '<a href="' . $_SERVER['PHP_SELF'] . '?exception=' . urlencode($fileinfo->getFilename()) . '">' . $fileinfo->getFilename() . '</a><br>';
	}
}