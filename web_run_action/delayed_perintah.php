<?php
$delay = $_GET['delay'] ?? 0;
$method = $_GET['method'] ?? 0;
$param_data = json_decode(($_GET['param_data'] ?? json_encode([])));
sleep(0);
KirimPerintah($method,$param_data);