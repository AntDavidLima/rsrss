<?php

declare(strict_types=1);

use OCP\Util;

Util::addScript(OCA\RsRss\AppInfo\Application::APP_ID, OCA\RsRss\AppInfo\Application::APP_ID . '-main');
Util::addStyle(OCA\RsRss\AppInfo\Application::APP_ID, OCA\RsRss\AppInfo\Application::APP_ID . '-main');

?>

<div id="rsrss"></div>
