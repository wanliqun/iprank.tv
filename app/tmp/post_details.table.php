<?php
return array (
  'fk_post_id' => 
  array (
    'name' => 'fk_post_id',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => true,
    'autoinc' => false,
  ),
  'title' => 
  array (
    'name' => 'title',
    'type' => 'varchar(1024)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'description' => 
  array (
    'name' => 'description',
    'type' => 'varchar(12288)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'src_type' => 
  array (
    'name' => 'src_type',
    'type' => 'enum(\'YouTube\')',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'src_from' => 
  array (
    'name' => 'src_from',
    'type' => 'varchar(1024)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'updated_on' => 
  array (
    'name' => 'updated_on',
    'type' => 'datetime',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
);

?>