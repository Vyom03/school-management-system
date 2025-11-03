import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Chart.js is loaded via CDN in the layout, so we don't import it here
// This avoids conflicts between Vite bundle and CDN

// Vue.js is available for new complex components
// Alpine.js will continue to be used for existing simple interactions

Alpine.start();

// Export Vue createApp for use in Blade templates
export { createApp } from 'vue';
