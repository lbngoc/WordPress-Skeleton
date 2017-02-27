#!/bin/bash

# .---------------- minute (0 - 59)
# |  .------------- hour (0 - 23)
# |  |  .---------- day of month (1 - 31)
# |  |  |  .------- month (1 - 12) OR jan,feb,mar,apr ...
# |  |  |  |  .---- day of week (0 - 6) (Sunday=0 or 7)  OR sun,mon,tue,wed,thu,fri,sat
# |  |  |  |  |
# *  *  *  *  *  command to be executed

# Use linux crontab
# > sudo crontab -e
# > 1 * * * * wget -q -O - "http://localhost:8001/wp/wp-cron.php" > /dev/null 2>&1

# Run every 40 seconds
WP_CRON_CMD="wget -q -O - 'http://localhost:8001/wp/wp-cron.php' >> wp-cron.log 2>&1"
# kill $(ps -au | grep "$WP_CRON_CMD" | awk '{print $2}')
pkill -f "wp-cron.php"
nohup watch -n 40 $WP_CRON_CMD >/dev/null 2>&1 & # runs in background, still doesn't create nohup.out

echo "WP-Cron is running..."
exit 0
