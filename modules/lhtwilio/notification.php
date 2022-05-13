<?php
$tpl = erLhcoreClassTemplate::getInstance('lhtwilio/notification.tpl.php');

$twilioOptions = erLhcoreClassModelChatConfig::fetch('twilio_notification');
$data = (array) $twilioOptions->data;

if (ezcInputForm::hasPostData()) {

    $Errors = erLhcoreClassTwilioValidator::validateNotification($data);

    if (count($Errors) == 0) {
        try {
            $twilioOptions->explain = '';
            $twilioOptions->type = 0;
            $twilioOptions->hidden = 1;
            $twilioOptions->identifier = 'twilio_notification';
            $twilioOptions->value = serialize($data);
            $twilioOptions->saveThis();

            $tpl->set('updated', true);
        } catch (Exception $e) {
            $tpl->set('errors', array(
                $e->getMessage()
            ));
        }

    } else {
        $tpl->set('errors', $Errors);
    }
}

$tpl->set('data',$data);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('twilio/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Twilio')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('twilio/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/groovehq', 'Twilio returning visitor template')
    )
);

?>