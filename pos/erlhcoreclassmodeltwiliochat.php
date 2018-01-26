<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_twilio_chat";
$def->class = "erLhcoreClassModelTwilioChat";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['phone'] = new ezcPersistentObjectProperty();
$def->properties['phone']->columnName   = 'phone';
$def->properties['phone']->propertyName = 'phone';
$def->properties['phone']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['chat_id'] = new ezcPersistentObjectProperty();
$def->properties['chat_id']->columnName   = 'chat_id';
$def->properties['chat_id']->propertyName = 'chat_id';
$def->properties['chat_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['tphone_id'] = new ezcPersistentObjectProperty();
$def->properties['tphone_id']->columnName   = 'tphone_id';
$def->properties['tphone_id']->propertyName = 'tphone_id';
$def->properties['tphone_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['ctime'] = new ezcPersistentObjectProperty();
$def->properties['ctime']->columnName   = 'ctime';
$def->properties['ctime']->propertyName = 'ctime';
$def->properties['ctime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['utime'] = new ezcPersistentObjectProperty();
$def->properties['utime']->columnName   = 'utime';
$def->properties['utime']->propertyName = 'utime';
$def->properties['utime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;

?>