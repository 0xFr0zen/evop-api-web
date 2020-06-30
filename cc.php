<?php

include_once __DIR__.'/../evop-essentials/checkchanges.php';

print(json_encode(checkchanges(), JSON_NUMERIC_CHECK));