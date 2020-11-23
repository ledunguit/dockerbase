<?php
namespace Venus;
use \Venus\Database as Database;
class Model extends Database {
    public function __construct($table = null, $map = null) {
        parent::__construct($table);
    }

    public function loadData() {

    }
    
}