<?php

class RecruitingTask extends Application
{
    public static $id_field = 'id';
    public static $table = 'recruiting_tasks';
    public $id;
    public $content;
    public $game_id;
    public $sort_order;

    public static function findAll($game_id)
    {
        return Flight::aod()->using('RecruitingTask')
            ->where(array('game_id @' => array(0, $game_id)))
            // show division specific tasks first
            ->sortDesc('game_id')
            // sort (need to make this editable in admin)
            ->sortAsc('sort_order')
            ->find();
    }
}
