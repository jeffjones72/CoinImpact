<?php
class EquipmentSlot extends CI_Model {
    public static $ids = array('vehicle' => 14, 'companion' => 15);
    private $name;
    private $id;
    public function __construct($id=null) {
        if($id === null) {
            return;
        }
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->from('slots');
        
        $query = $this->db->get();
        $row = $query->row_array();
        
        $this->name = $row['name'];
        $this->id = $id;
    }
    public function getType() {
        return strtolower($this->name);
    }
    public function getName() {
        return $this->name;
    }
    public function getId() {
        return $this->id;
    }
    public function isVehicle() {
        return self::$ids['vehicle'] == $this->id;
    }
    public function isCompanion() {
        return self::$ids['companion'] == $this->id;
    }
    public static function getAll() {
        $slots = array();
        $db = get_instance()->db;
        
        $db->select('*');
        $db->from('slots');
        
        foreach($db->get()->result_array() as $arr) {
            $slot = new EquipmentSlot();
            $slot->id = $arr['id'];
            $slot->name = $arr['name'];
            $slots[] = $slot;
        }
        return $slots;
    }
}
?>