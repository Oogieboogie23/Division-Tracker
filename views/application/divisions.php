<div class='panel panel-default trans-striped-bg'>
    <div class='panel-heading'><i class='fa fa-gamepad fa-lg pull-right text-muted'></i> <strong>Gaming
            Divisions</strong> (<?php echo count($divisions); ?>)</div>
    <div class="panel-body">
        <div class='list-group col-md-6'>
            <?php $i = 1; ?>
            <?php foreach ($divisions as $division) : ?>
            <a href='divisions/<?php echo $division->short_name ?>' class='list-group-item'
               style='padding-bottom: 18px;'>
            <span class='pull-left' style='margin-right: 20px; vertical-align: middle;'>
                <img class="img-responsive"
                     src='assets/images/game_icons/48x48/<?php echo $division->short_name ?>.png'/>
            </span>
                <h4 class='list-group-item-heading hidden-xs hidden-sm '>
                    <strong><?php echo $division->full_name ?></strong>
                </h4><h4 class='list-group-item-heading hidden-md hidden-lg' style="margin:10px 0;">
                    <strong><?php echo strtoupper($division->short_name); ?> Division</strong></h4>
                <p class='list-group-item-text text-muted hidden-xs hidden-sm'><?php echo $division->short_descr ?></p>
                <h5 class="pull-right text-muted big-num-main count-animated"><?php echo Division::totalCount($division->id); ?></h5>
            </a>
            <?php if ($i == ceil(count($divisions) / 2)): ?>
        </div>
        <div class='list-group col-md-6'>
            <?php endif; ?>
            <?php $i++; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
