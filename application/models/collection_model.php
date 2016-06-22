<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Collection_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function check_collections($player_id) {
        $this->db->select('t.partial_item_id, i.name');
        $this->db->from('things t');
        $this->db->join('items i', 't.partial_item_id = i.id');
        $this->db->where('partial_item_id is not null', NULL, false);
        $this->db->distinct();
        $collection_query = $this->db->get();

        if ($collection_query->num_rows > 0) {
            foreach ($collection_query->result_array() as $row) {
                $this->db->select('t.id, t.name, t.description, t.partial_item_id, pt.id as player_thing_id, pt.thing_id');
                $this->db->from('things t');
                $this->db->join('player_things pt', 't.id = pt.thing_id and pt.player_id = ' . $player_id, 'left outer');
                $this->db->where('t.partial_item_id', $row['partial_item_id']);
                $this->db->distinct();
                $player_collection_query = $this->db->get();

                if ($player_collection_query->num_rows > 0) {
                    $collection_count = 0;
                    foreach ($player_collection_query->result_array() as $record) {
                        $player_thing_items[$row['partial_item_id']]['thing'][$record['id']] = $record;
                        if ($record['thing_id'])
                            $collection_count++;
                    }
                    if ($collection_count == $player_collection_query->num_rows)
                        $player_thing_items[$row['partial_item_id']]['complete'] = 'yes';
                    else
                        $player_thing_items[$row['partial_item_id']]['complete'] = 'no';

                    $player_thing_items[$row['partial_item_id']]['name'] = $row['name'];
                    $player_thing_items[$row['partial_item_id']]['item_id'] = $row['partial_item_id'];
                }
            }
        }
        return $player_thing_items;
    }

    public function assemble($player_id, $item_id) {
        $this->db->where('partial_item_id', $item_id);
        $this->db->from('things');
        $thing_count = $this->db->count_all_results();
        $this->db->flush_cache();

        $this->db->select('pt.id as player_thing_id, pt.thing_id');
        $this->db->from('player_things pt');
        $this->db->join('things t', 't.id = pt.thing_id');
        $this->db->where('pt.player_id', $player_id);
        $this->db->where('t.partial_item_id', $item_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $thing) {
                $collection_things[$thing['thing_id']] = $thing;
            }
        }

        $success = FALSE;

        if ($thing_count == sizeof($collection_things)) {
            $success = TRUE;
            foreach ($collection_things as $row) {
                $this->db->delete('player_things', array('id' => $row['player_thing_id']));
            }

            $date = date("Y-m-d H:i:s", time());
            $repo = Repo::getInstance();
            //$player = $repo->getByID('Player', $player_id);
            //$item = $repo->getByID('PlayerItem', $item_id);
            //$success = $player->tryAdd($item);
            //$repo->finish();
            $item = array(
                'player_id' => $player_id,
                'item_id' => $item_id,
                'collected' => $date,
                'quality' => 1,
                'durability' => 100
            );

            $this->db->insert('player_items', $item);
        }

        return true;
    }

}
