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
            ->sortAsc('game_id')
            ->sortAsc('sort_order')
            ->find();
    }
}
