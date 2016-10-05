<?php
return array (
  'pk_id' => 
  array (
    'name' => 'pk_id',
    'type' => 'int(5)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => true,
    'autoinc' => true,
  ),
  'iso2' => 
  array (
    'name' => 'iso2',
    'type' => 'char(2)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'short_name' => 
  array (
    'name' => 'short_name',
    'type' => 'varchar(80)',
    'notnull' => 1,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'long_name' => 
  array (
    'name' => 'long_name',
    'type' => 'varchar(80)',
    'notnull' => 1,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'iso3' => 
  array (
    'name' => 'iso3',
    'type' => 'char(3)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'numcode' => 
  array (
    'name' => 'numcode',
    'type' => 'varchar(6)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'un_member' => 
  array (
    'name' => 'un_member',
    'type' => 'varchar(12)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'calling_code' => 
  array (
    'name' => 'calling_code',
    'type' => 'varchar(8)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'cctld' => 
  array (
    'name' => 'cctld',
    'type' => 'varchar(5)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
);

?>