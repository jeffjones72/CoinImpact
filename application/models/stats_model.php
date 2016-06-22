<?php
class stats_model extends CI_Model {
    
    public function get($player_id) {
        
        $sql = '
        SELECT p.attack as base_attack, 
         sum(coalesce(pi.mod_atk,0)) + SUM(coalesce(i.attack,0)) AS delta_attack,
         p.defense as base_defense,
         SUM(coalesce(pi.mod_def,0)) + SUM(coalesce(i.defense,0)) AS delta_defense, 
         coalesce(p.energy,0) as energy, 
         p.energy_limit,
         SUM(coalesce(i.energy,0)) AS delta_energy_limit,
         coalesce(p.stamina,0) as stamina, 
         p.stamina_limit,
         SUM(coalesce(i.stamina,0)) AS delta_stamina_limit,
         coalesce(p.health,0) as health,
         p.health_limit,
         SUM(coalesce(i.health,0)) AS delta_health_limit, 
         coalesce(p.strike,0) + coalesce(p.strike_boost,0) as base_strike,
         SUM(coalesce(i.strike,0)) + SUM(coalesce(i.strike_boost,0)) AS delta_strike, 
         coalesce(p.damage_boost,0) + SUM(coalesce(i.damage_boost,0)) AS damage_boost, 
         sum(coalesce(i.damage_boost,0)) as delta_damage_boost,
         coalesce(p.luck,0) as luck,
         SUM(coalesce(i.luck,0)) AS delta_luck, 
         coalesce(p.dodge,0) as dodge,
         SUM(coalesce(i.dodge,0)) AS delta_dodge
        FROM players p
        join player_items pi on p.id = pi.player_id
        JOIN items i ON pi.item_id = i.id AND pi.slot_id IS NOT NULL
        WHERE p.id = ?';

        $base = $this->db->query($sql, array($player_id));
        
        $skill_points = $this->db->get_where('spent_skills', array('player_id' => $player_id));
        
        $stats = $base->first_row();
        
        foreach($skill_points->result() as $skill) {
       
            switch ($skill->stat){
                case "attack":
                    $stats->base_attack += $skill->count;
                    break;
                case "defense":
                    $stats->base_defense += $skill->count;
                    break;
                case "energy_limit":
                    $stats->energy_limit += $skill->count;
                    break;
                case "stamina_limit":
                    $stats->stamina_limit += $skill->count;
                    break;
                case "health_limit":
                    $stats->health_limit += $skill->count;
                    break;
                }
            }       
        
        return $stats;
        
    }
    
}
?>