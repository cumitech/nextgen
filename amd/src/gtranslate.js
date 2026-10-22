// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * GTranslate dropdown for the public site.
 *
 * The chosen language is stored in a cookie and applied again after a refresh
 * or a move to another page. The address always includes that language, as
 * /en or /fr, and the server serves the page behind that prefix. English
 * restores the original page instead of running another translation. Auth
 * pages stay in that language without a switcher.
 *
 * @module     theme_nextgen/gtranslate
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const WRAPPER = '.gtranslate_wrapper';
const SCRIPT = 'https://cdn.gtranslate.net/widgets/latest/dwf.js';
const STORAGE = 'ng-gtranslate-lang';
const COOKIE = 'ng-gtranslate';
const LANGS = ['en', 'fr', 'it', 'es'];

/**
 * Whether this code is one of the switcher languages.
 *
 * @param {string} lang
 * @returns {boolean}
 */
const known = (lang) => LANGS.indexOf(lang) !== -1;

/**
 * Language saved from an earlier visit.
 *
 * @returns {string}
 */
const remembered = () => {
    const match = document.cookie.match(new RegExp('(?:^|;\\s*)' + COOKIE + '=([^;]+)'));
    if (match) {
        const lang = decodeURIComponent(match[1]);
        if (known(lang)) {
            return lang;
        }
    }
    try {
        const lang = localStorage.getItem(STORAGE);
        return known(lang) ? lang : '';
    } catch (e) {
        return '';
    }
};

/**
 * Remember the choice for later pages.
 *
 * @param {string} lang
 */
const remember = (lang) => {
    if (!known(lang)) {
        return;
    }
    document.cookie = COOKIE + '=' + encodeURIComponent(lang) + ';path=/;max-age=31536000;SameSite=Lax';
    try {
        localStorage.setItem(STORAGE, lang);
        if (lang === 'en') {
            localStorage.removeItem('__GT_TRANSLATE_LANGS');
        } else {
            localStorage.setItem('__GT_TRANSLATE_LANGS', JSON.stringify({
                srcLang: 'en',
                tgtLang: lang,
            }));
        }
    } catch (e) {
        // The cookie still carries the choice to the next page.
    }
};

/**
 * Site path in front of the language segment. Empty when the site is the domain root.
 *
 * @returns {string}
 */
const sitePath = () => {
    const www = window.M && window.M.cfg && window.M.cfg.wwwroot;
    if (!www) {
        return '';
    }
    try {
        const path = new URL(www, window.location.origin).pathname;
        return path === '/' ? '' : path.replace(/\/$/, '');
    } catch (e) {
        return '';
    }
};

/**
 * Language segment and the Moodle path that follows it.
 *
 * @param {string} pathname
 * @returns {{lang: string, rest: string}}
 */
const splitRoute = (pathname) => {
    const base = sitePath();
    let path = pathname || '/';
    if (base && (path === base || path.indexOf(base + '/') === 0)) {
        path = path.slice(base.length) || '/';
    }
    if (path.charAt(0) !== '/') {
        path = '/' + path;
    }
    const parts = path.split('/');
    const lang = known(parts[1]) ? parts[1] : '';
    if (lang) {
        parts.splice(1, 1);
    }
    let rest = parts.join('/');
    if (!rest) {
        rest = '/';
    }
    return {lang: lang, rest: rest};
};

/**
 * Language currently written in the address.
 *
 * @returns {string}
 */
const pathLang = () => splitRoute(window.location.pathname).lang;

/**
 * Same path with the language segment set.
 *
 * @param {string} lang
 * @param {string} pathname
 * @returns {string}
 */
const withLang = (lang, pathname) => {
    if (!known(lang)) {
        return pathname;
    }
    const route = splitRoute(pathname);
    const rest = route.rest === '/' ? '' : route.rest;
    return (sitePath() + '/' + lang + rest).replace(/\/{2,}/g, '/');
};

/**
 * Write the language segment into links and forms already on the page.
 *
 * @param {string} lang
 */
const stampLinks = (lang) => {
    document.querySelectorAll('a[href]').forEach((link) => {
        if (link.closest(WRAPPER) || link.target === '_blank' || link.hasAttribute('download')) {
            return;
        }
        const rewritten = rewriteHref(link.getAttribute('href'), lang);
        if (rewritten && rewritten !== link.getAttribute('href')) {
            link.setAttribute('href', rewritten);
        }
    });
    document.querySelectorAll('form[action]').forEach((form) => {
        const rewritten = rewriteHref(form.getAttribute('action'), lang);
        if (rewritten && rewritten !== form.getAttribute('action')) {
            form.setAttribute('action', rewritten);
        }
    });
};

/**
 * Show /en or /fr in the address without loading another page.
 *
 * @param {string} lang
 */
const showLang = (lang) => {
    if (!known(lang)) {
        return;
    }
    const next = withLang(lang, window.location.pathname) + window.location.search + window.location.hash;
    const current = window.location.pathname + window.location.search + window.location.hash;
    if (next !== current) {
        window.history.replaceState(window.history.state, '', next);
    }
    stampLinks(lang);
};

/**
 * Address for an internal link, with the language segment added.
 *
 * @param {string} href
 * @param {string} lang
 * @returns {string}
 */
const rewriteHref = (href, lang) => {
    if (!href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0 || href.indexOf('mailto:') === 0) {
        return '';
    }
    let url;
    try {
        url = new URL(href, window.location.href);
    } catch (e) {
        return '';
    }
    if (url.origin !== window.location.origin) {
        return '';
    }
    const base = sitePath();
    if (base && url.pathname !== base && url.pathname.indexOf(base + '/') !== 0) {
        return '';
    }
    url.pathname = withLang(lang, url.pathname);
    return url.pathname + url.search + url.hash;
};

/**
 * Keep the language segment on links and forms that stay on this site.
 */
const keepLangOnNavigation = () => {
    const current = () => pathLang() || remembered() || 'en';
    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');
        if (!link || link.closest(WRAPPER) || link.target === '_blank' || link.hasAttribute('download')) {
            return;
        }
        const rewritten = rewriteHref(link.getAttribute('href'), current());
        if (rewritten) {
            link.href = rewritten;
        }
    }, true);
    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!form || !form.getAttribute) {
            return;
        }
        const rewritten = rewriteHref(form.getAttribute('action') || window.location.href, current());
        if (rewritten) {
            form.setAttribute('action', rewritten);
        }
    }, true);
};

/**
 * Drop Google's translation cookie so the next load is the original page.
 */
const clearGoogtrans = () => {
    const host = window.location.hostname;
    const domains = ['', host, '.' + host];
    document.cookie.split(';').forEach((part) => {
        const name = part.split('=')[0].trim();
        if (name.indexOf('goog') !== 0) {
            return;
        }
        domains.forEach((domain) => {
            const domainpart = domain ? ';domain=' + domain : '';
            document.cookie = name + '=;path=/' + domainpart + ';max-age=0;SameSite=Lax';
        });
    });
};

/**
 * Whether Google has already replaced the original text.
 *
 * @returns {boolean}
 */
const translated = () => {
    if (/translated-/.test(document.documentElement.className)) {
        return true;
    }
    const lang = (document.documentElement.getAttribute('lang') || '').toLowerCase();
    if (lang && lang.indexOf('en') !== 0) {
        return true;
    }
    const match = document.cookie.match(/(?:^|;\s*)googtrans=([^;]+)/);
    if (!match) {
        return false;
    }
    return decodeURIComponent(match[1]).slice(-3) !== '/en';
};

/**
 * Put the original English page back.
 *
 * Translating into English would rewrite the source text. A reload after the
 * Google cookie is gone serves the page Moodle rendered.
 */
const restoreEnglish = () => {
    const needsReload = translated() || remembered() !== 'en';
    remember('en');
    clearGoogtrans();
    showLang('en');
    if (needsReload) {
        window.location.reload();
        return;
    }
    showSaved('en');
    const menu = document.querySelector(WRAPPER + ' .gt_option');
    if (menu) {
        menu.style.display = 'none';
    }
    const selected = document.querySelector(WRAPPER + ' .gt_selected a');
    if (selected) {
        selected.classList.remove('open');
    }
};

/**
 * Login, register, and password reset use Moodle's login layout.
 *
 * @returns {boolean}
 */
const authPage = () => document.body.classList.contains('pagelayout-login');

/**
 * Show the saved language in the closed control.
 *
 * @param {string} lang
 */
const showSaved = (lang) => {
    const selected = document.querySelector(WRAPPER + ' .gt_selected a');
    const option = document.querySelector(WRAPPER + ' [data-gt-lang="' + lang + '"]');
    if (!selected || !option) {
        return;
    }
    selected.innerHTML = option.innerHTML;
    selected.querySelectorAll('img').forEach((img) => {
        const src = img.getAttribute('data-gt-lazy-src');
        if (src && !img.getAttribute('src')) {
            img.setAttribute('src', src);
        }
    });
};

/**
 * Load GTranslate's library, then translate into the saved language.
 *
 * @param {string} lang
 */
const translateTo = (lang) => {
    if (!known(lang) || lang === 'en') {
        if (lang === 'en') {
            remember('en');
            clearGoogtrans();
            showSaved('en');
        }
        return;
    }
    remember(lang);
    showSaved(lang);
    const start = () => {
        const translator = window.__GT && window.__GT.translator;
        if (translator && translator.libReady && document.documentElement.lang !== lang) {
            translator.translate('en', lang);
        }
    };
    if (window.__GT && window.__GT.translator && window.__GT.translator.libReady) {
        start();
        return;
    }
    if (!window.gt_translate_script) {
        const library = document.createElement('script');
        library.src = 'https://cdn.gtranslate.net/widgets/latest/lib.min.js';
        library.dataset.ngGtlib = '1';
        window.gt_translate_script = library;
        library.addEventListener('load', start);
        document.body.appendChild(library);
        return;
    }
    window.gt_translate_script.addEventListener('load', start);
};

/**
 * Load the flag dropdown and keep its choice across pages.
 */
export const init = () => {
    if (document.querySelector('script[data-ng-gtranslate]')) {
        return;
    }
    const routed = pathLang();
    const saved = routed || remembered() || 'en';
    if (routed) {
        remember(routed);
    }
    showLang(saved);
    keepLangOnNavigation();
    if (!saved || saved === 'en') {
        clearGoogtrans();
    } else {
        remember(saved);
    }
    window.gtranslateSettings = {
        default_language: 'en',
        languages: LANGS,
        wrapper_selector: WRAPPER,
        switcher_horizontal_position: 'inline',
        switcher_open_direction: 'bottom',
        flag_style: '2d',
        flag_size: 16,
        native_language_names: true,
        alt_flags: {en: 'usa'},
    };
    if (authPage()) {
        document.querySelectorAll(WRAPPER).forEach((node) => node.remove());
        translateTo(saved);
        return;
    }
    if (!document.querySelector(WRAPPER)) {
        translateTo(saved);
        return;
    }
    document.addEventListener('click', (event) => {
        const link = event.target.closest(WRAPPER + ' [data-gt-lang]');
        if (!link) {
            return;
        }
        const lang = link.getAttribute('data-gt-lang');
        if (lang === 'en') {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
            restoreEnglish();
            return;
        }
        remember(lang);
        showLang(lang);
    }, true);

    const script = document.createElement('script');
    script.src = SCRIPT;
    script.async = false;
    script.dataset.ngGtranslate = '1';
    script.addEventListener('load', () => {
        translateTo(remembered());
    });
    document.body.appendChild(script);
};
