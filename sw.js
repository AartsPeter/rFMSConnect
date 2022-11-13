self.addEventListener("install",  e => {
    e.waitUntil(
        caches.open("static").then(cache => {
            return cache.addAll(["/", "./style.css", "./images/favicon_light.png"]);
        })
    );
});

self.addEventListener("fetch", e => {
    console.log('Interceptng fetch request for : ${e.request.url}');
});