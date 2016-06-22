<?php
class ErrorPrint {
    private $errno;
    private $errstr;
    private $errfile;
    private $errline;
    public function __construct($errno, $errstr, $errfile, $errline) {
        $this->errno = $errno;
        $this->errstr = $errstr;
        $this->errfile = $errfile;
        $this->errline = $errline;
    }
    public function doPrint() {
        echo '<pre>';
        echo $this->errstr.'('.$this->errno.') - '.$this->errfile.':'.$this->errline."\n";
        $back_trace_arrs = debug_backtrace();
        foreach($back_trace_arrs as $i => $back_trace) {
            if($i < 2) {
                continue;
            }
            echo $back_trace['function'].'(';
            foreach($back_trace['args'] as $j => $arg) {
                if($j == sizeof($back_trace['args'])-1) {
                    break;
                }
                echo self::printArg($arg);
                echo ', ';
            }
            if(sizeof($back_trace['args'])) {
                echo self::printArg($back_trace['args'][sizeof($back_trace['args'])-1]);
            }
            echo ')';
            echo ' - ';
            if(isset($back_trace['file'])) {
                echo $back_trace['file'];
            } else {
                echo '(no file)';
            }
            echo ':';
            if(isset($back_trace['line'])) {
                echo $back_trace['line'];
            } else {
                echo '(no line)';
            }
            echo "\n";
        }
        echo '</pre>';
    }
    public static function printArg($arg) {
        if(is_array($arg)) {
            echo '_array_';
            return;
        }
        if(is_object($arg)) {
            echo '_object_';
            return;
        }
        echo $arg;
    }
}
?>