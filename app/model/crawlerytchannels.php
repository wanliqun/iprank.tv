<?php

class CrawlerYtChannels extends AppModel{
	public $tableName = 'crawler_ytchannels';
	
	function getPaginatedChannels($condition, $pageSize=15, &$pager=null) {
		c('pagesize', $pageSize);
		$modelPager = new Corex_ModelPager($this, $condition);
		$modelPager->exec();
		$channels = $modelPager->getRs();
		$pager = $modelPager->getPager();
		
		return $channels;
	}
	
}