/**
 * Copyright 2016 Google Inc. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/

// DO NOT EDIT THIS GENERATED OUTPUT DIRECTLY!
// This file should be overwritten as part of your build process.
// If you need to extend the behavior of the generated service worker, the best approach is to write
// additional code and include it using the importScripts option:
//   https://github.com/GoogleChrome/sw-precache#importscripts-arraystring
//
// Alternatively, it's possible to make changes to the underlying template file and then use that as the
// new base for generating output, via the templateFilePath option:
//   https://github.com/GoogleChrome/sw-precache#templatefilepath-string
//
// If you go that route, make sure that whenever you update your sw-precache dependency, you reconcile any
// changes made to this original template file with your modified copy.

// This generated service worker JavaScript will precache your site's resources.
// The code needs to be saved in a .js file at the top-level of your site, and registered
// from your pages in order to be used. See
// https://github.com/googlechrome/sw-precache/blob/master/demo/app/js/service-worker-registration.js
// for an example of how you can register this script and handle various service worker events.

/* eslint-env worker, serviceworker */
/* eslint-disable indent, no-unused-vars, no-multiple-empty-lines, max-nested-callbacks, space-before-function-paren, quotes, comma-spacing */
'use strict';

var precacheConfig = [["dist/home/js/apps.js","22bee52cc944ca55402d96a8eb7bf3f4"],["dist/home/styles/css/styles.css","91c726f51653bf4b5d1a4df655c92341"],["dist/home/styles/fonts/fontawesome-webfont3295.eot","32400f4e08932a94d8bfd2422702c446"],["dist/home/styles/fonts/fontawesome-webfont3295.svg","f775f9cca88e21d45bebe185b27c0e5b"],["dist/home/styles/fonts/fontawesome-webfont3295.ttf","a3de2170e4e9df77161ea5d3f31b2668"],["dist/home/styles/fonts/fontawesome-webfont3295.woff","a35720c2fed2c7f043bc7e4ffb45e073"],["dist/home/styles/fonts/fontawesome-webfont3295.woff2","db812d8a70a4e88e888744c1c9a27e89"],["dist/home/styles/fonts/fontawesome-webfontd41d.eot","32400f4e08932a94d8bfd2422702c446"],["dist/img/bg-coming-soon.jpg","f913fde21fad1ee97abae281d684fba0"],["dist/img/bg-contact-us.jpg","328718df842b39671fdf1bb9cfcc0687"],["dist/img/bg-fun-fact.jpg","0061ddf21e682699a2bc98cc9f9c1017"],["dist/img/bg-quote.jpg","9201cd93a8d2fa44f02b73ac57bccfb1"],["dist/img/bg-slideshow-1.jpg","b733239fa6be29bdbef1c78f07d9737c"],["dist/img/bg-slideshow-2.jpg","0f07e4845eae33849c7e5f04ba3c71f6"],["dist/img/bg-slideshow-3.gif","64cbedd8fd1d79fc95637b8a8c1bacae"],["dist/img/bg-slideshow-4.jpg","0af6480a3bd52128cfcb44f75efe1986"],["dist/img/gallery/portfolio-image-1-l.jpg","fd66c29903d41db0ae3fa1b015ac9d57"],["dist/img/gallery/portfolio-image-1.jpg","4c1f69a74d4c12664f8d49a12322db29"],["dist/img/gallery/portfolio-image-2-l.jpg","3ed79224c588d8a1cf30daba17e40816"],["dist/img/gallery/portfolio-image-2.jpg","f05923c63002d8216948f2a9fd7164aa"],["dist/img/gallery/portfolio-image-3-l.jpg","dc64dc36b64f4fcfd17eac03da0e9ea0"],["dist/img/gallery/portfolio-image-3.jpg","3bea1cbc341f6a5df1ca318d67bff576"],["dist/img/gallery/portfolio-image-4-l.jpg","66025c00a7d062210bc031b7768ac0ad"],["dist/img/gallery/portfolio-image-4.jpg","18d85f60087662e56adc9104041dba28"],["dist/img/gallery/portfolio-image-5-l.jpg","5c1ac86fe22fbad285c3396b88f47b58"],["dist/img/gallery/portfolio-image-5.jpg","1d916c7461023299fd383f5b6b25ffdd"],["dist/img/gallery/portfolio-image-6-l.jpg","a3a3af169b0266f85bd24154eb127aff"],["dist/img/gallery/portfolio-image-6.jpg","244885fc7e768ed19a684c31eed78fc7"],["dist/img/gallery/portfolio-image-7-l.jpg","fb7c8c56781b70e035664bd6188e2a80"],["dist/img/gallery/portfolio-image-7.jpg","dea781a97d76b8672c96e10aeb9490d3"],["dist/img/gallery/portfolio-image-8-l.jpg","459567459f007d27fc037e2813bcf0fd"],["dist/img/gallery/portfolio-image-8.jpg","b384f4d045d1ea4974818ecceeff4699"],["dist/img/gallery/portfolio-image-9-l.jpg","f1df4e09060709a225681355b86681ec"],["dist/img/gallery/portfolio-image-9.jpg","a0a203e589d37eaf4d2e9e2c12dce36e"],["dist/img/preloader.gif","8b7500800c7aa8054e95673092355985"],["dist/img/preloader2.gif","ff59fac54cc0d54022ed864da7d66e12"],["dist/img/who-we-are-image-1.jpg","765d0a3c6f86366e87d1dd1ab7f2b182"],["dist/img/who-we-are-image-2.jpg","e54b809ed4c23f012fdf99c45b5e6dc5"],["dist/js/apps.js","d3e37efd0293370af5376891df1ef364"],["dist/js/apps/apikeymanagement-app.js","e6db5c5b25c4f13a236a8346f5709b0b"],["dist/js/apps/assets-app.js","e668c585f0a56e5bd4dc48882c61d1cc"],["dist/js/apps/citadeltax-app.js","cf4e7efca6b9bdd6c6b340749b3b0aa8"],["dist/js/apps/contracts-app.js","6bade01fcd3acffa6d4d1529a0667477"],["dist/js/apps/dashboard-app.js","ef9c6b4309c688a23acaeca131b53c4d"],["dist/js/apps/header.js","ff9a06d6e66062b1b87ed6a0a4340592"],["dist/js/apps/home.js","abd46238173ca41eb5ec08f489afc57a"],["dist/js/apps/itemhistory-app.js","2fa7865168e85c944c54658fd8378d23"],["dist/js/apps/login.js","f3a7b1bb6a51270e106b33c04c931c71"],["dist/js/apps/loginapikeymanagement-app.js","22c2b10ed6ac06a57b655ea3d2767989"],["dist/js/apps/luna.js","77e0173613c4d6c735e0dbfc4e5b6de2"],["dist/js/apps/marketorders-app.js","6e594940a9d1473f052e3fa037524904"],["dist/js/apps/nwtracker-app.js","1383d753eb12e20af7fbae0e10d9aaf3"],["dist/js/apps/pace_options.js","d286d47e1f61f84b3fd8ce3bf2395cb9"],["dist/js/apps/profits-app.js","a7ded6ef95e5ba64124a2579c2940484"],["dist/js/apps/recovery-app.js","4729f5049dd5c9ef5fdbe493efb27f29"],["dist/js/apps/settings-app.js","2f4ce4ff43424b0d33465121e8fbb899"],["dist/js/apps/statistics-app.js","ba7322fd9ddd0b74686bd65835ae4dc4"],["dist/js/apps/stocklists-app.js","c2ff1660c80e51ab7ab97894a2169af2"],["dist/js/apps/toastr_options.js","187502ac7fd46d7b784e73d7fd672bee"],["dist/js/apps/traderoutes-app.js","85edcecbe72ea87e697f8942389f5ed3"],["dist/js/apps/tradesimulator-app.js","a4acb7a24497aefb8ffb884e947532a8"],["dist/js/apps/transactions-app.js","f09afa721f0abd0e90f334aed00e47ec"],["dist/js/apps/validate_register.js","c834531a9e577563e948b673219731da"],["dist/luna/styles/bootstrap/fonts/glyphicons-halflings-regular.eot","f4769f9bdb7466be65088239c12046d1"],["dist/luna/styles/bootstrap/fonts/glyphicons-halflings-regular.svg","89889688147bd7575d6327160d64e760"],["dist/luna/styles/bootstrap/fonts/glyphicons-halflings-regular.ttf","e18bbf611f2a2e43afc071aa2f4e1512"],["dist/luna/styles/bootstrap/fonts/glyphicons-halflings-regular.woff","fa2772327f55d8198301fdb8bcfc8158"],["dist/luna/styles/bootstrap/fonts/glyphicons-halflings-regular.woff2","448c34a56d699c29117adc64c43affeb"],["dist/luna/styles/bootstrap/fonts/glyphicons-halflings-regulard41d.eot","f4769f9bdb7466be65088239c12046d1"],["dist/luna/styles/fontawesome/fonts/fontawesome-webfont3295.eot","32400f4e08932a94d8bfd2422702c446"],["dist/luna/styles/fontawesome/fonts/fontawesome-webfont3295.svg","f775f9cca88e21d45bebe185b27c0e5b"],["dist/luna/styles/fontawesome/fonts/fontawesome-webfont3295.ttf","a3de2170e4e9df77161ea5d3f31b2668"],["dist/luna/styles/fontawesome/fonts/fontawesome-webfont3295.woff","a35720c2fed2c7f043bc7e4ffb45e073"],["dist/luna/styles/fontawesome/fonts/fontawesome-webfont3295.woff2","db812d8a70a4e88e888744c1c9a27e89"],["dist/luna/styles/fontawesome/fonts/fontawesome-webfontd41d.eot","32400f4e08932a94d8bfd2422702c446"],["dist/luna/styles/pe-icons/Pe-icon-7-strokebb1d.eot","71394c0c7ad6c1e7d5c77e8ac292fba5"],["dist/luna/styles/pe-icons/Pe-icon-7-strokebb1d.svg","c45f7de008ab976a8e817e3c0e5095ca"],["dist/luna/styles/pe-icons/Pe-icon-7-strokebb1d.ttf","01798bc13e33afc36a52f2826638d386"],["dist/luna/styles/pe-icons/Pe-icon-7-strokebb1d.woff","b38ef310874bdd008ac14ef3db939032"],["dist/luna/styles/pe-icons/Pe-icon-7-stroked41d.eot","71394c0c7ad6c1e7d5c77e8ac292fba5"],["dist/luna/styles/pe-icons/helper.css","b041b560d4bd6a2b307610fc17db2047"],["dist/luna/styles/pe-icons/pe-icon-7-stroke.css","3d8bd60923943d7b336940fe1a2ddaac"],["dist/luna/styles/stroke-icons/stroke798b.eot","56c5b7676d5e926cd20a16732acdcfea"],["dist/luna/styles/stroke-icons/stroke798b.svg","3f60b4f463c01de3bbb85ebde87a3afa"],["dist/luna/styles/stroke-icons/stroke798b.ttf","2635d51e3d961df4ed7148b1036ac564"],["dist/luna/styles/stroke-icons/stroke798b.woff","fcc9dcbaca7fdb29fc03304714dbb2af"],["dist/luna/styles/stroke-icons/stroked41d.eot","56c5b7676d5e926cd20a16732acdcfea"],["dist/luna/styles/stroke-icons/style.css","a9928fbce14d84111df925471bd91217"],["dist/luna/styles/styles.css","d579359963ecd12cb81e4d7f105300d7"],["dist/luna/styles/theme.min.css","f708a33e6ab2ec337dd2b8466699188b"],["dist/me/app/app.min.js","c1ecf29e882095b8c0e9ca0e1e4a2f4a"],["dist/me/app/home/main-list-view.html","d55905326b205f20928f587c95433381"],["dist/me/app/home/trade-finder-view.html","d41d8cd98f00b204e9800998ecf8427e"],["dist/me/app/partials/me/chart/chart-view.html","cb9cf2e8ce9b5f57db439365a87a86f5"],["dist/me/app/partials/me/marketgroups/cats/market-sub-cats-view.html","0d2e229ee5e877e0359456fe2d9bf4e0"],["dist/me/app/partials/me/marketgroups/market-groups-view.html","41ccb2fb91c0af97589af1f4dafd9c59"],["dist/me/app/partials/me/searchbar/search-bar-view.html","fd04f9b83abd6faa1b1b1304cea0e32e"],["dist/me/css/styles.min.css","6ea869580c14fbbd57f8eb5a2664643f"],["dist/me/vendor/libs.min.js","c59a8265d0f90d25240bafa024951478"],["dist/me/vendor/libstyles.min.css","4cdd2aa179e0d848f4333e07761c641c"],["dist/polyfills/polyfills.js","d875a5e2d3e6a5c0178cf7553edbea27"]];
var cacheName = 'sw-precache-v3--' + (self.registration ? self.registration.scope : '');


var ignoreUrlParametersMatching = [/^utm_/];



var addDirectoryIndex = function (originalUrl, index) {
    var url = new URL(originalUrl);
    if (url.pathname.slice(-1) === '/') {
      url.pathname += index;
    }
    return url.toString();
  };

var cleanResponse = function (originalResponse) {
    // If this is not a redirected response, then we don't have to do anything.
    if (!originalResponse.redirected) {
      return Promise.resolve(originalResponse);
    }

    // Firefox 50 and below doesn't support the Response.body stream, so we may
    // need to read the entire body to memory as a Blob.
    var bodyPromise = 'body' in originalResponse ?
      Promise.resolve(originalResponse.body) :
      originalResponse.blob();

    return bodyPromise.then(function(body) {
      // new Response() is happy when passed either a stream or a Blob.
      return new Response(body, {
        headers: originalResponse.headers,
        status: originalResponse.status,
        statusText: originalResponse.statusText
      });
    });
  };

var createCacheKey = function (originalUrl, paramName, paramValue,
                           dontCacheBustUrlsMatching) {
    // Create a new URL object to avoid modifying originalUrl.
    var url = new URL(originalUrl);

    // If dontCacheBustUrlsMatching is not set, or if we don't have a match,
    // then add in the extra cache-busting URL parameter.
    if (!dontCacheBustUrlsMatching ||
        !(url.pathname.match(dontCacheBustUrlsMatching))) {
      url.search += (url.search ? '&' : '') +
        encodeURIComponent(paramName) + '=' + encodeURIComponent(paramValue);
    }

    return url.toString();
  };

var isPathWhitelisted = function (whitelist, absoluteUrlString) {
    // If the whitelist is empty, then consider all URLs to be whitelisted.
    if (whitelist.length === 0) {
      return true;
    }

    // Otherwise compare each path regex to the path of the URL passed in.
    var path = (new URL(absoluteUrlString)).pathname;
    return whitelist.some(function(whitelistedPathRegex) {
      return path.match(whitelistedPathRegex);
    });
  };

var stripIgnoredUrlParameters = function (originalUrl,
    ignoreUrlParametersMatching) {
    var url = new URL(originalUrl);
    // Remove the hash; see https://github.com/GoogleChrome/sw-precache/issues/290
    url.hash = '';

    url.search = url.search.slice(1) // Exclude initial '?'
      .split('&') // Split into an array of 'key=value' strings
      .map(function(kv) {
        return kv.split('='); // Split each 'key=value' string into a [key, value] array
      })
      .filter(function(kv) {
        return ignoreUrlParametersMatching.every(function(ignoredRegex) {
          return !ignoredRegex.test(kv[0]); // Return true iff the key doesn't match any of the regexes.
        });
      })
      .map(function(kv) {
        return kv.join('='); // Join each [key, value] array into a 'key=value' string
      })
      .join('&'); // Join the array of 'key=value' strings into a string with '&' in between each

    return url.toString();
  };


var hashParamName = '_sw-precache';
var urlsToCacheKeys = new Map(
  precacheConfig.map(function(item) {
    var relativeUrl = item[0];
    var hash = item[1];
    var absoluteUrl = new URL(relativeUrl, self.location);
    var cacheKey = createCacheKey(absoluteUrl, hashParamName, hash, false);
    return [absoluteUrl.toString(), cacheKey];
  })
);

function setOfCachedUrls(cache) {
  return cache.keys().then(function(requests) {
    return requests.map(function(request) {
      return request.url;
    });
  }).then(function(urls) {
    return new Set(urls);
  });
}

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(cacheName).then(function(cache) {
      return setOfCachedUrls(cache).then(function(cachedUrls) {
        return Promise.all(
          Array.from(urlsToCacheKeys.values()).map(function(cacheKey) {
            // If we don't have a key matching url in the cache already, add it.
            if (!cachedUrls.has(cacheKey)) {
              var request = new Request(cacheKey, {credentials: 'same-origin'});
              return fetch(request).then(function(response) {
                // Bail out of installation unless we get back a 200 OK for
                // every request.
                if (!response.ok) {
                  throw new Error('Request for ' + cacheKey + ' returned a ' +
                    'response with status ' + response.status);
                }

                return cleanResponse(response).then(function(responseToCache) {
                  return cache.put(cacheKey, responseToCache);
                });
              });
            }
          })
        );
      });
    }).then(function() {
      
      // Force the SW to transition from installing -> active state
      return self.skipWaiting();
      
    })
  );
});

self.addEventListener('activate', function(event) {
  var setOfExpectedUrls = new Set(urlsToCacheKeys.values());

  event.waitUntil(
    caches.open(cacheName).then(function(cache) {
      return cache.keys().then(function(existingRequests) {
        return Promise.all(
          existingRequests.map(function(existingRequest) {
            if (!setOfExpectedUrls.has(existingRequest.url)) {
              return cache.delete(existingRequest);
            }
          })
        );
      });
    }).then(function() {
      
      return self.clients.claim();
      
    })
  );
});


self.addEventListener('fetch', function(event) {
  if (event.request.method === 'GET') {
    // Should we call event.respondWith() inside this fetch event handler?
    // This needs to be determined synchronously, which will give other fetch
    // handlers a chance to handle the request if need be.
    var shouldRespond;

    // First, remove all the ignored parameters and hash fragment, and see if we
    // have that URL in our cache. If so, great! shouldRespond will be true.
    var url = stripIgnoredUrlParameters(event.request.url, ignoreUrlParametersMatching);
    shouldRespond = urlsToCacheKeys.has(url);

    // If shouldRespond is false, check again, this time with 'index.html'
    // (or whatever the directoryIndex option is set to) at the end.
    var directoryIndex = 'index.html';
    if (!shouldRespond && directoryIndex) {
      url = addDirectoryIndex(url, directoryIndex);
      shouldRespond = urlsToCacheKeys.has(url);
    }

    // If shouldRespond is still false, check to see if this is a navigation
    // request, and if so, whether the URL matches navigateFallbackWhitelist.
    var navigateFallback = '';
    if (!shouldRespond &&
        navigateFallback &&
        (event.request.mode === 'navigate') &&
        isPathWhitelisted([], event.request.url)) {
      url = new URL(navigateFallback, self.location).toString();
      shouldRespond = urlsToCacheKeys.has(url);
    }

    // If shouldRespond was set to true at any point, then call
    // event.respondWith(), using the appropriate cache key.
    if (shouldRespond) {
      event.respondWith(
        caches.open(cacheName).then(function(cache) {
          return cache.match(urlsToCacheKeys.get(url)).then(function(response) {
            if (response) {
              return response;
            }
            throw Error('The cached response that was expected is missing.');
          });
        }).catch(function(e) {
          // Fall back to just fetch()ing the request if some unexpected error
          // prevented the cached response from being valid.
          console.warn('Couldn\'t serve response for "%s" from cache: %O', event.request.url, e);
          return fetch(event.request);
        })
      );
    }
  }
});







