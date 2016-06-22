<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Store extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index()
    {
        $this->loadGlobals($this, $data);
        $this->load->model('players_model');
        
        if ($this->input->post('buy')) {
            $ret = $this->handleBuy();
            if ($ret) {
                $data['new_item'] = $ret;
            }
        }
        
        $player = newPlayer();
        
        $data['player'] = $player;
        
        $player_id = $player->id;
        $this->load->model('items_model');
        $data['items'] = $this->items_model->get_items($player_id);
        
        $this->load->model('store_model');
        $data['store_items'] = $this->store_model->get_items();
        $data['store_things'] = $this->store_model->get_things();
        $data['store_boosts'] = $this->store_model->get_boosts();
        $data['store_modifiers'] = $this->store_model->get_modifiers();
        
        $header['page_title'] = 'Store';
        $header['data'] = $this->get_counters();
        
        $this->load->model('cache_model');
        $data['caches'] = $this->cache_model->get_cache_info();
        
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        
        $data['content'] = $this->load->view('store', $data, TRUE);
        
        $this->load->view('header', $header);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }

    private function handleBuy()
    {
        if ($this->input->post('buy') !== 'ww2-item') { // only ww2 cache is supported right now
            return false;
        }
        $player = newPlayer();
        if (! $player->hasPremiumCoin()) {
            return false;
        }
        
        if ($player->isInventoryFull()) {
            $player->showGameError('Inventory is full. Can\'t add item.');
            return false;
        }
        
        $result = $player->generateRandomItem();
        
        $add_result = $player->tryAdd($result);
        $p_item = new PlayerItem($add_result);
        $player->takePremiumCoin();
        $player->showGameInfo('Congratulations! You won <a href="#" id="won_item" data-id="' . $p_item->item->id . '">' . $p_item->item->name . '</a>.');
        return $p_item->item;
    }

    public function item()
    {
        $item_id = $this->uri->segment(3, 0);
        
        $this->load->model('players_model');
        $this->load->model('items_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $data['player_id'] = $temp['player_id'];
        $data['balance'] = $temp['balance'];
        $data['premium_balance'] = $temp['premium_balance'];
        $item = $this->items_model->get_item($item_id);
        
        if (! $item['price'])
            $item['price'] = 0;
        if (! $item['premium_price'])
            $item['premium_price'] = 0;
        $item['buyable'] = FALSE;
        if ($data['balance'] >= $item['price'] && $data['premium_balance'] >= $item['premium_price'])
            $item['buyable'] = TRUE;
        
        $data['item'] = $item;
        
        $this->load->view('store_item', $data);
    }

    public function purchase_item()
    {
        $item_id = $this->input->post('id');
        
        $this->load->model('players_model');
        $this->load->model('items_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $player_id = $temp['player_id'];
        $item = $this->items_model->get_item($item_id);
        if (! $item['price'])
            $item['price'] = 0;
        if (! $item['premium_price'])
            $item['premium_price'] = 0;
        
        $this->items_model->insert($player_id, $item_id);
        $this->players_model->deduct_price($player_id, $item['price'], $item['premium_price']);
        
        redirect('profile');
    }

    public function modifier()
    {
        $modifier_id = $this->uri->segment(3, 0);
        
        $this->load->model('players_model');
        $this->load->model('modifiers_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $data['player_id'] = $temp['player_id'];
        $data['balance'] = $temp['balance'];
        $data['premium_balance'] = $temp['premium_balance'];
        $modifier = $this->modifiers_model->get_modifier($modifier_id);
        
        if (! $modifier['price'])
            $modifier['price'] = 0;
        if (! $modifier['premium_price'])
            $modifier['premium_price'] = 0;
        $modifier['buyable'] = FALSE;
        if ($data['balance'] >= $modifier['price'] && $data['premium_balance'] >= $modifier['premium_price'])
            $modifier['buyable'] = TRUE;
        
        $data['modifier'] = $modifier;
        
        $this->load->view('store_modifier', $data);
    }

    public function purchase_modifier()
    {
        $modifier_id = $this->input->post('id');
        
        $this->load->model('players_model');
        $this->load->model('modifiers_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $player_id = $temp['player_id'];
        $modifier = $this->modifiers_model->get_modifier($modifier_id);
        if (! $modifier['price'])
            $modifier['price'] = 0;
        if (! $modifier['premium_price'])
            $modifier['premium_price'] = 0;
        
        $this->modifiers_model->insert($player_id, $modifier_id);
        $this->players_model->deduct_price($player_id, $modifier['price'], $modifier['premium_price']);
        
        redirect('profile');
    }

    public function boost()
    {
        $boost_id = $this->uri->segment(3, 0);
        
        $this->load->model('players_model');
        $this->load->model('boosts_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $data['player_id'] = $temp['player_id'];
        $data['balance'] = $temp['balance'];
        $data['premium_balance'] = $temp['premium_balance'];
        $boost = $this->boosts_model->get_boost($boost_id);
        
        if (! $boost['price'])
            $boost['price'] = 0;
        if (! $boost['premium_price'])
            $boost['premium_price'] = 0;
        $boost['buyable'] = FALSE;
        if ($data['balance'] >= $boost['price'] && $data['premium_balance'] >= $boost['premium_price'])
            $boost['buyable'] = TRUE;
        
        $data['boost'] = $boost;
        
        $this->load->view('store_boost', $data);
    }

    public function purchase_boost()
    {
        $boost_id = $this->input->post('id');
        
        $this->load->model('players_model');
        $this->load->model('boosts_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $player_id = $temp['player_id'];
        $boost = $this->boosts_model->get_boost($boost_id);
        if (! $boost['price'])
            $boost['price'] = 0;
        if (! $boost['premium_price'])
            $boost['premium_price'] = 0;
        
        $this->boosts_model->insert($player_id, $boost_id);
        $this->players_model->deduct_price($player_id, $boost['price'], $boost['premium_price']);
        
        redirect('profile');
    }

    /*
     * Ticket #85
     */
    public function cache_items()
    {
        $this->loadGlobals($this, $data);
        
        $player_id = $this->session->userdata("player_id");
        $player = newPlayer($player_id);
        
        $data['player'] = $player;
        
        $this->load->model('items_model');
        $data['items'] = $this->items_model->get_items($player_id);
        
        $this->load->model('store_model');
        $data['store_items'] = $this->store_model->get_items();
        $data['store_things'] = $this->store_model->get_things();
        $data['store_boosts'] = $this->store_model->get_boosts();
        $data['store_modifiers'] = $this->store_model->get_modifiers();
        
        $header['page_title'] = 'Store';
        $header['data'] = $this->get_counters();
        
        $this->load->model('cache_model');
        $data['caches'] = $this->cache_model->get_cache_info();
        $order_by = $this->input->post("items_sort");
        if (isset($order_by)) {}
        
        $cache_items = $this->cache_model->get_cache_items($player_id, $order_by);
        
        // pagination
        $this->load->library('pagination');
        $pag['uri_segment'] = 3;
        $pag['base_url'] = base_url("store/cache_items");
        $page = $this->uri->segment(3, 1);
        $pag['total_rows'] = count($cache_items);
        $pag['per_page'] = 12;
        
        $pag['display_pages'] = false;
        $pag['full_tag_open'] = "<div class='page-button-container'>";
        $pag['full_tag_close'] = "</div >";
        
        $pag['first_link'] = false;
        $pag['last_link'] = false;
        $pag['prev_link'] = "<img class='page-button' src='" . base_url() . "_images/arrowLeft.png'>";
        $pag['next_link'] = "<img class='page-button' src='" . base_url() . "_images/arrowRight.png'>";
        $this->pagination->initialize($pag);
        $data['pagination'] = $this->pagination->create_links();
        if ($page == 1) {
            $start = 0;
        } else {
            $start = $page;
        }
        $data['cache_items'] = array_slice($cache_items, $start, $pag['per_page']);
        
        $data['content'] = $this->load->view('store_cache', $data, TRUE);
        
        $this->load->view('header', $header);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }

    /**
     * Ticket #85
     */
    public function ajax_order_items()
    {
        $this->load->model('cache_model');
        $player_id = $this->session->userdata("player_id");
        $data['caches'] = $this->cache_model->get_cache_info();
        $items_sort = $this->input->post("items_sort");
        if ($items_sort !=FALSE) {
            $items_sort = "rarity_id = {$items_sort}";
        }else{
            $items_sort="";
        }
        $cache_items = $this->cache_model->get_cache_items($player_id, $items_sort);
        // pagination
        $this->load->library('pagination'); 
        $pag['uri_segment'] = 3;
        $pag['base_url'] = base_url("store/cache_items");
        $page = $this->uri->segment(3, 1);
        $pag['total_rows'] = count($cache_items);
        $pag['per_page'] = 12;
        
        $pag['display_pages'] = false;
        $pag['full_tag_open'] = "<div class='page-button-container'>";
        $pag['full_tag_close'] = "</div >";
        
        $pag['first_link'] = false;
        $pag['last_link'] = false;
        $pag['prev_link'] = "<img class='page-button' src='" . base_url() . "_images/arrowLeft.png'>";
        $pag['next_link'] = "<img class='page-button' src='" . base_url() . "_images/arrowRight.png'>";
        $this->pagination->initialize($pag);
        $data['pagination'] = $this->pagination->create_links();
        if ($page == 1) {
            $start = 0;
        } else {
            $start = $page;
        }
        $data['cache_items'] = array_slice($cache_items, $start, $pag['per_page']);
        $this->load->view("ajax_store_cache_items", $data);
    }
    /**
     * Ticket #85
     */
    public function ajax_generate_cache_item(){
        $player_id = $this->session->userdata("player_id");
        $player = new Player($player_id);
        $this->load->model('cache_model');
      //  $data['generated_item'] = $this->cache_model->generate_cache_item($player_id);
        //$this->cache_model->
        
        
        
        $item_id = $this->input->post("item_id");
        if($item_id != false){
            $new_item = new Item($item_id);
            $result = $player->tryAdd($new_item);
            if($result=="full_inventory"){
                                
                $data['inventory_full'] = true;
                
            }
          //  $data['generated_item']="";
        }else{
            $data['generated_item'] = $this->cache_model->generate_cache_item($player_id);
        }
        $data['caches'] = $this->cache_model->get_cache_info();
        $items_sort = $this->input->post("items_sort");
        if ($items_sort !=FALSE) {
            $items_sort = "rarity_id = {$items_sort}";
        }else{
            $items_sort="";
        }
        $cache_items = $this->cache_model->get_cache_items($player_id, $items_sort);

        
        // pagination
        $this->load->library('pagination');
        $pag['uri_segment'] = 3;
        $pag['base_url'] = base_url("store/cache_items");
        $page = $this->uri->segment(3, 1);
        $pag['total_rows'] = count($cache_items);
        $pag['per_page'] = 12;
        
        $pag['display_pages'] = false;
        $pag['full_tag_open'] = "<div class='page-button-container'>";
        $pag['full_tag_close'] = "</div >";
        
        $pag['first_link'] = false;
        $pag['last_link'] = false;
        $pag['prev_link'] = "<img class='page-button' src='" . base_url() . "_images/arrowLeft.png'>";
        $pag['next_link'] = "<img class='page-button' src='" . base_url() . "_images/arrowRight.png'>";
        $this->pagination->initialize($pag);
        $data['pagination'] = $this->pagination->create_links();
        if ($page == 1) {
            $start = 0;
        } else {
            $start = $page;
        }
        $data['cache_items'] = array_slice($cache_items, $start, $pag['per_page']);
        
        
        $this->load->view("ajax_store_cache_items", $data);
    }
}

/* End of file store.php */
/* Location: ./application/controllers/store.php */