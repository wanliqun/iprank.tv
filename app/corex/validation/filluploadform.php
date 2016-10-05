<?php

import('corex/Validation');
class FillUploadForm extends Validation{
	function __construct($callback = null){
		parent::__construct($callback);
		
		$this->rules = array(
			'title' => array (
				'label' => 'title',
				'rules' => 'trim|required|maxlength[300]',
				'errors' => array(
					'required'	=> 'Title is blank.',
					'maxlength'	=> 'Title is too long (length must be at most 300).',
				),
			),
			'description' => array (
				'label' => 'description',
				'rules' => 'trim|required|maxlength[2500]',
				'errors' => array(
					'required'	=> 'Description is blank.',
					'maxlength'	=> 'Description is too long (length must be at most 2500).',
				),
			),
			'channel' => array (
				'label' => 'channel',
				'rules' => 'trim|required',
				'errors' => array(
					'required'	=> 'Channel is blank.',
				),
			),
			'tags' => array (
				'label' => 'tags',
				'rules' => 'trim|required',
				'errors' => array(
					'required'	=> 'Tags are blank.',
				),
			),
		);
	}
}
