
// resources/js/app.js
import $ from 'jquery';
import { Ziggy } from './ziggy';  // المسار الصحيح لملف ziggy.js
import route from 'ziggy-js'; 

if (window.location.pathname === '/categories') {
    import('./pages/categories').then(module => {
        module.default();
    });
}
if (window.location.pathname === '/transications') {
    import('./pages/transictions').then(module => {
        module.default();
    });
}
