/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
/**
 * Frontend'e įtraukiamas failas.
 * Failą būtina compole'inti su `php bin/console asset-map:compile -v`
 */

import './styles/app.scss';
//import { Alert } from 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/+esm';
//import fortawesomefontawesomeFree from 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/+esm';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
import $ from 'jquery';
window.$ = $;
import Disabilities from "./disabilities.js";

$('tr[data-href]').each(function (index, object) {
    //console.log(this, index, object);
    var tr = $(object);

    $(this).on('click', function (e) {
        location.href = tr.attr('data-href');
    });

    //return false;//break
    //return;//continue
});

$(document).ready(function () {
    if ($('#questionModal form').length) {
        setTimeout(function () {
            $('.ask_question').show();
        }, 200);
    }
});

var popoverTriggerList = $('[data-bs-toggle="popover"]');
var popoverList = popoverTriggerList.map(function (index, object) {
    return new bootstrap.Popover(object, {
        html: true,
    });
})

$('.delete-action').on('click', function (e) {
    console.log(e);
    var that = $(this);

    event.preventDefault();

    var choice = confirm(this.getAttribute('data-confirm'));

    if (choice) {
        $.post({
            url: that.attr('data-confirm-url'),
            method: 'DELETE',
            //datatype: 'json',//xml, json, script, text, html
            //async: false
            //data: {},
            //beforeSend: function (jqXHR, settings) {},
            success: function (json, textStatus, jqXHR) {
                if (json.redirect) {
                    window.location.href = json.redirect;
                }
            },
            //error: function (jqXHR, textStatus, errorThrown) {
            //    console.error(jqXHR, textStatus, errorThrown);
            //},
            //complete: function (jqXHR, textStatus) {}
        });
    }

});
