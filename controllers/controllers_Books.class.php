<?php

class controllers_books extends engine_Controllers_Abstract {

    private $booksManager = "";
    private $booksView = "";

    public function __construct($Request = "") {
        parent::__construct($Request);
        $this->booksManager = new dbFunctions_Books();
        $this->booksView = new views_Books();
    }

    protected function index() {
        
        //Code Exemple : list All books
        $theBooks = $this->booksManager->selectAll();
        $myMeta = $this->moduleMeta->readMeta(get_class($this), __FUNCTION__, "Ma blibliothèque...");
        $theView = $this->booksView->getView('Index', $theBooks);
        return array('view' => $theView, 'meta' => $myMeta);
    }
    
    protected function add(){
        $myMeta = $this->moduleMeta->readMeta(get_class($this), __FUNCTION__, "Ma blibliothèque...");
        return array('view' => $this->booksView->getView("Add"), 'meta' => $myMeta);
        
        //Insertion to be implemented...
        //For exemple : 
        //Assuming your books is in a $_POST array
        //$theBook = new entities_Books();
        //$theBook->hydrate($_POST);
        //$this->booksManager->Save($theBook);
    }

}
