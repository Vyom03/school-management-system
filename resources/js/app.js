import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Chart.js is loaded via CDN in the layout, so we don't import it here
// This avoids conflicts between Vite bundle and CDN

Alpine.start();
