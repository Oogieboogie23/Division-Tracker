<div class='container margin-top-20'>

    <ul class='breadcrumb'>
        <li><a href='./'>Home</a></li>
        <li class='active'>User Activity</li>
    </ul>


    <div class='page-header'>
        <h2><strong>Recent
                <small>Activity</small>
            </strong></h2>
    </div>


    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Recent Activity</strong><span
                        class="pull-right"><?php echo $division->full_name ?> Division</span></div>
                <ul class="activity-list">
                    <?php $actions = UserAction::findByDivision($division->id, 30); ?>
                    <?php foreach ($actions as $action) : ?>
                        <?php if (!is_null($action->target_id)): ?>
                            <li>
                                <i class="<?php echo $action->icon; ?> fa-2x"></i>
                                <div>
                                    <?php echo UserAction::humanize($action->type_id, $action->target_id,
                                        $action->user_id,
                                        $action->verbage); ?>
                                    <span><?php echo formatTime(strtotime($action->date), 1); ?></span>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">Filter by division</div>
                <div class="panel-body">

                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            Filter By Division <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php $divisions = Division::find_all(); ?>
                            <?php foreach ($divisions as $divisionItem): ?>
                                <li>
                                    <a href="activity/<?php echo $divisionItem->short_name ?>"><?php echo $divisionItem->full_name ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>