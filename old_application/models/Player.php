<?php

class Player extends CI_Model
{

    public $place_id;

    public $account = null;

    public $items = null;

    public $equipment = null;

    public $missions = null;

    public $places = null;

    public $p_place = null;

    public $boosts = null;

    public $things = null;

    public $rank = null;

    public $refill_coeff = 1;

    private $exploration = null;

    private $trainingDummy = null;

    const STARTING_STAMINA = 20;

    const STARTING_HEALTH = 100;

    const STARTING_ENERGY = 20;

    const STARTING_ATTACK = 10;

    const STARTING_DEFENSE = 10;

    const STARTING_DAMAGE_BOOST = 0;

    const STARTING_STRIKE = 0;

    const STARTING_STRIKE_BOOST = 0;

    const STARTING_LUCK = 0;

    const STARTING_DODGE = 0;

    const STARTING_STORAGE_CAP = 15;

    const SKILL_POINTS_PER_LEVEL = 5;

    public static $default_inventory_capacity = 10;

    public function __construct($data = null)
    {
        parent::__construct($data);
        $this->account = new AccountO($this->account_id);
        $this->rank = new Rank($this->rank_id);
        if (! $this->isValid()) {
            throw new Exception('Player is invalid');
        }
        $this->db->select('place_id');
        $this->db->where('player_id', $this->id);
        $this->db->where('active', 1);
        $this->db->from('player_places');
        
        $query = $this->db->get();
        $row = $query->row_array();
        
        $this->place_id = $row['place_id'];
        $this->convertRefillToUT();
        $this->queryPlace();
        if ($this->isAtBase()) {
            $this->refill_coeff /= 2;
        }
        $this->applyBuffs();
        $this->exploration = new Exploration($this);
        $this->trainingDummy = new TrainingDummy($this);
    }

    public function getExploration()
    {
        return $this->exploration;
    }

    public function getTrainingDummy()
    {
        return $this->trainingDummy;
    }

    public function getEquipment()
    {
        if ($this->items === null) {
            $this->queryItems();
        }
        if ($this->equipment === null) {
            $this->equipment = new Equipment($this->items);
        }
        return $this->equipment;
    }

    public function getPlace(Place $place)
    {
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->where('place_id', $place->id);
        $this->db->from('player_places');
        
        $query = $this->db->get();
        if (! $query->num_rows) {
            throw new Exception('Player place not found for place id: ' . $place->id);
        }
        return new PlayerPlace($query->row_array());
    }

    public function getRequirementFor($obj)
    {
        if ($obj instanceof Place) {
            return $this->getRequirementForPlace($obj);
        }
        throw new Exception($message, $code, $previous);
    }

    public function getRequirementForPlace(Place $place)
    {
        $this->db->select('*');
        $this->db->where('id < ' . $place->id, null, false);
        $this->db->where('id NOT IN(' . Place::$ids['base'] . ', ' . Place::$ids['enemy_safehouse'] . ')', null, false);
        $this->db->from('places');
        $this->db->order_by('id', 'desc');
        
        $query = $this->db->get();
        if (! $query->num_rows) {
            return null;
        }
        $prev_place = new Place($query->row_array());
        if (! $this->hasCompleted($prev_place)) {
            return $prev_place;
        }
        $main_boss = $prev_place->getMainBoss();
        if ($main_boss && ! $this->hasKilled($main_boss)) {
            return $main_boss;
        }
        return null;
    }

    public function canGoTo(Place $place)
    {
        return $this->getRequirementFor($place) === null;
    }

    public function hasKilled(Boss $boss)
    {
        $this->db->select('*');
        $this->db->where('boss_id', $boss->id);
        $this->db->where('player_id', $this->id);
        $this->db->where('completed is not null');
        $this->db->from('player_bosses');
        
        $query = $this->db->get();
        return $query->num_rows != 0;
    }

    public function getStorageCap()
    {
        return $this->storage_cap;
    }

    public function getStoredItemsCount()
    {
        $this->db->select('count(*) as count');
        $this->db->where('player_item_id IN (SELECT id FROM player_items WHERE player_id="' . $this->id . '")');
        $this->db->from('storage');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['count'];
    }

    public static function getByAccountID($id)
    {
        $db = get_instance()->db;
        $db->select('*');
        $db->where('account_id', $id);
        $db->from('players');
        
        $query = $db->get();
        if ($query->num_rows == 0) {
            return null;
        }
        $result = $query->row_array();
        
        return new Player($result);
    }

    public function isAtBase()
    {
        return $this->p_place->place->id == Place::$ids['base'];
    }

    public function getNextLevelXP()
    {
        $level = new Level($this->level_id);
        $next_level = $level->getNextLevel();
        if (! $next_level) {
            return 0; // To-Do: what really to return?
        }
        return $next_level->experience;
    }

    public function getCurrentLevelXP()
    {
        $level = Level::getByID($this->level_id);
        return $level->experience;
    }

    public function getCollectBosses()
    {
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->where('player_boss_id IN (SELECT id FROM player_bosses WHERE health=0)', null, false);
        $this->db->from('player_boss_combatants');
        
        $result = $this->db->get()->result_array();
        
        $p_b_combatants = array();
        foreach ($result as $arr) {
            $p_b_combatants[] = new PlayerBossCombatant($arr);
        }
        return $p_b_combatants;
    }

    public static function getUsernameByID($id)
    {
        $db = get_instance()->db;
        $account_id = Player::getAccountIDByID($id);
        $db->select('username');
        $db->where('id', $account_id);
        $db->from('accounts');
        
        $query = $db->get();
        $result = $query->row_array();
        
        if (! $result) {
            return null;
        }
        
        return $result['username'];
    }

    public static function getAccountIDByID($id)
    {
        $db = get_instance()->db;
        $db->select('account_id');
        $db->where('id', $id);
        $db->from('players');
        
        $query = $db->get();
        $result = $query->row_array();
        
        if (! $result) {
            return null;
        }
        
        return $result['account_id'];
    }

    public static function getIDByAccountID($id)
    {
        $this->db->select('id');
        $this->db->where('account_id', $id);
        $this->db->from('players');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        if (! $result) {
            return null;
        }
        
        return $result['id'];
    }

    private function convertRefillToUT()
    { // Where UT is UNIX_TIMESTAMP
        $this->health_refill = strtotime($this->health_refill);
        $this->energy_refill = strtotime($this->energy_refill);
        $this->stamina_refill = strtotime($this->stamina_refill);
    }

    public function getRespawnTimes()
    {
        $bosses = $this->getAvailableBosses();
        $respawn_times = array();
        
        foreach ($bosses as $boss) {
            $respawn_times[] = $this->getRespawnTimeFor($boss);
        }
        
        return $respawn_times;
    }

    public function getRespawnTimeFor(Boss $boss)
    {
        $this->db->select('*');
        $this->db->from('player_bosses');
        $this->db->where('player_id', $this->id);
        $this->db->where('boss_id', $boss->id);
        $this->db->order_by('generated', 'desc');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        if (! $result) {
            return 0;
        }
        
        $player_boss = new PlayerBoss($result);
        
        $time = $player_boss->generated + $boss->next_summon;
        return $time;
    }

    public function getDowntimeFor(Boss $boss)
    {
        $d_time = $this->getRespawnTimeFor($boss) - time();
        if ($d_time < 0) {
            return 0;
        }
        return $d_time;
    }

    public function activate(Boss $boss)
    {
        $this->db->set('boss_id', $boss->id);
        $this->db->set('player_id', $this->id);
        $this->db->insert('player_available_bosses');
    }

    public function queryItems()
    {
        $this->items = array();
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->from('player_items');
        
        $query = $this->db->get();
        $result_arr = $query->result_array();
        
        foreach ($result_arr as $result) {
            $this->items[] = new PlayerItem($result);
        }
    }

    public function queryMissions()
    {
        $this->missions = array();
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->from('player_missions');
        
        $query = $this->db->get();
        $result_arr = $query->result_array();
        
        foreach ($result_arr as $result) {
            $this->missions[] = new PlayerMission($result);
        }
    }

    public function queryPlaces()
    {
        $repo = Repo::getInstance();
        $this->places = array();
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->from('player_places');
        
        $query = $this->db->get();
        $result_arr = $query->result_array();
        
        foreach ($result_arr as $result) {
            $this->places[] = new PlayerPlace($result);
        }
    }

    public function queryPlace()
    {
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->where('active', 1);
        $this->db->from('player_places');
        
        $query = $this->db->get();
        if ($query->num_rows == 0) {
            throw new Exception('No active player place');
        }
        $result = $query->row_array();
        $this->p_place = new PlayerPlace($result);
    }

    public function queryBoosts()
    {
        $this->boosts = array();
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->from('player_boosts');
        
        $query = $this->db->get();
        $result_arr = $query->result_array();
        
        foreach ($result_arr as $result) {
            $this->boosts[] = new PlayerBoost($result);
        }
    }

    function queryFriends()
    {
        $this->db->select('id');
        $this->db->where('player_id', $this->id);
        $query = $this->db->get('player_friends');
        
        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = new PlayerFriend($row['id']);
            }
            return $data;
        }
        return array();
    }

    public function queryThings()
    {
        $this->things = array();
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->from('player_things');
        
        $query = $this->db->get();
        $result_arr = $query->result_array();
        
        foreach ($result_arr as $result) {
            $this->things[] = new PlayerThing($result);
        }
    }

    public function has(Item $item)
    {
        if ($this->items === null) {
            $this->queryItems();
        }
        foreach ($this->items as $player_item) {
            if ($player_item->item_id == $item->id) {
                return true;
            }
        }
        return false;
    }

    public function hasCompletedMission(Mission $mission)
    {
        if ($this->missions == null) {
            $this->queryMissions();
        }
        foreach ($this->missions as $player_mission) {
            if ($player_mission->mission->id == $mission->id) {
                return true;
            }
        }
        return false;
    }

    public function hasCompletedPlace(Place $place)
    {
        $p_place = $this->getPlace($place);
        return $p_place->progress == 100;
    }

    public function hasCompleted($obj)
    {
        if ($obj instanceof Mission) {
            return $this->hasCompletedMission($obj);
        } else 
            if ($obj instanceof Place) {
                return $this->hasCompletedPlace($obj);
            }
        throw new Exception('Unsupported operation.');
    }

    public function getProgressFor(Place $place)
    {
        $this->db->select('progress');
        $this->db->where('player_id', $this->id);
        $this->db->where('place_id', $place->id);
        $this->db->from('player_places');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['progress'];
    }

    public function tryActivate(Boss $boss)
    {
        $requirements = $boss->getRequirements();
        foreach ($requirements as $requirement) {
            if ($requirement->is('item')) {
                $item = $requirement;
                if (! $this->has($item)) {
                    return;
                }
            } else 
                if ($requirement->is('mission')) {
                    $mission = $requirement;
                    if (! $this->hasCompleted($mission)) {
                        return;
                    }
                } else 
                    if ($requirement->is('place')) {
                        $place = $requirement;
                        if ($this->getProgressFor($place) < 100) {
                            return;
                        }
                    }
        }
        $this->activate($boss);
        if ($this->isActivated($boss)) {
            return;
        }
        $this->setActivated($boss);
        $this->showGameInfo('Congratulations! You have successfully unclocked <a href="/bosses">' . $boss->name . '</a>(Boss)');
    }

    private function setActivated(Boss $boss)
    {
        $this->db->set('player_id', $this->id);
        $this->db->set('boss_id', $boss->id);
        $this->db->insert('boss_messages');
    }

    public function isActivated(Boss $boss)
    {
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->where('boss_id', $boss->id);
        $this->db->from('boss_messages');
        
        $query = $this->db->get();
        if ($query->num_rows() === 0) {
            return false;
        }
        $query->row_array();
        return true;
    }

    public function isDeveloper()
    {
        return $this instanceof Developer;
    }

    public function recieveDamage($amount)
    {
        $this->health -= $amount;
        if ($this->health < 0) {
            $this->health = 0;
        }
        if ($this->isDeath()) {
            $base_place = new Place(Place::$ids['base']);
            if ($this->energy >= $base_place->energy) {
                // $this->takeEnergy($base_place->energy);
                $this->health = 1;
                $this->showGameInfo("You barely escape alive");
                $this->travelTo($base_place);
            }
        }
        $this->db->set('health', $this->health);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function takeStamina($amount)
    {
        $this->stamina -= $amount;
        if ($this->stamina < 0) {
            throw new Exception('Not enoughs stamina');
        }
        
        $this->db->set('stamina', $this->stamina);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function awardXP($xp)
    {
        $this->experience += $xp;
        
        $this->db->set('experience', $this->experience);
        $this->db->where('id', $this->id);
        $this->db->update('players');
        
        $level = Level::getByXP($this->experience);
        $this->setLevel($level);
    }

    public function awardCredit($credit)
    {
        $this->balance += $credit;
        
        $this->db->set('balance', $this->balance);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addSkillPoints($amount)
    {
        $this->db->set('skill', 'skill+' . $this->db->escape($amount), false);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addPCoins($p_coins)
    {
        $this->db->set('premium_balance', 'premium_balance+' . $this->db->escape($p_coins), false);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function setLevel(Level $level)
    {
        $level_ups = $level->id - $this->level_id;
        if ($level_ups > 0) {
            $p_coins = $this->getPCoinsForLevels($this->level_id, $level_ups);
            $this->addPCoins($p_coins);
            $this->showGameInfo('Congratulations! You reached level ' . $level->id);
            $this->fillHealth();
            $this->fillStamina();
            $this->fillEnergy();
            $this->addSkillPoints(Player::SKILL_POINTS_PER_LEVEL);
        }
        $this->level_id = $level->id;
        
        $this->db->set('level_id', $level->id);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function decreaseHealthLimit($amount)
    {
        $this->health_limit -= $amount;
        if ($this->health > $this->health_limit) {
            $this->health = $this->health_limit;
        }
        $this->db->set('health_limit', $this->health_limit);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function decreaseStaminaLimit($amount)
    {
        $this->stamina_limit -= $amount;
        if ($this->stamina > $this->stamina_limit) {
            $this->stamina = $this->stamina_limit;
        }
        $this->db->set('stamina', $this->stamina);
        $this->db->set('stamina_limit', $this->stamina_limit);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function decreaseEnergyLimit($amount)
    {
        $this->energy_limit -= $amount;
        if ($this->energy > $this->energy_limit) {
            $this->energy = $this->energy_limit;
        }
        $this->db->set('energy_limit', $this->energy_limit);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    /*
     * CODE:
     */
    public function decreaseHealth($amount)
    {
        $new_health = $this->health - $amount;
        if ($new_health <= 0) {
            $this->health = 0;
        } else {
            $this->health = $new_health;
        }
        $this->db->set('health', $this->health);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    /*
     * CODE:
     */
    public function decreaseEnergy($amount)
    {
        $energy = $this->energy - $amount;
        if ($energy <= 0) {
            $this->energy = 0;
        } else {
            $this->energy = $energy;
        }
        
        $this->db->set('energy', $this->energy);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    /*
     * COD:
     */
    public function decreaseStamina($amount)
    {
        $new_stamina = $this->stamina - $amount;
        if ($new_stamina <= 0) {
            $this->stamina = 0;
        } else {
            $this->stamina = $new_stamina;
        }
        
        $this->db->set('stamina', $this->stamina);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function decreaseAttack($amount)
    {
        $this->attack -= $amount;
        $this->db->set('attack', $this->attack);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function decreaseDefense($amount)
    {
        $this->defense -= $amount;
        $this->db->set('defense', $this->defense);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function decreaseStrike($amount)
    {
        $this->strike -= $amount;
        $this->db->set('strike', $this->strike);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function decreaseLuck($amount)
    {
        $this->luck -= $amount;
        $this->db->set('luck', $this->luck);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function decreaseDodge($amount)
    {
        $this->dodge -= $amount;
        $this->db->set('dodge', $this->dodge);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function decreaseStrikeBoost($amount)
    {
        $this->strike_boost -= $amount;
        $this->db->set('strike_boost', $this->strike_boost);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }
    // ToDo: Finish this
    private function setStat($stat, $amount)
    {
        $this->$stat = $amount;
        $this->db->set('stat', $amount);
    }

    public function decreaseDamageBoost($amount)
    {
        $this->strike_boost -= $amount;
        $this->db->set('damage_boost', $this->damage_boost);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToHealthLimit($amount)
    {
        $this->health_limit += $amount;
        $this->db->set('health_limit', $this->health_limit);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToStaminaLimit($amount)
    {
        $this->stamina_limit += $amount;
        $this->db->set('stamina_limit', $this->stamina_limit);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToEnergyLimit($amount)
    {
        $this->energy_limit += $amount;
        $this->db->set('energy_limit', $this->energy_limit);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    /*
     * COD:
     */
    public function addToHealth($amount)
    {
        $new_health = $this->health + $amount;
        if ($new_health >= $this->health_limit) {
            $this->health = $this->health_limit;
        } else {
            $this->health = $new_health;
        }
        $this->db->set('health', $this->health);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    /*
     * COD:
     */
    public function addToEnergy($amount)
    {
        $addEnergy = $this->energy + $amount;
        if ($addEnergy >= $this->energy_limit) {
            $this->energy = $this->energy_limit;
        } else {
            $this->energy = $addEnergy;
        }
        
        $this->db->set('energy', $this->energy);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    /*
     * COD:
     */
    public function addToStamina($amount)
    {
        $new_stamina = $this->stamina + $amount;
        if ($new_stamina >= $this->stamina_limit) {
            $this->stamina = $this->stamina_limit;
        } else {
            $this->stamina = $new_stamina;
        }
        
        $this->db->set('stamina', $this->stamina);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToAttack($amount)
    {
        $this->attack += $amount;
        $this->db->set('attack', $this->attack);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToDefense($amount)
    {
        $this->defense = (int) $this->defense + $amount;
        
        // add pi.mod_def
        $this->db->set('defense', $this->defense);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToStrike($amount)
    {
        $this->strike += $amount;
        $this->db->set('strike', $this->strike);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToLuck($amount)
    {
        $this->luck += $amount;
        $this->db->set('luck', $this->luck);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToDodge($amount)
    {
        $this->dodge += $amount;
        $this->db->set('dodge', $this->dodge);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToStrikeBoost($amount)
    {
        $this->strike_boost += $amount;
        $this->db->set('strike_boost', $this->strike_boost);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function addToDamageBoost($amount)
    {
        $this->strike_boost += $amount;
        $this->db->set('damage_boost', $this->damage_boost);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function fillHealth()
    {
        $this->db->set('health', 'health_limit', false);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function fillStamina()
    {
        $this->db->set('stamina', 'stamina_limit', false);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function fillEnergy()
    {
        $this->db->set('energy', 'energy_limit', false);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    private function getPCoinsForLevels($level, $level_ups)
    {
        $p_coins = $level % 2;
        $p_coins += ($level_ups - 1) / 2;
        return $p_coins;
    }

    public function tryTravelTo(Place $place)
    {
        if ($this->energy < $place->energy) {
            return 'not_enough_energy';
        }
        if ($this->isInCombat()) {
            return 'in_combat';
        }
        $this->travelTo($place);
        return true;
    }

    public function travelTo(Place $place)
    {
        $this->db->set('active', 0);
        $this->db->where('player_id', $this->id);
        $this->db->update('player_places');
        
        $this->db->select('id');
        $this->db->where('player_id', $this->id);
        $this->db->where('place_id', $place->id);
        $this->db->from('player_places');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->db->set('active', 1);
        $this->db->where('id', $result['id']);
        $this->db->update('player_places');
        
        $this->db->set('location_id', $result['id']);
        $this->db->where('id', $this->id);
        $this->db->update('players');
        
        $this->takeEnergy($place->energy);
    }

    public function canEngage(PlayerBoss $player_boss)
    {
        return true;
    }

    public function canExplore($obj = null)
    {
        if ($this->health < 10 || $this->isInCombat() || $this->hasEvent() || $this->hasTrader()) {
            return false;
        }
        if ($obj === null) {
            return true;
        }
        if ($obj instanceof Place && ! $this->canExplorePlace($obj)) {
            return false;
        }
        return true;
    }
    // ToDo check if completed previous places if needed
    public function canExplorePlace(Place $place)
    {
        if ($place->id == Place::$ids['base'] || $place->id == Place::$ids['enemy_safehouse']) {
            return false;
        }
        return true;
    }

    public function showGameInfo($info)
    {
        $this->session->set_userdata(array(
            "game_info" => $info
        ));
    }

    public function showGameError($error)
    {
        $this->session->set_userdata(array(
            "game_error" => $error
        ));
    }

    public function getGameInfo($unset = true)
    {
        $tmp = $this->session->userdata('game_info');
        if ($tmp && $unset) {
            $this->session->unset_userdata('game_info');
        }
        return $tmp;
    }

    public function getGameError($unset = true)
    {
        $tmp = $this->session->userdata('game_error');
        if ($tmp && $unset) {
            $this->session->unset_userdata('game_error');
        }
        return $tmp;
    }

    public function takeEnergy($amount)
    {
        $this->energy -= $amount;
        $this->db->set('energy', 'energy-' . (int) $amount, false);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function isDeath()
    {
        return $this->health <= 0;
    }

    public function applyBuffs()
    {
        if ($this->refill_coeff != 1) {
            foreach (array(
                "energy",
                "health",
                "stamina"
            ) as $stat) {
                if (time() > $this->{$stat . "_refill"}) {
                    $this->handleRefill();
                }
                $this->{$stat . '_refill'} = ($this->{$stat . "_refill"} - time()) * $this->refill_coeff + time();
                $this->{$stat . '_rate'} *= $this->refill_coeff;
            }
        }
    }

    public function store(PlayerItem $p_item)
    {
        if ($p_item->player_id != $this->id) {
            return false;
        }
        $this->db->query('REPLACE INTO storage VALUES (NULL, "' . ((int) $p_item->id) . '")');
        return true;
    }

    public function getFromStorage(PlayerItem $p_item)
    {
        if ($p_item->player_id != $this->id) {
            return false;
        }
        $this->db->where('player_item_id', $p_item->id);
        $this->db->delete('storage');
        return true;
    }

    public function handleRefill()
    {
        $changed_columns = array();
        foreach (array(
            "energy",
            "health",
            "stamina"
        ) as $stat) {
            if ($this->{$stat} == $this->{$stat . "_limit"}) {
                $changed_columns[] = $stat . "_refill";
                $this->{$stat . "_refill"} = time() + $this->{$stat . "_rate"};
            } else 
                if (time() > $this->{$stat . "_refill"}) { // If it is time to refill
                    if ($this->{$stat} < $this->{$stat . "_limit"}) {
                        $changed_columns[] = $stat;
                        $changed_columns[] = $stat . '_refill';
                        $this->{$stat} += 1 + (int) ((time() - $this->{$stat . "_refill"}) / $this->{$stat . "_rate"}); // refill
                        if ($this->{$stat} > $this->{$stat . "_limit"}) {
                            $this->{$stat} = $this->{$stat . "_limit"};
                        }
                        $this->{$stat . "_refill"} = time() + $this->{$stat . "_rate"} - ((int) (time() - $this->{$stat . "_refill"}) % $this->{$stat . "_rate"}); // and reset timer
                    }
                }
        }
        foreach ($changed_columns as $column) {
            if (substr($column, - strlen('refill')) == 'refill') {
                $this->db->set($column, date(Globals::MYSQL_DATE_FORMAT, $this->{$column}));
            } else {
                $this->db->set($column, $this->{$column});
            }
        }
        if (sizeof($changed_columns)) {
            $this->db->where('id', $this->id);
            $this->db->update('players');
        }
    }

    public function save()
    {
        // We convert them so they are saved properly
        $this->health_refill = date("Y-m-d H:i:s", $this->health_refill);
        $this->energy_refill = date("Y-m-d H:i:s", $this->energy_refill);
        $this->stamina_refill = date("Y-m-d H:i:s", $this->stamina_refill);
        parent::save();
        // And convert them back to normal
        $this->convertRefillToUT();
    }

    public function isInCombat()
    {
        $query = $this->db->query('SELECT COUNT(id) as count FROM `player_combatants` WHERE active=1 AND place_id IN (SELECT id FROM player_places WHERE player_id="' . $this->db->escape($this->id) . '")');
        $result = $query->row_array();
        
        if ($result['count']) {
            return true;
        }
        return false;
    }

    public function getAvailableBosses()
    {
        $CI = & get_instance();
        $db = $CI->db;
        $db->select('boss_id');
        $db->where('player_id', $this->id);
        $db->from('player_available_bosses');
        
        $query = $db->get();
        $arrs = $query->result_array();
        
        $bosses = array();
        foreach ($arrs as $boss) {
            $bosses[] = new Boss($boss['boss_id']);
        }
        return $bosses;
    }

    public function searchBosses()
    {
        $query = $this->db->query('SELECT * FROM `player_bosses` AS pb JOIN bosses AS b WHERE pb.player_id="' . $this->db->escape($this->id) . '" AND pb.generated+b.timeout>NOW()');
        
        $arrs = $query->result_array();
        $bosses = array();
        foreach ($arrs as $arr) {
            $bosses[] = new PlayerBoss($arr);
        }
        return $bosses;
    }

    public function getCurrentPlace()
    {
        $this->db->select('*');
        $this->db->from('player_places');
        $this->db->where('player_id', $this->id);
        $this->db->where('active', 1);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return new PlayerPlace($result);
    }

    public function tryAdd($obj, $slot_id = null)
    {
        if ($this->isInventoryFull() && ! ($obj instanceof PlayerCombatantThing) && ! ($obj instanceof PlayerThing)) {
            return 'full_inventory';
        }
        $result = null;
        $result_obj = null;
        if ($obj instanceof Item) {
            $result = $this->tryAddItem($obj);
            $result_obj = new PlayerItem($result);
            // $this->tryEquip($result_obj);
        } else 
            if ($obj instanceof PlayerCombatantItem) {
                $result = $this->tryAddCombatantItem($obj);
                $result_obj = new PlayerItem($result);
                // $this->tryEquip($result_obj);
            } else 
                if ($obj instanceof PlayerCombatantThing) {
                    $result = $this->tryAddCombatantThing($obj);
                    $result_obj = new PlayerThing($result);
                } else 
                    if ($obj instanceof Thing) {
                        $result = $this->tryAddThing($obj);
                        $result_obj = new PlayerThing($result);
                    } else 
                        if ($obj instanceof Boost) {
                            $result = $this->tryAddBoost($obj);
                        } else 
                            if ($obj instanceof Mission) {
                                $result = $this->tryAddMission($obj);
                            } else {
                                throw new Exception('Unsupported operation');
                            }
        if ($result_obj !== null) {
            if ($result_obj instanceof PlayerItem) {
                $this->items[] = $result_obj;
            }
            if (method_exists($result_obj, 'tryAddMissions')) {
                $result_obj->tryAddMissions();
            }
            if (method_exists($result_obj, 'tryActivateBosses')) {
                $result_obj->tryActivateBosses();
            }
        }
        return $result;
    }

    public function tryAddMission(Mission $mission)
    {
        $requirements = $mission->getRequirements();
    }

    public function hasPremiumCoin()
    {
        return $this->hasPremiumCoins(1);
    }

    public function hasPremiumCoins($amount)
    {
        return $this->premium_balance >= $amount;
    }

    public function takePremiumCoin()
    {
        $this->takePremiumCoins(1);
    }

    public function takePremiumCoins($amount)
    {
        $this->premium_balance -= $amount;
        
        $this->db->set('premium_balance', 'premium_balance-' . $this->db->escape($amount), false);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function tryAddBoost(Boost $boost)
    {
        $this->db->set('player_id', $this->id);
        $this->db->set('boost_id', $boost->id);
        $this->db->set('collected', 'NOW()', false);
        $this->db->insert('player_boosts');
    }

    public function tryAddThing(Thing $thing)
    {
        $this->db->set('thing_id', $thing->id);
        $this->db->set('player_id', $this->id);
        $this->db->set('collected', 'NOW()', false);
        $this->db->insert('player_things');
        $player_thing_id = $this->db->insert_id();
        
        return $player_thing_id;
    }

    public function tryEquip(PlayerItem $p_item)
    {
        $slot_id = $p_item->item->getSlot()->getId();
        // if(!$this->hasEquipSlotFree($slot_id)) {
        // log_message('debug', 'Trying to add item to not free slot id');
        // return false;
        // }
        if ($p_item->player_id != $this->id) {
            return false;
        }
        if ($p_item->item->isWeapon()) {
            $slot_id = $this->getEquipSlotForWeapon($p_item);
            // echo 'slot_id='.$slot_id;
            // exit;
        } else {
            $this->unequipBySlotID($slot_id);
        }
        $this->db->set('slot_id', $slot_id);
        $this->db->where('id', $p_item->id);
        $this->db->update('player_items');
        $this->addStatsFrom($p_item->item);
        $this->queryItems();
        return true;
    }

    public function drop(PlayerItem $p_item)
    {
        if ($this->id != $p_item->player_id) {
            return false;
        }
        $this->db->where('id', $p_item->id);
        $this->db->delete('player_items');
        return true;
    }

    private function getEquipSlotForWeapon(PlayerItem $p_item)
    {
        assert($p_item->item->weight == 1 || $p_item->item->weight == 2);
        if ($p_item->item->weight == 2) {
            $this->unequipRightHand();
            $this->unequipLeftHand();
        }
        $left_hand_equipped = $this->hasLeftHandEquipped();
        $right_hand_equipped = $this->hasRightHandEquipped();
        // var_dump($right_hand_equipped);
        // var_dump($left_hand_equipped);
        // var_dump($p_item->item);
        // exit;
        if (! $left_hand_equipped) {
            return ITEM::LEFT_HAND_SLOT_ID;
        }
        assert($p_item->item->weight == 1);
        if (! $right_hand_equipped) {
            return ITEM::RIGHT_HAND_SLOT_ID;
        }
        $this->unequipLeftHand();
        return ITEM::LEFT_HAND_SLOT_ID;
    }

    public function hasLeftHandEquipped()
    {
        return ! $this->hasEquipmentSlotFree(Item::LEFT_HAND_SLOT_ID);
    }

    public function hasRightHandEquipped()
    {
        return ! $this->hasEquipmentSlotFree(Item::RIGHT_HAND_SLOT_ID);
    }

    public function unequipLeftHand()
    {
        $this->unequipBySlotID(Item::LEFT_HAND_SLOT_ID);
    }

    public function unequipRightHand()
    {
        $this->unequipBySlotID(Item::RIGHT_HAND_SLOT_ID);
    }

    public function unequipBySlotID($slot_id)
    {
        $this->db->set('slot_id', null);
        $this->db->where('slot_id', $slot_id);
        $this->db->where('player_id', $this->id);
        $this->db->update('player_items');
        $this->queryItems();
    }

    public function unequip(PlayerItem $p_item)
    {
        if ($p_item->slot_id === null) {
            return false;
        }
        if ($p_item->player_id != $this->id) {
            return false;
        }
        $this->db->set('slot_id', null);
        $this->db->where('id', $p_item->id);
        $this->db->update('player_items');
        //echo "<pre>";
//         var_dump($p_item->item);
//         die();
        $this->removeStatsFrom($p_item->item);
        $this->queryItems();
        return true;
    }

    /*
     * COD:
     */
    public function addStatsFrom(Item $item)
    {
        $this->addToHealth($item->health);
        $this->addToEnergy($item->energy);
        $this->addToStamina($item->stamina);
        $this->addToAttack($item->attack);
        // var_dump($item->defense);die();
        $this->addToDefense($item->defense);
        $this->addToLuck($item->luck);
        $this->addToDodge($item->dodge);
        $this->addToStrike($item->strike);
        $this->addToStrikeBoost($item->strike_boost);
        $this->addToDamageBoost($item->damage_boost);
        
        // $this->addToHealthLimit($item->health);
        // $this->addToEnergyLimit($item->energy);
        // $this->addToStaminaLimit($item->stamina);
        // $this->addToAttack($item->attack);
        // $this->addToDefense($this->defense);
        // $this->addToLuck($this->luck);
        // $this->addToDodge($this->dodge);
        // $this->addToStrike($this->strike);
        // $this->addToStrikeBoost($this->strike_boost);
        // $this->addToDamageBoost($this->damage_boost);
        
        // $fields = array('proper' => array('attack', 'defense', 'luck', 'dodge', 'strike', 'strike_boost', 'damage_boost'),
        // 'limit' => array('stamina', 'energy', 'health'));
        // foreach($fields as $type => $arr) {
        // foreach($arr as $field) {
        // $class_field = $type == 'limit' ? $field.'_limit' : $field;
        // $this->$class_field += $item->$field;
        // $this->db->set($class_field, $item->$field);
        // }
        // }
        // $this->db->where('id', $this->id);
        // $this->db->update('players');
    }

    /**
     * COD:
     * 
     * @param Item $item            
     */
    public function removeStatsFrom(Item $item)
    {
        $this->decreaseHealth($item->health);
        $this->decreaseEnergy($item->energy);
        $this->decreaseStamina($item->stamina);
        $this->decreaseAttack($item->attack); // /here should decrease with mod_atk from players_item
        $this->decreaseDefense($item->defense);
        $this->decreaseLuck($item->luck);
        $this->decreaseDodge($item->dodge);
        $this->decreaseStrike($item->strike);
        $this->decreaseStrikeBoost($item->strike_boost);
        $this->decreaseDamageBoost($item->damage_boost);
        
        // $this->decreaseHealthLimit($item->health);
        // $this->decreaseEnergyLimit($item->energy);
        // $this->decreaseStaminaLimit($item->stamina);
        // $this->decreaseAttack($item->attack);
        // $this->decreaseDefense($this->defense);
        // $this->decreaseLuck($this->luck);
        // $this->decreaseDodge($this->dodge);
        // $this->decreaseStrike($this->strike);
        // $this->decreaseStrikeBoost($this->strike_boost);
        // $this->decreaseDamageBoost($this->damage_boost);
    }

    public function tryAddItem(Item $item, $slot_id = null)
    {
        if ($slot_id !== null) {
            if (! $this->hasEquipSlotFree($slot_id)) {
                log_message('debug', 'Trying to add item to not free slot id');
                return false;
            }
        } else {
            // $slot_id = $this->getFreeInventorySlot();
        }
        $this->db->set('player_id', $this->id);
        $this->db->set('item_id', $item->id);
        $this->db->set('slot_id', $slot_id);
        $this->db->set('collected', 'NOW()', false);
        $this->db->insert('player_items');
        $player_item_id = $this->db->insert_id();
        
        return $player_item_id;
    }

    public function tryAddCombatantThing(PlayerCombatantThing $comb_thing)
    {
        if ($comb_thing->player_combatant->player_place->player_id != $this->id) {
            log_message('debug', 'Trying to add item with invalid combatant.');
            return false;
        }
        $this->db->set('collected', date(Globals::MYSQL_DATE_FORMAT));
        $this->db->set('player_id', $this->id);
        $this->db->set('thing_id', $comb_thing->thing_id);
        $this->db->insert('player_things');
        
        $this->db->where('id', $comb_thing->id);
        $this->db->delete('player_combatant_things');
    }

    public function tryAddCombatantItem(PlayerCombatantItem $comb_item, $slot_id = null)
    {
        if ($comb_item->player_combatant->player_place->player_id != $this->id) {
            log_message('debug', 'Trying to add item with invalid combatant.');
            return false;
        }
        if ($slot_id !== null) {
            if (! $this->hasEquipmentSlotFree($slot_id)) {
                log_console('debug', 'Trying to add item to not free slot id');
                return false;
            }
        } else {
            // $slot_id = $this->getFreeInventorySlot();
        }
        $this->db->set('collected', date(Globals::MYSQL_DATE_FORMAT));
        $this->db->set('player_id', $this->id);
        $this->db->set('slot_id', $slot_id);
        $this->db->set('item_id', $comb_item->item_id);
        $this->db->set('quality', $comb_item->quality);
        $this->db->set('durability', $comb_item->durability);
        $this->db->set('mod_atk', $comb_item->mod_atk);
        $this->db->set('mod_def', $comb_item->mod_def);
        $this->db->insert('player_items');
        
        $player_item_id = $this->db->insert_id();
        $comb_item->delete();
        
        return $player_item_id;
    }

    public function has2HweaponEquipped()
    {
        foreach (array(
            Item::LEFT_HAND_SLOT_ID,
            Item::RIGHT_HAND_SLOT_ID
        ) as $id) {
            $this->db->select('item_id');
            $this->db->where('player_id', $this->id);
            $this->db->where('slot_id', $id);
            $this->db->from('player_items');
            
            $query = $this->db->get();
            if (! $query->num_rows) {
                continue;
            }
            $row = $query->row_array();
            
            $item = new Item($row['item_id']);
            if ($item->weight == 2) {
                return true;
            }
        }
        return false;
    }

    public function hasEquipmentSlotFree($slot_id)
    {
        if ($this->items === null) {
            $this->queryItems();
        }
        if (($slot_id == Item::LEFT_HAND_SLOT_ID || $slot_id == Item::RIGHT_HAND_SLOT_ID) && $this->has2HWeaponEquipped()) {
            return false;
        }
        foreach ($this->items as $player_item) {
            if ($player_item->slot_id == $slot_id) {
                return false;
            }
        }
        $free_slots = $this->getFreeInventorySlots();
        return in_array($slot_id, $free_slots);
    }

    public function getUncompletedEvents()
    {
        $events = array();
        $this->db->select('*');
        $this->db->where('completed is null', NULL, false);
        $this->db->where('place_id IN (SELECT id FROM player_places WHERE player_id=' . $this->db->escape($this->id) . ')', NULL, false);
        $this->db->from('player_events');
        
        $query = $this->db->get();
        $arr_events = $query->result_array();
        
        foreach ($arr_events as $event) {
            $events[] = new PlayerEvent($event);
        }
        
        return $events;
    }

    public function hasEvent()
    {
        $this->db->select('id');
        $this->db->where('completed is null', NULL, false);
        $this->db->where('place_id IN (SELECT id FROM player_places WHERE player_id=' . $this->db->escape($this->id) . ')', NULL, false);
        $this->db->from('player_events');
        
        $query = $this->db->get();
        return $query->num_rows > 0;
    }

    public function hasTrader()
    {
        $this->db->select('id');
        $this->db->where('active', true);
        $this->db->where('place_id IN (SELECT id FROM player_places WHERE player_id=' . $this->db->escape($this->id) . ')', NULL, false);
        $this->db->from('player_traders');
        
        $query = $this->db->get();
        return $query->num_rows > 0;
    }

    public function getFreeInventorySlot()
    {
        $free_slots = $this->getFreeInventorySlots();
        if (! sizeof($free_slots)) {
            return false;
        }
        return $free_slots[0];
    }

    public function getFreeInventorySlots()
    {
        $taken_slots = array();
        $free_slots = array();
        foreach ($this->items as $player_item) {
            $taken_slots[] = $player_item->slot_id;
        }
        sort($taken_slots);
        for ($i = 0; $i < $this->getInventoryCapacity(); ++ $i) {
            if (! in_array($i, $taken_slots)) {
                $free_slots[] = $i;
            }
        }
        return $free_slots;
    }

    public function getInventoryItemsCount()
    {
        return sizeof($this->getItemsInInventory());
    }

    public function isInventoryFull()
    {
        $inventory_items = $this->getItemsInInventory();
        return sizeof($inventory_items) >= $this->getInventoryCapacity();
    }

    public function getItemsInInventory()
    {
        if ($this->items === null) {
            $this->queryItems();
        }
        $items = array();
        foreach ($this->items as $item) {
            if ($item->isInInventory()) {
                $items[] = $item;
            }
        }
        return $items;
    }

    public function getInventoryCapacity()
    {
        $this->db->select_sum('i.capacity', 'capacity');
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id = i.id');
        $this->db->where('pi.player_id', $this->id);
        $this->db->where('pi.slot_id is not null');
        
        $query = $this->db->get();
        $result = $query->row_array();
        $cap_from_items = $result['capacity'];
        if ($cap_from_items === null) {
            $cap_from_items = 0;
        }
        return Player::$default_inventory_capacity + $cap_from_items;
    }

    public function trySummon(Boss $boss)
    {
        if (! $this->canSummon($boss)) {
            return false;
        }
        $this->summon($boss);
        $this->showGameInfo($boss->name . ' summoned successfully.');
    }

    public function summon(Boss $boss)
    {
        $id = '';
        do {
            $id = generateRandomString();
            
            $this->db->select('share_id');
            $this->db->where('share_id', $id);
            $this->db->from('player_bosses');
            
            $query = $this->db->get();
            $result = $query->row_array();
        } while ($result);
        $this->db->set('player_id', $this->id);
        $this->db->set('boss_id', $boss->id);
        $this->db->set('health', $boss->health);
        $this->db->set('generated', 'NOW()', false);
        $this->db->set('share_id', $id);
        $this->db->insert('player_bosses');
        
        $player_boss_id = $this->db->insert_id();
        if (! $player_boss_id) {
            throw new Exception('player_boss_id=0');
        }
        $arr = array(
            'player_id' => $this->id,
            'player_boss_id' => $player_boss_id
        );
        
        $this->db->insert('player_boss_combatants', $arr);
    }

    public function canSummon(Boss $boss)
    {
        if ($this->getDowntimeFor($boss)) {
            return false;
        }
        // outlaw guards doesn't have respawn time but you can have only one summoned at a time
        if ($boss->id == Boss::$ids['outlaw_guards'] && $this->hasBossActive($boss)) {
            return false;
        }
        $this->db->select('id');
        $this->db->where('player_id', $this->id);
        $this->db->where('boss_id', $boss->id);
        $this->db->from('player_available_bosses');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        if (! $result) {
            return false;
        }
        // $requirements = $boss->getRequirements();
        // foreach($requirements as $requirement) {
        // if($requirement->is('item')) {
        // $item = $requirement;
        // if(!$this->has($item)) {
        // return false;
        // }
        // } else if($requirement->is('mission')) {
        // $mission = $requirement;
        // if(!$this->hasCompleted($mission)) {
        // return false;
        // }
        // } else if($requirement->is('place')) {
        // $place = $requirement;
        // if($this->getProgressFor($place) < 100) {
        // return false;
        // }
        // }
        // }
        return true;
    }

    public function getActiveBosses()
    {
        $query = $this->db->query('SELECT player_id, player_boss_id
FROM `player_boss_combatants`
WHERE player_id ="' . $this->id . '"');
        
        $result = $query->result_array();
        
        $ret = array();
        foreach ($result as $arr) {
            $ret[] = new PlayerBoss($arr['player_boss_id']);
        }
        return $ret;
    }

    public function hasBossActive(Boss $boss)
    {
        $this->db->select('id');
        $this->db->where('player_id', $this->id);
        $this->db->where('boss_id', $boss->id);
        $this->db->where('completed is NULL', NULL, false);
        // $this->db->where('NOT is_boss_expired(id)', NULL, false);
        $this->db->from('player_bosses');
        
        $query = $this->db->get();
        $result = $query->row_array();
        if ($result) {
            return true;
        }
        return false;
    }

    public function getCurrentBoss()
    {
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->where('is_current', true);
        $this->db->from('player_boss_combatants');
        
        $query = $this->db->get();
        $result = $query->row_array();
        if (! $result) {
            return null;
        }
        return new PlayerBoss($result['player_boss_id']);
    }

    public function getCurrentCombatant()
    {
        $this->db->select('*');
        $this->db->where('place_id IN (SELECT id FROM player_places WHERE player_id=' . $this->db->escape($this->id) . ')', null, false);
        $this->db->where('active', true);
        $this->db->from('player_combatants');
        
        $query = $this->db->get();
        $result = $query->row_array();
        if (! $result) {
            return null;
        }
        return new PlayerCombatant($result);
    }

    public function tryJoinBossFight($share_id, $boss_name, $level)
    {
        $query = $this->db->query("SELECT * FROM player_bosses WHERE share_id=" . $this->db->escape($share_id) . " AND boss_id IN (SELECT id FROM bosses WHERE name=" . $this->db->escape($boss_name) . " AND level=" . $this->db->escape($level) . ")");
        $result = $query->row_array();
        
        if (! $result) {
            return false;
        }
        
        $player_boss = new PlayerBoss($result);
        
        $this->db->select('id');
        $this->db->where('player_id', $this->id);
        $this->db->where('player_boss_id', $result['id']);
        $this->db->from('player_boss_combatants');
        
        $query_existing = $this->db->get();
        $result_existing = $query_existing->row_array();
        
        if ($result_existing) {
            return 'already_fightning';
        }
        
        if ($player_boss->getCombatantsCount() >= $player_boss->boss->max_players) {
            return 'raid_full';
        }
        
        $this->db->set('player_id', $this->id);
        $this->db->set('player_boss_id', $result['id']);
        $this->db->insert('player_boss_combatants');
        
        return $player_boss;
    }

    public function getCombatant()
    {
        $this->db->select('*');
        $this->db->where('active', 1);
        $this->db->where('place_id IN (SELECT id FROM player_places WHERE player_id="' . $this->id . '" AND active=1)', null, false);
        $this->db->from('player_combatants');
        
        $query = $this->db->get();
        if ($query->num_rows == 0) {
            return null;
        }
        $result = $query->row_array();
        
        return new PlayerCombatant($result);
    }

    public function tryAttack(PlayerBoss $player_boss)
    {
        $this->db->where('player_id', $this->id);
        $this->db->set('is_current', false);
        $this->db->update('player_boss_combatants');
        
        $this->db->where('player_id', $this->id);
        $this->db->where('player_boss_id', $player_boss->id);
        $this->db->set('is_current', true);
        $this->db->update('player_boss_combatants');
        $this->showGameInfo($player_boss->boss->name . ' is now your current traget.');
    }

    public function flee(PlayerCombatant $p_combatant)
    {
        $stamina_cost = $p_combatant->player_place->place->energy * 5;
        if ($this->stamina < $stamina_cost) {
            return 'not_enough_stamina';
        }
        if ($p_combatant->player_place->player_id != $this->id) {
            return false;
        }
        $energy = $p_combatant->player_place->place->energy;
        $this->db->set('active', 0);
        $this->db->where('id', $p_combatant->id);
        $this->db->update('player_combatants');
        $this->stamina -= $stamina_cost;
        $this->syncStaminaFromObj();
        return true;
    }

    private function syncStaminaFromObj()
    {
        $this->db->set('stamina', $this->stamina);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function canAttack()
    {
        if ($this->health < 10) {
            return false;
        }
        return true;
    }

    public function hasIntroPassed()
    {
        $this->db->select('passed_intro');
        $this->db->where('id', $this->id);
        $this->db->from('players');
        
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['passed_intro'];
    }

    public function setIntroPassed()
    {
        $this->session->set_userdata('passed_intro', true);
        $this->db->set('passed_intro', true);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function attackPlayerCombatant(PlayerCombatant $p_combatant)
    {
        $player_attack = $this->getAttack();
        $player_defense = $this->getDefense();
        $low = 75;
        $high = 120;
        $dodge_value = 10 + $p_combatant->combatant->dodge;
        $low_roll = $dodge_value + 1;
        $high_roll = 95 - round(95 * ($player_attack / 100));
        // Get attack odds
        $roll = roll(100);
        $dmg = 0;
        if ($roll < $low_roll) {
            // DMG remains 0
        } elseif ($roll < $high_roll + 1) {
            $strike_multiplier = roll($high, $low);
            $dmg = round(($player_attack + (.25 * $player_defense)) * (($strike_multiplier - $p_combatant->combatant->dodge) / 100));
        } elseif ($roll < ($high_roll + 1) + round(.6 * (100 - ($high_roll + 1)))) {
            $strike_multiplier = 200;
            $dmg = round(($player_attack + (.25 * $player_defense)) * (($strike_multiplier - $p_combatant->combatant->dodge) / 100));
        } else {
            $strike_multiplier = 300;
            $dmg = round(($player_attack + (.25 * $player_defense)) * (($strike_multiplier - $p_combatant->combatant->dodge) / 100));
        }
        $this->dealDamageTo($p_combatant, $dmg);
        return $dmg;
    }

    public function getInactiveModifiers()
    {
        $modifiers = array();
        
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->where('item_id is NULL', null, false);
        $this->db->from('player_modifiers');
        
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $modifier_arr) {
            $modifiers[] = new PlayerModifier($modifier_arr);
        }
        return $modifiers;
    }
    // TODO: need to check modifiers_sections_whitelist
    public function getModifiers()
    {
        $modifiers = array();
        
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->from('player_modifiers');
        
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $modifier_arr) {
            $modifiers[] = new PlayerModifier($modifier_arr);
        }
        
        return $modifiers;
    }

    public function attackPlayerBoss(PlayerBoss $p_boss, $stamina)
    {
        if ($stamina < 0) {
            throw new Exeception('Can\'t attack with negative stamina.');
        }
        if ($this->stamina < $stamina) {
            return 'not_enough_stamina';
        }
        $player_attack = $this->getAttack();
        $player_defense = $this->getDefense();
        $low = 75;
        $high = 120;
        $high_roll = 95 - round(95 * ($player_attack / 100));
        $roll = roll(100);
        $dmg = 0;
        if ($roll < $high_roll + 1) {
            $strike_multiplier = roll($high);
            $dmg = round(($player_attack + (.25 * $player_defense)) * ($strike_multiplier / 100));
        } elseif ($roll < ($high_roll + 1) + round(.6 * (100 - ($high_roll + 1)))) {
            $strike_multiplier = 200;
            $dmg = round(($player_attack + (.25 * $player_defense)) * ($strike_multiplier / 100));
        } else {
            $strike_multiplier = 300;
            $dmg = round(($player_attack + (.25 * $player_defense)) * ($strike_multiplier / 100));
        }
        $dmg *= $stamina;
        $this->takeStamina($stamina);
        $this->dealDamageTo($p_boss, $dmg);
        return $dmg;
    }

    public function attack($target)
    {
        if ($target instanceof PlayerCombatant) {
            return $this->attackPlayerCombatant($target);
        } else 
            if ($target instanceof PlayerBoss) {
                return $this->attackPlayerBoss($target);
            }
    }

    public function dealDamageTo($combatant, $dmg)
    {
        if ($combatant instanceof PlayerBoss) {
            $this->dealDamageToBoss($combatant, $dmg);
        } else 
            if ($combatant instanceof PlayerCombatant) {
                $this->dealDamageToCombatant($combatant, $dmg);
            }
    }

    public function dealDamageToBoss(PlayerBoss $p_boss, $dmg)
    {
        $p_boss->recieveDamage($dmg);
        $this->db->set('damage', 'damage+' . $this->db->escape($dmg), false);
        $this->db->where('player_boss_id', $p_boss->id);
        $this->db->where('player_id', $this->id);
        $this->db->update('player_boss_combatants');
    }

    public function dealDamageToCombatant(PlayerCombatant $p_combatant, $dmg)
    {
        $p_combatant->recieveDamage($dmg);
    }

    public function getVehicle()
    {
        return $this->getItemBySlot(14);
    }

    public function getItemBySlot($id)
    {
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->where('slot_id', $id);
        $this->db->from('player_items');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        if (! $result) {
            return null;
        }
        
        return new PlayerItem($result);
    }

    public function getSkillSpentOn($stat)
    {
        $this->db->select('count');
        $this->db->where('player_id', $this->id);
        $this->db->where('stat', $stat);
        $this->db->from('spent_skills');
        
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return 0;
        }
        $result = $query->row_array();
        return $result['count'];
    }

    public function getBaseEnergyLimit()
    {
        return Player::STARTING_ENERGY + $this->getSkillSpentOn('energy_limit');
    }

    public function getBaseStaminaLimit()
    {
        return Player::STARTING_STAMINA + $this->getSkillSpentOn('stamina_limit') / 2;
    }

    public function getBaseHealthLimit()
    {
        return Player::STARTING_HEALTH + $this->getSkillSpentOn('health_limit') / 5;
    }

    public function getBaseAttack()
    {
        return Player::STARTING_ATTACK + $this->getSkillSpentOn('attack');
    }

    public function getBaseDefense()
    {
        return Player::STARTING_DEFENSE + $this->getSkillSpentOn('defense');
    }

    public function getBaseStrike()
    {
        return Player::STARTING_STRIKE + $this->getSkillSpentOn('strike');
    }

    public function getBaseDodge()
    {
        return Player::STARTING_DODGE + $this->getSkillSpentOn('dodge');
    }

    public function getBaseLuck()
    {
        return Player::STARTING_LUCK + $this->getSkillSpentOn('luck');
    }

    public function spendPoints($stat, $points)
    {
        if ($this->skill < $points) {
            return false;
        }
        switch ($stat) {
            case 'stamina_limit':
                $this->addToStaminaLimit($points / 2);
                break;
            case 'energy_limit':
                $this->addToEnergyLimit($points);
                break;
            case 'health_limit':
                $this->addToHealthLimit($points * 5);
                break;
            case 'attack':
                $this->addToAttack($points);
                break;
            case 'defense':
                $this->addToDefense($points);
                break;
        }
        $this->setSkillSpent($stat, $points);
        $this->takeSkillPoints($points);
        return true;
    }
    /*
     * CODE: CI:B0202 
     * this is used when you want to spend skill points to get new features
     * here was a bug, the old code is in comment
     * it updated the same line in database and overwrited it with new stat (attack, defense, health_limit ...)
     * it should have more entries, with one for each atribute attack, health_limit and so on
     * 
     */
    
    private function setSkillSpent($stat, $points)
    {
        if (! is_numeric($points) || $points < 0) {
            return false;
        }
        
        $where = array(
            "player_id" => $this->id,
            "stat" => $stat
        );
        $select = $this->db->get_where("spent_skills", $where);
        if ($select->num_rows > 0) {
            $update_data = array(
                "count" => "count+{$points}"
            );
            $this->db->update('spent_skills', $update_data, $where);
        } else {
            $insert_data = array(
                "player_id" => $this->id,
                "stat" => $stat,
                "count" => $points
            );
            $this->db->insert("spent_skills", $insert_data);
        }
        
        
        /*
        $this->db->set('player_id', $this->id);
        $this->db->set('stat', $stat);
        $this->db->set('count', 'count+' . $points, false);
        $this->db->update('spent_skills');
        $affected_rows = $this->db->affected_rows();
        assert($affected_rows <= 1);
        if ($affected_rows) {
            return true;
        }
        $this->db->set('player_id', $this->id);
        $this->db->set('stat', $stat);
        $this->db->set('count', $points);
        $this->db->insert('spent_skills');
        return true;
        */
    }

    public function takeSkillPoints($points)
    {
        $this->skill -= $points;
        if ($this->skill < 0) {
            $this->skill = 0;
        }
        $this->db->set('skill', $this->skill);
        $this->db->where('id', $this->id);
        $this->db->update('players');
    }

    public function getEnergyLimit()
    {
        return $this->energy_limit;
    }

    public function getStaminaLimit()
    {
        return $this->stamina_limit;
    }

    public function getHealthLimit()
    {
        return $this->health_limit;
    }

    public function getAttack()
    {
        return $this->attack;
    }

    public function getStrike()
    {
        return $this->strike;
    }

    public function getDefense()
    {
        return $this->defense;
    }

    public function getDodge()
    {
        return $this->dodge;
    }

    public function getLuck()
    {
        return $this->luck;
    }
    public function getStamina()
    {
        return $this->stamina;
    }
    public function getEnergy()
    {
        return $this->energy;
    }
    public function getHealth()
    {
        return $this->health;
    }
    
    
    public function getAttackFromItems()
    {
        $this->db->select('SUM(coalesce(i.attack, 0) + coalesce(pi.mod_atk, 0)) as attack', false);
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id=i.id');
        $this->db->where('pi.player_id', $this->id);
        //$this->db->where('pi.slot_id is not null', NULL, false);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['attack'];
    }

    public function getDefenseFromItems()
    {
        $this->db->select('SUM(coalesce(i.defense,0) + coalesce(pi.mod_def, 0)) as defense', false);
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id=i.id');
        $this->db->where('pi.player_id', $this->id);
     //   $this->db->where('pi.slot_id is not null', NULL, false);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['defense'];
    }

    public function getStrikeFromItems()
    {
        $this->db->select('SUM(coalesce(i.strike, 0)) as strike', false);
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id=i.id');
        $this->db->where('pi.player_id', $this->id);
        $this->db->where('pi.slot_id is not null', NULL, false);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['strike'];
    }

    public function getLuckFromItems()
    {
        $this->db->select('SUM(coalesce(i.luck, 0)) as luck', false);
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id=i.id');
        $this->db->where('pi.player_id', $this->id);
        $this->db->where('pi.slot_id is not null', NULL, false);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['luck'];
    }

    public function getDodgeFromItems()
    {
        $this->db->select('SUM(coalesce(i.dodge, 0)) as dodge', false);
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id=i.id');
        $this->db->where('pi.player_id', $this->id);
        $this->db->where('pi.slot_id is not null', NULL, false);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['dodge'];
    }

    public function getEnergyFromItems()
    {
        $this->db->select('SUM(coalesce(i.energy, 0)) as energy', false);
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id=i.id');
        $this->db->where('pi.player_id', $this->id);
        $this->db->where('pi.slot_id is not null', NULL, false);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['energy'];
    }

    public function getHealthFromItems()
    {
        $this->db->select('SUM(coalesce(i.health, 0)) as health', false);
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id=i.id');
        $this->db->where('pi.player_id', $this->id);
        $this->db->where('pi.slot_id is not null', NULL, false);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['health'];
    }

    public function getStaminaFromItems()
    {
        $this->db->select('SUM(coalesce(i.stamina, 0)) as stamina', false);
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id=i.id');
        $this->db->where('pi.player_id', $this->id);
        $this->db->where('pi.slot_id is not null', NULL, false);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['stamina'];
    }

    public function generateRandomItem()
    {
        $this->db->query('SET @rnd := RAND()');
        
        $this->db->select('id');
        $this->db->where('@rnd > coeff_min', NULL, false);
        $this->db->where('@rnd <= coeff_max', NULL, false);
        $this->db->from('rarities');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->db->select('*');
        // $this->db->where('from_store', true);
        $this->db->where('id IN (SELECT item_id FROM caches_allowed_items WHERE cache_id=1)', NULL, false);
        $this->db->where('rarity_id', $result['id']);
        $this->db->from('items');
        $this->db->order_by('RAND()');
        $this->db->limit(1);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        if (! $result) {
            return null;
        }
        
        return new Item($result);
    }

    public function getIntroPage()
    {
        $this->db->select('page');
        $this->db->where('player_id', $this->id);
        $this->db->from('intro_info');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['page'];
    }

    public function getAllPlayerBosses()
    {
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->from('player_bosses');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        $player_bosses = array();
        
        foreach ($result as $p_b_arr) {
            $player_bosses[] = new PlayerBoss($p_b_arr);
        }
        
        return $player_bosses;
    }

    public function getAllPayerCombatants()
    {
        $this->db->select('*');
        $this->db->where('place_id IN (SELECT place_id FROM player_places WHERE player_id="' . $this->db->escape($this->id) . '")', null, false);
        $this->db->from('player_combatants');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        $player_combatants = array();
        
        foreach ($result as $p_c_arr) {
            $player_combatants[] = new PlayerCombatant($p_c_arr);
        }
        
        return $player_combatants;
    }

    public function getAllPayerTraders()
    {
        $this->db->select('*');
        $this->db->where('place_id IN (SELECT place_id FROM player_places WHERE player_id="' . $this->db->escape($this->id) . '")', null, false);
        $this->db->from('player_traders');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        $player_traders = array();
        
        foreach ($result as $p_t_arr) {
            $player_traders[] = new PlayerTrader($p_t_arr);
        }
        
        return $player_traders;
    }

    public function getKillCountFor(Boss $boss)
    {
        /*
         * $this->db->select('COUNT(id) as count');
         * $this->db->where('id IN (SELECT boss_id FROM player_bosses WHERE '
         * . 'boss_id='.$this->db->escape($boss->id).' AND id IN '
         * . '(SELECT player_boss_id FROM player_boss_combatants '
         * . 'WHERE player_id='.$this->db->escape($this->id).'))', null, false);
         * $this->db->from('bosses');
         */
        $this->db->select('count(*) as count');
        $this->db->where('player_id', $this->id);
        $this->db->where('boss_id', $boss->id);
        $this->db->from('boss_kill_counts');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['count'];
    }

    public static function getAll()
    {
        $db = get_instance()->db;
        $db->select('*');
        $db->from('players');
        
        $player_arrs = $db->get()->result_array();
        $players = array();
        
        foreach ($player_arrs as $player_arr) {
            $players[] = newPlayer($player_arr);
        }
        return $players;
    }

    public function delete()
    {
        $this->db->where('id', $this->id);
        $this->db->delete('players');
        
        $this->db->where('player_id', $this->id);
        $this->db->delete('actions');
        
        $player_bosses = $this->getAllPlayerBosses();
        $player_combatants = $this->getAllPayerCombatants();
        
        if ($this->items === null) {
            $this->queryItems();
        }
        if ($this->places === null) {
            $this->queryPlaces();
        }
        if ($this->missions === null) {
            $this->queryMissions();
        }
        if ($this->boosts === null) {
            $this->queryBoosts();
        }
        if ($this->things === null) {
            $this->queryThings();
        }
        
        $this->deleteEvents();
        $this->deleteTraders();
        $this->deleteFriends();
        $this->deleteBossCombatants();
        
        foreach ($player_combatants as $p_combatant) {
            $p_combatant->delete();
        }
        foreach ($player_bosses as $p_boss) {
            $p_boss->delete();
        }
        foreach ($this->items as $item) {
            $item->delete();
        }
        foreach ($this->places as $place) {
            $place->delete();
        }
        foreach ($this->missions as $mission) {
            $mission->delete();
        }
        foreach ($this->boosts as $boost) {
            $boost->delete();
        }
        foreach ($this->things as $thing) {
            $thing->delete();
        }
        $this->db->where('player_id', $this->id);
        $this->db->delete('intro_info');
        // and so on and so forth ;)
    }

    public function deleteEvents()
    {
        $this->db->where('place_id IN (SELECT id FROM player_places WHERE player_id="' . $this->db->escape($this->id) . '")');
        $this->db->delete('player_events');
    }

    public function deleteTraders()
    {
        $player_traders = $this->getAllPayerTraders();
        foreach ($player_traders as $player_trader) {
            $player_trader->delete();
        }
    }

    public function deleteFriends()
    {
        $this->db->where('player_id', $this->id);
        $this->db->delete('player_friends');
    }

    public function deleteBossCombatants()
    {
        $this->db->where('player_id', $this->id);
        $this->db->delete('player_boss_combatants');
    }

    public function getCompletedMissions()
    {
        $missions = array();
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->where('completed is null', null, false);
        $this->db->where('started is not null', null, false);
        $this->db->from('player_missions');
        
        $query = $this->db->get();
        $arrs = $query->result_array();
        foreach ($arrs as $arr) {
            $missions[] = new PlayerMission($arr);
        }
        return $missions;
    }

    public function getUncompletedMissions()
    {
        $missions = array();
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->where('not (completed is null)', null, false);
        $this->db->where('started is not null', null, false);
        $this->db->from('player_missions');
        
        $query = $this->db->get();
        $arrs = $query->result_array();
        foreach ($arrs as $arr) {
            $missions[] = new PlayerMission($arr);
        }
        return $missions;
    }

    public function isFighting(PlayerCombatant $p_combatant)
    {
        if ($p_combatant->player->id != $this->id) {
            return false;
        }
        return (bool) $p_combatant->fighting;
    }

    public function getBiggestHit()
    {
        $hitBosses = $this->getBiggestHitAgainstBosses();
        $hitCombatants = $this->getBiggestHitAgainstCombatants();
        if ($hitBosses < $hitCombatants) {
            return $hitCombatants;
        } else {
            return $hitBosses;
        }
    }

    public function getBiggestHitAgainstBosses()
    {
        $this->db->select('damage_to_boss');
        $this->db->where('player_id', $this->id);
        $this->db->from('boss_combat_log');
        $this->db->order_by('damage_to_boss', 'desc');
        
        $query = $this->db->get();
        if (! $query->num_rows) {
            return 0;
        }
        $row = $query->row_array();
        return $row['damage_to_boss'];
    }

    public function getBiggestHitAgainstCombatants()
    {
        $this->db->select('damage');
        $this->db->where('player_id', $this->id);
        $this->db->from('actions');
        $this->db->order_by('damage', 'desc');
    }

    public function getDaysPlayed()
    {}

    public function getDamageWhileExploring()
    {}

    public function getDamageWhileFightingBosses()
    {}

    public function getCoinsSpent()
    {}

    public function getPremiumCoinsSpent()
    {}

    public function getCollectedItemsTypes()
    {}

    public function getBossKillCount()
    {}

    public static function getArrById($id)
    {
        $db = get_instance()->db;
        $db->select('*');
        $db->where('id', $id);
        $db->from('players');
        
        $query = $db->get();
        if (! $query->num_rows) {
            return false;
        }
        
        return $query->row_array();
    }
    
    /**
     * CI:B0211
     * used to calculate the trust bar
     * @param  $amount the amount the player gives to beggar
     */
    public function calcTrustProgress($amount, $max_amount){
        
        $percent = $amount/$max_amount*100;
        $percent += $this->trustProgress;
        if($percent>=100){
            $this->trustProgress=100;
        }else{
            $this->trustProgress=$percent;
        }
        $this->save();
        return $this->trustProgress;
        
    }
}
?>