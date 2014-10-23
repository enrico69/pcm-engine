$("#formulaireContact").submit(function()
{
    var messageErreur = "";

    if ($('#Nom').val().length == 0) {
        messageErreur = messageErreur + "Name is missing.\n";
    }
    if ($('#Email').val().length == 0) {
        messageErreur = messageErreur + "Email is missing.\n";
    }
    if ($('#Message').val().length == 0) {
        messageErreur = messageErreur + "Message is missing.\n";
    }
    if ($('#Anti').val() != "rouge") {
        messageErreur = messageErreur + "AntiSpam doesn't match.\n";
    }

    if (messageErreur == "") {
        $.ajax(
                {
                    type: "POST",
                    data: $(this).serialize(),
                    url: $(this).attr('action'),
                    success: XMLHttpRequest.getResponseHeader("Location"),
                    dataType: "html",
                    error: function()
                    {
                        alert('Impossible to connect to the server!')
                    }
                });
    } else {
        alert(messageErreur);
    }
    return false;
});