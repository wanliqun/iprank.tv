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
  'btitle' => 
  array (
    'name' => 'btitle',
    'type' => 'varchar(512)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'bdescription' => 
  array (
    'name' => 'bdescription',
    'type' => 'varchar(2048)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'bmore' => 
  array (
    'name' => 'bmore',
    'type' => 'tinyint(1)',
    'notnull' => 1,
    'default' => '0',
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
  'fk_channel_id' => 
  array (
    'name' => 'fk_channel_id',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'fk_channel_name' => 
  array (
    'name' => 'fk_channel_name',
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
  'cover_url' => 
  array (
    'name' => 'cover_url',
    'type' => 'varchar(1024)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'media_ids' => 
  array (
    'name' => 'media_ids',
    'type' => 'varchar(1024)',
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
  'is_nsfw' => 
  array (
    'name' => 'is_nsfw',
    'type' => 'tinyint(1)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'is_original' => 
  array (
    'name' => 'is_original',
    'type' => 'tinyint(1)',
    'notnull' => 1,
    'default' => '1',
    'primary' => false,
    'autoinc' => false,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'tinyint(8)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
);

?>