$(document).ready(function () {
    $('.slogan-wrapper > h1').append('<span id="clock">' + $('.main-view-holder').attr('data-time') + '</span>');
    updateTime();

    var status = $('.main-view-holder').attr('data-status');
    if (status === 'today') {
        findCountdown( $('.main-view-holder').attr('data-start-time'));
    }

    if (status === 'past') {
        $('.view-item').each(function () {
            $(this).addClass('completed');
        });

    }

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
 *
 * @param start
 */
function findCountdown(start) {
    $.ajax('/time').done(
        function (data) {
            var items = $('.view-item');
            var time = stringTimeToSeconds(data['time']);
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
    projectStart = stringTimeToSeconds(projectStart);
    itemDeadline = stringTimeToSeconds(itemDeadline);
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
    $.ajax('/time').done(
        function (data) {
            var now = stringTimeToSeconds(data['time']);

            //start - nex start in seconds
            var startTime = findStartTime($(countDown));
            var endTime = stringTimeToSeconds($(countDown).attr('data-deadline'));
            var complete = (endTime - startTime);
            now = (now - startTime);
            var progressbar = createProgressbar($(countDown), now, complete);
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
    var startTime = countDown.parent('.view-item-wrapper').prev().find('.view-item').attr('data-deadline');
    if (typeof startTime === 'undefined') {
        return stringTimeToSeconds($('.main-view-holder').attr('data-start-time'));
    } else {
        return stringTimeToSeconds(startTime);
    }
}

/**
 * Turn time string 'hh:mm:ss' to seconds.
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