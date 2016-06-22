<?php
function format($duration) {
    $days = $duration/(24*60*60);
    $left = $duration%(24*60*60);
    $hours = $left/(60*60);
    $left = $left%(60*60);
    $minutes = (int)($left / 60);
    $seconds = (int)($left % 60);
    if(strlen($minutes) == 1) {
        $minutes .= 0;
    }
    if(strlen($seconds) == 1) {
        $seconds .= 0;
    }
    $str = $minutes . ":" . $seconds;
    if($hours) {
        $str = (int)$hours.":".$str;
    }
    if($days) {
        $str = (int)$days." day(s) ".$str;
    }
    return $str;
}
function handleExpiredIsects() {
    $p_bosses = PlayerBoss::getAllUnhandledInsects();
    foreach($p_bosses as $p_boss) {
        $p_boss->generateLoot();
    }
}
function roll($sides, $start = 1) {
    return mt_rand($start, $sides);
}
function rollf($max, $min=0) {
    return ($min+lcg_value()*(abs($max-$min)));
}
function rarity_roll() {
    $rarity = rollf(.60+.22+.14+.0325+.0075);
    $rarity_id = 1;
    if ($rarity >= .60+.22+.14+.0325) {
        $rarity_id = 5;
    } elseif ($rarity >= .60+.22+.14) {
        $rarity_id = 4;
    } elseif ($rarity >= .60+.22) {
        $rarity_id = 3;
    } elseif ($rarity >= .60) {
        $rarity_id = 2;
    } else {
        $rarity_id = 1;
    }
    return $rarity_id;
}
function quality_roll() {
    $quality = 0;
    $roll = roll(100);
    if ($roll < 16)
        $quality = 1;
    elseif ($roll < 31)
        $quality = 2;
    elseif ($roll < 81)
        $quality = 3;
    elseif ($roll < 95)
        $quality = 4;
    else
        $quality = 5;
    return $quality;
}
function getTableFromEntityName($name) {
    if($name === 'boss') {
        return 'bosses';
    }
    if($name === 'player_boss') {
        return 'player_bosses';
    }
    return $name.'s';
}
function getTableFromClass($class) {
    if($class == "Boss") {
        return "bosses";
    }
    if($class == "PlayerBoss") {
        return 'player_bosses';
    }
    if($class == 'AccountO') {
        return 'accounts';
    }

    $table_name = '';

    for($i=0;$i<strlen($class);++$i) {
        if(ctype_upper($class[$i])) {
            if($i != 0) {
                $table_name .= "_";
            }
            $table_name .= strtolower($class[$i]);
        } else {
            $table_name .= $class[$i];
        }
    }

    $table_name .= 's';
    return $table_name;
}
function getClassFromEntityName($name) {
    if($name == 'account') {
        return 'AccountO';
    }
    $class_name = '';
    $uc_next = false;
    for($i=0;$i<strlen($name);++$i) {
        if($name[$i] == '_') {
            $uc_next = true;
            continue;
        }
        if($uc_next) {
            $uc_next = false;
            $class_name .= ucfirst($name[$i]);
            continue;
        }
        $class_name .= $name[$i];
    }
    return ucfirst($class_name);
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function newPlayer($data = null) {
    $repo = Repo::getInstance();
    return $repo->newPlayer($data);
}
function newAccount($data = null) {
    return new AccountO($data);
}
function generateActivationCode($id ){
    $key="dreamcode";
    $encrypt_code=md5(time().md5($id.$key));
    return $encrypt_code;
}
?>