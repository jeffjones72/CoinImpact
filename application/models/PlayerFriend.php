<?php
class PlayerFriend extends CI_Model {
    
    public $friend;

    public $player;
    
    public function __construct($data = null) {
        parent::__construct($data);
        $this->player = new Player($this->player_id);
        $this->friend = new Player($this->friend_id );
    }
}
?>