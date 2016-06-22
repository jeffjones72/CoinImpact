<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Explore extends MY_Controller {
    private $player = null;

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
        $this->output->enable_profiler(TRUE);
    }

    public function index() {
        $lang = Lang::getInstance();
        $this->player = $player = newPlayer();
        if($this->input->post('action') === 'explore') {
            $this->handleExplore();
        }

        if($this->input->post('flee_combatant_id')) {
            $p_combatant = new PlayerCombatant($this->input->post('flee_combatant_id'));
            $player->flee($p_combatant);
        }
        if($this->input->post('player_combatant_item_id')) {
            
            $result = $this->handleCollectItem($this->input->post('player_combatant_item_id'));
            
            if(is_string($result)) {
                $player->showGameError($lang['collect_item_error_'.$result]);
            }
        }
        if($this->input->post('player_combatant_thing_id')) {
            $this->handleCollectThing($this->input->post('player_combatant_thing_id'));
        }
        if($player->isAtBase()) {

            redirect('base');
        }
        $user_id = $this->session->userdata('id');
        $this->loadGlobals($this, $data);
        $this->load->model('accounts_model');
        $account = $this->accounts_model->get_account_info($user_id);

        $this->load->model('players_model');

        //$player = $this->players_model->get_player_info($user_id);


        $player_id = $player->id;

        $data['inventory_count'] = $player->getInventoryItemsCount();
        $data['inventory_capacity'] = $player->getInventoryCapacity();

        $this->load->model('items_model');
        $data['items'] = $this->items_model->get_items($player_id);

        $place_id = $player->place_id;
        $p_combatant = $player->getCombatant();
        $data['p_combatant'] = $p_combatant;
        $data['has_trader'] = $this->players_model->has_trader($player_id);
        if ($data['has_trader']['hasTrader']) {
            $data['trader'] = $data['has_trader']['trader'];
        }
        $data['hasTrader'] = $data['has_trader']['hasTrader'];

        $player = newPlayer();
        $events = $player->getUncompletedEvents();
        foreach($events as $p_event) {
            if($p_event->event->damage) {
                $player->recieveDamage($p_event->event->damage);
                $data['p_event'] = $p_event;
                $p_event->setCompleted();
            }
        }

        $data['has_boss'] = $this->players_model->has_boss($player_id);
        $data['boss'] = $data['has_boss'];
        $data['has_boss'] = $data['has_boss']['has_boss'];

        if($p_combatant) {
            $data['actions'] = $p_combatant->getActions();
        }

        if (!isset($data['p_event']) && !isset($data['p_combatant']) && $data['hasTrader'] == 0) {
            $this->load->model('missions_model');
            $data['missions'] = $this->missions_model->get_missions($player_id);
        }

        $data['percent'] = $this->session->flashdata("percent");
        $header ['page_title'] = 'Explore';
        $header['data'] = $this->get_counters();

        $data['account'] = $account;
        $data['player'] = $player;
        $data['output'] = $this->output->get_output();
        $data['content'] = $this->load->view('explore', $data, TRUE);
        $this->load->view('header', $header);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }
    private function handleExplore() {
        $result = $this->player->getExploration()->explore();
        $lang = Lang::getInstance();
        if(is_string($result)) {
            $this->player->showGameError($lang['error_'.$result]);
        }
    }
    private function handleCollectItem($item_id) {
        $p_c_item = new PlayerCombatantItem($item_id);
//         echo "testpo $item_id <br>";
//         var_dump($p_c_item);die("pCombatItem new");
        return $this->player->tryAdd($p_c_item);
    }
    private function handleCollectThing($thing_id) {
        $p_c_thing = new PlayerCombatantThing($thing_id);
        return $this->player->tryAdd($p_c_thing);
    }
}

/* End of file explore.php */
/* Location: ./application/controllers/explore.php */
