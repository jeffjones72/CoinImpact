<?php
class ActionO extends CI_Model {
    public static function attackCombatant(PlayerCombatant $p_combatant, 
            $damage, $health) {
        $db = get_instance()->db;

        $date = date("N", strtotime('now'));

        $db->select('sum(damage) as cumulative_damage, sum(health) as cumulative_health,stamina');
        $db->from('actions');
        $db->where('combatant_id',$p_combatant->id);
        $query = $db->get();

        if($query !== false){
            $result = $query->result()[0];
            $cumulative_damage = $result->cumulative_damage + $damage;
            $cumulative_health = $result->cumulative_health + $health;
            $stamina = $result->stamina+1;
        } else {
            $cumulative_damage = $damage;
            $cumulative_health = $health;
            $stamina = 1;
        }

        $db->set('date',$date);
        $db->set('player_id', $p_combatant->player_place->player_id);
        $db->set('place_id', $p_combatant->place_id);
        $db->set('combatant_id', $p_combatant->id);
       if($p_combatant->isDead()) {
           $progress = (($p_combatant->player_place->progress + $p_combatant->combatant->place_progress) < 100 ? 
                   ($p_combatant->player_place->progress + $p_combatant->combatant->place_progress) : 100);
           $db->set('progress', $progress);
           $db->set('fatal_hit', 1);
           $db->set('credit', $p_combatant->combatant->credit_reward);
           $db->set('experience', $p_combatant->combatant->experience_reward);
       }
        $db->set('damage', $damage);
        $db->set('cumulative_damage',$cumulative_damage);
        $db->set('health', $health);
        $db->set('cumulative_health',$cumulative_health);
        $db->set('stamina',$stamina);
        $db->insert('actions');
    }
}
?>