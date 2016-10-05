<?php
/**
 * 命令行控制器
 * cmd.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage controller
 * @version 2014-05-08
 */
class Cmd extends CmdController{
	function index(){
		return $this->msg('usage php init.php -act action ......');
	}
	
	/**
	 * Remap the channel which post should belong to by keyword in title.
	 * This method is good to run after the first crawl from YouTube.
	 */
	function remap() {
		$remapTable = array(
			'channels'=>array(
				1=>'Pickup Line Pranks', 
				2=>'Sexy Pranks', 
				3=>'Funny Pranks',
				4=>'Scary Pranks', 
				5=>'Office/School Pranks', 
				6=>'Police/Criminal Pranks',
				7=>'Animal Pranks', 
				8=>'Prank Call', 
				9=>'Social Experiment',
				10=>'How to Prank', 
				11=>'Awkward Pranks',
			),
			'matchPatterns'=>array(
				'/(pickup|pick up|get girl|gets girl )/i'=>1,
				'/(sex|dick|boob|nude|yoga pant|pussy)/i'=>2,
				'/(scare|scary|zombie|devil|evil)/i'=>4,
				'/(office|school|colleague|classmate)/i'=>5,
				'/(police|cop|gang|gangster|mafia|murder|prisoner|criminal)/i'=>6,
				'/(animal|cat|dog)/i'=>7,
				'/(prank call|call prank)/i'=>8,
				'/(social experiment|experiment)/i'=>9,
				'/(how to prank|pranks to play|prank to play)/i'=>10,
				'/(awkward)/i'=>11,
			)
		);
		
		echo $this->msg('remaping post to new channel......');
		$cond = array (
			'where'=>array('fk_channel_id'=>3), 
			'order'=>'pk_id ASC',
		); 
		$mPosts = m('Posts'); $pager = null; $pageSize = 50; $curPage = 1;
		$channelTable = $remapTable['channels']; $matchPtnTable = $remapTable['matchPatterns'];
		$updateBacklogs = array();
		do {
			$_REQUEST['page'] = $curPage;
			$posts = $mPosts->getPaginatedPosts($cond, $pageSize, $pager, false);
			foreach($posts as $post) {
				$title = $post['btitle']; $postId = $post['pk_id'];
				$matchChannel = m('LibChannel')->guessChannel(array('title'=>$title));
				$chid = $matchChannel['chid']; $chname = $matchChannel['chname'];
				if ($chid != 3) {
					echo $this->msg('***' . $title. '*** pattern matched ===' . $chname . '===');
					$data = array('fk_channel_id'=>$chid, 'fk_channel_name'=>$chname,);
					$updateCond = array('where'=>array('pk_id'=>$postId),);
					$updateBacklogs[] = array($updateCond, $data);
				}
			}
			echo $this->msg($curPage);
			$curPage = $pager['nextPage'];
		} while($curPage > 0);
		
		foreach($updateBacklogs as $backlog) {
			$cond = $backlog[0];
			$data = $backlog[1];
			$mPosts->update($data, $cond);
		}
		return $this->msg('done......');
	}
}