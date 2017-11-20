<?php

//erLhcoreClassLog::write(print_r($_POST,true));
$dumpArray['ToCountry'] = 'US';
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
$dumpArray['ApiVersion'] = '2010-04-01';

/* $dumpArray = $_POST; */

try {
    $dynmarkSMS = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionTwilio');
    $dynmarkSMS->processCallback($dumpArray);
} catch (Exception $e) {
    throw $e;
    erLhcoreClassLog::write(print_r($_POST,true));
    erLhcoreClassLog::write(print_r($e,true));
}

header("content-type: text/xml");

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n
<Response>
</Response>";

exit;
?>