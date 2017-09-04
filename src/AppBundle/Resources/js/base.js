$(document).foundation();

$(document).ready(function () {
    console.log(Foundation.MediaQuery.current);

    sizeAppTiles(false);
    positionAppTitles();
    createFillerRow();
    makeBackgroundFillScreen();
    showNavMenu();
    setSlogan();
    prepareforms();

    //Slide in home box
    MotionUI.animateIn($('#home-box'), 'slide-in-down');

    $('#background').attr('data-mediaQuery', Foundation.MediaQuery.current);

    var oldScrollPos = 0;
    depthScrollEffect($(window).scrollTop(), oldScrollPos);
    $(window).scroll(function () {
            depthScrollEffect($(window).scrollTop(), oldScrollPos);
            oldScrollPos = $(window).scrollTop();
        }
    );
});

$(window).resize(function () {
    console.log(Foundation.MediaQuery.current);

    //Detect foundation media breakpoint transition.
    var mediaTransition = false;
    if ($('#background').attr('data-mediaQuery') !== Foundation.MediaQuery.current) {
        $('#background').attr('data-mediaQuery', Foundation.MediaQuery.current);
        mediaTransition = true;
    }

    sizeAppTiles(mediaTransition);
    positionAppTitles();
    makeBackgroundFillScreen();
    setSlogan();
});

////////////////////////////////////////////////////////////////
///////////////////////// Control //////////////////////////////
////////////////////////////////////////////////////////////////

//Open nav menu if requested.
function showNavMenu() {
    var status = $('#navigation-menu').attr('data-status');
    if ('open' === status) {
        $('#navigation-menu').foundation('open');
    }
}

////////////////////////////////////////////////////////////////
///////////////////// general styling //////////////////////////
////////////////////////////////////////////////////////////////

//Create rows that function as separators for above and below rows.
function createFillerRow() {
    var filler = $('.row.filler');
    filler.each(function () {
        var margin = $(this).data('filler');
        $(this).css('margin-top', margin + 'em');
    });
}

//create depth scroll effect.
function depthScrollEffect(currentScroll, oldScroll) {
    var background = $('#background');
    var currentBPos = 0;
    if (!isNaN(background.attr('data-pos'))) {
        currentBPos = background.attr('data-pos');
    }

    var newBPos = currentBPos - ((currentScroll - oldScroll) / 4);
    background.css('background-position', 'center' + ' ' + newBPos + 'px');
    background.attr('data-pos', newBPos);
}

//make sure background fills the screen.
function makeBackgroundFillScreen() {
    var backgroundHeight = $('#background').height();
    var windowHeight = $(window).height();

    if (backgroundHeight < windowHeight) {
        $('#background').height(windowHeight);
    }
}

///////////////////// header styling //////////////////////////
function setSlogan() {
    var homeboxHeight = $('#home-box').outerHeight();
    var sloganHeight = $('.slogan-wrapper > h1').height();
    var sloganWrapper = $('.slogan-wrapper')
    sloganWrapper.height(homeboxHeight);
    sloganWrapper.css('padding-top', (homeboxHeight - sloganHeight) / 2 + 'px');
}

////////////////////////////////////////////////////////////////
////////////////////////// form ////////////////////////////////
////////////////////////////////////////////////////////////////

/**
 * Set modal forms.
 */
function prepareforms() {
    $('.modal-form').each(function () {
        $(this).click(function () {
            loadFormInModal(
                $(this).attr('data-form'),
                $(this).attr('data-modal')
            )
        })
    });
}

/**
 * Load form into the modal and show modal.
 *
 * @param url
 * @param modal
 */
function loadFormInModal(url, modal) {
    $.ajax({
        url: url,
        dataType: 'json',
        success: function (resp) {
            modal = $('#' + modal);
            var formHolder = modal.find('.form-here')[0];
            formHolder.innerHTML = resp.formView;
            var submitButton = $(formHolder).find('button[type="submit"]')[0];
            $(submitButton).on('click', function () {
                submitFormFromModal(event, url, $(formHolder).find('form')[0]);
            });
            modal.foundation('open');
        },
        error: function (req, status, err) {
            console.log('something went wrong', status, err);
        }
    });
}

/**
 * Submit for via AJAX call, reload for if not valid, redirect if valid.
 *
 * @param clickEvent    Click event
 * @param url           Url to submit form to.
 * @param form          Form to submit.
 */
function submitFormFromModal(clickEvent, url, form) {
    clickEvent.preventDefault();
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: $(form).serialize(),
        success: function (data) {
            if (data.status === 'incomplete') {
                form.innerHTML = $(data.formView).find('form')[0].outerHTML;
                var submitButton = $(form).find('button[type="submit"]')[0];
                $(submitButton).on('click', function () {
                    submitFormFromModal(event, url, form);
                });

            } else if (data.status === 'complete') {
                window.location.replace(data.redirectUrl);
            }
        },
        error: function (req, status, err) {
            console.log('something went wrong', status, err);
        }
    });
}
