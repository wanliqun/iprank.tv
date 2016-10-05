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
  'description' => 
  array (
    'name' => 'description',
    'type' => 'varchar(512)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'icon_path' => 
  array (
    'name' => 'icon_path',
    'type' => 'varchar(256)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'banner_path' => 
  array (
    'name' => 'banner_path',
    'type' => 'varchar(1024)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'position' => 
  array (
    'name' => 'position',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '100',
    'primary' => false,
    'autoinc' => false,
  ),
);

?>