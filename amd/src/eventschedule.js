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
 * Switch between day panels in the landing-page event schedule.
 *
 * @module     theme_nextgen/eventschedule
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {
    'use strict';

    var ROOT = '[data-ng-event-schedule]';

    /**
     * Set the selected tab and its matching panel.
     *
     * @param {HTMLButtonElement} selected
     * @param {HTMLButtonElement[]} tabs
     * @param {HTMLElement[]} panels
     */
    var selectTab = function(selected, tabs, panels) {
        tabs.forEach(function(tab) {
            var active = tab === selected;
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
            tab.tabIndex = active ? 0 : -1;
        });

        panels.forEach(function(panel) {
            panel.hidden = panel.id !== selected.getAttribute('aria-controls');
        });
    };

    /**
     * Bind day tabs in one schedule.
     *
     * @param {HTMLElement} root
     */
    var bind = function(root) {
        var tabs = Array.from(root.querySelectorAll('[role="tab"]'));
        var panels = Array.from(root.querySelectorAll('[role="tabpanel"]'));
        if (!tabs.length || !panels.length) {
            return;
        }

        root.dataset.ngEventScheduleReady = '1';
        var initial = tabs.find(function(tab) {
            return tab.getAttribute('aria-selected') === 'true';
        }) || tabs[0];
        selectTab(initial, tabs, panels);

        tabs.forEach(function(tab, index) {
            tab.addEventListener('click', function() {
                selectTab(tab, tabs, panels);
            });
            tab.addEventListener('keydown', function(event) {
                var nextIndex;
                if (event.key === 'ArrowRight') {
                    nextIndex = (index + 1) % tabs.length;
                } else if (event.key === 'ArrowLeft') {
                    nextIndex = (index - 1 + tabs.length) % tabs.length;
                } else if (event.key === 'Home') {
                    nextIndex = 0;
                } else if (event.key === 'End') {
                    nextIndex = tabs.length - 1;
                } else {
                    return;
                }
                event.preventDefault();
                tabs[nextIndex].focus();
                selectTab(tabs[nextIndex], tabs, panels);
            });
        });
    };

    /**
     * Bind each landing-page schedule.
     */
    var init = function() {
        document.querySelectorAll(ROOT).forEach(function(root) {
            if (!root.dataset.ngEventScheduleReady) {
                bind(root);
            }
        });
    };

    return {
        init: init,
    };
});
