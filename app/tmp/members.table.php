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
  'source' => 
  array (
    'name' => 'source',
    'type' => 'enum(\'iPrank\',\'Facebook\')',
    'notnull' => 1,
    'default' => 'iPrank',
    'primary' => false,
    'autoinc' => false,
  ),
  'username' => 
  array (
    'name' => 'username',
    'type' => 'varchar(128)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'avatar_url' => 
  array (
    'name' => 'avatar_url',
    'type' => 'varchar(512)',
    'notnull' => 0,
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
  'email' => 
  array (
    'name' => 'email',
    'type' => 'varchar(128)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'password' => 
  array (
    'name' => 'password',
    'type' => 'varchar(128)',
    'notnull' => 1,
    'default' => '\'\'',
    'primary' => false,
    'autoinc' => false,
  ),
  'gender' => 
  array (
    'name' => 'gender',
    'type' => 'enum(\'Male\',\'Female\')',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'fk_country_iso2' => 
  array (
    'name' => 'fk_country_iso2',
    'type' => 'varchar(32)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'birthday' => 
  array (
    'name' => 'birthday',
    'type' => 'date',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'is_subscribed' => 
  array (
    'name' => 'is_subscribed',
    'type' => 'tinyint(1)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'verification_code' => 
  array (
    'name' => 'verification_code',
    'type' => 'varchar(128)',
    'notnull' => 0,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'is_verified' => 
  array (
    'name' => 'is_verified',
    'type' => 'tinyint(1)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'member_since' => 
  array (
    'name' => 'member_since',
    'type' => 'datetime',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'from_ip' => 
  array (
    'name' => 'from_ip',
    'type' => 'varchar(128)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'is_fbconnected' => 
  array (
    'name' => 'is_fbconnected',
    'type' => 'tinyint(1)',
    'notnull' => 1,
    'default' => '0',
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