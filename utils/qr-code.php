<?php

include_once __DIR__.'/../../essentials/qr-code.php';

QRcode::png($_REQUEST['data'], false, 3, 4);