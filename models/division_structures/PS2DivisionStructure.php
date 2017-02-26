<?php

class PS2DivisionStructure
{
    private $banner;

    public function __construct($game_id)
    {
        $this->banner = "http://i.imgur.com/BKkTYMC.jpg";
        $this->game_id = $game_id;

        // get data
        $this->division = Division::findById($this->game_id);
        $this->platoons = Platoon::find_all($this->game_id);

        // colors
        $this->division_leaders_color = "#FF0000";
        $this->general_sergeants_color = "#00FFFF";
        $this->platoon_name_color = "#00FFFF";
        $this->platoon_leader_color = "#FFA07A";
        $this->squad_leader_color = "orange";

        // number of columns
        $this->num_columns_squads = 3;

        // widths
        $this->players_width = 900;
        $this->info_width = 800;

        // misc settings
        $this->min_num_squad_leaders = 2;

        self::generate();
    }

    public function generate()
    {
        // header
        $division_structure = "[center]";

        // banner
        $division_structure .= "[img]{$this->banner}[/img]\r\n";

        // division leaders
        $division_structure .= "\r\n\r\n[size=5][color={$this->division_leaders_color}][b][i][u]Division Leadership[/u][/i][/b][/color][/size]\r\n";
        $division_structure .= "[size=4]";
        $division_structure = $this->getDivisionLeaders($division_structure);
        $division_structure .= "[/size]\r\n\r\n";

        // general sergeants
        $division_structure .= "[size=3][color={$this->general_sergeants_color}]General Sergeants[/color]\r\n";
        $division_structure = $this->getGeneralSergeants($division_structure);
        $division_structure .= "[/size][/center]";

        // groups
        $division_structure .= "[TABLE=\"align: center\"]";
        $division_structure = $this->getGroups($division_structure);
        $division_structure .= "[/table]";

        // reaper group
        $division_structure = $this->getReaperGroup($division_structure);

        // LOAs
        $division_structure = $this->getLoas($division_structure);

        // populate content
        $this->content = $division_structure;
    }

    /**
     * @param $division_structure
     * @return string
     */
    private function getDivisionLeaders($division_structure)
    {
        $division_leaders = Division::findDivisionLeaders($this->game_id);
        foreach ($division_leaders as $division_leader) {
            $aod_url = Member::createAODlink([
                'member_id' => $division_leader->member_id,
                'rank' => Rank::convert($division_leader->rank_id)->abbr,
                'forum_name' => $division_leader->forum_name,
            ]);
            $division_structure .= (property_exists($division_leader,
                'position_desc')) ? "{$aod_url} - {$division_leader->position_desc}\r\n" : "{$aod_url}\r\n";
        }
        return $division_structure;
    }

    /**
     * @param $division_structure
     * @return string
     */
    private function getGeneralSergeants($division_structure)
    {
        $general_sergeants = Division::findGeneralSergeants($this->game_id);
        foreach ($general_sergeants as $general_sergeant) {
            $aod_url = Member::createAODlink([
                'member_id' => $general_sergeant->member_id,
                'rank' => Rank::convert($general_sergeant->rank_id)->abbr,
                'forum_name' => $general_sergeant->forum_name,
            ]);
            $division_structure .= "{$aod_url}\r\n";
        }
        return $division_structure;
    }

    /**
     * @param $division_structure
     * @return string
     */
    private function getGroups($division_structure)
    {
        foreach ($this->platoons as $platoon) {
            $division_structure .= "[tr][td]\r\n\r\n\r\n[/td][/tr]";

            // prevent reaper group from appearing
            if (strtolower($platoon->name) == "reaper group") {
                break;
            }

            // group leader
            $division_structure .= "[TR][TD][/TD][TD]";
            $division_structure .= "[size=4][color={$this->platoon_name_color}]{$platoon->name}[/color][/size]\r\n\r\n";
            $group_leader = Member::findByMemberId($platoon->leader_id);

            // is a group leader assigned?
            if ($platoon->leader_id != 0) {
                $aod_url = Member::createAODlink(array(
                    'member_id' => $group_leader->member_id,
                    'forum_name' => Rank::convert($group_leader->rank_id)->abbr . " " . $group_leader->forum_name,
                    'color' => $this->platoon_leader_color
                ));
                $division_structure .= "[size=4]{$aod_url}[/size]\r\n\r\n";

            } else {
                $division_structure .= "[size=4]TBA[/size]\r\n\r\n";
            }
            $division_structure .= "[/td][/tr]";

            /**
             * Squads
             */
            $division_structure .= "[tr]";
            list($division_structure, $aod_url) = $this->getSquads($division_structure, $platoon);

            $division_structure .= "[/tr]";
            $division_structure .= "\r\n\r\n\r\n\r\n\r\n\r\n\r\n";
        }
        return $division_structure;
    }

    /**
     * @param $division_structure
     * @param $platoon
     * @return array
     */
    private function getSquads($division_structure, $platoon)
    {
        $squads = Squad::findAll($this->game_id, $platoon->id);
        $iterate_squad = 0;

        if ('spec group' == strtolower($platoon->name)) {
            $this->squad_leader_color = "#006699";
        }

        foreach ($squads as $squad) {
            $division_structure .= "[td]";
            // squad leader
            if ($squad->leader_id != 0) {
                $squad_leader = Member::findById($squad->leader_id);
                $aod_url = Member::createAODlink([
                    'member_id' => $squad_leader->member_id,
                    'forum_name' => Rank::convert($squad_leader->rank_id)->abbr . " " . ucfirst($squad_leader->forum_name),
                    'color' => $this->squad_leader_color
                ]);
                $division_structure .= "[size=4]{$aod_url}[/size]\r\n\r\n";

                $recruits = arrayToObject(Member::findRecruits($squad_leader->member_id, $squad_leader->platoon_id,
                    false, true));
                $division_structure .= "[size=1][list=1]";
                foreach ($recruits as $recruit) {
                    $aod_url = Member::createAODlink([
                        'member_id' => $recruit->member_id,
                        'forum_name' => Rank::convert($recruit->rank_id)->abbr . " " . $recruit->forum_name,
                    ]);
                    $division_structure .= "[*]{$aod_url}\r\n\r\n";
                }
                $division_structure .= "[/list][/size]\r\n";


            } else {
                $division_structure .= "[size=4][color={$this->squad_leader_color}]TBA[/color][/size]\r\n\r\n";
            }
            // end squad leader
            // squad members
            $squadMembers = arrayToObject(Squad::findSquadMembers($squad->id, true, $squad_leader->member_id));
            if (count((array) $squadMembers)) {
                $division_structure .= "[list=1]";
                foreach ($squadMembers as $squadMember) {
                    $player_name = Rank::convert($squadMember->rank_id)->abbr . " " . $squadMember->forum_name;
                    $aod_url = Member::createAODlink(array(
                        'member_id' => $squadMember->member_id,
                        'forum_name' => $player_name
                    ));
                    $division_structure .= "[*]{$aod_url}\r\n";
                }
                $division_structure .= "[/list]";
            }
            // end squad members
            $division_structure .= "[/td]";
            $iterate_squad++;

            if ($iterate_squad % 3 == 0) {
                $division_structure .= "[/tr][tr]";
            }
        }
        return array($division_structure, $aod_url);
    }


    private function getReaperGroup($division_structure)
    {
        $platoon = Platoon::findByName('reaper group');
        $squads = Squad::findAll($this->game_id, $platoon->id);
        $group_leader = Member::findByMemberId($platoon->leader_id);
        $this->squad_leader_color = "orange";

        $reaper_leader = Member::createAODlink(array(
            'member_id' => $group_leader->member_id,
            'rank' => Rank::convert($group_leader->rank_id)->abbr,
            'forum_name' => $group_leader->forum_name,
        ));

        // fancy image and title
        $division_structure .= "\r\n\r\n\r\n[center][IMG]http://i.imgur.com/z1DbehX.png[/IMG]\r\n[SIZE=5][B][COLOR=\"#FF0000\"]\r\nR[SIZE=4]EAPERS[/SIZE] S[SIZE=4]PECIAL[/SIZE] O[SIZE=4]PERATIONS[/SIZE] G[SIZE=4]ROUP[/SIZE][/COLOR][/B][/SIZE]\r\n[SIZE=2]C[COLOR=\"#000000\"]-[/COLOR] O[COLOR=\"#000000\"]-[/COLOR] M[COLOR=\"#000000\"]-[/COLOR] P[COLOR=\"#000000\"]-[/COLOR] E[COLOR=\"#000000\"]-[/COLOR] T[COLOR=\"#000000\"]-[/COLOR] I[COLOR=\"#000000\"]-[/COLOR] T[COLOR=\"#000000\"]-[/COLOR] I[COLOR=\"#000000\"]-[/COLOR] V[COLOR=\"#000000\"]-[/COLOR] E[COLOR=\"#000000\"]-[/COLOR] [COLOR=\"#000000\"]---[/COLOR] T[COLOR=\"#000000\"]-[/COLOR] E[COLOR=\"#000000\"]-[/COLOR] A[COLOR=\"#000000\"]-[/COLOR] M[/SIZE]\r\n\r\n\r\n";

        $division_structure .= "[COLOR=\"#FF0000\"][SIZE=5]Group Leader[/SIZE][/COLOR]\r\n";
        $division_structure .= "[SIZE=4][profile=33860]{$reaper_leader}[/profile][/size]\r\n\r\n";

        foreach ($squads as $squad) {
            if ($squad->leader_id != 0) {
                $squad_leader = Member::findById($squad->leader_id);
                $aod_url = Member::createAODlink([
                    'member_id' => $squad_leader->member_id,
                    'forum_name' => Rank::convert($squad_leader->rank_id)->abbr . " " . ucfirst($squad_leader->forum_name),
                    'color' => $this->squad_leader_color
                ]);
                $division_structure .= "[COLOR=\"#FF0000\"][SIZE=4]Squad Leader[/SIZE][/COLOR]\r\n";
                $division_structure .= "[size=3]{$aod_url}[/size]\r\n\r\n";
            } else {
                $division_structure .= "[size=3][color={$this->squad_leader_color}]TBA[/color][/size]\r\n\r\n";
            }

            // end squad leader
            // squad members
            $squadMembers = arrayToObject(Squad::findSquadMembers($squad->id, true, $squad_leader->member_id));
            if (count((array) $squadMembers)) {
                $division_structure .= "[COLOR=\"#FF0000\"][SIZE=3]Group Members[/SIZE][/COLOR]\r\n\r\n";
                foreach ($squadMembers as $squadMember) {
                    $player_name = Rank::convert($squadMember->rank_id)->abbr . " " . $squadMember->forum_name;
                    $aod_url = Member::createAODlink(array(
                        'member_id' => $squadMember->member_id,
                        'forum_name' => $player_name
                    ));
                    $division_structure .= "{$aod_url}\r\n";
                }
            }
        }

        $division_structure .= "[/center]";

        return $division_structure;
    }

    /**
     * @param $division_structure
     * @return string
     */
    private function getLoas($division_structure)
    {
        if (count((array) LeaveOfAbsence::find_all($this->game_id))) {
            $i = 1;

            // header
            $division_structure .= "\r\n\r\n\r\n[table='align:center,width: 500']";
            $division_structure .= "[tr][td]\r\n[center][size=3][b]Leaves of Absence[/b][/size][/center][/td][/tr]";
            $division_structure .= "[/table]\r\n\r\n";

            // players
            $division_structure .= "[table='align:center,width: 500']";
            $loas = LeaveOfAbsence::find_all($this->game_id);

            foreach ($loas as $player) {
                $date_end = (strtotime($player->date_end) < strtotime('now')) ? "[COLOR='#FF0000']Expired " . formatTime(strtotime($player->date_end)) . "[/COLOR]" : date("M d, Y",
                    strtotime($player->date_end));
                $profile = Member::findByMemberId($player->member_id);
                $aod_url = Member::createAODlink(array(
                    'member_id' => $player->member_id,
                    'forum_name' => "AOD_" . $profile->forum_name
                ));

                $division_structure .= "[tr][td]{$aod_url}[/td][td]{$date_end}[/td][td]{$player->reason}[/td][/tr]";
                $i++;
            }

            $division_structure .= "[/table]";
            return $division_structure;
        }
        return $division_structure;
    }
}
