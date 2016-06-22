<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Missions_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_missions($player_id) {
        $data = array();
        $this->db->select('pm.id as player_mission_id, date, started, completed, m.id as mission_id, name, 
			description, tabindex, duration, experience_reward, credit_reward, 
			skill_reward, minimum_items, unique_items, minimum_things, unique_things,
			minimum_combatants, unique_combatants, minimum_events, unique_events');
        $this->db->from('player_missions pm');
        $this->db->join('missions m', 'pm.mission_id = m.id');
        $this->db->where('pm.player_id', $player_id);
        $this->db->order_by('m.id', 'asc');
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            $mission_index = 0;
            foreach ($query->result_array() as $row) {
                $data[$mission_index] = $row;

                $data[$mission_index]['required_events_completed'] = 0;
                $data[$mission_index]['required_combatants_completed'] = 0;
                $data[$mission_index]['required_items_completed'] = 0;
                $data[$mission_index]['required_things_completed'] = 0;
                $data[$mission_index]['total_collected_count'] = 0;
                $data[$mission_index]['total_required_count'] = 0;
                $data[$mission_index]['completed_pct'] = 0;

                //Get Events if any
                $this->db->select('count(id) as event_count', FALSE);
                $this->db->where('mission_id', $row['mission_id']);
                $events = $this->db->get('missions_required_events');

                $tmp = $events->row_array();
                $data[$mission_index]['required_event_count'] = $tmp['event_count'];

                if ($data[$mission_index]['required_event_count'] > 0) {
                    $data[$mission_index]['total_required_count'] += $data[$mission_index]['required_event_count'];

                    //Get completed Events if any
                    $this->db->select('distinct mre.id', FALSE);
                    $this->db->from('missions_required_events mre');
                    $this->db->join('player_events pe', 'mre.event_id = pe.event_id');
                    $this->db->where('mre.mission_id', $row['mission_id']);
                    $completed_events = $this->db->get();

                    $data[$mission_index]['required_events_completed'] = $completed_events->num_rows;

                    $data[$mission_index]['total_collected_count'] += $completed_events->num_rows;
                }

                //Get Combatants if any
                $this->db->select('count(id) as combatant_count', FALSE);
                $this->db->where('mission_id', $row['mission_id']);
                $combatants = $this->db->get('missions_required_combatants');

                $tmp = $combatants->row_array();
                $data[$mission_index]['required_combatant_count'] = $tmp['combatant_count'];

                if ($data[$mission_index]['required_combatant_count'] > 0) {
                    $data[$mission_index]['total_required_count'] += $data[$mission_index]['required_combatant_count'];

                    //Get completed Combatants if any
                    $this->db->select('distinct mrc.id', FALSE);
                    $this->db->from('missions_required_combatants mrc');
                    $this->db->join('player_combatants pc', 'mrc.combatant_id = pc.combatant_id');
                    $this->db->where('mrc.mission_id', $row['mission_id']);
                    $completed_combatants = $this->db->get();

                    $data[$mission_index]['required_combatants_completed'] = $completed_combatants->num_rows;

                    $data[$mission_index]['total_collected_count'] += $completed_combatants->num_rows;
                }

                //Get Items if any
                $this->db->select('count(id) as item_count', FALSE);
                $this->db->where('mission_id', $row['mission_id']);
                $items = $this->db->get('missions_required_items');

                $tmp = $items->row_array();
                $data[$mission_index]['required_item_count'] = $tmp['item_count'];

                if ($data[$mission_index]['required_item_count'] > 0) {
                    $data[$mission_index]['total_required_count'] += $data[$mission_index]['required_item_count'];

                    //Get collected Items if any
                    $this->db->select('distinct mri.id', FALSE);
                    $this->db->from('missions_required_items mri');
                    $this->db->join('player_items pi', 'mri.item_id = pi.item_id');
                    $this->db->where('mri.mission_id', $row['mission_id']);
                    $completed_items = $this->db->get();

                    $data[$mission_index]['required_items_completed'] = $completed_items->num_rows;

                    $data[$mission_index]['total_collected_count'] += $completed_items->num_rows;
                }

                //Get Things if any
                $this->db->select('count(id) as thing_count', FALSE);
                $this->db->where('mission_id', $row['mission_id']);
                $things = $this->db->get('missions_required_things');

                $tmp = $things->row_array();
                $data[$mission_index]['required_thing_count'] = $tmp['thing_count'];

                if ($data[$mission_index]['required_thing_count'] > 0) {
                    $data[$mission_index]['total_required_count'] += $data[$mission_index]['required_thing_count'];

                    //Get completed Combatants if any
                    $this->db->select('distinct mrt.id', FALSE);
                    $this->db->from('missions_required_things mrt');
                    $this->db->join('player_things pt', 'mrt.thing_id = pt.thing_id');
                    $this->db->where('mrt.mission_id', $row['mission_id']);
                    $completed_things = $this->db->get();

                    $data[$mission_index]['required_things_completed'] = $completed_things->num_rows;

                    $data[$mission_index]['total_collected_count'] += $completed_things->num_rows;
                }

                $data[$mission_index]['completed_pct'] = round(($data[$mission_index]['total_collected_count'] / $data[$mission_index]['total_required_count']) * 100);
                $mission_index++;
            }

            //echo '<pre>';
            //print_r($data);
            //echo '</pre>';
        }

        return $data;
    }

}

/* End of file missions_model.php */
/* Location: ./application/models/missions_model.php */