1. Just put in extensions folder twilio folder
2. Copy `extension/twilio/settings.ini.default.php` to extension/twilio/settings.ini.php
3. Execute
```
php cron.php -s site_admin -e twilio -c cron/update_structure
```
or 
`https://github.com/LiveHelperChat/twilio/blob/master/doc/install.sql`

4. Enable extension in `settings/settings.ini.php`
```
'extensions' =>
      array (
        'twilio'
      ),
```
5. Clear cache from back office.
6. Twilio module you will find in left menu under Modules.
7. Register phone from Live Helper Chat back office.

Settings should look similar to this. P.s I have changed phone number and `Account SID` with `Auth Token`

![See image](https://raw.githubusercontent.com/LiveHelperChat/twilio/master/doc/phonenumber.png)

If you are using `WhatsApp` phone number should look like `whatsapp:+14155234444`

6. Send SMS to registered phone number
7. Enjoy :)

Callback URL you will find once your register phone in back office.
