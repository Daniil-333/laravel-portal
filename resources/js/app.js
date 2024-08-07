import './bootstrap';

import Alpine from 'alpinejs';
import {Base} from './common/base.js';

window.Alpine = Alpine;

window.addEventListener('load', () => {
    window.Base = new Base();
})

Alpine.start();
