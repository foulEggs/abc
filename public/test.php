<?php
echo 111;
fastcgi_finish_request();

sleep(2);
@file_put_contents('./log.txt', 'dfgdfg');