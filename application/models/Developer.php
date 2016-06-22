<?php
class Developer extends Player {
    public function getAvailableBosses() {
        $CI = & get_instance();
        $db = $CI->db;
        $db->select('*');
        $db->from('bosses');
        
        $query = $db->get();
        $arrs = $query->result_array();
        
        $bosses = array();
        foreach($arrs as $boss) {
            $bosses[] = new Boss($boss);
        }
        return $bosses;
    }
    public function getRespawnTimes() {
        $bosses = $this->getAvailableBosses();
        $respawn_times = array();
                
        for($i=0;$i<sizeof($bosses);++$i) {
            $respawn_times[] = 0;
        }
        
        return $respawn_times;
    }
    public function canSummon(Boss $boss) {
        // outlaw guards doesn't have respawn time but you can have only one summoned at a time
        if($boss->id == Boss::$ids['outlaw_guards'] && $this->hasBossActive($boss)) {
            return false;
        }
        return true;
    }
}
?>