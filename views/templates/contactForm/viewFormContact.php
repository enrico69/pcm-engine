<h1>Contact</h1>
<form class="" id="formulaireContact" method="POST" action="<?php echo $GLOBALS['Application']->getUrlSite();?>contact/">
    <p>
        <strong>Name : </strong><input id="Nom" name="Nom" type="text" value="">
    </p>
    <p>
        <strong>Email : </strong><input id="Email" name="Email" type="text" value="">
    </p>
    <p><strong>Your Message :</strong></p>
    <p>
        <textarea name="Message" id="Message" cols="70"></textarea>
    </p>
    <p>
        <strong>AntiSpam :</strong> What is the color of a tomato (in lowercase)? : <input id="Anti" name="Anti" type="text" value="">
    </p>
    <p>
        <input name="Verif" id="Verif" type="hidden" value="ICI">
    </p>
    <input id="Go" type="submit" value="Envoyer"> 
</form>
<script src="<?php echo $GLOBALS['Application']->getUrlSite() . 'scripts/contact-form.js'; ?>" type="text/javascript"></script> 