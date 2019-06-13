#!/bin/bash

#定时脚本
echo '* * * * * nginx /usr/bin/php /var/www/html/artisan schedule:run >> /dev/null 2>&1' >> /etc/crontab
