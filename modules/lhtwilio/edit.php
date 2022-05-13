<?php 

$tpl = erLhcoreClassTemplate::getInstance('lhtwilio/edit.tpl.php');

$item =  erLhcoreClassModelTwilioPhone::fetch($Params['user_parameters']['id']);

if (ezcInputForm::hasPostData()) {
        
    if (isset($_POST['Cancel_action'])) {
        erLhcoreClassModule::redirect('twilio/list');
        exit ;
    }
    
    $Errors = erLhcoreClassTwilioValidator::validatePhone($item);

    if (count($Errors) == 0) {
        try {
            $item->saveThis();
                       
            erLhcoreClassModule::redirect('twilio/list');
            exit;
            
        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }

    } else {
        $tpl->set('errors',$Errors);
    }       
}

$tpl->setArray(array(
        'item' => $item,
));

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
    array (       
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Edit phone')
    )
);

?>