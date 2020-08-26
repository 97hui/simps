#!/usr/bin/env php
<?php

require './autoload.php';

\Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_FLAGS);

Simps\Application::run();
