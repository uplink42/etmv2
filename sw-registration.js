(function registerServiceWorker() {
  // register sw script in supporting browsers
  if ('serviceWorker' in navigator) {
    /*navigator.serviceWorker.register('service-worker.js', { scope: '/v2/' }).then(() => {
      console.log('Offline Service Worker registered successfully.');
    }).catch(error => {
      console.log('Service Worker registration failed:', error);
    });*/

    navigator.serviceWorker.register('service-worker-pushbots.js', { scope: '/v2/' }).then(() => {
      console.log('Pushbots Service Worker registered successfully.');
    }).catch(error => {
      console.log('Service Worker registration failed:', error);
    });
  }
})();