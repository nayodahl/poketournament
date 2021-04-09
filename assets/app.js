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

// Importing bootsrap Javascript
const $ = require('jquery');
require('bootstrap');

// start the Stimulus application
import './bootstrap';

require('@fortawesome/fontawesome-free/css/all.min.css');

// swup page transitions
import Swup from 'swup';
import SwupOverlayTheme from '@swup/overlay-theme';

const swup = new Swup({
  plugins: [
    new SwupOverlayTheme({
      color: '#17a2b8',    
      duration: 500,
    }),
  ]
});

// bootstrap-table for pokedex page
import 'bootstrap-table/dist/bootstrap-table.min.css';
import 'bootstrap-table';
import 'bootstrap-table/dist/locale/bootstrap-table-fr-FR';
import 'bootstrap-table/dist/extensions/export/bootstrap-table-export';
import './libs/tableExport.min.js';
import './libs/FileSaver.min.js';

// autocompletion for create tournament form
import './styles/create_form.css';
import 'autocomplete.js/dist/autocomplete.jquery';


function init(){

  // squares animation
  const squares = document.querySelectorAll(".square");

  setInterval(() => {
    squares.forEach((square) => {
      square.classList.toggle("xyz-in");
      square.classList.toggle("xyz-out");
    });
  }, 3000);

  // bootstrap-table for pokedex page
  $('#table').bootstrapTable({
    locale: 'fr-FR',
    url: 'utility/pokedex',
    pagination: true,
    search: true,
    exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
    columns: [
      {
        field: 'apiId',
        title: 'N° national',
        sortable: true,
        width: 80,
        formatter: (value, row) => {
          return '<a style="text-decoration: none; color: inherit;" href="/pokedex/' + row.slug +'">' + row.apiId + '</a>'
        }
      },
      {
        field: 'name',
        title: 'Nom', 
        sortable: true,
        formatter: (value, row) => {
          return '<a style="text-decoration: none; color: inherit;" href="/pokedex/' + row.slug +'">' + row.name + '</a>'
        }
      },
      {
        field: 'image',
        title: 'Image',
        width: 200,
        formatter: (value, row) => {
          return '<a href="/pokedex/' + row.slug +'"><img class="card-img-top mx-auto" src=\'images/' + row.apiId + '.png\' alt="pokemon avatar"></a>'
        }
      },
      {
        field: 'type1.name',
        title: 'Type 1', 
        sortable: true,
        width: 100,
      },
      {
        field: 'type2.name',
        title: 'Type 2', 
        sortable: true,
        width: 100,
      },
      {
        field: 'generation.apiId',
        title: 'Gén.', 
        width: 50,
      },
      {
        field: 'color',
        title: 'Couleur',
        width: 100,
      },
    ]
  })


  // autocompletion for create tournament form
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
}

init();

// once swup transition is used to replace content, this event is triggered, launch init function so that other JS code of the page is executed
document.addEventListener('swup:contentReplaced', init);

