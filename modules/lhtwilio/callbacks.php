<?php

/*Nov 08 09:00:06 [Warning] [default] [default] Array
(
    [ToCountry] => US
    [ToState] => AZ
    [SmsMessageSid] => SM0b9acb7ecd7a1ceed4384b9f08c84679
    [NumMedia] => 0
    [ToCity] =>
    [FromZip] =>
    [SmsSid] => SM0b9acb7ecd7a1ceed4384b9f08c84679
    [FromState] => AZ
    [SmsStatus] => received
    [FromCity] =>
    [Body] => Hi there does it works
    [FromCountry] => US
    [To] => +14803607305
    [ToZip] =>
    [NumSegments] => 1
    [MessageSid] => SM0b9acb7ecd7a1ceed4384b9f08c84679
    [AccountSid] => ACd7bc2319cfa2b1b8d45d17f4e4823dd4
    [From] => +14803607305
    [ApiVersion] => 2010-04-01
)*/

/* $dumpArray['ToCountry'] = 'US';
$dumpArray['ToState'] = 'AZ';
$dumpArray['SmsMessageSid'] = 'MM729c0bced4770d1e41a42b2443d67226';
$dumpArray['NumMedia'] = '1';
$dumpArray['ToCity'] = null;
$dumpArray['FromZip'] = null;
$dumpArray['SmsSid'] = 'SM0b9acb7ecd7a1ceed4384b9f08c84679';
$dumpArray['FromState'] = 'AZ';
$dumpArray['SmsStatus'] = 'received';
$dumpArray['FromCity'] = null;
$dumpArray['Body'] = 'Hi there does it works';
$dumpArray['FromCountry'] = 'US';
$dumpArray['To'] = '+14803607305';
$dumpArray['ToZip'] = null;
$dumpArray['NumSegments'] = 1;
$dumpArray['MessageSid'] = 'SM0b9acb7ecd7a1ceed4384b9f08c84679';
$dumpArray['AccountSid'] = 'ACd7bc2319cfa2b1b8d45d17f4e4823dd4';
$dumpArray['From'] = '+14803607305';
$dumpArray['ApiVersion'] = '2010-04-01'; */

/* $dumpArray['ToCountry'] = 'US';
$dumpArray['MediaContentType0'] = 'image/png';
$dumpArray['ToState'] = 'AZ';
$dumpArray['SmsMessageSid'] = 'MM729c0bced4770d1e41a42b2443d67226';
$dumpArray['NumMedia'] = '1';
$dumpArray['ToCity'] = null;
$dumpArray['FromZip'] = null;
$dumpArray['SmsSid'] = 'MM729c0bced4770d1e41a42b2443d67226';
$dumpArray['FromState'] = 'AZ';
$dumpArray['SmsStatus'] = 'received';
$dumpArray['FromCity'] =
$dumpArray['Body'] = 'asdad';
$dumpArray['FromCountry'] = 'US';
$dumpArray['To'] = '+14803607305';
$dumpArray['ToZip'] = null;
$dumpArray['NumSegments'] = '1';
$dumpArray['MessageSid'] = 'MM729c0bced4770d1e41a42b2443d67226';
$dumpArray['AccountSid'] = 'ACd7bc2319cfa2b1b8d45d17f4e4823dd4';
$dumpArray['From'] = '+37065272274';
$dumpArray['MediaUrl0'] = 'https://api.twilio.com/2010-04-01/Accounts/ACd7bc2319cfa2b1b8d45d17f4e4823dd4/Messages/MM729c0bced4770d1e41a42b2443d67226/Media/ME7920446f04994dc102ff663f2a389d4a';
$dumpArray['ApiVersion'] = '2010-04-01'; */

/* erLhcoreClassLog::write(print_r($_POST,true)); */

$dumpArray = $_POST;

try {
    $dynmarkSMS = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionTwilio');
    $dynmarkSMS->processCallback($dumpArray);
} catch (Exception $e) {
    throw $e;
    erLhcoreClassLog::write(print_r($_POST,true));
    erLhcoreClassLog::write(print_r($e,true));
}

header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";

exit;
?>