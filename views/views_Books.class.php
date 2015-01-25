<?php
class views_Books extends engine_Views_Abstract{
    protected function index() {
        include ('views/templates/books/viewListAll.php');
    }
    
    protected function add() {
        include ('views/templates/books/viewAdd.php');
    }
    
    protected function detail() {
        include ('views/templates/books/viewDetail.php');
    }
}
