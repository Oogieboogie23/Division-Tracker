<?php
class PS2StatsController{



  public static function _getPS2Activity($char){
      $census=new Census();
      $character=$census->getUserByName($char);
      if(!empty($character)){
          $character->playtime=$census->playtime($character->character_id);
          $character->squadleading=$census->squadleading($character->character_id);
          $character->platoonleading=$census->platoonleading($character->character_id);
          Flight::json($character);

        }else{
          $error=new stdClass();
          $error->error="Character Not Found";
          Flight::json($error);

        }
  }


}


 ?>
