<?php if ($currentUser->hasAccessTo('lhtwilio','use')) : ?>
<li class="li-icon nav-item"><a class="nav-link" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Send SMS');?>" href="<?php echo erLhcoreClassDesign::baseurl('twilio/sendmessage')?>"><i class="material-icons">textsms</i></a></li>
<?php endif; ?>