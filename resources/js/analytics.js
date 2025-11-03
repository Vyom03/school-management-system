import { createApp } from 'vue';
import AnalyticsDashboard from './components/AnalyticsDashboard.vue';

// Wait for DOM to be ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAnalytics);
} else {
    initAnalytics();
}

function initAnalytics() {
    const mountPoint = document.getElementById('analytics-dashboard');
    if (mountPoint) {
        try {
            const app = createApp(AnalyticsDashboard);
            app.mount('#analytics-dashboard');
            console.log('Analytics Dashboard mounted successfully');
        } catch (error) {
            console.error('Error mounting Analytics Dashboard:', error);
        }
    } else {
        console.warn('Analytics dashboard mount point not found');
    }
}
