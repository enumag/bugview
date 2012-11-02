<?php

//Specify the correct path here
define('LOG_DIR', __DIR__ . '/../log');

//Hash or raw password if algorithm is empty string
define('PASSWORD_HASH', '');

//Algorithm to calculate the hash
define('PASSWORD_ALGORITHM', 'sha1');

//Separated by comma, * for any IP adress
define('IP_ADDRESS', '*');