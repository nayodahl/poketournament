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

// squares animation
const squares = document.querySelectorAll(".square");

setInterval(() => {
  squares.forEach((square) => {
    square.classList.toggle("xyz-in");
    square.classList.toggle("xyz-out");
  });
}, 3000);
