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
  'created_at' => 
  array (
    'name' => 'created_at',
    'type' => 'datetime',
    'notnull' => 1,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'default_liked' => 
  array (
    'name' => 'default_liked',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'newly_liked' => 
  array (
    'name' => 'newly_liked',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'default_disliked' => 
  array (
    'name' => 'default_disliked',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'newly_disliked' => 
  array (
    'name' => 'newly_disliked',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'default_favorited' => 
  array (
    'name' => 'default_favorited',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'newly_favorited' => 
  array (
    'name' => 'newly_favorited',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'default_commented' => 
  array (
    'name' => 'default_commented',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'newly_commented' => 
  array (
    'name' => 'newly_commented',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'default_viewed' => 
  array (
    'name' => 'default_viewed',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'newly_viewed' => 
  array (
    'name' => 'newly_viewed',
    'type' => 'int(11)',
    'notnull' => 1,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
);

?>