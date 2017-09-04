$(document).ready(function () {
    $('.slogan-wrapper > h1').append('<span id="clock">' + $('.main-view-holder').attr('data-time') + '</span>');
    updateTime();
    findCountdown();
});

/**
 * Run clock to indicate server time.
 */
function updateTime() {
    var clock = setInterval(function () {
        // clearInterval(clock);
        $.ajax('/time').done(
            function (data) {
                $('#clock').html(data['time']);
            }
        );
    }, 1000);
}

/**
 * Find proper view item for count-down.
 */
function findCountdown() {
    $.ajax('/time').done(
        function (data) {
            var items = $('.view-item');
            var time = stringTimeToSeconds(data['time']);
            var prevItem = {
                item: null,
                time: null
            };

            $(items).each(function () {
                checkViewItem(this, prevItem, time);
            });
        }
    );
}

/**
 * Handle view items.
 *
 * @param viewItem
 * @param prevItem
 * @param time
 */
function checkViewItem(viewItem, prevItem, time) {
    var itemStartTime = $(viewItem).attr('data-start');
    itemStartTime = stringTimeToSeconds(itemStartTime);
    if (itemStartTime < time) {
        // Set view items from past as disabled.
        if (null !== prevItem.item) {
            prevItem.item.addClass('completed');
        }

    } else {
        if (prevItem.time < time) {
            // Set startCountdown on current view-item
            startCountdown(prevItem.item);
        }
    }

    prevItem.item = $(viewItem);
    prevItem.time = itemStartTime;
}

/**
 * Start countdown on jquery object.
 *
 * @param countDown
 */
function startCountdown(countDown) {
    $.ajax('/time').done(
        function (data) {
            var now = stringTimeToSeconds(data['time']);

            //start - nex start in seconds
            var startTime = stringTimeToSeconds($(countDown).attr('data-start'));
            var endTime = findEndTime(countDown);
            var complete = (endTime - startTime);
            now = (now - startTime);
            var progressbar = createProgressbar(countDown, now, complete);
            runProgressbar(progressbar, now, 1, complete, 1000);
        }
    );
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
            progressbar.progressbar("destroy");
            findCountdown();
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
function createProgressbar(countDown, now, complete){
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
 * Find Endtime for countdown progressbar.
 *
 * @param countDown
 * @returns {number}
 */
function findEndTime(countDown) {
    var endTime = countDown.parent('.view-item-wrapper').next().find('.view-item').attr('data-start');
    if (typeof endTime === 'undefined') {
        return stringTimeToSeconds($('.main-view-holder').attr('data-end-time'));
    } else {
        return stringTimeToSeconds(endTime);
    }
}

/**
 * turn time string 'hh:mm:ss' to seconds.
 *
 * @param time
 * @returns {number}
 */
function stringTimeToSeconds(time) {
    var timeExp = time.split(':');
    var seconds = Number(timeExp[0]) * 3600;
    seconds = seconds + Number(timeExp[1]) * 60;

    return seconds + Number(timeExp[2]);
}