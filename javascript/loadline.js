// Thin top progress line, shown while a page is opening.
(function() {
    var bar = null;
    var inner = null;
    var value = 0;
    var trickle = null;
    var hideTimer = null;

    function ensure() {
        if (bar) {
            return;
        }
        bar = document.createElement('div');
        bar.className = 'ng-loadline';
        bar.setAttribute('aria-hidden', 'true');
        inner = document.createElement('div');
        inner.className = 'ng-loadline-bar';
        bar.appendChild(inner);
        (document.body || document.documentElement).appendChild(bar);
    }

    function set(next) {
        value = Math.max(0, Math.min(100, next));
        if (inner) {
            inner.style.width = value + '%';
        }
    }

    function start() {
        ensure();
        window.clearTimeout(hideTimer);
        window.clearInterval(trickle);
        bar.classList.add('is-active');
        bar.classList.remove('is-done');
        set(value > 0 && value < 100 ? value : 12);
        trickle = window.setInterval(function() {
            if (value < 88) {
                set(value + Math.max(0.6, (88 - value) * 0.07));
            }
        }, 180);
    }

    function done() {
        if (!bar) {
            return;
        }
        window.clearInterval(trickle);
        bar.classList.add('is-active');
        bar.classList.remove('is-done');
        set(100);
        hideTimer = window.setTimeout(function() {
            bar.classList.add('is-done');
            bar.classList.remove('is-active');
            window.setTimeout(function() {
                bar.classList.remove('is-done');
                set(0);
            }, 320);
        }, 160);
    }

    function isPlainClick(event) {
        return event.button === 0 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey;
    }

    function opensHere(anchor) {
        var href = anchor.getAttribute('href');
        if (!href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) {
            return false;
        }
        if (anchor.target && anchor.target !== '_self') {
            return false;
        }
        if (anchor.hasAttribute('download') || anchor.hasAttribute('data-bs-toggle') || anchor.hasAttribute('data-toggler')) {
            return false;
        }
        var url;
        try {
            url = new URL(anchor.href, window.location.href);
        } catch (e) {
            return false;
        }
        if (url.origin !== window.location.origin) {
            return false;
        }
        if (url.pathname === window.location.pathname && url.search === window.location.search) {
            return false;
        }
        return true;
    }

    document.addEventListener('click', function(event) {
        if (event.defaultPrevented || !isPlainClick(event)) {
            return;
        }
        var anchor = event.target && event.target.closest ? event.target.closest('a[href]') : null;
        if (!anchor || !opensHere(anchor)) {
            return;
        }
        start();
    }, true);

    document.addEventListener('submit', function(event) {
        var form = event.target;
        window.setTimeout(function() {
            if (event.defaultPrevented || !form || (form.target && form.target !== '_self')) {
                return;
            }
            start();
        }, 0);
    }, true);

    if (document.readyState !== 'complete') {
        if (document.body) {
            start();
        } else {
            document.addEventListener('DOMContentLoaded', start);
        }
        window.addEventListener('load', done);
    }

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            done();
        }
    });
}());
