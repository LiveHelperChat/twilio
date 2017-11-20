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

$ViewList['list'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['new'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['edit'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_admin'),
);

$ViewList['index'] = array(
    'params' => array(),
    'functions' => array('use_admin'),
);

$FunctionList['use'] = array('explain' => 'Allow operator to send SMS directly');
$FunctionList['use_admin'] = array('explain' => 'Allow operator to add phone number to department');
