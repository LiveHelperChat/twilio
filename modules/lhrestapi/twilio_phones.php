<?php

try {
    erLhcoreClassRestAPIHandler::validateRequest();

    $user = erLhcoreClassRestAPIHandler::getUser();

    if (!$user->hasAccessTo('lhtwilio','use')) {
        throw new Exception('You do not have permission to use twilio! "lhtwilio","use" permission is missing');
    }

    echo erLhcoreClassRestAPIHandler::outputResponse(array(
        'error' => false,
        'result' => array_values(erLhcoreClassModelTwilioPhone::getList(array('limit' => false,'ignore_fields' => array('account_sid','auth_token'))))
    ));

} catch (Exception $e) {
    http_response_code(400);
    echo erLhcoreClassRestAPIHandler::outputResponse(array(
        'error' => true,
        'result' => $e->getMessage()
    ));
}
exit;

?>