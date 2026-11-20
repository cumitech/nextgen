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
 * Display-only course currency selector.
 *
 * @module     theme_nextgen/currency
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const STORAGE = 'ng-display-currency';
const SWITCHER = '.ng-currency-switch';
const PRICE = '[data-ng-price-amount][data-ng-price-currency]';

/**
 * Render a numeric price in the selected display currency.
 *
 * @param {number} amount
 * @param {string} currency
 * @returns {string}
 */
const formatPrice = (amount, currency) => {
    const options = {style: 'currency', currency: currency, currencyDisplay: 'code'};
    if (currency === 'USD') {
        options.minimumFractionDigits = 0;
        options.maximumFractionDigits = 0;
    }
    return new Intl.NumberFormat(document.documentElement.lang || 'en', options).format(amount);
};

/**
 * Apply the display currency without changing any payment data.
 *
 * @param {HTMLSelectElement} select
 * @param {number} xafPerUsd
 */
const updatePrices = (select, xafPerUsd) => {
    const target = select.value;
    document.querySelectorAll(PRICE).forEach((node) => {
        const amount = Number(node.dataset.ngPriceAmount);
        const source = node.dataset.ngPriceCurrency;
        if (!Number.isFinite(amount) || amount <= 0 || !['XAF', 'USD'].includes(source)) {
            return;
        }
        const converted = source === target
            ? amount
            : (source === 'XAF' ? amount / xafPerUsd : amount * xafPerUsd);
        node.textContent = formatPrice(target === 'USD' ? Math.round(converted) : converted, target);
    });
};

/**
 * Initialise the display currency selector and restore the visitor's choice.
 */
export const init = () => {
    const wrapper = document.querySelector(SWITCHER);
    if (!wrapper) {
        return;
    }

    const select = wrapper.querySelector('select');
    const xafPerUsd = Number(wrapper.dataset.ngXafPerUsd);
    if (!select || !Number.isFinite(xafPerUsd) || xafPerUsd <= 0) {
        return;
    }

    let currency = 'XAF';
    try {
        const stored = localStorage.getItem(STORAGE);
        if (stored === 'XAF' || stored === 'USD') {
            currency = stored;
        }
    } catch (e) {
        // The selector remains usable when browser storage is unavailable.
    }

    select.value = currency;
    updatePrices(select, xafPerUsd);
    select.addEventListener('change', () => {
        updatePrices(select, xafPerUsd);
        try {
            localStorage.setItem(STORAGE, select.value);
        } catch (e) {
            // The selection still applies to the current page.
        }
    });
};
