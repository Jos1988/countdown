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

    $('.actions-holder').each(function () {
        initActionForm(this);
    });
});

function initActionForm(actions) {
    var newActionButton = $('<div class="add_action button primary small">Add Action</div>');
    var actionHolder = $(actions);
    actionHolder.append(newActionButton);
    var count = actionHolder.find(':input').length;
    $(this).data('index', count);
    newActionButton.on('click', function (e) {
        e.preventDefault();
        addActionForm(actionHolder, newActionButton);
    });

    actionHolder.find('.action-form').each(function () {
        setActionDeleteButton(this);
    });
}

function addActionForm(actionHolder, newActionButton) {
    var prototype = actionHolder.data('prototype');
    var index = actionHolder.data('index');
    var newActionForm = prototype;
    newActionForm = newActionForm.replace('/__name__/g', index);
    actionHolder.data('index', index++);
    newActionButton.before(newActionForm);
    setActionDeleteButton($(actionHolder).find('.action-form:last'));
}

function setActionDeleteButton(actionForm) {
    var deleteButton = $(actionForm).find('.delete-action')[0];
    $(deleteButton).on('click', function (e) {
        e.preventDefault;
        actionForm.remove();
    });
}

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
    $collectionHolder.data('index', index++);
    var $newFormLi = $('<fieldset class="schedule-item"></fieldset>').append(newForm);
    $newFormLi = $('<div class="row 2"></div>').append($newFormLi);
    $newLinkLi.before($newFormLi);
    addItemFormDeleteLink($newFormLi, true);
    index--;
    //activate new dropdown pane.
    $('#actions-pane-schedule_items_' + index).foundation();
    $('#extra-pane-schedule_items_' + index).foundation();

    //Start action form.
    initActionForm($('#actions-pane-schedule_items_' + index + '> .actions-holder'));
}

/**
 * Add delete button to item form.
 *
 * @param $itemFormLi
 * @param $addedFrom
 */
function addItemFormDeleteLink($itemFormLi, $addedFrom) {
    var $removeFormA = $('<div class="small-1 end columns text-right"><a class="button alert" href="#">delete</a></div>');
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