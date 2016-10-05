<?php
return array (
  'pk_id' => 
  array (
    'name' => 'pk_id',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => true,
    'autoinc' => true,
  ),
  'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar(256)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'fk_member_id' => 
  array (
    'name' => 'fk_member_id',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'fk_user_name' => 
  array (
    'name' => 'fk_user_name',
    'type' => 'varchar(128)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'created_at' => 
  array (
    'name' => 'created_at',
    'type' => 'datetime',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'tinyint(2)',
    'notnull' => 1,
    'default' => '1',
    'primary' => false,
    'autoinc' => false,
  ),
);

?>