// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Keep the selected Campay method summary in sync in Moodle's payment modal.
 *
 * @module     theme_nextgen/campay_payment_method
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Update the visible and announced selection when the learner changes method.
 *
 * @param {Event} event
 */
const updateSelection = (event) => {
    const input = event.target;
    if (
        !(input instanceof HTMLInputElement) ||
        input.type !== 'radio'
    ) {
        return;
    }

    const methods = input.closest('[data-region="campay-payment-methods"]');
    if (!methods) {
        return;
    }

    selectGateway(methods);

    const label = methods.querySelector(`label[for="${input.id}"] .ng-campay-method-label`);
    const selected = methods.querySelector('[data-region="selected-method"]');
    if (label && selected) {
        selected.textContent = label.textContent.trim();
    }
};

/**
 * Select the Campay gateway when a payment method is chosen.
 *
 * @param {Element} methods
 */
const selectGateway = (methods) => {
    const container = methods.closest('.form-check');
    const gateway = container && container.querySelector('input[name="payby"]');
    if (gateway && !gateway.checked) {
        gateway.checked = true;
        gateway.dispatchEvent(new Event('change', {bubbles: true}));
    }
};

/**
 * Select the gateway even when the learner clicks the already-checked option.
 *
 * @param {MouseEvent} event
 */
const handleMethodClick = (event) => {
    const target = event.target;
    if (!(target instanceof Element)) {
        return;
    }

    const option = target.closest('.ng-campay-method-input, .ng-campay-method-card');
    const methods = option && option.closest('[data-region="campay-payment-methods"]');
    if (methods) {
        selectGateway(methods);
    }
};

/**
 * Register the selection listener.
 */
export const init = () => {
    document.addEventListener('change', updateSelection);
    document.addEventListener('click', handleMethodClick);
};
