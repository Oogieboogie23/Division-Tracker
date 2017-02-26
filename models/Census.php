<?php

class Census
{

    private $base_url = "https://census.daybreakgames.com/s:zeroorder/get/ps2:v2";

    public function getUserByName($character_name)
    {
      //  $url = "/character?name.first=" . $character_name . "&c:case=false&c:limit=1000&c:join=type:characters_world^list:0^inject_at:world^terms:world_id=17^outer:0,type:outfit_member_extended^list:0^inject_at:outfit_member^terms:outfit_id=37530159341115681^outer:0,type:faction^inject_at:faction";
        $url = "/character?name.first=" . $character_name . "&c:case=false&c:limit=1000&c:join=type:characters_world^list:0^inject_at:world^terms:world_id=17^outer:0,type:outfit_member_extended^list:0^inject_at:outfit_member^outer:0,type:faction^inject_at:faction";

        $character = $this->query($url);
        if (!empty ($character->character_list [0])) {
            return $character->character_list [0];
        }
        return null;
    }

    public function query($path)
    {
        return $this->makerequest($this->base_url . $path);
    }

    private function makerequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output);
    }

    public function playtime($char_id)
    {
        if (empty ($char_id)) {
            return null;
        }
        $url = "/characters_stat_history?character_id=" . $char_id . "&stat_name=time";

        $ptime = $this->query($url);

        if (!empty ($ptime->characters_stat_history_list [0])) {
            $member = $ptime->characters_stat_history_list [0];
            $labels = array();
            $data = array();
            $count = 0;
            $labels = array();// = date("m-d-Y", $member->last_save);
            for($i=0;$i<31;$i++){
              $labels[]=date("m-d-Y", strtotime("- $i days"));
            }
              $day_diff=$this->s_datediff("d","now",$member->last_save_date);
              for($i=0;$i<intval($day_diff);$i++){
                $data[]=0;
              }
            // $member->pl_lead=platoonleading($member->character_id);
            foreach (( array ) $member->day as $key => $val) {
                //$labels [] = $label;
                $data [] = number_format((($val * 1) / 60) / 60, 2);
                $count++;
              //  $label = date("m-d-Y", strtotime("- $count days", $member->last_save));
            }
            $member->day->chart = new stdClass ();
            $member->day->chart->labels = $labels;
            $member->day->chart->data = array();
            $member->day->chart->data [] = $data;

            $labels = array();
            $data = array();
            $count = 0;
            // $cur_date= new DateTime("now");
            // $save_date=new DateTime();
            //$save_date->setTimestamp(strtotime($member->last_save_date));
            $date_diff=$this->s_datediff("m","now",$member->last_save_date);
            $cur_month=intval(date("n"));
            for($i=$cur_month+1;$i<13;$i++){
              $labels[]=date('F', mktime(0, 0, 0, $i, 10));
            }
            for($i=1;$i<$cur_month+1;$i++){
              $labels[]=date('F', mktime(0, 0, 0, $i, 10));
            }
            $stat_start=intval(date("n",strtotime($member->last_save_date)));
            $member->date_diff=$date_diff;
            for($i=0;$i<intval($date_diff);$i++){
              $data[]=0;
            }
            foreach (( array ) $member->month as $key => $val) {

              //  $labels [] = date("F", strtotime("- $count months", $member->last_save));
                $data [] = number_format((($val * 1) / 60) / 60, 2);
                $count++;
            }

            $member->month->chart = new stdClass ();
            $member->month->chart->labels = array_reverse($labels);
            $member->month->chart->data = array();
            $member->month->chart->data [] = $data;

            return $member;
        }
        return null;
    }

    public function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false){

           if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
           if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

           $diff = date_diff( $dt_menor, $dt_maior, ! $relative);

           switch( $str_interval){
               case "y":
                   $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
               case "m":
                   $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
                   break;
               case "d":
                   $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
                   break;
               case "h":
                   $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
                   break;
               case "i":
                   $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
                   break;
               case "s":
                   $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
                   break;
              }
           if( $diff->invert)
                   return -1 * $total;
           else    return $total;
       }
    public function platoonleading($char_id, $days = 30)
    {
        $leading = new stdClass ();
        $data = array();
        $leading->avg = 0;
        $leading->total = 0;
        $leading->data = array();
        $leading->labels = array();
        $timestamp = strtotime("-" . $days . " days");

        $url = "/characters_event/?after=" . $timestamp . "&character_id=" . $char_id . "&c:limit=10000&type=ACHIEVMENT&c:join=type:achievement^inject_at:achievement^outer:0^show:name'achievement_id^terms:achievement_id=90041";
        $achievements = $this->query($url);
        $pl = array();
        if (!empty ($achievements->characters_event_list)) {
            foreach ($achievements->characters_event_list as $event) {
                // print_r($event);
                if (!empty ($event->achievement_id) && $event->achievement_id == "90041") {
                    if (empty ($pl [date('m-d-Y', $event->timestamp)])) {
                        $pl [date('m-d-Y', $event->timestamp)] = 0;
                    }
                    $pl [date('m-d-Y', $event->timestamp)] += 1;
                    $leading->total++;
                }
                if (!empty ($event->achievement_id) && $event->achievement_id == "90042") {
                    if (empty ($pl [date('m-d-Y', $event->timestamp)])) {
                        $pl [date('m-d-Y', $event->timestamp)] = 0;
                    }
                    $pl [date('m-d-Y', $event->timestamp)] += 2;
                    $leading->total += 2;
                }
            }
        }
        if (count($pl) > 0) {
            $leading->avg = $leading->total / count($pl);
        }
        $day = strtotime("now");
        $count = 1;

        while (date('m-d-Y', $day) != date('m-d-Y', $timestamp)) {
            if (empty ($pl [date('m-d-Y', $day)])) {
                $pl [date('m-d-Y', $day)] = 0;
            }
            $day = strtotime("-" . $count . " days");
            $count++;
        }
        krsort($pl);
        foreach ($pl as $key => $val) {
            $leading->labels [] = $key;
            $data [] = $val;
        }
        $leading->data [] = $data;
        return $leading;

    }

    public function squadleading($char_id, $days = 30)
    {
        $leading = new stdClass ();
        $data = array();
        $leading->avg = 0;
        $leading->total = 0;
        $leading->data = array();
        $leading->labels = array();
        $timestamp = strtotime("-" . $days . " days");

        $url = "/characters_event/?after=" . $timestamp . "&character_id=" . $char_id . "&c:limit=10000&type=ACHIEVMENT&c:join=type:achievement^inject_at:achievement^outer:0^show:name'achievement_id^terms:achievement_id=90041";
        $achievements = $this->query($url);
        $sl = array();
        if (!empty ($achievements->characters_event_list)) {
            foreach ($achievements->characters_event_list as $event) {
                // print_r($event);
                if (!empty ($event->achievement_id) && $event->achievement_id == "90040") {
                    if (empty ($sl [date('m-d-Y', $event->timestamp)])) {
                        $sl [date('m-d-Y', $event->timestamp)] = 0;
                    }
                    $sl [date('m-d-Y', $event->timestamp)] += 1;
                    $leading->total++;
                }
                if (!empty ($event->achievement_id) && ($event->achievement_id == "90039" || $event->achievement_id = "90031")) {
                    if (empty ($sl [date('m-d-Y', $event->timestamp)])) {
                        $sl [date('m-d-Y', $event->timestamp)] = 0;
                    }
                    $sl [date('m-d-Y', $event->timestamp)] += 1;
                    $leading->total++;
                }
            }
        }
        if (count($sl) > 0) {
            $leading->avg = $leading->total / count($sl);
        }
        $day = strtotime("now");
        $count = 1;

        while (date('m-d-Y', $day) != date('m-d-Y', $timestamp)) {
            if (empty ($sl [date('m-d-Y', $day)])) {
                $sl [date('m-d-Y', $day)] = 0;
            }
            $day = strtotime("-" . $count . " days");
            $count++;
        }
        krsort($sl);
        foreach ($sl as $key => $val) {
            $leading->labels [] = $key;
            $data [] = $val;
        }
        $leading->data [] = $data;
        return $leading;

    }
}

?>
