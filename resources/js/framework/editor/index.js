import Alpine from 'alpinejs';
import state from './state';
import config from './config';

document.addEventListener('alpine:init', () => {
    Alpine.data('richEditor', (settings = {}) => state(settings, config));
});
