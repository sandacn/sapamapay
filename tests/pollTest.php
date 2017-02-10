<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Edwinmugendi\Poller\FilePoller;

$file_poller = new FilePoller();

$file_poller->poll();