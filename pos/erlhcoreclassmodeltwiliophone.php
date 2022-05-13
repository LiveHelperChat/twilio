<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_twilio_phone";
$def->class = "erLhcoreClassModelTwilioPhone";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['phone'] = new ezcPersistentObjectProperty();
$def->properties['phone']->columnName   = 'phone';
$def->properties['phone']->propertyName = 'phone';
$def->properties['phone']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['base_phone'] = new ezcPersistentObjectProperty();
$def->properties['base_phone']->columnName   = 'base_phone';
$def->properties['base_phone']->propertyName = 'base_phone';
$def->properties['base_phone']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['account_sid'] = new ezcPersistentObjectProperty();
$def->properties['account_sid']->columnName   = 'account_sid';
$def->properties['account_sid']->propertyName = 'account_sid';
$def->properties['account_sid']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['auth_token'] = new ezcPersistentObjectProperty();
$def->properties['auth_token']->columnName   = 'auth_token';
$def->properties['auth_token']->propertyName = 'auth_token';
$def->properties['auth_token']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['dep_id'] = new ezcPersistentObjectProperty();
$def->properties['dep_id']->columnName   = 'dep_id';
$def->properties['dep_id']->propertyName = 'dep_id';
$def->properties['dep_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['chat_timeout'] = new ezcPersistentObjectProperty();
$def->properties['chat_timeout']->columnName   = 'chat_timeout';
$def->properties['chat_timeout']->propertyName = 'chat_timeout';
$def->properties['chat_timeout']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['responder_timeout'] = new ezcPersistentObjectProperty();
$def->properties['responder_timeout']->columnName   = 'responder_timeout';
$def->properties['responder_timeout']->propertyName = 'responder_timeout';
$def->properties['responder_timeout']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['originator'] = new ezcPersistentObjectProperty();
$def->properties['originator']->columnName   = 'originator';
$def->properties['originator']->propertyName = 'originator';
$def->properties['originator']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['ah_provided'] = new ezcPersistentObjectProperty();
$def->properties['ah_provided']->columnName   = 'ah_provided';
$def->properties['ah_provided']->propertyName = 'ah_provided';
$def->properties['ah_provided']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;

?>