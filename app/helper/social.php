<?php

function disqus_shortname() { 
	return 'Prank';
}

function disqus_identifier($post) {
	$title = slugify($post['btitle']);
	return "{$post['pk_id']}-{$title}";
}

function disqus_title($post) {
	return $post['btitle'];
}

function disqus_url($post) {
	return _pv($post['pk_id'], $post['btitle'], $post['type']);
}
