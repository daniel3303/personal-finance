import $ from "jquery";
import 'bootstrap';
import '../../css/backend/_theme.scss';
import 'select2';
import "select2/dist/css/select2.css";
import "select2-bootstrap-theme/dist/select2-bootstrap.css";
import './form-collection';
import './confirm-form';
import 'eonasdan-bootstrap-datetimepicker';
import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css';

// Toggle the side navigation
$("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    var $sidebar = $('.sidebar');
    $("body").toggleClass("sidebar-toggled");
    $sidebar.toggleClass("toggled");
    if ($sidebar.hasClass("toggled")) {
        $('.sidebar .collapse').collapse('hide');
    }
});

// Close any open menu accordions when window is resized below 768px
$(window).resize(function() {
    if ($(window).width() < 768) {
        $('.sidebar .collapse').collapse('hide');
    }
});

// Prevent the content wrapper from scrolling when the fixed side navigation hovered over
$('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
        var e0 = e.originalEvent,
            delta = e0.wheelDelta || -e0.detail;
        this.scrollTop += (delta < 0 ? 1 : -1) * 30;
        e.preventDefault();
    }
});

// Smooth scrolling using jQuery easing
$(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
        scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
});

// File input show name
$('.custom-file-input').on('change', function (event) {
    var inputFile = event.currentTarget;
    $(inputFile).parent()
        .find('.custom-file-label')
        .html(inputFile.files[0].name);
});


window.addEventListener("load", function (event) {
    apply_select2("select.form-control");
    //Mutation observer for new select boxes in the DOM
    var observer = new MutationObserver(function(mutations) {
        console.log(mutations);
        //loop through the detected mutations(added controls)
        mutations.forEach(function(mutation) {
            //addedNodes contains all detected new controls
            if (mutation && mutation.addedNodes) {
                mutation.addedNodes.forEach(function(elm) {
                    //only apply select2 to select elements
                    if (elm && $(elm).has("select.form-control")) {
                        apply_select2($(elm).find('select.form-control'));
                    }
                });
            }
        });
    });

    // pass in the target node, as well as the observer options
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // tooltips
    $('a[title], button[title]').tooltip();
});

// Date picker
$(document).ready(function () {
    let icons = {
        time: 'fa fa-time',
        date: 'fa fa-calendar',
        up: 'fa fa-chevron-up',
        down: 'fa fa-chevron-down',
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-calendar-day',
        clear: 'fa fa-trash',
        close: 'fa fa-remove'
    };
    $('input[type="date"]').datetimepicker({
        sideBySide: true,
        showTodayButton: true,
        showClear: true,
        format: "Y-MM-DD",
        //locale: "pt",
        icons: icons
    });

    $('input[type="datetime-local"]').datetimepicker({
        sideBySide: true,
        showTodayButton: true,
        showClear: true,
        format: "Y-MM-DDTHH:mm",
        //locale: "pt",
        icons: icons
    });
});

function apply_select2(selector) {
    $(selector).select2({
        //language: "pt",
        theme: "bootstrap",
        allowClear: true,
        //placeholder: "Selecione uma opção",
        placeholder: "Select an option...",
    });
}