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
  'fk_member_id' => 
  array (
    'name' => 'fk_member_id',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => NULL,
    'primary' => true,
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
  'favorited_at' => 
  array (
    'name' => 'favorited_at',
    'type' => 'datetime',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'favorited_value' => 
  array (
    'name' => 'favorited_value',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
);

?>