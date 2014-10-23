<?php

class entities_Books extends engine_Entities_Abstract {

    private $Title = "";
    private $Editor = "";
    private $Description = "";
    private $Type = "";
    private $Author = "";

    public function getTitle() {
        return $this->Title;
    }

    public function getEditor() {
        return $this->Editor;
    }

    public function getDescription() {
        return $this->Description;
    }

    public function getType() {
        return $this->Type;
    }

    public function getAuthor() {
        return $this->Author;
    }

    public function setTitle($Title) {
        $this->Title = $Title;
    }

    public function setEditor($Editor) {
        $this->Editor = $Editor;
    }

    public function setDescription($Description) {
        $this->Description = $Description;
    }

    public function setType($Type) {
        $this->Type = $Type;
    }

    public function setAuthor($Author) {
        $this->Author = $Author;
    }

}
