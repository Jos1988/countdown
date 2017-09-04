var $collectionHolder;

// setup an "add a tag" link
var $addRoleLink = $('<a href="#" class="add_role_link">Add a role</a>');
var $newLinkLi = $('<div></div>').append($addRoleLink);

$(document).ready(function () {
    //Code for adding roles to user in user form
    $collectionHolder = $('ul.roles');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $addRoleLink.on('click', function (e) {
        e.preventDefault();
        addRoleForm($collectionHolder, $newLinkLi);
    });

    //Code for removing role from user in form.
    $collectionHolder.find('li').each(function () {
        addRoleFormDeleteLink($(this));
    });
});

//Adds a role field to the user form
function addRoleForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li class="row"></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    addRoleFormDeleteLink($newFormLi);
}

//Adds deletion link for role.
function addRoleFormDeleteLink($roleFormLi) {
    var $removeFormA = $('<div class="small-2 column"><div class="button alert">delete</div></div>');
    $roleFormLi.append($removeFormA);
    $roleFormLi.find('div').first().addClass('small-10 column');

    $removeFormA.on('click', function (e) {
        e.preventDefault();
        $roleFormLi.remove();
    });
}