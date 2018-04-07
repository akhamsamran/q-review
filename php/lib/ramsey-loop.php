<?php
namespace Edu\Cnm\Faqt;

require_once(dirname(__DIR__, 1) . "/classes/autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

$numberOfUuid = 20;

for ($i = 0; $i < $numberOfUuid ; $i++) {
	$uuid1 = Uuid::uuid1();
	echo $uuid1->toString() . "\r\n";
}

