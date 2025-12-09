/**
 * Bootstrap file untuk WebTransaksi
 * Dipakai oleh Vite (otomatis di-load lewat app.js)
 */

import _ from 'lodash';
window._ = _;

/**
 * Axios Setup
 */
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * ✅ Laravel Echo + Reverb (Resmi Laravel 12)
 * Tidak butuh pusher-js atau laravel-reverb-js terpisah
 */
import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'nqzlhsdtj4eka5lmydqj',
    wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

console.log('%c✅ Laravel Echo Reverb Connected!', 'color: limegreen; font-weight: bold;');

// (Opsional) pantau koneksi socket
if (window.Echo.connector?.socket) {
    window.Echo.connector.socket.on('connect', () => {
        console.log('%c🟢 Reverb Connected', 'color: #16a34a; font-weight: bold;');
    });

    window.Echo.connector.socket.on('disconnect', () => {
        console.warn('%c🔴 Reverb Disconnected', 'color: #dc2626; font-weight: bold;');
    });
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
