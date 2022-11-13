window.addEventListener('DOMContentLoaded', function () {

    function doStuff (callback) {
        callback();
    }

    doStuff(function () {
        document.body.className = 'visible';
    });
}, false);