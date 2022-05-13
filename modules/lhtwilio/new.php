<?php
$tpl = erLhcoreClassTemplate::getInstance('lhtwilio/new.tpl.php');

$item = new erLhcoreClassModelTwilioPhone();

$tpl->set('item',$item);

if (ezcInputForm::hasPostData()) {

    $Errors = erLhcoreClassTwilioValidator::validatePhone($item);

    if (count($Errors) == 0) {
        try {
            $item->saveThis();
             
            erLhcoreClassModule::redirect('twilio/list');
            exit ;

        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }

    } else {
        $tpl->set('errors',$Errors);
    }
}

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('twilio/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Twilio')
    ),
    array (
        'url' =>erLhcoreClassDesign::baseurl('twilio/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Twilio phones')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'New')
    )
);

?>