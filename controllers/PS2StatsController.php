<?php

class PS2StatsController
{

    public static function _getPS2Activity()
    {
      $char_str = Flight::request()->query->chars;
      $chars=explode(",",$char_str);
      $TR=array();
      $NC=array();
      $VS=array();
      $characters=array();
      foreach( $chars as $char){
        $census = new Census();
        $character = $census->getUserByName(trim($char));

        if (!empty($character)) {
            $character->playtime = $census->playtime($character->character_id);
            $character->squadleading = $census->squadleading($character->character_id);
            $character->platoonleading = $census->platoonleading($character->character_id);
            switch($character->faction_id){
              case 3:
                $TR[]=$character;
                break;
              case 2:
                $NC[]=$character;
                break;
              case 1:
                $VS[]=$character;
            }
          //  $characters[]=$character;

        }
      }
      Flight::json(array("tr"=>$TR,"nc"=>$NC,"vs"=>$VS));
    }

}
