<?php

$Module = array( "name" => "iText SMS",
				 'variable_params' => true );

$ViewList = array();

$ViewList['callbacks'] = array(
    'params' => array(),
    'uparams' => array()
);

$ViewList['sendmessage'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use')
);

$FunctionList['use'] = array('explain' => 'Allow operator to send SMS directly');