<?php

import('corex/Validation');
class MemberSignupForm extends Validation{
	function __construct($callback = null){
		parent::__construct($callback);
		
		$this->rules = c('member_rules');
	}
}
