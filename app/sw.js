const CACHE_NAME = 'vidapro-static-v1';
const STATIC_ASSETS = [
  './',
  './index.html',
  './styles.css',
  './app.js',
  './manifest.json',
  './icons/icon-192.svg',
  './icons/icon-512.svg',
  './images/comidas/bowl-verde.svg',
  './images/rutinas/cardio.svg',
  './images/habitos/meditacion.svg'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const { request } = event;
  if (request.url.includes('/api/')) {
    event.respondWith(networkFirst(request));
  } else {
    event.respondWith(cacheFirst(request));
  }
});

function cacheFirst(request) {
  return caches.match(request).then((cached) => cached || fetch(request));
}

function networkFirst(request) {
  return fetch(request)
    .then((response) => {
      const clone = response.clone();
      caches.open('vidapro-api').then((cache) => cache.put(request, clone));
      return response;
    })
    .catch(() => caches.match(request));
}

self.addEventListener('push', (event) => {
  const data = event.data?.json() || { title: 'VidaPro+', body: 'Sigue moviéndote hoy 💪' };
  event.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: 'icons/icon-192.svg'
    })
  );
});

self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  event.waitUntil(
    self.clients.matchAll({ type: 'window' }).then((clientsArr) => {
      const hadWindow = clientsArr.some((windowClient) => windowClient.url.includes('app/index.html'));
      if (!hadWindow && self.clients.openWindow) {
        return self.clients.openWindow('./index.html');
      }
    })
  );
});
