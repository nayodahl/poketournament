/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/app.less';
import './styles/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
import 'bootstrap';
import 'autocomplete.js/dist/autocomplete.jquery';

import autocomplete from 'autocomplete.js';

console.log('Hello Webpack Encore! Edit me in assets/app.js');

$(document).ready(function() {
    $('.js-pokemon-autocomplete').autocomplete({hint: false}, [
        {
            source: function(query, cb) {
                cb([
                    {value: 'foo'},
                    {value: 'bar'}
                ])
            }
        }
    ]);
});


