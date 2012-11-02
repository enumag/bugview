<?php

if (isset(\Nette\Diagnostics\Debugger::$logger)) {

	$mailer = \Nette\Diagnostics\Debugger::$logger->mailer;

	\Nette\Diagnostics\Debugger::$logger->mailer = function ($message, $email) use ($mailer) {
		$parts = explode('@@', $message);
		if (isset($parts[count($parts) - 1])) {
			$parts[count($parts) - 1] = '  http://' . $_SERVER['SERVER_NAME'] . '/' . pathinfo(__DIR__, PATHINFO_BASENAME) . '/index.php?exception=' . urlencode(trim($parts[count($parts) - 1]));
			$message = implode('@@', $parts);
		}
		$mailer($message, $email);
	};

}