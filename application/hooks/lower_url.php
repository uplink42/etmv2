<?php

class LowerUrl {
    public function run() {
            $_SERVER['REQUEST_URI'] = strtolower($_SERVER['REQUEST_URI']);
    }
}