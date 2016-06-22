<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bosses extends MY_Controller {

    public $valid = false;

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $player = newPlayer();
//         echo "<pre>";
//         var_dump($player);die();
         if($this->input->post('active_id')) {
            $this->makeCurrentTarget($player, new PlayerBoss($this->input->post('active_id')));
            redirect('/bosses#current-target');
        }
        if(sizeof($_GET)) {
            $this->joinFight($data);
        }
        $current_boss = $player->getCurrentBoss();
        $p_b_combatant = null;
        if($current_boss) {
            $p_b_combatant = $current_boss->getPlayerBossCombatant();
            $data['p_b_combatant'] = $p_b_combatant;
        }
        if($this->input->post('collect_item_id') || $this->input->post('collect_thing_id')) {
            $this->collectItem($p_b_combatant);
        }
        if($this->input->post('attack') && $player->canAttack() && !$current_boss->isDead()) {
            $this->attackBoss($data, $player, $current_boss);
        }
        if($this->input->post('discard_id')) {
            $this->handleDiscard($p_b_combatant);
        }
        $user_id = $this->session->userdata('id');
        $this->loadGlobals($this, $data);
        $this->load->model('accounts_model');
        if($this->input->post('locate_id')) {
            $player->trySummon(new Boss($this->input->post('locate_id')));
        }

        $account = $this->accounts_model->get_account_info($user_id);

        $this->load->model('players_model');
        $player_a = $this->players_model->get_player_info($user_id);
        $player_id = $player_a['player_id'];
        $this->load->model('items_model');
        $data['items'] = $this->items_model->get_items($player_id);

        $player_places = $this->players_model->get_player_places($player_id);
        $header ['page_title'] = 'Bosses';
        $header['data'] = $this->get_counters();

        $data['locate_bosses'] = $player->getAvailableBosses();

        if($current_boss) {
            $data['combat_log'] = $current_boss->getLog();
        }
        $data['search_bosses'] = $player->searchBosses();
        $data['active_bosses'] = $player->getActiveBosses();
        $data['current_target'] = $current_boss;
        $data['respawn_times'] = $player->getRespawnTimes();
        $data['collect'] = $player->getCollectBosses();

        $data['account'] = $account;
        //$data['player'] = $player_a;
        $data['player_places'] = $player_places;

        $data['content'] = $this->load->view('bosses', $data, TRUE);

        $this->load->view('header', $header);
        $this->load->view('base', $data);
        $this->load->view('footer', $data);
    }
    
    
    private function collectItem($p_b_combatant) {
        if(!$p_b_combatant) {
            return false;
        }
        $collect = null;
        if($this->input->post('collect_item_id')) {
            $collect = new PlayerBossItem($this->input->post('collect_item_id'));
        }
        if($this->input->post('collect_thing_id')) {
            $collect = new PlayerBossThing($this->input->post('collect_thing_id'));
        }
        if(!$collect->isValid()) {
            return false;
        }
        $ret = $p_b_combatant->tryCollect($collect);
        if($ret === 'inventory_full') {
            $p_b_combatant->player->showGameError('Your inventory is full.');
        }
        return true;
    }
    private function handleDiscard($p_b_combatant) {
        $id = $this->input->post('discard_id');
        $type = $this->input->post('discard_type');
        if(!is_numeric($id) || !in_array($type, array('thing', 'item'))) {
            return false;
        }
        $p_b_item = null;
        if($type == 'item') {
            $p_b_item = new PlayerBossItem($id);
        } else {
            $p_b_item = new PlayerBossThing($id);
        }
        if(!$p_b_item->isValid()) {
            return false;
        }
        $p_b_combatant->discard($p_b_item);
        return true;
    }
    public function check_isvalidated() {
        parent::check_isvalidated();
    }
    private function makeCurrentTarget($player, PlayerBoss $boss) {
        $player->tryAttack($boss);
    }
    private function attackBoss(&$data, $player, PlayerBoss $boss) {
        if(!$player->canAttack()) {
            return false;
        }
        $stamina = $this->input->post('attack');
        if($player->stamina < $stamina) {
            $player->showGameError('Not enough stamina');
            return false;
        }
        if(!in_array($stamina, array('1', '5', '20'))) {
            return false;
        }
        $dmg_to_boss = $player->attackPlayerBoss($boss, $stamina);
        $dmg_to_player = 0;
        if($boss->isDead()) {

        } else {
            $dmg_to_player = $boss->hit($player);
        }
        $boss->log($dmg_to_player, $dmg_to_boss, $player);
        return true;
    }
    private function joinFight(&$data) {
        $share_id = $this->input->get('share_id');
        $boss_name = $this->input->get('name');
        $level = $this->input->get('level');

        $player = newPlayer();
        $result = $player->tryJoinBossFight($share_id, $boss_name, $level);
        if(!$result) {
            $player->showGameError('Invalid link');
        } else if($result === 'already_fightning') {
            $player->showGameError('You are already fighting that boss.');
        } else if($result === 'raid_full') {
            $player->showGameError('This raid is full.');
        } else if($result instanceof PlayerBoss) {
            $p_boss = $result;
            $player->showGameInfo('You now have '.$p_boss->player->account->username.'\'s '.$p_boss->boss->name.' as active boss');
        }
        redirect('bosses');
    }
}

/* End of file bosses.php */
/* Location: ./application/controllers/bosses.php */