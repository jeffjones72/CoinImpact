<?php
class ActionO extends CI_Model {
    public static function attackCombatant(PlayerCombatant $p_combatant, 
            $damage, $health) {
        $db = get_instance()->db;
        $db->set('player_id', $p_combatant->player_place->player_id);
        $db->set('place_id', $p_combatant->place_id);
        $db->set('combatant_id', $p_combatant->id);
//        if($p_combatant->isDead()) {
//            $progress = (($p_combatant->player_place->progress + $p_combatant->combatant->place_progress) < 100 ? 
//                    ($p_combatant->player_place->progress + $p_combatant->combatant->place_progress) : 100);
//            $db->set('progress', $progress);
//            $db->set('fatal_hit', true);
//            $db->set('credit', $p_combatant->combatant->credit_reward);
//        }
        $db->set('damage', $damage);
        $db->set('health', $health);
        $db->insert('actions');
    }
}
?>