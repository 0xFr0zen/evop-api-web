<?php

include_once __DIR__.'/../../essentials/qr-code.php';

QRcode::png($_REQUEST['data'], false, QRcode::QR_ECLEVEL_L, 4);