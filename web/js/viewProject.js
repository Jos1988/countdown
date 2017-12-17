$(document).ready(function () {
    // $('.slogan-wrapper > h1').append('<span id="clock">' + $('.main-view-holder').attr('data-time') + '</span>');

    var status = $('.main-view-holder').attr('data-status');
    if (status === 'today') {
        findCountdown($('.main-view-holder').attr('data-start-time'));
    }

    if (status === 'past') {
        $('.view-item').each(function () {
            $(this).addClass('completed');
        });
    }

    armCheckboxes();
    getActionUpdates();
});

/**
 * Start loop for continues checking for updated actions.
 */
function getActionUpdates() {
    var mainViewHolder = $('.main-view-holder');
    var interval = mainViewHolder.attr('data-timeout');
    getUpdate(mainViewHolder.attr('data-project'), mainViewHolder.attr('data-last-update'), interval);
}

/**
 * Check for updated actions.
 *
 * @param project       project id
 * @param lastUpdate    Timestamp of last update received.
 * @param interval      Current interval of update loop.
 */
function getUpdate(project, lastUpdate, interval) {
    var url = '/action/pull/' + project + '/' + lastUpdate;
    setTimeout(function () {
        $.ajax(url).done(function (data) {
            var mainViewHolder = $('.main-view-holder');
            updateCheckboxes(mainViewHolder, data);
            if (undefined !== data['interval']) {
               interval = data['interval'];
            }

            getUpdate(mainViewHolder.attr('data-project'), mainViewHolder.attr('data-last-update'), interval);
        });
    }, interval);
}

function updateCheckboxes(mainViewHolder, data) {
    mainViewHolder.attr('data-last-update', data['newLastUpdate']);
    $.each(data, function (key, value) {
        if (-1 === $.inArray(key, ['interval', 'newLastUpdate'])) {
            $('#action-' + key).attr('checked', value);
        }
    });
}

/**
 * Arm Action checkboxes to send update on check or uncheck.
 */
function armCheckboxes() {
    $('.action-checkbox').click(function () {
        var checkbox = this;
        var action = checkbox.id;
        action = action.split('-');
        var url = '/action/push/' + action[1] + '/' + checkbox.checked;
        $.ajax(url);
    });
}

/**
 * Find proper view item for count-down.
 *
 * @param start
 */
function findCountdown(start) {
    $.ajax('/update').done(
        function (data) {
            var items = $('.view-item');
            var time = data['time'];
            var prevItem = {
                item: null,
                time: null
            };

            $(items).each(function () {
                checkViewItem(this, prevItem, start, time);
            });
        }
    );
}

/**
 * Handle view items.
 *
 * @param viewItem
 * @param prevItem
 * @param projectStart
 * @param time
 */
function checkViewItem(viewItem, prevItem, projectStart, time) {
    var itemDeadline = $(viewItem).attr('data-deadline');
    if (null === prevItem.time) {
        prevItem.time = projectStart;
    }

    if (time < itemDeadline && time > prevItem.time) {
        // If current time between current and previous deadline, activate viewItem.
        startCountdown(viewItem);
    }

    if (time > itemDeadline) {
        // Set viewItem as completed if its deadline has passed.
        $(viewItem).addClass('completed');
    }

    prevItem.item = $(viewItem);
    prevItem.time = itemDeadline;
}

/**
 * Start countdown on jquery object.
 *
 * @param countDown
 */
function startCountdown(countDown) {
    $.ajax('/update').done(
        function (data) {
            var now = data['time'];
            var interval = data['interval'];

            //start - nex start in seconds
            var startTime = findStartTime($(countDown));
            var endTime = $(countDown).attr('data-deadline');
            var complete = (endTime - startTime);
            now = (now - startTime);
            var progressbar = createProgressbar($(countDown), now, complete);
            toggleItemData(countDown, true, false);
            runProgressbar(progressbar, now, 1, complete, interval);
        }
    );
}

/**
 * Toggle data field corresponding to view Item.
 *
 * @param viewItem
 */
function toggleItemData(viewItem, openOnly, closeOnly) {
    var itemData = $(viewItem).parent('.view-item-wrapper').next();
    var currentStatus = $(itemData).is(':visible');
    var id = itemData[0].id;
    if (openOnly && false === currentStatus) {
        $('#' + id).foundation('toggle');
    }

    if (closeOnly && true === currentStatus) {
        $('#' + id).foundation('toggle');
    }

    if (false === closeOnly && false === openOnly) {
        $('#' + id).foundation('toggle');
    }
}

/**
 * Increment progressbar, destroy and activate next on completion.
 *
 * @param progressbar
 * @param now
 * @param increment
 * @param complete
 * @param interval
 */
function runProgressbar(progressbar, now, increment, complete, interval) {
    var counter = setInterval(function () {
        now = now + increment;
        if (now > complete) {
            clearInterval(counter);
            toggleItemData(progressbar, false, true);
            progressbar.progressbar("destroy");
            findCountdown($('.main-view-holder').attr('data-start-time'));
        } else {
            progressbar.progressbar({value: now});
        }
    }, interval);
}

/**
 * Create progressbar.
 *
 * @param countDown
 * @param now
 * @param complete
 *
 * @returns {*}
 */
function createProgressbar(countDown, now, complete) {
    return countDown.progressbar({
        value: now,
        max: complete,
        classes: {
            "ui-progressbar": "ui-corner-all count-down-bar",
            "ui-progressbar-complete": "ui-corner-right",
            "ui-progressbar-value": "ui-corner-left count-down-progress"
        }
    });
}

/**
 * Find startTime for countdown progressbar.
 *
 * @param countDown
 * @returns {number}
 */
function findStartTime(countDown) {
    var startTime = countDown.parent('.view-item-wrapper').prev().prev().find('.view-item').attr('data-deadline');
    if (typeof startTime === 'undefined') {
        return $('.main-view-holder').attr('data-start-time');
    } else {
        return startTime;
    }
}