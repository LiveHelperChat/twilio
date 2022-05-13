<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Phone');?>*</label>
    <input type="text" maxlength="25" class="form-control" name="phone" value="<?php echo htmlspecialchars($item->phone)?>" />
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Base Phone Number');?></label>
    <input type="text" maxlength="25" class="form-control" placeholder="E.g +1" name="base_phone" value="<?php echo htmlspecialchars($item->base_phone)?>" />
</div>

<?php if (erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionTwilio')->settings['ahenviroment'] == true) : ?>
<label><input type="checkbox" onchange="$('#auth-required').toggle('hide')" <?php if ($item->ah_provided == 1) : ?>checked="checked"<?php endif;?> name="ah_provided" value="on"> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Provided by service provider');?></label>
<?php endif; ?>

<div id="auth-required" style="display: <?php $item->ah_provided == 1 ? print 'none' : print 'block'?>">
    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Account SID');?>*</label>
        <input type="text" maxlength="35" class="form-control" name="account_sid" value="<?php echo htmlspecialchars($item->account_sid)?>" />
    </div>
    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Auth Token');?>*</label>
        <input type="text" maxlength="35" class="form-control" name="auth_token" value="<?php echo htmlspecialchars($item->auth_token)?>" />
    </div>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Chat timeout (seconds)');?>*</label>
    <input type="text" maxlength="35" class="form-control" name="chat_timeout" value="<?php echo htmlspecialchars($item->chat_timeout)?>" />
    <p><i><small>How long chat is considered existing before new chat is created</small></i></p>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Close auto responder timeout (seconds)');?>*</label>
    <input type="text" maxlength="35" class="form-control" name="responder_timeout" value="<?php echo htmlspecialchars($item->responder_timeout)?>" />
    <p><i><small>How long we have to wait before another closed department message is send from last send message.</small></i></p>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Department');?>*</label>
    <?php echo erLhcoreClassRenderHelper::renderCombobox(array(
            'input_name'     => 'dep_id',
    		'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select department'),
            'selected_id'    => $item->dep_id,
            'css_class'      => 'form-control',
            'list_function'  => 'erLhcoreClassModelDepartament::getList',
            'list_function_params'  => array(),
    )); ?>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Originator');?></label>
    <input type="text" maxlength="35" class="form-control" name="originator" value="<?php echo htmlspecialchars($item->originator)?>" />
    <p><i><small>From what phone message should be send if we could not detect original recipient, by default in twilio api from address is recipient address</small></i></p>
</div>
