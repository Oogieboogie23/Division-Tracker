<?php if ($memberInfo->status_id == 4) : ?><!-- member is removed -->
	<div class='alert alert-danger'><i class='fa fa-times-circle fa-lg'></i> This remember is currently removed from the division and will not appear on the division structure until he is re-recruited and his member status is approved on the forums.</div>

<?php elseif ($memberInfo->status_id == 999) : ?><!-- member is pending approval -->
	<div class='alert alert-warning'><i class='fa fa-exclamation-triangle fa-lg'></i> This member is pending, and will not have any forum specific information until their member status has been approved.</div>
<?php endif; ?>

<?php if (Member::isOnLeave($memberInfo->member_id)) : ?><!-- member is on leave (existing LOA) -->
	<div class='alert alert-warning'><i class='fa fa-clock-o fa-lg'></i>  This player currently has a leave of absence in place.</div>
<?php endif; ?>

<?php if (Member::isFlaggedForInactivity($memberInfo->member_id)) : ?><!-- member is flagged to be removed -->
	<div class='alert alert-warning'><i class='fa fa-flag fa-lg'></i>  This player has been flagged for removal due to inactivity and will be removed from the division during the next cleanout period.</div>
<?php endif; ?>
