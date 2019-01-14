//tynimce plugin for text area
tinymce.init({
    selector:'textarea',
    resize: false,
    height : "350"
});

    //function for cookie
$(function() {
    //Read the cookie, if it has been previously set
    var language = $.cookie( 'language' );

    //Set language to previously set value
    !language || $('#languages').val( language );

    $('#languages').on('change', function() {
        language = this.value;
        $.cookie( 'language', language );
    })
        .change();
});

//Validate email by regex
function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

//Validate email function
function validate() {
    var $result = $("#result");
    var email = $("#email").val();
    $result.text("");

    if (validateEmail(email)) {
        $result.text(email + " is valid : email)");
        $result.css("color", "green");
        document.getElementById("id").submit();
    } else {
        $result.text(email + " is not valid email");
        $result.css("color", "red");
    }
    return false;
}

$("#validate").bind("click", validate);