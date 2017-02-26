<?php

class DivisionController
{

    public static function _index($div)
    {
        $user = User::find(intval($_SESSION['userid']));
        $member = Member::find(intval($_SESSION['memberid']));
        $tools = Tool::find_all($user->role);
        $divisions = Division::find_all();
        $division = Division::findByName(strtolower($div));
        $js = 'division';

        if (property_exists($division, 'id')) {
            $division_leaders = Division::findDivisionLeaders($division->id);
            Flight::render('division/main/statistics', compact('division'), 'statistics');
            Flight::render('division/main/index', compact('user', 'member', 'division', 'division_leaders'), 'content');
            Flight::render('layouts/application', compact('user', 'member', 'tools', 'divisions', 'js'));
        } else {
            Flight::redirect('/404', 404);
        }
    }

    public static function _manage_inactives()
    {
        $user = User::find(intval($_SESSION['userid']));
        $member = Member::find(intval($_SESSION['memberid']));
        $tools = Tool::find_all($user->role);
        $divisions = Division::find_all();

        switch ($user->role) {
            case User::isDev():
                $type = "div";
                $id = $member->game_id;
                break;
            case 1:
                $type = "sqd"; //$id = $member->squad_id; break;
            case 2:
                $type = "plt";
                $id = $member->platoon_id;
                break;
            case 3:
                $type = "div";
                $id = $member->game_id;
                break;
            default:
                $type = "div";
                $id = $member->game_id;
                break;
        }

        $flagged_inactives = Member::findInactives($id, $type, true);
        $flaggedCount = (count($flagged_inactives)) ? count($flagged_inactives) : 0;

        $inactives = Member::findInactives($id, $type);
        $inactiveCount = (count($inactives)) ? count($inactives) : 0;

        Flight::render('manage/inactive_members', array(
            'member' => $member,
            'user' => $user,
            'inactives' => arrayToObject($inactives),
            'flagged' => arrayToObject($flagged_inactives),
            'flaggedCount' => $flaggedCount,
            'inactiveCount' => $inactiveCount
        ), 'content');
        Flight::render('layouts/application', array(
            'user' => $user,
            'member' => $member,
            'tools' => $tools,
            'divisions' => $divisions,
            'js' => 'manage'
        ));

    }

    public static function _manage_part_time()
    {
        $user = User::find(intval($_SESSION['userid']));
        $member = Member::find(intval($_SESSION['memberid']));
        $tools = Tool::find_all($user->role);
        $divisions = Division::find_all();

        $part_time = PartTime::find_all($member->game_id);
        Flight::render('manage/part_time', array('member' => $member, 'user' => $user, 'part_time' => $part_time),
            'content');
        Flight::render('layouts/application', array(
            'user' => $user,
            'member' => $member,
            'tools' => $tools,
            'divisions' => $divisions,
            'js' => 'manage'
        ));
    }

    public static function _doAddPartTimeMember()
    {
        $member = Member::find(intval($_SESSION['memberid']));

        $member_params = array(
            'member_id' => $_POST['member_id'],
            'forum_name' => $_POST['name'],
            'ingame_alias' => $_POST['ingame_alias'],
            'game_id' => $member->game_id
        );

        PartTime::add($member_params);
        Flight::redirect('/manage/part-time');
    }

    public static function _doRemovePartTimeMember($id)
    {
        $user = User::find(intval($_SESSION['userid']));

        if ($user->role > 0) {

            if (PartTime::delete($id)) {
                $data = array('success' => true, 'message' => "Member removed!");
            } else {
                $data = array('success' => false, 'message' => "Member does not exist!");
            }
        } else {
            $data = array('success' => false, 'message' => "You do not have acess to perform this function");
        }
         echo json_encode($data);
    }

    public static function _manage_loas()
    {

        $user = User::find(intval($_SESSION['userid']));
        $member = Member::find(intval($_SESSION['memberid']));
        $tools = Tool::find_all($user->role);
        $divisions = Division::find_all();
        $division = Division::findById(intval($member->game_id));

        Flight::render('manage/loas', array('division' => $division, 'member' => $member, 'user' => $user), 'content');
        Flight::render('layouts/application', array(
            'user' => $user,
            'member' => $member,
            'tools' => $tools,
            'divisions' => $divisions,
            'js' => 'manage'
        ));

    }

    public static function _reports()
    {
        Flight::render('modals/reports');
    }

    public static function _generateDivisionStructure()
    {
        $member = Member::find(intval($_SESSION['memberid']));
        switch ($member->game_id) {
            case 2:
                $division_structure = new BfDivisionStructure($member->game_id);
                break;
            case 3:
                $division_structure = new WgDivisionStructure($member->game_id);
                break;
            case 4:
                $division_structure = new SWBDivisionStructure($member->game_id);
                break;
            case 6:
                $division_structure = new PS2DivisionStructure($member->game_id);
                break;
            case 7:
                $division_structure = new H1Z1DivisionStructure($member->game_id);
                break;
            default:
                $division_structure = new DivisionStructure($member->game_id);
        }

        Flight::render('modals/division_structure', array('division_structure' => $division_structure->content));
    }


    public static function _updateLoa()
    {

        $user = User::find(intval($_SESSION['userid']));
        $member = Member::find(intval($_SESSION['memberid']));

        if (isset($_POST['remove']) || isset($_POST['approve'])) {

            if ($user->role < 2) {
                $data = array('success' => false, 'message' => "You are not authorized to perform that action.");
            } else {

                $loa = (isset($_POST['loa_id'])) ? LeaveOfAbsence::findById($_POST['loa_id']) : null;

                if (isset($_POST['remove'])) {

                    $revoked = LeaveOfAbsence::delete($loa->id);
                    UserAction::create(array(
                        'type_id' => 8,
                        'date' => date("Y-m-d H:i:s"),
                        'user_id' => $member->member_id,
                        'target_id' => $loa->member_id
                    ));
                    $data = array('success' => true, 'message' => "Leave of absence successfully removed.");

                } else {
                    if (isset($_POST['approve'])) {

                        if ($member->member_id == $loa->member_id) {
                            $data = array(
                                'success' => false,
                                'message' => "You can't approve your own leave of absence!"
                            );
                        } else {

                            $approved = LeaveOfAbsence::approve($loa->id, $member->member_id);
                            UserAction::create(array(
                                'type_id' => 7,
                                'date' => date("Y-m-d H:i:s"),
                                'user_id' => $member->member_id,
                                'target_id' => $loa->member_id
                            ));
                            $data = array('success' => true, 'message' => "Leave of absence successfully approved.");
                        }

                    }
                }
            }

        } else {

            $date = date('Y-m-d', strtotime($_POST['date']));
            $reason = $_POST['reason'];
            $comment = htmlentities($_POST['comment'], ENT_QUOTES);
            $name = Member::findForumName($_POST['id']);

            if ($name) {
                if (strtotime($date) > strtotime('now')) {
                    if (LeaveOfAbsence::exists($member->member_id)) {
                        $data = array('success' => false, 'message' => "This member already has an LOA in place!");
                    } else {
                        LeaveOfAbsence::add($_POST['id'], $date, $reason, $comment, $member->game_id);
                        UserAction::create(array(
                            'type_id' => 11,
                            'date' => date("Y-m-d H:i:s"),
                            'user_id' => $_POST['id']
                        ));
                        $data = array(
                            'success' => true,
                            'Request successfully submitted!',
                            'id' => $_POST['id'],
                            'name' => $name,
                            'date' => date('M d, Y', strtotime($date)),
                            'reason' => $reason
                        );
                    }
                } else {
                    $data = array('success' => false, 'message' => "Date cannot be before today's date.");
                }
            } else {
                $data = array('success' => false, 'message' => 'The member id you provided appears to be invalid.');
            }

        }

        echo json_encode($data);
    }

}
