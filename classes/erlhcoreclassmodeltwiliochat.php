<?php
#[\AllowDynamicProperties]
class erLhcoreClassModelTwilioChat
{
	use erLhcoreClassDBTrait;

	public static $dbTable = 'lhc_twilio_chat';

	public static $dbTableId = 'id';

	public static $dbSessionHandler = 'erLhcoreClassExtensionTwilio::getSession';

	public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'phone' => $this->phone,
            'chat_id' => $this->chat_id,
            'ctime' => $this->ctime,
            'utime' => $this->utime,
            'tphone_id' => $this->tphone_id
        );
    }

    public function __toString()
    {
        return $this->phone;
    }

    public function __get($var)
    {
        switch ($var) {
            
            case 'chat':
                $this->chat = erLhcoreClassModelChat::fetch($this->chat_id);
                return $this->chat;
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
        $q->deleteFrom('lhc_twilio_chat')->where($q->expr->eq('phone', $this->phone));
        $stmt = $q->prepare();
        $stmt->execute();
    }
    
    public $id = null;
    
    public $phone = null;
    
    public $chat_id = null;
    
    public $tphone_id = null;

    public $ctime = null;
    
    public $utime = null;
    
}

?>