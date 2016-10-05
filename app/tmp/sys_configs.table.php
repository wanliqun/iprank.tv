<?php
return array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => true,
    'autoinc' => true,
  ),
  'parent_id' => 
  array (
    'name' => 'parent_id',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'code' => 
  array (
    'name' => 'code',
    'type' => 'varchar(25)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar(255)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'description' => 
  array (
    'name' => 'description',
    'type' => 'varchar(255)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'input_type' => 
  array (
    'name' => 'input_type',
    'type' => 'varchar(32)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'input_values' => 
  array (
    'name' => 'input_values',
    'type' => 'text',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'conf_value' => 
  array (
    'name' => 'conf_value',
    'type' => 'text',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'rules' => 
  array (
    'name' => 'rules',
    'type' => 'varchar(255)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'sorts' => 
  array (
    'name' => 'sorts',
    'type' => 'smallint(6)',
    'notnull' => 1,
    'default' => '10000',
    'primary' => false,
    'autoinc' => false,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'tinyint(4)',
    'notnull' => 1,
    'default' => '1',
    'primary' => false,
    'autoinc' => false,
  ),
);

?>