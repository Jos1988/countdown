var $collectionHolder;
var $newItemLink = $('<a href="#" class="button primary add_tag_link">Add item</a>');
var $newLinkLi = $('<div class="row text-right"></div>').append($newItemLink);

$(document).ready(function () {
    $collectionHolder = $('div.items');
    $collectionHolder.find('.schedule-item').each(function () {
        addItemFormDeleteLink($(this), false);
    });

    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $newItemLink.on('click', function (e) {
        e.preventDefault();
        newItemForm($collectionHolder, $newLinkLi);
    });
});

/**
 * Create new Item form.
 *
 * @param $collectionHolder
 * @param $newLinkLi
 */
function newItemForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<fieldset class="schedule-item"></fieldset>').append(newForm);
    $newFormLi = $('<div class="row 2"></div>').append($newFormLi);
    $newLinkLi.before($newFormLi);
    addItemFormDeleteLink($newFormLi, true);
}

/**
 * Add delete button to item form.
 *
 * @param $itemFormLi
 * @param $addedFrom
 */
function addItemFormDeleteLink($itemFormLi, $addedFrom) {
    var $removeFormA = $('<div class="small-1 columns"><a class="button alert" href="#">delete</a></div>');
    if ($addedFrom) {
        $itemFormLi.find('fieldset').append($removeFormA);
    } else {
        $itemFormLi.append($removeFormA);
    }

    $removeFormA.on('click', function (e) {
        e.preventDefault();
        $itemFormLi.remove();
    });
}