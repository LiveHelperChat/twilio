<?php
$chatVariables = $chat->chat_variables_array;
if (isset($chatVariables['twilio_sms_chat'])) : ?>
	<tr>
		<td><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/adminchat','SMS')?></td>
		<td><strong><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/adminchat','YES')?></strong>&nbsp;<?php if (isset($chatVariables['twilio_sms_chat_send'])) : ?>(<?php echo $chatVariables['twilio_sms_chat_send']?> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/adminchat','SMS were send')?>)<?php endif;?>	
		<script>
    	jQuery('#CSChatMessage-<?php echo $chat->id?>').keyup(function() {
        	var length = $(this).val().length;
    		$('#user-is-typing-'+<?php echo $chat->id?>).html(length+' '+<?php echo json_encode(htmlspecialchars_decode(erTranslationClassLhTranslation::getInstance()->getTranslation('chat/adminchat','characters'),ENT_QUOTES))?>+', '+Math.ceil($(this).val().length/160)+' '+<?php echo json_encode(htmlspecialchars_decode(erTranslationClassLhTranslation::getInstance()->getTranslation('chat/adminchat','sms will be send'),ENT_QUOTES))?>).css('visibility','visible');
    	});
    	</script>	
		</td>
	</tr>
<?php endif;?>