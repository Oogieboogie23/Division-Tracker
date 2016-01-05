<?php

class ApplicationController
{

    public static function _index()
    {

        $user = User::find(intval($_SESSION['userid']));
        $member = Member::find(intval($_SESSION['memberid']));
        $tools = Tool::find_all($user->role);
        $divisions = Division::find_all();
        $division = Division::findById(intval($member->game_id));
        $notifications = new Notification($user, $member);

        $squad = Squad::find($member->member_id);
        $platoon = Platoon::find($member->platoon_id);
        $squads = Squad::findAll($member->game_id, $member->platoon_id);


        Flight::render('user/main_tools', compact('user', 'tools'), 'main_tools');
        Flight::render('member/personnel', compact('member', 'squad', 'platoon', 'squads'), 'personnel');
        Flight::render('application/divisions', compact('divisions'), 'divisions_list');
        Flight::render('user/notifications', array('notifications' => $notifications->messages), 'notifications_list');
        Flight::render('layouts/home', compact('user', 'member', 'division'), 'content');
        Flight::render('layouts/application', compact('user', 'member', 'tools', 'divisions', 'division'));
    }

    public static function _activity()
    {
        $user = User::find(intval($_SESSION['userid']));
        $member = Member::find(intval($_SESSION['memberid']));
        $tools = Tool::find_all($user->role);
        $divisions = Division::find_all();
        $division = Division::findById(intval($member->game_id));
        $js = 'help';
        Flight::render('application/activity', array('division' => $division), 'content');
        Flight::render('layouts/application', compact('js', 'user', 'member', 'tools', 'divisions'));

    }

    public static function _help()
    {
        $user = User::find(intval($_SESSION['userid']));
        $member = Member::find(intval($_SESSION['memberid']));
        $tools = Tool::find_all($user->role);
        $divisions = Division::find_all();
        $division = Division::findById(intval($member->game_id));
        $js = 'help';

        Flight::render('application/help', compact('user', 'member', 'division'), 'content');
        Flight::render('layouts/application', compact('js', 'user', 'member', 'tools', 'divisions'));
    }

    public static function _doUsersOnline()
    {
        if (isset($_SESSION['loggedIn'])) {
            $user = User::find(intval($_SESSION['userid']));
            $member = Member::find(intval($_SESSION['memberid']));
            Flight::render('user/online_list', compact('user', 'member'));
        } else {
            Flight::render('user/online_list');
        }
    }

    public static function _doSearch()
    {
        $name = trim($_POST['name']);
        $results = Member::search($name);
        Flight::render('member/search', compact('results'));
    }

    public static function _invalidLogin()
    {
        Flight::render('errors/invalid_login', [], 'content');
        Flight::render('layouts/application');
    }

    public static function _unavailable()
    {
        Flight::render('errors/unavailable', [], 'content');
        Flight::render('errors/main');
    }

    public static function _404()
    {
        Flight::render('errors/404', [], 'content');
        Flight::render('errors/main');
    }

    public static function _error()
    {
        Flight::render('errors/error', [], 'content');
        Flight::render('errors/main');
    }

    public static function _doUpdateAlert()
    {
        $id = $_POST['id'];
        $user = $_POST['user'];
        $params = compact('id', 'user');
        AlertStatus::create($params);
    }

}
