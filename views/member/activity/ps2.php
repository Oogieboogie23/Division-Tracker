<div ng-app="ps2App" ng-controller="profileCtrl">
<input type="hidden" value="<?php echo $ps2_character_name; ?>" id="ps2_character_name">
<input type="hidden" value="<?php //echo $ps2_nc_character_names; ?>" id="ps2_nc_character_names">
<input type="hidden" value="<?php //echo $ps2_vs_character_names; ?>" id="ps2_vs_character_names">
<div class="row">
<div class="col-sm-8 col-sm-offset-2" ng-show="loading">
<br/><br/><br/>
<div class="well">
<p class="text-center">
<i  class="fa fa-spinner fa-spin  fa-4x"></i>
<h2>Pulling Data From DayBreak</h2>
<small>This may take a few minutes</small>
</p>
</div>
</div>

</div>
<div class="row">
<div class="col-sm-8 col-sm-offset-2" ng-show="!loading&&!profile">
<br/><br/><br/>
<div class="well">
<p class="text-center">

<h2>Character Not Found</h2>
<small>Make sure their character name is correct in their "tr name" alias</small>
</p>
</div>
</div>

</div>
<div class='panel panel-primary' ng-show="!loading&&profile">
  <div class="panel-heading">Planetside 2 Activity</div>
  <div class="panel-body">
    <div class="col-sm-12">
      <ul class="nav nav-pills" ng-init="faction=0">
        <li role="presentation"  ng-class="{active:faction==0}" ng-click="faction=0"><a href="#">All</a></li>
        <li role="presentation" ng-class="{active:faction==1}" ng-click="faction=1" ng-show="characters.tr.length>0"><a href="#">TR</a></li>
        <li role="presentation"  ng-class="{active:faction==2}" ng-click="faction=2" ng-show="characters.nc.length>0"><a href="#">NC</a></li>
        <li role="presentation" ng-class="{active:faction==3}" ng-click="faction=3" ng-show="characters.vs.length>0"><a href="#">VS</a></li>

      </ul>

    </div>
  <div class="row">

  <div class="col-sm-12" >
    <div class="col-sm-4" ng-show="faction>0">
      <h2>{{profile.name.first}}</h2>
<strong>Last Login:</strong> {{profile.times.last_login_date }}<br/>

<strong>Stats last saved:</strong> {{profile.playtime.last_save_date}}<br/>
<strong>Battle Rank:</strong> {{profile.battle_rank.value}}<br/>

<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{profile.battle_rank.percent_to_next}}%;">
    {{profile.battle_rank.percent_to_next}}% to {{(profile.battle_rank.value-0)+1}}
  </div>
</div><br/>
<strong>Current Outfit:</strong> {{profile.outfit_member.alias}}<br/>
<strong>Member Since:</strong> {{profile.outfit_member.member_since_date}}
</div>
    <div  ng-class="{'col-sm-12':faction==0,'col-sm-8':faction>0}">
    <div ng-show="chart==1">
    <strong>Hours by Month</strong>
    <canvas id="{{profile.name.first}}-month-bar" class="chart chart-line"
      chart-data="profile.playtime.month.chart.data" chart-labels="profile.playtime.month.chart.labels" chart-options="chartoptions" chart-colors="colors" height="300" width="600" >
    </canvas>
    </div>
    <div ng-show="chart==2">
    <strong>Hours by Day</strong>
    <canvas id="{{profile.name.first}}-day-bar" class="chart chart-line"
      chart-data="profile.playtime.day.chart.data" chart-labels="profile.playtime.day.chart.labels" chart-options="chartoptions" chart-colors="colors" height="300" width="600" >
    </canvas>
    </div>
    <hr/>
    <ul class="nav nav-pills" ng-init="chart=1">
      <li role="presentation"  ng-class="{active:chart==1}" ng-click="chart=1"><a href="#">month</a></li>
      <li role="presentation" ng-class="{active:chart==2}" ng-click="chart=2"><a href="#">day</a></li>
    </ul>
    </div>
    <hr/>
    <hr/>

    <strong>Effective Platoon leading</strong><br/><small>score is calculated by the sum of platoon leading ribons earned (platoon leading=1, platoon conquest=2)</small>

    <canvas id="{{profile.name.first}}-pl-day-bar" class="chart chart-line"
      chart-data="profile.platoonleading.data" chart-labels="profile.platoonleading.labels" chart-options="chartoptions" chart-colors="colors" height="300" width="900" >
    </canvas>
    <strong> Average:</strong> {{profile.platoonleading.avg}}

    <hr/>
    <strong>Squad leading</strong><br/><small>score is calculated by the sum of squad leading ribons earned (Squad Leading=1, Drill Sergeant =1, Squad Spawn =1)</small>
    <canvas id="{{profile.name.first}}-sl-day-bar" class="chart chart-line"
      chart-data="profile.squadleading.data" chart-labels="profile.squadleading.labels" chart-options="chartoptions" chart-colors="colors" height="300" width="900" >
    </canvas>
    <strong> Average:</strong> {{profile.squadleading.avg}}
</div>
</div>
</div>
</div>

</div>
