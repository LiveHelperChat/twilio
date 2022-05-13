<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/osticket','Twilio notification settings')?></h1>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php if (isset($updated) && $updated == true) : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Settings updated'); ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<form action="" method="post" ng-non-bindable>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/osticket','Notification Message');?></label>
        <textarea class="form-control" rows="10" name="message"><?php if (isset($data['message']) && !empty($data['message'])) : ?><?php echo htmlspecialchars($data['message'])?><?php else : ?><?php echo "Chat ID - {id}\nUser nick - {nick},\nUser e-mail - {email},\nUser time zone - {user_tz_identifier}\nChat was created at - {time_created_front}\n\n//---------------//\nURL to view a chat (url provided if chat exists)\n{url}\n\n//---------------//\nReferer\n{referrer}\n\n//---------------//\nChat log\n{messages}\n\n//---------------//\nOperator remarks\n{remarks}\n\n//----------------//\nUser geo data:\nCountry code - {country_code} {country_name} {city}\n\n//----------------//\nAdditional chat data\n{additional_data}";?><?php endif;?></textarea>
        <p><small>Supported variables - {chat_duration}, {waited}, {created}, {user_left}, {chat_id}, {phone}, {name}, {email}, {url_request}, {ip}, {department}, {country}, {city}</small></p>
    </div>

    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" name="Update" class="btn btn-primary" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/osticket','Save');?>">
    </div>

</form>