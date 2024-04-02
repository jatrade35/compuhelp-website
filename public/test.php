<?php

$locale = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
echo $locale;
//echo phpinfo();

echo xdebug_info();