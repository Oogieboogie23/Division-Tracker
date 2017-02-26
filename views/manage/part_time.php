<div class='container'>

    <input type="hidden" id="member_id" value="<?php echo $member->member_id ?>"></input>

    <ul class='breadcrumb'>
        <li><a href='./'>Home</a></li>
        <li class='active'>Manage part time players</li>
    </ul>


    <div class='page-header'>
        <h2><strong>Manage
                <small>Part Time Members</small>
            </strong></h2>
    </div>

    <div class="row">

        <div class="col-md-3 col-md-push-9">
            <div class="panel panel-primary">
                <div class="panel-heading">Add member</div>
                <div class="panel-body">
                    <form action="do/add-parttime" method="POST">
                        <div class="form-group">
                            <label for="member_id" class="control-label">Member ID</label>
                            <input type="number" class="form-control" name="member_id"/>
                        </div>

                        <div class="form-group">
                            <label for="name" class="control-label">Forum Name</label>
                            <input type="text" class="form-control" name="name"/>
                        </div>

                        <div class="form-group">
                            <label for="ingame_alias" class="control-label">Ingame Alias</label>
                            <input type="text" class="form-control" name="ingame_alias"/>
                        </div>

                        <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
                    </form>
                </div><!-- body -->
            </div><!-- panel -->
        </div><!-- col-md-4 -->

        <div class="col-md-9 col-md-pull-3">
            <div class="panel panel-primary">
                <div class="panel-heading">Currently Assigned</div>
                <?php if (count($part_time)): ?>
                    <div class="panel-body">
                        <!--  Form Input -->
                        <input class="form-control" id="search-collection" type="text" placeholder="Filter list"/>
                    </div>
                <?php endif; ?>
                <div class="collection">
                    <?php if (count($part_time)): ?>
                        <?php foreach ($part_time as $member): ?>
                            <li class="list-group-item collection-item part-timer"
                                data-member-id="<?php echo $member->id ?>"><?php echo $member->forum_name ?><span
                                    class="pull-right delete-part-time" title="Remove part-time member"><i
                                        class="fa fa-trash text-danger"></i></span></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="list-group-item text-muted">You have no part time members assigned.</p>
                    <?php endif; ?>
                </div>
            </div><!-- panel -->
        </div><!-- col-md-8 -->


    </div><!-- row -->

</div>
