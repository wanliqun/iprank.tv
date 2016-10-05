<?php
/**
 * 测试控制器
 * test.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage controller
 * @version 2014-05-08
 */
class Test extends AppController{
	function index(){
		exit();
	}
	
	/**
	 * svn_username\svn_password\svn_url
	 */
	function svn(){
		$username = $this->app->getRequest('username',c('svn_username'));
		$password = $this->app->getRequest('password',c('svn_password'));
		$action = $this->app->getRequest('action');
		echo "useage action=up|co<br>";
		if($username&&$password){
			switch($action){
				case 'up':
					echo '<h2>svn update ...</h2>';
					$hl=popen("svn up --username $username --password $password ","r");
					$read=stream_get_contents($hl);
					echo "<pre>";
		    		printf($read);
		    		echo "</pre>";
		    		pclose($hl);
					break;
				case 'co':
					$url = c('svn_url');
					if($url){
						echo '<h2>svn checkout ...</h2>';
						$command="svn co $url --username $username --password $password ".DOCUROOT;
		    			exec($command);
		    			echo 'checkout Success!';
					}
					break;
			}
		}
		exit();
	}
	
	/**
	 * 导入地区数据
	 */
	function importregions(){
		$groupId = 16;
		$rs = Itbeing::loadModel('metadata_groups')->findBycode('regions',1);
		if($rs) $groupId = $rs['id'];
		$this->_importRegions(1,0,$groupId);
	}
	
	/**
	 * 导入地区数据
	 */
	function _importRegions($pid=0,$opid=0,$groupId=16){
		$items = Itbeing::loadModel('regions')->findByparent_id($pid);
		
		if(!empty($items)){
			$pids = array();
			if($opid>0){
				$rs = Itbeing::loadModel('metadatas')->findById($opid);
				if($rs && !empty($rs['parent_ids'])){
					$pids = explode(',',$rs['parent_ids']);
				}
				$pids[] = $opid;
			}
			foreach($items as $item){
				$row = array(
					'parent_id' => $opid,
					'parent_ids' => implode(',',$pids),
					'group_id' => $groupId,
					'name' => $item['region_name'],
					'level' => $item['region_level'],
					'sorts' => $item['id'],
				);
				$id = Itbeing::loadModel('metadatas')->add($row);
				echo Itbeing::loadModel('metadatas')->sql->sql.'<br>';
				$this->_importRegions($item['id'],$id,$groupId);
			}
		}
	} 
}
