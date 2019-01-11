# raspberry-pi-tempsensor
Interface for Raspberry PI 3B+ temperature sensor


It draws temperature graphs of values recorded in the last 24 hours, 7 days, 1 month, 1 year.
You can set minimum and maximum temperature values, out of this range the system send an email alert to a choosen address.

You can use Cron to read temperature from Raspberry every n minutes:

*/5 * * * * php /var/www/vh/temperature/gettemp.php > /dev/null 2>&1

I use this for data rack temperature monitoring.
There is not elegant code, just a draft written in hurry, but it gets the job done.

![screenshot1](/screenshot/temperature_screenshot.jpg?raw=true "temp graphs")
![screenshot2](/screenshot/temperature_screenshot_max_temp.jpg?raw=true "temp mas warning")
