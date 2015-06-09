<div class='container'>

	<ul class='breadcrumb'>
		<li><a href='./'>Home</a></li>
		<li><a href='./issues'>Issue Tracker</a></li>
		<li class='active'>Issue #<?php echo $issue->getNumber() ?></li>
	</ul>

	<div class='page-header'>
		<h2><strong><?php echo $issue->getTitle(); ?></strong><br><small>#<?php echo $issue->getNumber(); ?></small></h2>
	</div>

	<h4><small><?php echo "This issue was opened on " . substr($issue->getCreatedAt(), 0, 10); ?></small></h4>
	<h4><small><?php echo "This issue was last edited on " . substr($issue->getUpdatedAt(), 0, 10);?></small></h4>

	<p><?php echo $issue->getBody() ?: "No description exists for this issue"; ?></p>

	<?php if ($issue->getAssignee()): ?>
		<h3>Assigned Developer</h3>
		<p><?php echo $issue->getAssignee()->getLogin(); ?></p>
	<?php endif; ?>

	<h3>Comments</h3>
	<?php if (count($comments)): ?>

		<?php foreach($comments as $comment): ?>
			<p><?php echo $comment->getBody(); ?></p>		
		<?php endforeach; ?>

	<?php else: ?>
		<p>There are no comments for this issue.</p>
	<?php endif; ?>

	<?php /* <?php if($user->role > 2 || User::isDev()): ?>
		<h3>Would you like to close this issue?</h3>
		<h4><small>Click <a href='./close'>here</a> to close this issue</small></h4>
	<?php endif; ?> */ ?>

</div>