<?php
#[\AllowDynamicProperties]
class erLhcoreClassModelTwilioPhone
{
	use erLhcoreClassDBTrait;

	public static $dbTable = 'lhc_twilio_phone';

	public static $dbTableId = 'id';

	public static $dbSessionHandler = 'erLhcoreClassExtensionTwilio::getSession';

	public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'phone' => $this->phone,
            'base_phone' => $this->base_phone,
            'account_sid' => $this->account_sid,
            'auth_token' => $this->auth_token,
            'dep_id' => $this->dep_id,
            'chat_timeout' => $this->chat_timeout,
            'originator' => $this->originator,
            'responder_timeout' => $this->responder_timeout,
            'ah_provided' => $this->ah_provided
        );
    }

    public function __toString()
    {
    	return $this->phone;
    }

    public function __get($var)
    {
        switch ($var) {
                
            case 'callback_url':
                $this->callback_url = erLhcoreClassXMP::getBaseHost() . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurldirect('twilio/callbacks');
                return $this->callback_url;
                break;
                
            default:
                ;
                break;
        }
    }

    /**
     * Delete page chat's
     */
    public function beforeRemove()
    {
        $q = ezcDbInstance::get()->createDeleteQuery();
        $q->deleteFrom('lhc_twilio_chat')->where($q->expr->eq('phone', ezcDbInstance::get()->quote($this->phone)));
        $stmt = $q->prepare();
        $stmt->execute();
    }
    
    public $id = null;

    public $phone = null;
    
    public $account_sid = null;

    public $auth_token = null;
    
    public $dep_id = null;

    public $base_phone = '';

    public $chat_timeout = 3600*72;

    public $responder_timeout = 12*3600;

    public $originator = '';

    public $ah_provided = 0;
}

?>