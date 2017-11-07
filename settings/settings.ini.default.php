<?php 

return array(
       
    'AccountSidSend' => '',
    'AuthTokenSend' => '', 

    // Is it correct recipient phone
    'recipient' => array(''),    
    'AccountSid' => '', // Used for validation from what Sid it come from. Can be the same as AccountSidSend
    
    // From what phone message should be send if we could not detect original recipient
    'originator' => array(''),
    
    'chattimeout' => 3600*72, // How long chat is considered existing before new chat is created
    
    'ahenviroment' => false, // Is it automated hosting enviroment, not support in twilio at the moment

    'phone_department' => array( // You can have different phone numbers to be assigned to different departments
        'phonenumber' => '<dep_id>'
    ),

    'debug' => true, // If debug enabled you can find details in default.log
);

?>