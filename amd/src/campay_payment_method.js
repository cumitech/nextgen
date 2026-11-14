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

let modalObserver = null;

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
    let selectedMethod = methods.querySelector('.ng-campay-method-input:checked');
    if (!selectedMethod) {
        selectedMethod = methods.querySelector('.ng-campay-method-input[value="MOMO"]');
        if (selectedMethod) {
            selectedMethod.checked = true;
            selectedMethod.dispatchEvent(new Event('change', {bubbles: true}));
        }
    }

    const container = methods.closest('.form-check');
    const gateway = container && container.querySelector('input[name="payby"]');
    if (gateway && !gateway.checked) {
        gateway.checked = true;
        gateway.dispatchEvent(new Event('change', {bubbles: true}));
    }
};

/**
 * Replace Moodle's broken interpolated fee label with the XAF charge shown on checkout.
 *
 * @param {Element} methods
 * @returns {boolean} Whether the modal cost was updated.
 */
const updateModalCost = (methods) => {
    const modal = methods.closest('[data-region="modal-container"]');
    const fee = modal && modal.querySelector('.core_payment_fee_breakdown');
    const pageCharge = document.querySelector('.ng-checkout-xaf-price strong');
    const gatewayContainer = methods.closest('.form-check');
    const gateway = gatewayContainer && gatewayContainer.querySelector('input[name="payby"]');
    const fallbackCost = gateway && gateway.dataset.cost ? gateway.dataset.cost.trim() : '';
    const amount = (pageCharge && pageCharge.textContent.trim()) ||
        (fallbackCost && /\b(?:XAF|CFA|FCFA)\b/i.test(fallbackCost) ? fallbackCost : '');
    if (!fee || !amount) {
        return false;
    }
    if (fee.dataset.ngXafAmount === amount) {
        return true;
    }

    const labelText = fee.textContent.trim().split(':', 1)[0].trim();
    if (!labelText) {
        return false;
    }

    const label = document.createElement('span');
    label.className = 'ng-campay-modal-cost-label';
    label.textContent = labelText;

    const value = document.createElement('strong');
    value.className = 'ng-campay-modal-cost-value';
    value.textContent = amount;

    fee.classList.add('ng-campay-modal-cost');
    fee.replaceChildren(label, value);
    fee.dataset.ngXafAmount = amount;
    return true;
};

/**
 * Select Campay as the core gateway and show its XAF cost when the modal content loads.
 *
 * @returns {boolean} Whether the modal has been fully updated.
 */
const enhancePaymentModal = () => {
    const methods = document.querySelector(
        '.core_payment_gateways_modal .campay [data-region="campay-payment-methods"]'
    );
    if (!methods) {
        return false;
    }

    selectGateway(methods);
    return updateModalCost(methods);
};

/**
 * Watch the payment modal while Moodle loads its gateway and fee regions.
 */
const watchPaymentModal = () => {
    if (modalObserver) {
        modalObserver.disconnect();
    }
    const observer = new MutationObserver(() => {
        if (enhancePaymentModal()) {
            observer.disconnect();
            if (modalObserver === observer) {
                modalObserver = null;
            }
        }
    });
    modalObserver = observer;
    observer.observe(document.body, {childList: true, subtree: true});

    if (enhancePaymentModal()) {
        observer.disconnect();
        modalObserver = null;
    }
    setTimeout(() => {
        observer.disconnect();
        if (modalObserver === observer) {
            modalObserver = null;
        }
    }, 15000);
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
    document.addEventListener('click', (event) => {
        if (
            document.body.classList.contains('nextgen-enrol-checkout') &&
            event.target instanceof Element &&
            event.target.closest('[data-action="core_payment/triggerPayment"]')
        ) {
            watchPaymentModal();
        }
    });
};
