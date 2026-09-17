// Clear all caches and deactivate service worker
self.addEventListener('install', event => {
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => caches.delete(cacheName))
            );
        }).then(() => {
            return self.clients.claim();
        }).then(() => {
            return self.registration.unregister();
        })
    );
});

// Never intercept or cache requests
self.addEventListener('fetch', event => {
    return;
});

