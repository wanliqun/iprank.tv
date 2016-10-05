<?php foreach($posts as $postid=>$post): ?>
<?php if($post['like'] > 0): ?>
$("#<?=$prefix?>-<?=$postid?> .btn-like").addClass('active');
<?php elseif ($post['like'] < 0): ?>
$("#<?=$prefix?>-<?=$postid?> .btn-dislike").addClass('active');
<?php endif; ?>
<?php if($post['favoured']): ?>
$("#<?=$prefix?>-<?=$postid?> .btn-bookmark").addClass('active');
<?php endif; ?>
<?php endforeach; ?>