$(document).ready(function (){
    styleFosAlert();
    insertBackButton();
});

//style fos-user bundle login form alert
function styleFosAlert (){
    $('#fos-form div').attr('data-closable', '');
    $('#fos-form div').addClass('callout alert small');
    $('#fos-form div').append('<button class="close-button" type="button" data-close><span>&times;</span></button>');
}

//Inert back button.
function insertBackButton(){
    var homepage = $('#fos-form').attr('data-home');
    console.log(homepage);
    $('#_submit').after('<button type="button" class="button"><a href="' + homepage + '">back</a></button>');
}