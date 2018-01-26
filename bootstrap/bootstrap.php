<?php

class erLhcoreClassExtensionTwilio
{
    public function __construct()
    {}

    private $settings = array();

    private $ahinstance = null;

    private static $persistentSession;
    
    public function run()
    {
        $this->registerAutoload ();
        
        $this->settings = include ('extension/twilio/settings/settings.ini.php');
        
        require 'extension/twilio/vendor/twilio-php-master/Services/Twilio.php';
        
        if ($this->settings['ahenviroment'] == true) {
            $this->ahinstance = erLhcoreClassInstance::getInstance();
        }
        
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        
        $dispatcher->listen('chat.web_add_msg_admin', array(
            $this,
            'sendSMSUser'
        ));
        
        $dispatcher->listen('chat.desktop_client_admin_msg', array(
            $this,
            'sendSMSUser'
        ));        
    }
    
    public function registerAutoload() {
        spl_autoload_register ( array (
            $this,
            'autoload'
        ), true, false );
    }
    
    public function autoload($className) {
        $classesArray = array (
            'erLhcoreClassModelTwilioChat'  => 'extension/twilio/classes/erlhcoreclassmodeltwiliochat.php',
            'erLhcoreClassModelTwilioPhone'  => 'extension/twilio/classes/erlhcoreclassmodeltwiliophone.php',
            'erLhcoreClassTwilioValidator' => 'extension/twilio/classes/erlhcoreclasstwiliovalidator.php'
        );
        
        if (key_exists ( $className, $classesArray )) {
            include_once $classesArray [$className];
        }
    }
    
    public static function getSession() {
        if (! isset ( self::$persistentSession )) {
            self::$persistentSession = new ezcPersistentSession ( ezcDbInstance::get (), new ezcPersistentCodeManager ( './extension/twilio/pos' ) );
        }
        return self::$persistentSession;
    }
    
    /**
     * Messages files
     * */
    public static function getMessageFiles($matches)
    {
        if (isset($matches[1])){
            list($fileID,$hash) = explode('_',$matches[1]);
            try {
                $file = erLhcoreClassModelChatFile::fetch($fileID);
        
                // Check that user has permission to see the chat. Let say if user purposely types file bbcode
                if ($hash == md5($file->name.'_'.$file->chat_id)) {
                    return ""; // you can change there to any text you want
                }
            } catch (Exception $e) {
        
            }
        
            return '';
        }
    }
    
    /*
     * Then operator sends a message parse message for attatched files etc.
     * */
    public function addMMSAttatchements(& $params)
    {
        $matches = array();

        preg_match_all('/\[file="?(.*?)"?\]/is', $params['Body'],$matches);
                
        $files = array();
        
        if (isset($matches[1])) {
            foreach ($matches[1] as $matchItem) {
                list($fileID,$hash) = explode('_',$matchItem);                        
                try {
                    $file = erLhcoreClassModelChatFile::fetch($fileID);
                    $files[] = $file;
                } catch (Exception $e) {
            
                }
            }
        }
        
        $params['Body'] = preg_replace_callback('#\[file="?(.*?)"?\]#is', 'erLhcoreClassExtensionTwilio::getMessageFiles', $params['Body']);
        
        foreach ($files as $file) {
            $params['MediaUrl'][] = erLhcoreClassXMP::getBaseHost() . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('file/downloadfile')."/{$file->id}/{$file->security_hash}";
        }
        
        if (empty($params['Body'])) {
            $params['Body'] = 'MMS attatched';
        }
    }
    
    /**
     * Sends SMS to user as manual action
     * */
    public function sendManualMessage($params)
    {
        $tPhone = erLhcoreClassModelTwilioPhone::fetch($params['twilio_id']);

        // Prepend Signature if Telegram extension is used
        $signatureText = '';

        $statusSignature = erLhcoreClassChatEventDispatcher::getInstance()->dispatch('telegram.get_signature',array('user_id' => erLhcoreClassUser::instance()->getUserID()));

        if ($statusSignature !== false) {
            $signatureText = $statusSignature['signature'];
        }

        $paramsSend = array(
            'AccountSidSend' => $tPhone->account_sid,
            'AuthTokenSend' => $tPhone->auth_token,
            'originator' => $tPhone->originator,
            'text' => $params['msg'] . $signatureText,
            'recipient' => $params['phone_number']
        );

        $client = new Services_Twilio($paramsSend['AccountSidSend'], $paramsSend['AuthTokenSend']);

        $paramsMMS = array(
            'To' => $paramsSend['recipient'],
            'From' => $paramsSend['originator'],
            'Body' => $paramsSend['text']
        );

        // Attatch MMS if required
        $this->addMMSAttatchements($paramsMMS);
        
        $client->account->messages->create($paramsMMS);

        if ($params['create_chat'] == true) {
            
            $chat = new erLhcoreClassModelChat();
            $chat->phone = $params['phone_number'];
            $chat->dep_id = $params['dep_id'];
            
            if ($chat->dep_id == 0) {
                $departments = erLhcoreClassModelDepartament::getList(array(
                    'limit' => 1,
                    'filter' => array(
                        'disabled' => 0
                    )
                ));
                
                if (! empty($departments)) {
                    $department = array_shift($departments);
                    $chat->dep_id = $department->id;
                    $chat->priority = $department->priority;
                } else {
                    throw new Exception('Could not detect default department');
                }
            }

            $chat->nick = erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sms', 'SMS') . ' ' . $chat->phone;
            $chat->time = time();
            $chat->status = 1;
            $chat->hash = erLhcoreClassChat::generateHash();
            $chat->referrer = '';
            $chat->session_referrer = '';
            $chat->chat_variables = json_encode(array(
                'twilio_sms_chat' => true,
                'twilio_originator' => $paramsSend['originator']
            ));
   
            $chat->saveThis();

            $tChat = new erLhcoreClassModelTwilioChat();
            $tChat->phone = $paramsSend['recipient'];
            $tChat->utime = time();
            $tChat->ctime = time();
            $tChat->chat_id = $chat->id;
            $tChat->saveThis();
            
            /**
             * Store new message
            */
            $msg = new erLhcoreClassModelmsg();
            $msg->msg = trim($paramsSend['text']);
            $msg->chat_id = $chat->id;
            $msg->user_id = $params['operator_id'];
            $msg->name_support = $params['name_support'];
            $msg->time = time();
            
            /**
             * Message
             */
            erLhcoreClassChat::getSession()->save($msg);
            
            /**
             * Set appropriate chat attributes
            */
            $chat->last_msg_id = $msg->id;
            $chat->last_user_msg_time = $msg->time;
            $chat->saveThis();
            
            /**
             * Execute standard callback as chat was started
            */
            erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.chat_started', array(
                'chat' => & $chat
            ));
            
            return $chat;
        }
    }
    
    /**
     * Sends SMS to user
     * 
     * */
    public function sendSMSUser($params)
    {       
        $chatVariables = $params['chat']->chat_variables_array;
        
        // It's SMS chat we need to send a message
        if (isset($chatVariables['twilio_sms_chat']) && $chatVariables['twilio_sms_chat'] == 1) {
            
            try {
                
                $response = erLhcoreClassChatEventDispatcher::getInstance()->dispatch('twilio.send_sms_user', $params);
                
                // Check is module disabled
                if ($response !== false && $response['status'] === erLhcoreClassChatEventDispatcher::STOP_WORKFLOW) {
                    throw new Exception(erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sms', 'Module is disabled for you!'));
                }
                
                if ($params['msg']->msg == '') {
                    throw new Exception(erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sms', 'Please enter a message!'));
                }
                
                if ($this->settings['ahenviroment'] == true && $this->ahinstance->hard_limit_in_effect == true) {
                    throw new Exception(erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sms', 'SMS could not be send because you have reached your SMS hard limit!'));
                }

                $twilioPhone = erLhcoreClassModelTwilioPhone::fetch($chatVariables['twilio_phone_id']);

                // Prepend Signature if Telegram extension is used
                $signatureText = '';

                $statusSignature = erLhcoreClassChatEventDispatcher::getInstance()->dispatch('telegram.get_signature',array('user_id' => erLhcoreClassUser::instance()->getUserID()));

                if ($statusSignature !== false) {
                    $signatureText = $statusSignature['signature'];
                }

                $paramsSend = array(
                    'AccountSidSend' => $twilioPhone->account_sid,
                    'AuthTokenSend' => $twilioPhone->auth_token,
                    'originator' => $twilioPhone->phone,
                    'text' => $params['msg']->msg . $signatureText,
                    'recipient' => $params['chat']->phone
                );

                if (isset($chatVariables['twilio_originator']) && $chatVariables['twilio_originator'] != '') {
                    $paramsSend['originator'] = $chatVariables['twilio_originator'];
                }

                if ($this->settings['ahenviroment'] == true) {
                    
                    if (isset($chatVariables['twilio_originator'])) {
                        $paramsSend['originator'] = $chatVariables['twilio_originator']; // Use same sender as recipient
                    } else {
                        $paramsSend['originator'] = $this->ahinstance->phone_number_first;
                    }
                    
                    $paramsSend['name'] = $this->ahinstance->getPhoneAttribute('AccountSidSend');
                    $paramsSend['password'] = $this->ahinstance->getPhoneAttribute('AuthTokenSend');
                }
                
                $client = new Services_Twilio($paramsSend['AccountSidSend'], $paramsSend['AuthTokenSend']); 
                 
                $paramsMMS = array(  
                	'To' => $paramsSend['recipient'], 
                	'From' => $paramsSend['originator'], 
                    'Body' => $paramsSend['text']
                );
                
                // Attatch MMS if required
                $this->addMMSAttatchements($paramsMMS);

                $client->account->messages->create($paramsMMS);
                                
                if (! isset($chatVariables['twilio_sms_chat_send'])) {
                    $chatVariables['twilio_sms_chat_send'] = 0;
                }
                
                $newMessagesCount = ceil(mb_strlen($params['msg']->msg) / 160);
                $chatVariables['twilio_sms_chat_send'] += $newMessagesCount;
                
                $db = ezcDbInstance::get();
                $db->beginTransaction();
                
                $stmt = $db->prepare('UPDATE lh_chat SET chat_variables = :chat_variables,operation_admin = :operation_admin WHERE id = :id');
                $stmt->bindValue(':id', $params['chat']->id, PDO::PARAM_INT);
                $stmt->bindValue(':chat_variables', json_encode($chatVariables), PDO::PARAM_STR);
                $stmt->bindValue(':operation_admin', "lhinst.updateVoteStatus(" . $params['chat']->id . ")", PDO::PARAM_STR);
                $stmt->execute();
                
                $db->commit();
                
                if ($this->settings['ahenviroment'] == true) {
                    $this->ahinstance->addSMSMessageSend($newMessagesCount);
                }
                                
                // General module signal that it has send an sms
                erLhcoreClassChatEventDispatcher::getInstance()->dispatch('twilio.sms_send_to_user',array('chat' => & $params['chat']));
                
                // If operator has closed a chat we need force back office sync
                erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.nodjshelper_notify_delay', array(
                    'chat' => & $params['chat']
                ));
                
            } catch (Exception $e) {
                
                $msg = new erLhcoreClassModelmsg();
                $msg->msg = $e->getMessage();
                $msg->chat_id = $params['chat']->id;
                $msg->user_id = - 1;
                $msg->time = time();
                erLhcoreClassChat::getSession()->save($msg);
                
                // Update chat attributes
                $db = ezcDbInstance::get();
                $db->beginTransaction();
                
                $stmt = $db->prepare('UPDATE lh_chat SET last_user_msg_time = :last_user_msg_time, last_msg_id = :last_msg_id WHERE id = :id');
                $stmt->bindValue(':id', $params['chat']->id, PDO::PARAM_INT);
                $stmt->bindValue(':last_user_msg_time', $msg->time, PDO::PARAM_STR);
                $stmt->bindValue(':last_msg_id', $msg->id, PDO::PARAM_STR);
                $stmt->execute();
                
                $db->commit();
                
                if ($this->settings['debug'] == true) {
                    erLhcoreClassLog::write(print_r($e, true));
                }
            }
        }
    }
    
    /**
     * Process MMS Attatchement and returns string for message.
     * */
    public function processMMSAttatchements($chat, $params)
    {
        $response = false;
        
        if ($params['NumMedia'] > 0) {            
            for ($i = 0; $i < $params['NumMedia']; $i++) {
                if (isset($params['MediaUrl'.$i]) && !empty($params['MediaUrl'.$i])) {
                    
                    $mediaContent = erLhcoreClassModelChatOnlineUser::executeRequest($params['MediaUrl'.$i]);
                    $mediaContentType = $params['MediaContentType'.$i];
                    
                    if (!empty($mediaContent)) {
                        
                        $fileData = erLhcoreClassModelChatConfig::fetch('file_configuration');
                        $data = (array)$fileData->data;
                        $path = 'var/storage/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$chat->id.'/';

                        erLhcoreClassChatEventDispatcher::getInstance()->dispatch('file.uploadfile.file_path', array('path' => & $path, 'storage_id' => $chat->id));

                        erLhcoreClassFileUpload::mkdirRecursive( $path );
                        
                        $mimeTypes = array(
                            'image/gif' => 'gif',
                            'image/png' => 'png',
                            'image/jpeg' => 'jpg',
                        );

                        $fileUpload = new erLhcoreClassModelChatFile();
                        $fileUpload->size = strlen($mediaContent);
                        $fileUpload->type = $mediaContentType;
                        $fileUpload->name = md5($params['MediaUrl'.$i] . time() . rand(0,100));
                        $fileUpload->date = time();
                        $fileUpload->user_id = 0;
                        $fileUpload->upload_name = 'mms.' . (key_exists($mediaContentType, $mimeTypes) ? $mimeTypes[$mediaContentType] : 'jpg');
                        $fileUpload->file_path = $path;                        
                        $fileUpload->extension = (key_exists($mediaContentType, $mimeTypes) ? $mimeTypes[$mediaContentType] : 'jpg');
                        $fileUpload->chat_id = $chat->id;
                        $fileUpload->saveThis();
                        
                        // Store content
                        file_put_contents($path . $fileUpload->name, $mediaContent);
                        chmod($path . $fileUpload->name, 0644);
                        
                        erLhcoreClassChatEventDispatcher::getInstance()->dispatch('file.uploadfile.file_store', array('chat_file' => $fileUpload));
                        
                        $response = '[file='.$fileUpload->id.'_'.md5($fileUpload->name.'_'.$chat->id).']';
                    }
                }
            }
        }
            
        return $response;    
    }
    
    /*
     *
     * @desc processes sms callback and stores a new chat or appends a message to existing chat
     *
     */
    public function processCallback($params)
    {
        $response = erLhcoreClassChatEventDispatcher::getInstance()->dispatch('twilio.proces_callback', $params);
        
        // Check is module disabled
        if ($response !== false && $response['status'] === erLhcoreClassChatEventDispatcher::STOP_WORKFLOW) {
            throw new Exception('Module disabled for user');
        }
        
        $twilioPhone = erLhcoreClassModelTwilioPhone::findOne(array('filter' => array('phone' => $params['To'])));
        
        if (($this->settings['ahenviroment'] == false && $twilioPhone === false) || ($this->settings['ahenviroment'] == true && ! key_exists($params['To'], $this->ahinstance->phone_number_departments))) {
            throw new Exception('Invalid recipient');
        }
        
        if (($this->settings['ahenviroment'] == false && $twilioPhone->account_sid != $params['AccountSid']) || ($this->settings['ahenviroment'] == true && $this->ahinstance->getPhoneAttribute('AccountSid') != $params['AccountSid'])) {
            throw new Exception('Invalid AccountSid');
        }
        
        $tChat = erLhcoreClassModelTwilioChat::findOne(array(
            'filtergt' => array(
                'utime' => (time() - $twilioPhone->chat_timeout)
            ),
            'filter' => array(
                'phone' => $params['From'],
                'tphone_id' => $twilioPhone->id
            )            
        ));
        
        if ($tChat !== false && ($chat = $tChat->chat) !== false ) {
           
            $msg = new erLhcoreClassModelmsg();
            $msg->msg = trim($params['Body']);
            $msg->chat_id = $chat->id;
            $msg->user_id = 0;
            $msg->time = time();
            
            $responseText = $this->processMMSAttatchements($chat, $params);
            
            if ($responseText !== false) {
                $msg->msg .= "\n".$responseText;
            }

            erLhcoreClassChat::getSession()->save($msg);
                        
            // Update related chat attributes
            $db = ezcDbInstance::get();
            $db->beginTransaction();
            
            $stmt = $db->prepare('UPDATE lh_chat SET last_user_msg_time = :last_user_msg_time, last_msg_id = :last_msg_id, has_unread_messages = 1 WHERE id = :id');
            $stmt->bindValue(':id', $chat->id, PDO::PARAM_INT);
            $stmt->bindValue(':last_user_msg_time', $msg->time, PDO::PARAM_INT);
            
            // Set last message ID
            if ($chat->last_msg_id < $msg->id) {
                $stmt->bindValue(':last_msg_id', $msg->id, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':last_msg_id', $chat->last_msg_id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
            $tChat->utime = time();
            $tChat->saveThis();
            
            $db->commit();
            
            // Standard event on unread chat messages
            if ($chat->has_unread_messages == 1 && $chat->last_user_msg_time < (time() - 5)) {
                erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.unread_chat', array(
                    'chat' => & $chat
                ));
            }
            
            // We dispatch same event as we were using desktop client, because it force admins and users to resync chat for new messages
            // This allows NodeJS users to know about new message. In this particular case it's admin users
            // If operator has opened chat instantly sync
            erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.messages_added_passive', array(
                'chat' => & $chat
            ));
            
            // If operator has closed a chat we need force back office sync
            erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.nodjshelper_notify_delay', array(
                'chat' => & $chat
            ));
            
            // General module signal that it has received an sms
            erLhcoreClassChatEventDispatcher::getInstance()->dispatch('twilio.sms_received',array('chat' => & $chat));
            
        } else {
            $chat = new erLhcoreClassModelChat();
            $chat->phone = $params['From'];
            
            if ($this->settings['ahenviroment'] == true) {
                // Perhaps phone number has assigned department directly
                if ($this->ahinstance->phone_number_departments[$params['To']] > 0) {
                    try {
                        $department = erLhcoreClassModelDepartament::fetch($this->ahinstance->phone_number_departments[$params['To']]);
                        $chat->dep_id = $department->id;
                    } catch (Exception $e) {}
                } else 
                    if ($this->ahinstance->phone_default_department > 0) { // Fallback to default if not defined
                        try {
                            $department = erLhcoreClassModelDepartament::fetch($this->ahinstance->phone_default_department);
                            $chat->dep_id = $department->id;
                        } catch (Exception $e) {}
                    }
            }
            
            if ($chat->dep_id == 0) {

                if ($this->settings['ahenviroment'] == false && $twilioPhone->dep_id > 0) {

                    $depId = $twilioPhone->dep_id;
                    $department = erLhcoreClassModelDepartament::fetch($depId);

                    if ($department instanceof erLhcoreClassModelDepartament) {
                        $chat->dep_id = $department->id;
                        $chat->priority = $department->priority;
                    } else {
                        throw new Exception('Could not find department by phone number - ' . $depId);
                    }

                } else {
                    $departments = erLhcoreClassModelDepartament::getList(array(
                        'limit' => 1,
                        'filter' => array(
                            'disabled' => 0
                        )
                    ));

                    if (! empty($departments)) {
                        $department = array_shift($departments);
                        $chat->dep_id = $department->id;
                        $chat->priority = $department->priority;
                    } else {
                        throw new Exception('Could not detect default department');
                    }
                }
            }
                    
            $chat->nick = erTranslationClassLhTranslation::getInstance()->getTranslation('twilio/sms', 'SMS') . ' ' . $chat->phone . ($params['FromCountry'] != '' ? ' | '.$params['FromCountry'] : '');
            $chat->time = time();
            $chat->status = 0;
            $chat->hash = erLhcoreClassChat::generateHash();
            $chat->referrer = '';
            $chat->session_referrer = '';
            $chat->chat_variables = json_encode(array(
                'twilio_sms_chat' => true,
                'twilio_phone_id' => $twilioPhone->id,
                'twilio_originator' => $params['To']
            ));
            
            if ($params['FromCountry'] != '') {
                 $chat->country_code = strtolower($params['FromCountry']);
            }

            $chat->saveThis();

            /**
             * Store new message
             */
            $msg = new erLhcoreClassModelmsg();
            $msg->msg = trim($params['Body']);
            $msg->chat_id = $chat->id;
            $msg->user_id = 0;
            $msg->time = time();
            
            /**
             * Attatch MMS
             * */
            $responseText = $this->processMMSAttatchements($chat, $params);
            
            if ($responseText !== false) {
                $msg->msg .= "\n".$responseText;
            }
            
            erLhcoreClassChat::getSession()->save($msg);
            
            /**
             * Set appropriate chat attributes
             */
            $chat->last_msg_id = $msg->id;
            $chat->last_user_msg_time = $msg->time;
            $chat->saveThis();

            /**
             * Save twilio chat
             */
            $tChat = new erLhcoreClassModelTwilioChat();
            $tChat->phone = $params['From'];
            $tChat->chat_id = $chat->id;
            $tChat->tphone_id = $twilioPhone->id;
            $tChat->utime = time();
            $tChat->ctime = time();
            $tChat->saveThis();
            
            /**
             * Execute standard callback as chat was started
             */
            erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.chat_started', array(
                'chat' => & $chat
            ));
            
            // General module signal that it has received an sms
            erLhcoreClassChatEventDispatcher::getInstance()->dispatch('twilio.sms_received',array('chat' => & $chat));
        }
    }
}


