<?php

include_once __DIR__.'/../evop-essentials/chechkchanges.php';

print(json_encode(checkchanges(), JSON_NUMERIC_CHECK));