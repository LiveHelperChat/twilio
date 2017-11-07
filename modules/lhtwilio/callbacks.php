<?php

//erLhcoreClassLog::write(print_r($_POST,true));

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
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n
<Response>
</Response>";

exit;
?>