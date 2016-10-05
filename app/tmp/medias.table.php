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
  'fk_parent_id' => 
  array (
    'name' => 'fk_parent_id',
    'type' => 'int(11)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'type' => 
  array (
    'name' => 'type',
    'type' => 'enum(\'Video\',\'Picture\')',
    'notnull' => 1,
    'default' => 'Video',
    'primary' => false,
    'autoinc' => false,
  ),
  'size' => 
  array (
    'name' => 'size',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '-1',
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
  'created_at' => 
  array (
    'name' => 'created_at',
    'type' => 'datetime',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'local_path' => 
  array (
    'name' => 'local_path',
    'type' => 'varchar(2048)',
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
    'type' => 'varchar(2048)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
);

?>