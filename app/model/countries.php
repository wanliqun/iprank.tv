<?php

class Countries extends AppModel{
	public $tableName = 'countries';
	
	function getCountries($condition=null) {
		return $this->find($condition);
	}
	
	function getCountriesKeyByIso2($condition=null) {
		$countries = $this->getCountries($condition);
		$iso2KeyedCountries = array();
		foreach($countries as $country) {
			$iso2KeyedCountries[$country['iso2']] = $country;
		}
		
		return $iso2KeyedCountries;
	}
}