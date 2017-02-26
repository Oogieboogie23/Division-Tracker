<div class="row">
    <div class='col-md-12 margin-top-50'>
        <div class='page-header'>
            <h3><i class="fa fa-tachometer fa-lg"></i> Division Statistics</h3>
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-md-6'>
        <div class='panel panel-primary toplist'>
            <div class='panel-heading'>Total Membership</div>
            <div class="panel-body count-detail-big follow-tool striped-bg">
                <span class="count-animated"><?php echo Division::totalCount($division->id); ?></span>
            </div>
        </div>
    </div>

    <div class='col-md-6'>
        <div class='panel panel-primary toplist'>
            <div class='panel-heading'>Recruits This Month</div>
            <div class="panel-body count-detail-big follow-tool striped-bg">
                <span class="count-animated"><?php echo Division::recruitsThisMonth($division->id); ?></span>
            </div>
        </div>
    </div>

</div>
