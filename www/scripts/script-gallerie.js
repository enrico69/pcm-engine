var Largeur = 0;
var Hauteur = 0;

jQuery.fn.center = function() {

    $("#background").css('margin-top', $(document).scrollTop());

    this.css("position", "absolute");
    var bougeHaut = ($(document).scrollTop() + ($(window).height() - Hauteur) / 2);
    this.css("top", bougeHaut);
    this.css("left", ($(window).width() - Largeur) / 2 + "px");

    return this;
};

function messageChargement(statut) {
    if (statut == "in") {
        var divMessage = '<div id="divMessage" style="visibility: hidden; width:200px; height:100px; background-color:grey;"></div>';
        $("body").append(divMessage);
        $('#divMessage').css('font-weight', 'bold');
        $('#divMessage').css('font-size', 'large');
        $('#divMessage').css('line-height', '100px');
        $('#divMessage').css('text-align', 'center');
        $('#divMessage').css('vertical-align', 'middle');
        $('#divMessage').css('position', 'absolute');
        $('#divMessage').append("Loading...");
        $('#divMessage').css('visibility', 'visible');

        var bougeHaut = ($(document).scrollTop() + ($(window).height() - 100) / 2);
        $('#divMessage').css("top", bougeHaut);
        $('#divMessage').css("left", ($(window).width() - 200) / 2 + "px");
    } else if (statut == "out") {
        $('#divMessage').remove();
    } else {

    }
}

$(document).ready(function() {
    $("#conteneur-miniatures img").click(function(e) {
        messageChargement("in");


        $("#background").css({"opacity": "0.7"}).fadeIn("slow");
        var monImage = $(this);

        var urlImage = $(this).parent().attr("href");
        var img = new Image();

        img.onload = function() {
            Largeur = this.width;
            Hauteur = this.height;
            $("#large").html("<img src='" + monImage.parent().attr("href") + "' alt='" + monImage.attr("alt") + "' /><br/>" + monImage.attr("rel") + "")
                    .center()
                    .fadeIn("slow");
            messageChargement("out");
        };
        img.src = urlImage;
        return false;
    });

    function fadeOut() {
        $("#background").fadeOut("slow");
        $("#large").fadeOut("slow");
    }

    jQuery(document).ready(function()
    {
        $("body").append('<div id="background"></div>');
        $("body").append('<div id="large"></div>');
    });

    $(document).on({
        click: function() {
            fadeOut();
        }
    }, '#background');

    $(document).on({
        click: function() {
            fadeOut();
        }
    }, '#large');
});