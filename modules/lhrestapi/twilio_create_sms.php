<?php

try {
    erLhcoreClassRestAPIHandler::validateRequest();

    $user = erLhcoreClassRestAPIHandler::getUser();

    if (!$user->hasAccessTo('lhtwilio','use')) {
        throw new Exception('You do not have permission to use twilio! "lhtwilio","use" permission is missing');
    }

    $params = json_decode(file_get_contents('php://input'),true);

    // Twilio phone number
    if (!isset($params['phone_number']) || empty($params['phone_number'])) {
        $Errors['phone_number'] = erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sendmessage','Please enter phone number!');
    }

    // Twilio message
    if (!isset($params['msg']) || empty($params['msg'])) {
        $Errors['msg'] = erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sendmessage','Please enter message!');
    }

    // Should we create a chat or just send an SMS
    if (!isset($params['create_chat'])) {
        $params['create_chat'] = false;
    } else {
        $params['create_chat'] = true;
    }

    if (!isset($params['twilio_id']) || !is_numeric($params['twilio_id'])) {
        $Errors['twilio_id'] = erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sendmessage','Please enter twilio phone!');
    }

    if (!empty($Errors)) {
        throw new Exception(json_encode($Errors));
    }

    $twilio = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionTwilio');


    $params['operator_id'] = $user->id;
    $params['name_support'] = $user->name_support;

    $chat = $twilio->sendManualMessage($params);

    echo erLhcoreClassRestAPIHandler::outputResponse(array(
        'error' => false,
        'result' => 'ok'
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