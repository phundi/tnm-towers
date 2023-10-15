<?php

require_once realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap_cronjob.php';

echo('Querying for failed Transactions!!');

$subs = CheckFailedSubs();

var_dump($subs);
