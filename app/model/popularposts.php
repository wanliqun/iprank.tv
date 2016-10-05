<?php

class PopularPosts {
	private $mPosts;
	private $mPostStatistics;

	function __construct(){
		$this->mPosts = m("Posts");
		$this->mPostStatistics  = m("PostStatistics");
	}
	
	function getMostPopularPosts($filter='always', $num=20) {
		return $this->getPopularPosts('overall', $filter, $num);
	}
	
	function getPopularPosts($acc='overall', $filter='always', $num=60) {
		$weightViews = c('weight_views');
		$weightLikes = c('weight_likes');
		$weightComments = c('weight_comments');
		
		$filterStart=null; $filterEnd=null;
		$timeUnit = 24 * 60 * 60.0; // day
		switch($filter) {
			case 'today': {
				$filterStart = date("Y-m-d H:i:s", strtotime('today midnight'));
				$filterEnd = date("Y-m-d H:i:s", strtotime('tomorrow midnight'));
				$timeUnit = 60 * 60.0; // hours
				break;
			}
			case 'this-week': {
				$filterStart = date("Y-m-d H:i:s", strtotime('last sunday + 1 day midnight'));
				$filterEnd = date("Y-m-d H:i:s", strtotime('this sunday + 1 day midnight'));
				$timeUnit = 12 * 60 * 60.0; // half day
				break;
			}
			case 'this-month': {
				$filterStart = date("Y-m-d H:i:s", strtotime('first day of this month midnight')); 
				$filterEnd = date("Y-m-d H:i:s", strtotime('first day of next month midnight')); 
				break;
			}
			case 'always':
			default:
				$filterStart = '1970-01-01 00:00:00'; 
				$filterEnd = '9999-01-01 00:00:00';
				break;
		}
		$timeFactor = "GREATEST(TIMESTAMPDIFF(SECOND, ps.`created_at`, NOW()) / {$timeUnit}, 1.0)";
		
		$fieldNumViewd = '(ps.`default_viewed` + ps.`newly_viewed`)';
		$fieldNumLiked = '(ps.`default_liked` + ps.`newly_liked`)';
		$fieldNumCommented = '(ps.`default_commented` + ps.`newly_commented`)';
		$statFields = ''; $weight = '';
		switch($acc) {
			case 'most-viewed':
				$statFields = "{$fieldNumViewd} as `num_viewed`";
				$weight = "{$fieldNumViewd}";
				break;
			case 'most-liked';
				$statFields = "{$fieldNumLiked} as `num_liked`";
				$weight = "{$fieldNumLiked}";
				break;
			case 'most-commented':
				$statFields = "{$fieldNumCommented} as `num_commented`";
				$weight = "{$fieldNumCommented}";
				break;
			case 'overall':
			default:
				$statFields = "{$fieldNumViewd} as `num_viewed`, {$fieldNumLiked} as `num_liked`, "
					. "{$fieldNumCommented} as `num_commented`";
				$weight = "({$fieldNumViewd} * {$weightViews} + {$fieldNumLiked} * {$weightLikes} + ".
					"{$fieldNumCommented} * {$weightComments}) / {$timeFactor}";
				break;
		}
		
		$sql = <<<EOD
SELECT  ps.`created_at`, {$statFields}, {$weight} as `weight`, ps.`newly_commented`,
		p.`pk_id`, p.`btitle`, p.`type`, p.`cover_url`
	FROM `post_statistics` as ps, `posts` as p
	WHERE ps.`fk_post_id` = p.`pk_id` AND p.`status` = 1
		AND ps.`created_at` >= '{$filterStart}' AND ps.`created_at` < '{$filterEnd}' 
	ORDER BY {$weight} DESC, ps.`created_at` DESC LIMIT 0, {$num};		
EOD;
		if(Db::getInstance()->query($sql)) {
			$posts = Db::getInstance()->getAll();
			foreach($posts as &$post) {
				$post['statistics'] = array(
					'num_viewed'=>$post['num_viewed'], 
					'num_commented'=>$post['newly_commented'], 
					'num_liked'=>$post['num_liked'],
				);
				unset($post);
			}
			return $posts;
		}
	}
}