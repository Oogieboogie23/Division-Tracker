<?php

class Census {

	private $base_url = "https://census.daybreakgames.com/s:zeroorder/get/ps2:v2";
	public function query($path) {
		return $this->makerequest ( $this->base_url . $path );
	}
	public function getUserByName($character_name) {
		$url = "/character?name.first=" . $character_name . "&c:case=false&c:limit=1000&c:join=type:characters_world^list:0^inject_at:world^terms:world_id=17^outer:0,type:outfit_member_extended^list:0^inject_at:outfit_member^terms:outfit_id=37530159341115681^outer:0,type:faction^inject_at:faction";
		$character = $this->query ( $url );
		if (! empty ( $character->character_list [0] )) {
			return $character->character_list [0];
		}
		return null;
	}
	public function playtime($char_id) {
		if (empty ( $char_id )) {
			return null;
		}
		$url = "/characters_stat_history?character_id=" . $char_id . "&stat_name=time";

		$ptime = $this->query ( $url );

		if (! empty ( $ptime->characters_stat_history_list [0] )) {
			$member = $ptime->characters_stat_history_list [0];
			$labels = array ();
			$data = array ();
			$count = 0;
			$label = date ( "m-d-Y", $member->last_save );
			// $member->pl_lead=platoonleading($member->character_id);
			foreach ( ( array ) $member->day as $key => $val ) {
				$labels [] = $label;
				$data [] = number_format ( (($val * 1) / 60) / 60, 2 );
				$count ++;
				$label = date ( "m-d-Y", strtotime ( "- $count days", $member->last_save ) );
			}
			$member->day->chart = new stdClass ();
			$member->day->chart->labels = $labels;
			$member->day->chart->data = array ();
			$member->day->chart->data [] = $data;
			$labels = array ();
			$data = array ();
			$count = 0;
			foreach ( ( array ) $member->month as $key => $val ) {

				$labels [] = date ( "F", strtotime ( "- $count months", $member->last_save ) );
				$data [] = number_format ( (($val * 1) / 60) / 60, 2 );
				$count ++;
			}
			$member->month->chart = new stdClass ();
			$member->month->chart->labels = $labels;
			$member->month->chart->data = array ();
			$member->month->chart->data [] = $data;
			return $member;
		}
		return null;
	}
	public function platoonleading($char_id, $days = 30) {
		$leading = new stdClass ();
		$data = array ();
		$leading->avg = 0;
		$leading->total = 0;
		$leading->data = array ();
		$leading->labels = array ();
		$timestamp = strtotime ( "-" . $days . " days" );

		$url = "/characters_event/?after=" . $timestamp . "&character_id=" . $char_id . "&c:limit=10000&type=ACHIEVMENT&c:join=type:achievement^inject_at:achievement^outer:0^show:name'achievement_id^terms:achievement_id=90041";
		$achievements = $this->query ( $url );
		$pl = array ();
		if (! empty ( $achievements->characters_event_list )) {
			foreach ( $achievements->characters_event_list as $event ) {
				// print_r($event);
				if (! empty ( $event->achievement_id ) && $event->achievement_id == "90041") {
					if (empty ( $pl [date ( 'm-d-Y', $event->timestamp )] )) {
						$pl [date ( 'm-d-Y', $event->timestamp )] = 0;
					}
					$pl [date ( 'm-d-Y', $event->timestamp )] += 1;
					$leading->total ++;
				}
				if (! empty ( $event->achievement_id ) && $event->achievement_id == "90042") {
					if (empty ( $pl [date ( 'm-d-Y', $event->timestamp )] )) {
						$pl [date ( 'm-d-Y', $event->timestamp )] = 0;
					}
					$pl [date ( 'm-d-Y', $event->timestamp )] += 2;
					$leading->total += 2;
				}
			}
		}
		if (count ( $pl ) > 0) {
			$leading->avg = $leading->total / count ( $pl );
		}
		$day = strtotime ( "now" );
		$count = 1;

		while ( date ( 'm-d-Y', $day ) != date ( 'm-d-Y', $timestamp ) ) {
			if (empty ( $pl [date ( 'm-d-Y', $day )] )) {
				$pl [date ( 'm-d-Y', $day )] = 0;
			}
			$day = strtotime ( "-" . $count . " days" );
			$count ++;
		}
		krsort ( $pl );
		foreach ( $pl as $key => $val ) {
			$leading->labels [] = $key;
			$data [] = $val;
		}
		$leading->data [] = $data;
		return $leading;

	}
	public function squadleading($char_id, $days = 30) {
		$leading = new stdClass ();
		$data = array ();
		$leading->avg = 0;
		$leading->total = 0;
		$leading->data = array ();
		$leading->labels = array ();
		$timestamp = strtotime ( "-" . $days . " days" );

		$url = "/characters_event/?after=" . $timestamp . "&character_id=" . $char_id . "&c:limit=10000&type=ACHIEVMENT&c:join=type:achievement^inject_at:achievement^outer:0^show:name'achievement_id^terms:achievement_id=90041";
		$achievements = $this->query ( $url );
		$sl = array ();
		if (! empty ( $achievements->characters_event_list )) {
			foreach ( $achievements->characters_event_list as $event ) {
				// print_r($event);
				if (! empty ( $event->achievement_id ) && $event->achievement_id == "90040") {
					if (empty ( $sl [date ( 'm-d-Y', $event->timestamp )] )) {
						$sl [date ( 'm-d-Y', $event->timestamp )] = 0;
					}
					$sl [date ( 'm-d-Y', $event->timestamp )] += 1;
					$leading->total ++;
				}
				if (! empty ( $event->achievement_id ) && ($event->achievement_id == "90039" || $event->achievement_id = "90031")) {
					if (empty ( $sl [date ( 'm-d-Y', $event->timestamp )] )) {
						$sl [date ( 'm-d-Y', $event->timestamp )] = 0;
					}
					$sl [date ( 'm-d-Y', $event->timestamp )] += 1;
					$leading->total ++;
				}
			}
		}
		if (count ( $sl ) > 0) {
			$leading->avg = $leading->total / count ( $sl );
		}
		$day = strtotime ( "now" );
		$count = 1;

		while ( date ( 'm-d-Y', $day ) != date ( 'm-d-Y', $timestamp ) ) {
			if (empty ( $sl [date ( 'm-d-Y', $day )] )) {
				$sl [date ( 'm-d-Y', $day )] = 0;
			}
			$day = strtotime ( "-" . $count . " days" );
			$count ++;
		}
		krsort ( $sl );
		foreach ( $sl as $key => $val ) {
			$leading->labels [] = $key;
			$data [] = $val;
		}
		$leading->data [] = $data;
		return $leading;

	}
	private function makerequest($url) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );

		$output = curl_exec ( $ch );
		curl_close ( $ch );
		return json_decode ( $output );
	}
}
?>
