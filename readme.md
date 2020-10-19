1. Just put in extensions folder twilio folder
2. Copy extension/twilio/settings.ini.default.php to extension/twilio/settings.ini.php
3. Execute
```
php cron.php -s site_admin -e twilio -c cron/update_structure
```
or https://github.com/LiveHelperChat/twilio/blob/master/doc/install.sql

4. Enable extension in `lhc_web/settings/settings.ini.php` main Live Helper Chat configuration file.
'extensions' =>
      array (
        'twilio'
      ),
5. Clear cache from back office. Click `Settings > System -> Clear Cache`
6. Register phone from Live Helper Chat back office.
6. Send SMS to registered phone number
7. Enjoy :)

Twilio module you will find in left menu under Modules.

Callback URL you will find once your register phone in back office.
