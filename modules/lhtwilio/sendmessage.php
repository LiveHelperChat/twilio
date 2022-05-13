<?php 

$tpl = erLhcoreClassTemplate::getInstance('lhtwilio/sendmessage.tpl.php');

$input = new stdClass();
$input->phone_number = '';
$input->message = '';
$input->create_chat = true;
$input->dep_id = 0;
$input->twilio_id = 0;

/**
 * Has post data
 */
if (ezcInputForm::hasPostData()) {
    
    $definition = array(
        'TwilioPhoneNumber' => new ezcInputFormDefinitionElement(ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw', null),
        'TwilioMessage' => new ezcInputFormDefinitionElement(ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw', null),
        'TwilioCreateChat' => new ezcInputFormDefinitionElement(ezcInputFormDefinitionElement::OPTIONAL, 'boolean', null),
        'TwilioDepartment' => new ezcInputFormDefinitionElement(ezcInputFormDefinitionElement::OPTIONAL, 'int', null),
        'TwilioId' => new ezcInputFormDefinitionElement(ezcInputFormDefinitionElement::OPTIONAL, 'int', null)
    );
    
    $Errors = array();
    $form = new ezcInputForm(INPUT_POST, $definition);
    
    // Twilio phone number
    if ($form->hasValidData('TwilioPhoneNumber') && !empty($form->TwilioPhoneNumber)) {
        $input->phone_number = $form->TwilioPhoneNumber;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sendmessage','Please enter phone number!');
    }
    
    // Twilio message
    if ($form->hasValidData('TwilioMessage') && !empty($form->TwilioMessage)) {
        $input->message = $form->TwilioMessage;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sendmessage','Please enter message!');
    }
    
    // Should we create a chat or just send an SMS
    if ($form->hasValidData('TwilioCreateChat') && $form->TwilioCreateChat == true) {
        $input->create_chat = true;
    } else {
        $input->create_chat = false;
    }
    
    if ($form->hasValidData('TwilioId')) {
        $input->twilio_id = $form->TwilioId;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sendmessage','Please choose twilio phone!');
    }
    
    // Set department
    if ($form->hasValidData('TwilioDepartment')) {
        $input->dep_id = $form->TwilioDepartment;
    } else {
        $input->dep_id = 0;
    }
    
    if (empty($Errors))
    {
        try {

            $currentUser = erLhcoreClassUser::instance();
            $userData = $currentUser->getUserData();

            $twilio = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionTwilio');
            $chat = $twilio->sendManualMessage(array(
                'msg' => $input->message,
                'phone_number' => $input->phone_number,
                'create_chat' => $input->create_chat,
                'dep_id' => $input->dep_id,
                'operator_id' => $userData->id,
                'name_support' => $userData->name_support,
                'twilio_id' => $input->twilio_id,
            ));

            $tpl->set('updated',true);
            $tpl->set('chat',$chat);

        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }
        
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('input',$input);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('twilio/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Twilio')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('sugarcrm/module', 'Send SMS')
    )
);

?>