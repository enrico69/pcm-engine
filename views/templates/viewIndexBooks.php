<h1>All my books</h1>
<?php
foreach ($this->content as $value) {
    echo "<br/><h2>".$value->getTitle()."</h2>";
    echo "<br/>Editor : ".$value->getEditor();
    echo "<br/>Description : ".$value->getDescription();
    echo "<br/>Type : ".$value->getType();
    echo "<br/>Author : ".$value->getAuthor();
}
?>
