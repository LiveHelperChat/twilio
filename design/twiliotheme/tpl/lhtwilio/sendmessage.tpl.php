<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('sugarcrm/module', 'Send message'); ?></h1>

<?php if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php if (isset($updated)) : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/module','Message was send!'); ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<?php if (isset($chat)) : ?>
    <a title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('sugarcrm/module', 'Open in a new window'); ?>" ng-click="lhc.startChatNewWindow(<?php echo $chat->id?>,'<?php echo erLhcoreClassDesign::shrt($chat->nick,10,'...',30,ENT_QUOTES);?>')"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/module','Open chat')?></a>
<?php endif; ?>

<form action="" method="post" ng-non-bindable>

    <div class="form-group">
    	<label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/module','Phone number')?></label> 
    	<input type="text" class="form-control" name="TwilioPhoneNumber" value="<?php echo htmlspecialchars($input->phone_number);?>" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('sugarcrm/module','Phone number')?>" />
    </div>

    <div class="form-group">
    	<label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/module','Message')?></label> 
    	<textarea class="form-control" name="TwilioMessage" rows="" cols=""><?php echo htmlspecialchars($input->message);?></textarea>
    </div>
    
    <div class="form-group">
	   <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Department');?></label>
	   <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
                'input_name'     => 'TwilioDepartment',
				'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Choose department'),
                'selected_id'    => $input->dep_id,	
	            'css_class'      => 'form-control',			
                'list_function'  => 'erLhcoreClassModelDepartament::getList'
       )); ?> 
    </div> 
    
    <div class="form-group">
	   <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Twilio');?></label>
	   <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
                'input_name'     => 'TwilioId',
				'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Choose phone'),
                'selected_id'    => $input->twilio_id,	
	            'css_class'      => 'form-control',	
	            'display_name'       => 'phone',
                'list_function'  => 'erLhcoreClassModelTwilioPhone::getList'
       )); ?> 
    </div>
    
    <div class="form-group">
		<label><input type="checkbox" name="TwilioCreateChat" value="on" <?php ($input->create_chat == true) ? print 'checked="checked"' : print '';?>>&nbsp;<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('sugarcrm/module','Create chat')?></label>
	</div>
    
    <input type="submit" name="Update" class="btn btn-primary" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/module','Send')?>">
    
</form>