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
  'cover_url' => 
  array (
    'name' => 'cover_url',
    'type' => 'varchar(1024)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'position' => 
  array (
    'name' => 'position',
    'type' => 'smallint(5) unsigned',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'featured_at' => 
  array (
    'name' => 'featured_at',
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