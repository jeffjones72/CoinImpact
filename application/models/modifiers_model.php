<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modifiers_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_modifiers($player_id = 0) {
        $this->db->select('m.*, pm.id as modifier_id, m.name as modifier_name, m.description as modifier_description,pm.collected,pm.item_id');
        $this->db->from('modifiers m');
        $this->db->join('player_modifiers pm', 'm.id = pm.modifier_id and player_id = ' . $player_id, 'LEFT OUTER', FALSE);
        if ($player_id > 0) {
            $this->db->where('pm.id is not null');
        }
        $this->db->order_by('m.name', 'asc');
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            $unassigned = 0;
            foreach ($query->result_array() as $row) {
                $data[] = $row;
                if (!$row['item_id'] && $row['modifier_id'] && $player_id > 0) {
                    $data['unassigned'][] = $row;

                    $this->db->select('i.name, i.id, pi.id as player_item_id');
                    $this->db->from('player_items pi');
                    $this->db->join('items i', 'pi.item_id = i.id and pi.player_id = ' . $player_id, FALSE);
                    $this->db->join('modifiers_sections_whitelist msw', 'i.section_id = msw.itemsection_id');
                    $this->db->where('msw.modifier_id', $row['id']);
                    $query = $this->db->get();

                    if ($query->num_rows > 0) {
                        foreach ($query->result_array() as $item) {
                            $data['unassigned'][$unassigned]['items'][] = $item;
                        }
                    }
                    $unassigned++;
                }
            }
            /*
              $this->db->where('player_id',$player_id);
              $this->db->where('item_id is null');
              $query = $this->db->get('player_modifiers');

              if ($query->num_rows > 0)
              {
              foreach($query->result_array() as $row)
              {
              $data['unassigned'] = $row;
              }
              }
             */
            return $data;
        }
    }

    function get_modifier_count() {
        $data = $this->db->count_all('modifiers');

        return $data;
    }

    function get_modifier($modifier_id) {
        $this->db->where('id', $modifier_id);
        $query = $this->db->get('modifiers');

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data = $row;
            }
        }

        return $data;
    }

    function insert($player_id, $modifier_id) {

        $date = date("Y-m-d H:i:s", time());

        $arr = array(
            'player_id' => $player_id,
            'modifier_id' => $modifier_id,
            'collected' => $date,
            'quality' => 1
        );

        $this->db->insert('player_modifiers', $arr);
    }

    function collect_boost($player_id, $modifier_id) {
        // Build data array to insert in to the player_modifers table.
        $data = array(
            'player_id' => $player_id,
            'modifier_id' => $modifier_id,
            'collected' => now()
        );

        /* 	TODO: Add this and two arguments to receive combatant_id and is_staff for 
         * 	modifiers and security.

          // Get additional data from the modifiers table.
          $this->db->select('has_quality');
          $query = $this->db->get_where('modifiers',$modifier_id, 1);

          foreach ($query->result_array() as $row
          {
          $data['quality'] = $row['has_quality'];
          }
         */

        $this->db->insert('player_modifiers', $data) or die(mysql_error());

        return true;
    }

}

/* End of file modifiers_model.php */
/* Location: ./application/models/modifiers_model.php */