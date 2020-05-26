#!/usr/bin/env php
<?php

require_once __DIR__ . '/bootstrap.php';

$application = new \Symfony\Component\Console\Application();

$application->add(new \Mehio\WhmUtil\IpReporter\Command\PullModsecLogCommand());
$application->add(new \Mehio\WhmUtil\IpReporter\Command\PullHulkLogCommand());
$application->add(new \Mehio\WhmUtil\IpReporter\Command\ReportToAbuseIpDbCommand());

$application->run();