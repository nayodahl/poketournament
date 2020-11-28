import './styles/create_form.css';

import $ from 'jquery';
import 'bootstrap';

import 'autocomplete.js/dist/autocomplete.jquery';

$(document).ready(function() {
    $('.js-pokemon-autocomplete').each(function() {
        var autocompleteUrl = $(this).data('autocomplete-url');

        $(this).autocomplete({hint: true}, [
            {
                source: function(query, cb) {
                    $.ajax({
                        url: autocompleteUrl+'?query='+query
                    }).then(function(data) {
                        cb(data.pokemons);
                    });
                },
                displayKey: 'name',
                debounce: 100 // only request every 100ms
            }
        ])
    });
});