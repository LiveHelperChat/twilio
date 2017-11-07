1. Just put in extensions folder twilio folder
2. Copy extension/twilio/settings.ini.default.php to extension/twilio/settings.ini.php
3. Edit file content and set twilio data
2. Enable extension in settings/settings.ini.php
'extensions' =>
      array (
        'twilio'
      ),
3. Clear cache from back office.
4. Send SMS to registered phone number
5. Enjoy :)

Callback URL example
http://example.com/twilio/callbacks