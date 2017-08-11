
/**
 * Laravel Easy Log
 */

window.$ = window.jQuery = require('jquery');
try {
    window._ = require('lodash');
    window.$ = window.jQuery = require('jquery');
    require('bootstrap-sass');
} catch (e) {
    console.log(e);
}

import {lel_multiselect} from '../js/lib/multiselect';
import {lel_daterange} from '../js/lib/daterange';

$(function () {
    lel_multiselect.init();
    lel_daterange.init();
});
