<?php
$tpl = erLhcoreClassTemplate::getInstance('lhtwilio/index.tpl.php');

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('twilio/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Twilio')
    )
);

?>