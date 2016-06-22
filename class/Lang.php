<?php
class Lang implements arrayaccess {
    private static $instance = null;
    private $language;
    private $vals;
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new Lang('english');
        }
        return self::$instance;
    }
    private function __construct($language) {
        $this->language = $language;
        $this->vals = include(getcwd().'/lang/'.$language.'.php');
    }

    public function offsetExists($offset) {
        return isset($this->vals[$offset]);
    }

    public function offsetGet($offset) {
        return $this->vals[$offset];
    }

    public function offsetSet($offset, $value) {
        throw new Exception('unsupported operation');
    }

    public function offsetUnset($offset) {
        throw new Exception('unsupported operation');
    }
}
?>