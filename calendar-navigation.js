function navigate(begin, direction) {
    jQuery(document).ready(function($) {	
        $.ajax({
            url: "actions/calendar-range.php",
            type: "POST",
            data: {
                begin: begin,
                direction: direction
            },
            beforeSend: function() {
                $('.calendar-wrapper').append("<img class='loadimg' src='ring.gif'>");
                $('.calendar-content').css({"opacity":"0.4","filter":"alpha(opacity=40)","pointer-events":"none"});
            }
        }).done(function (data){
            $(".calendar-content").html(data);
        }).fail(function (jqXHR, textStatus, errorThrown){
            // Log the error to the console
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            )
        }).always(function () {
            $('.calendar-content').css({"opacity":"","filter":"","pointer-events":""});
            $( ".loadimg" ).remove();
        });
    });
};