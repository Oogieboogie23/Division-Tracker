<?php

// maintenance mode
// Flight::route('*', array('ApplicationController', '_unavailable'));

if (empty($_SESSION['userid'])) {
    Flight::route('/', array('UserController', '_login'));
    Flight::route('/register', array('UserController', '_register'));
    Flight::route('POST /do/login', array('UserController', '_doLogin'));
    Flight::route('POST /do/register', array('UserController', '_doRegister'));
    Flight::route('/invalid-login', array('ApplicationController', '_invalidLogin'));
    Flight::route('POST /do/online-list', array('ApplicationController', '_doUsersOnline'));
} else {

    // user views
    Flight::route('/', array('ApplicationController', '_index'));
    Flight::route('/logout', array('UserController', '_doLogout'));
    Flight::route('/help', array('ApplicationController', '_help'));
    Flight::route('/recruiting', array('RecruitingController', '_index'));
    Flight::route('/activity', array('ApplicationController', '_activity'));
    Flight::route('/recruiting/new-member', array('RecruitingController', '_addNewMember'));

    // manage
    Flight::route('/manage/inactive-members', array('DivisionController', '_manage_inactives'));
    Flight::route('/manage/leaves-of-absence', array('DivisionController', '_manage_loas'));
    Flight::route('/divisions/@div/platoon/@plt/manage', array('PlatoonController', '_manage_platoon'));
    Flight::route('/divisions/@div/platoon/@plt/squad/@squad', array('SquadController', '_manage_squad'));
    Flight::route('/manage/part-time', array('DivisionController', '_manage_part_time'));

    // view
    Flight::route('/divisions/@div', array('DivisionController', '_index'));
    Flight::route('/divisions/@div/platoon/@plt', array('PlatoonController', '_index'));
    Flight::route('/member/@id', array('MemberController', '_profile'));
    Flight::route('/activity/@div', array('ApplicationController', '_activity'));
    /* Flight::route('/issues/view/@id', array('GithubController', '_view'));
    Flight::route('/issues/@filter|/issues', array('GithubController', '_index'));*/

    // updates
    Flight::route('POST /do/search-members', array('ApplicationController', '_doSearch'));
    Flight::route('POST /do/online-list', array('ApplicationController', '_doUsersOnline'));
    Flight::route('POST /do/update-alert', array('ApplicationController', '_doUpdateAlert'));
    Flight::route('POST /do/update-member', array('MemberController', '_doUpdateMember'));
    Flight::route('POST /do/update-member-squad', array('PlatoonController', '_doUpdateMemberSquad'));
    Flight::route('POST /do/validate-member', array('MemberController', '_doValidateMember'));
    Flight::route('POST /do/add-member', array('MemberController', '_doAddMember'));
    Flight::route('POST /do/add-parttime', array('DivisionController', '_doAddPartTimeMember'));
    Flight::route('/do/part-time/delete/@id', array('DivisionController', '_doRemovePartTimeMember'));
    Flight::route('POST /do/update-flag', array('MemberController', '_doUpdateFlag'));
    Flight::route('POST /do/update-loa', array('DivisionController', '_updateLoa'));
    Flight::route('POST /do/remove-member', array('MemberController', '_doKickFromAod'));
    Flight::route('POST /do/issue-submit', array('GithubController', '_doSubmitIssue'));
    Flight::route('POST /do/create-squad', array('SquadController', '_doCreateSquad'));
    Flight::route('POST /do/modify-squad', array('SquadController', '_doModifySquad'));
    Flight::route('POST /do/assign-to-platoon', ['MemberController', '_doAssignMemberToPlatoon']);

    // modals
    Flight::route('POST /edit/member', array('MemberController', '_edit'));
    Flight::route('/get/division-structure', array('DivisionController', '_generateDivisionStructure'));
    Flight::route('/create/issue', array('GithubController', '_createIssue'));
    Flight::route('/create/squad', array('SquadController', '_createSquad'));
    Flight::route('/modify/squad', array('SquadController', '_modifySquad'));


    // reports
    Flight::route('/reports/retention', array('ReportController', '_retentionNumbers'));

    // cURLS
    Flight::route('POST /do/check-division-threads', array('RecruitingController', '_doDivisionThreadCheck'));

    //REST API FOR PS2 STATS
    Flight::route('GET /ps2activity/@char', array('PS2StatsController','_getPS2Activity'));
    /*
    Flight::route('/settings', array('UserController', '_settings'));

    // view screens
    Flight::route('/member/[0-9]+', array('MemberController', '_profile'));



    // manage

    Flight::route('/manage/division', array('DivisionController', '_manage_division'));
    Flight::route('/manage/loas', array('DivisionController', '_manage_loas'));


    // admin
    Flight::route('/admin', array('AdminController', '_show'));
    */

    // update user activity
    if (isset($_SESSION['userid'])) {
        User::updateActivityStatus($_SESSION['userid']);
    }
}

// 404 redirect
Flight::map('notFound', array('ApplicationController', '_404'));

// error handler
Flight::route('/error', array('ApplicationController', '_error'));

// graphics
Flight::route('/stats/@division/top10.png', array('GraphicsController', '_generateDivisionTop10'));

// authenticate
Flight::route('GET /authenticate', array('UserController', '_authenticate'));
Flight::route('POST /do/authenticate', array('UserController', '_doAuthenticate'));
Flight::route('POST /do/reset-authentication', array('UserController', '_doResetAuthentication'));

/*// handle errors privately unless localhost
if(!in_array($_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ))){
    Flight::set('flight.log_errors', true);
    Flight::map('error', function(Exception $ex){
        Flight::redirect('/error', 500);
    });
}
*/
