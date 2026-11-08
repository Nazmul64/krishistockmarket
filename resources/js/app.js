import React from 'react';
import ReactDOM from 'react-dom/client';
import HomeApp from './HomeApp';

// Mount React Home App if target element exists
document.addEventListener('DOMContentLoaded', () => {
    const rootEl = document.getElementById('react-home-root');
    if (rootEl) {
        let props = {};
        try {
            const rawData = rootEl.getAttribute('data-props');
            if (rawData) {
                props = JSON.parse(rawData);
            }
        } catch (e) {
            console.error('Failed to parse react home props:', e);
        }

        const root = ReactDOM.createRoot(rootEl);
        root.render(<HomeApp data={props} />);
    }
});
