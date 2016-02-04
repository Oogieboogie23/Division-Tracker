<?php

/**
 * WG Division Structure
 *
 * Generates a bb-code template with prepopulated member data
 *
 */
class WgDivisionStructure
{
    public function __construct($game_id)
    {
        $this->game_id = $game_id;

        $this->content = $this->generate();
    }

    public function generate()
    {
        // open center
        $structure = "[CENTER]";

        // header
        $structure .= "[SIZE=7][B][U][COLOR=#00ffff]Wargaming Universe[/COLOR][/U][/B][/SIZE]\r\n[SIZE=6][B][U][COLOR=#00ffff]Division Roster[/COLOR][/U][/B][/SIZE]\r\n_________________________________________________\r\n\r\n";

        // division leaders
        $structure = $this->getDivisionLeaders($structure);

        // close center
        $structure .= "[/center]";

        return $structure;

    }

    private function getGameLinks($member)
    {
        $output = '';
        $games = MemberGame::getGamesPlayed($member);
        $handle = MemberHandle::findHandle($member->id, 7);

        foreach ($games as $game) {
            if (count($handle->num_rows) && in_array($game->short_name, array('ws', 'wt')) ) {
                $output .= "[url="
            }
        }

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
}
