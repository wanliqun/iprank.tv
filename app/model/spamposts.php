<?php

class SpamPosts extends AppModel{
	public $tableName = 'spam_posts';

	function __construct($tableName = null){
		parent::__construct($tableName);
	}
}