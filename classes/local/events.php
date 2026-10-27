<?php
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

namespace theme_nextgen\local;

/**
 * Public events and event-management helpers backed by Moodle's site calendar.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class events {
    /** @var int Number of events shown on each results page. */
    private const PERPAGE = 9;

    /** @var int Number of days shown in the landing-page schedule. */
    private const LANDINGDAYS = 5;

    /** @var int Number of events shown on each management page. */
    private const MANAGEPERPAGE = 30;

    /** @var string Marker for NextGen-only event details stored in the calendar description. */
    private const METADATA_MARKER = 'theme_nextgen_event:';

    /**
     * Whether the current user can create or manage public site-calendar events.
     *
     * @return bool
     */
    public static function can_manage(): bool {
        global $USER;

        if (!isloggedin() || isguestuser()) {
            return false;
        }

        $systemcontext = \context_system::instance();
        return is_siteadmin($USER)
            || has_capability('moodle/site:config', $systemcontext)
            || has_capability('moodle/calendar:manageentries', $systemcontext);
    }

    /**
     * Event categories used by event forms and filters.
     *
     * @return array<string, string>
     */
    public static function types(): array {
        $types = [];
        foreach (['seminar', 'workshop', 'webinar', 'conference', 'other'] as $type) {
            $types[$type] = get_string('eventtype' . $type, 'theme_nextgen');
        }
        return $types;
    }

    /**
     * Event delivery formats used by event forms and filters.
     *
     * @return array<string, string>
     */
    public static function deliveries(): array {
        $deliveries = [];
        foreach (['online', 'inperson', 'blended'] as $delivery) {
            $deliveries[$delivery] = get_string('eventdelivery' . $delivery, 'theme_nextgen');
        }
        return $deliveries;
    }

    /**
     * Build the data needed to prefill the event form.
     *
     * @param \stdClass $event Calendar event record.
     * @return \stdClass
     */
    public static function form_data(\stdClass $event): \stdClass {
        $metadata = self::metadata($event);
        $start = (int) $event->timestart;
        $status = self::status($event, $metadata);

        return (object) [
            'id' => (int) $event->id,
            'title' => (string) $event->name,
            'eventtype' => $metadata['eventtype'] ?? 'other',
            'delivery' => $metadata['delivery'] ?? 'online',
            'summary' => $metadata['summary'] ?? self::description_summary($event),
            'imageurl' => $metadata['imageurl'] ?? '',
            'description' => self::description_text($event),
            'timestart' => $start,
            'timeend' => $start + (int) $event->timeduration,
            'venue' => (string) ($event->location ?? ''),
            'cost' => $metadata['cost'] ?? '',
            'registrationurl' => $metadata['registrationurl'] ?? '',
            'status' => $status,
        ];
    }

    /**
     * Save an event through Moodle's calendar event API.
     *
     * @param int $id Existing site-calendar event id, or 0 to create.
     * @param \stdClass $data Validated event form data.
     * @return int Calendar event id.
     */
    public static function save_event(int $id, \stdClass $data): int {
        global $CFG, $DB, $USER;

        require_once($CFG->dirroot . '/calendar/lib.php');
        $registrationurl = self::safe_registration_url((string) $data->registrationurl);
        $imageurl = self::safe_event_image_url((string) ($data->imageurl ?? ''));
        $description = self::description_with_metadata(
            trim((string) $data->description),
            [
                'eventtype' => (string) $data->eventtype,
                'delivery' => (string) $data->delivery,
                'summary' => trim((string) $data->summary),
                'imageurl' => $imageurl,
                'cost' => trim((string) $data->cost),
                'registrationurl' => $registrationurl,
                'status' => (int) $data->status,
            ]
        );
        $properties = (object) [
            'name' => trim((string) $data->title),
            'description' => $description,
            'format' => FORMAT_HTML,
            'location' => trim((string) $data->venue),
            'timestart' => (int) $data->timestart,
            'timeduration' => (int) $data->timeend - (int) $data->timestart,
            'visible' => (int) $data->status === 0 ? 0 : 1,
        ];

        if ($id > 0) {
            $record = self::editable_event($id);
            $event = \calendar_event::load($record);
            $event->update($properties);
            return $id;
        }

        $properties->eventtype = 'site';
        $properties->courseid = SITEID;
        $properties->groupid = 0;
        $properties->categoryid = 0;
        $properties->userid = (int) $USER->id;
        $properties->repeatid = 0;
        $properties->modulename = '';
        $properties->instance = 0;
        $properties->type = CALENDAR_EVENT_TYPE_STANDARD;
        $properties->timesort = (int) $data->timestart;
        $properties->uuid = '';
        $properties->sequence = 1;
        $properties->timemodified = time();

        $event = \calendar_event::create($properties);
        if (!$event) {
            throw new \moodle_exception('eventcreationfailed', 'theme_nextgen');
        }
        return (int) $event->id;
    }

    /**
     * Move one legacy theme event into Moodle's site calendar.
     *
     * @param \stdClass $legacy Legacy theme event row.
     */
    public static function migrate_legacy_event(\stdClass $legacy): void {
        global $CFG, $DB;

        require_once($CFG->dirroot . '/calendar/lib.php');
        $properties = (object) [
            'name' => (string) $legacy->title,
            'description' => self::description_with_metadata((string) $legacy->description, [
                'eventtype' => (string) $legacy->eventtype,
                'delivery' => (string) $legacy->delivery,
                'summary' => (string) $legacy->summary,
                'cost' => (string) $legacy->cost,
                'registrationurl' => self::safe_registration_url((string) $legacy->registrationurl),
                'status' => (int) $legacy->status,
            ]),
            'format' => FORMAT_HTML,
            'location' => (string) $legacy->venue,
            'courseid' => SITEID,
            'groupid' => 0,
            'categoryid' => 0,
            'userid' => 0,
            'repeatid' => 0,
            'modulename' => '',
            'instance' => 0,
            'eventtype' => 'site',
            'type' => CALENDAR_EVENT_TYPE_STANDARD,
            'timestart' => (int) $legacy->timestart,
            'timeduration' => (int) $legacy->timeend - (int) $legacy->timestart,
            'timesort' => (int) $legacy->timestart,
            'visible' => (int) $legacy->status === 0 ? 0 : 1,
            'uuid' => '',
            'sequence' => 1,
            'timemodified' => time(),
        ];

        $existing = $DB->get_record('event', [
            'name' => $properties->name,
            'timestart' => $properties->timestart,
            'eventtype' => 'site',
        ]);
        if ($existing && self::is_editable_event($existing)) {
            $event = \calendar_event::load($existing);
            $event->update((object) [
                'description' => $properties->description,
                'format' => $properties->format,
                'location' => $properties->location,
                'timeduration' => $properties->timeduration,
                'visible' => $properties->visible,
            ], false);
            return;
        }

        \calendar_event::create($properties, false);
    }

    /**
     * Upcoming public events grouped by their local start date.
     *
     * @return array
     */
    public static function landing_schedule(): array {
        $records = array_values(array_filter(self::calendar_events(), static function(\stdClass $event): bool {
            return self::status($event) > 0
                && (int) $event->timestart + (int) $event->timeduration >= time();
        }));
        usort($records, static fn(\stdClass $a, \stdClass $b): int =>
            (int) $a->timestart <=> (int) $b->timestart
        );
        $records = array_slice($records, 0, 12);

        $eventdays = [];
        foreach ($records as $record) {
            $start = (int) $record->timestart;
            $end = $start + (int) $record->timeduration;
            $datekey = userdate($start, '%Y-%m-%d');

            if (!isset($eventdays[$datekey])) {
                if (count($eventdays) >= self::LANDINGDAYS) {
                    break;
                }
                $eventdays[$datekey] = [
                    'dateid' => str_replace('-', '', $datekey),
                    'tabid' => 'ng-event-tab-' . str_replace('-', '', $datekey),
                    'panelid' => 'ng-event-panel-' . str_replace('-', '', $datekey),
                    'dateiso' => $datekey,
                    'day' => userdate($start, '%d'),
                    'month' => userdate($start, '%b'),
                    'label' => userdate($start, get_string('strftimedaydate', 'langconfig')),
                    'daytab' => get_string('eventdaytab', 'theme_nextgen', count($eventdays) + 1),
                    'tabdate' => userdate($start, get_string('eventdaydateformat', 'theme_nextgen')),
                    'active' => empty($eventdays),
                    'events' => [],
                ];
            }

            $event = self::event_card($record);
            if ($end - $start >= DAYSECS && userdate($start, '%H:%M') === '00:00') {
                $time = get_string('eventallday', 'theme_nextgen');
            } else {
                $timeformat = get_string('strftimetime', 'langconfig');
                $time = userdate($start, $timeformat) . ' - ' . userdate($end, $timeformat);
            }

            $eventdays[$datekey]['events'][] = [
                'title' => $event['title'],
                'url' => $event['url'],
                'summary' => $event['summary'],
                'type' => $event['type'],
                'delivery' => $event['delivery'],
                'time' => $time,
                'startiso' => gmdate(DATE_ATOM, $start),
                'venue' => $event['venue'],
                'hasvenue' => $event['hasvenue'],
            ];
        }

        return [
            'hasevents' => !empty($eventdays),
            'eventdays' => array_values($eventdays),
            'eventsurl' => (new \moodle_url('/events.php'))->out(false),
        ];
    }

    /**
     * Public site-calendar events, with search, filters, and pagination.
     *
     * @return array
     */
    public static function listing_context(): array {
        global $OUTPUT;

        $search = trim(optional_param('search', '', PARAM_TEXT));
        $type = optional_param('type', '', PARAM_ALPHANUMEXT);
        $delivery = optional_param('delivery', '', PARAM_ALPHANUMEXT);
        $when = optional_param('when', 'upcoming', PARAM_ALPHA);
        $page = max(0, optional_param('page', 0, PARAM_INT));
        if (!isset(self::types()[$type])) {
            $type = '';
        }
        if (!isset(self::deliveries()[$delivery])) {
            $delivery = '';
        }
        if (!in_array($when, ['upcoming', 'all', 'past'], true)) {
            $when = 'upcoming';
        }

        $now = time();
        $needle = \core_text::strtolower($search);
        $records = array_values(array_filter(self::calendar_events(), static function(\stdClass $event) use (
            $search, $needle, $type, $delivery, $when, $now
        ): bool {
            $metadata = self::metadata($event);
            $status = self::status($event, $metadata);
            $start = (int) $event->timestart;
            $end = $start + (int) $event->timeduration;
            if ($status === 0 || ($when === 'upcoming' && $end < $now) || ($when === 'past' && $end >= $now)) {
                return false;
            }
            if ($type !== '' && ($metadata['eventtype'] ?? 'other') !== $type) {
                return false;
            }
            if ($delivery !== '' && ($metadata['delivery'] ?? 'online') !== $delivery) {
                return false;
            }
            if ($search !== '') {
                $haystack = \core_text::strtolower(implode(' ', [
                    (string) $event->name,
                    (string) ($metadata['summary'] ?? self::description_summary($event)),
                    self::description_text($event),
                    (string) ($event->location ?? ''),
                ]));
                if (strpos($haystack, $needle) === false) {
                    return false;
                }
            }
            return true;
        }));

        usort($records, static function(\stdClass $a, \stdClass $b) use ($when): int {
            $order = (int) $a->timestart <=> (int) $b->timestart;
            return $when === 'past' ? -$order : $order;
        });

        $total = count($records);
        $items = array_map(
            [self::class, 'event_card'],
            array_slice($records, $page * self::PERPAGE, self::PERPAGE)
        );
        $filterurl = new \moodle_url('/events.php', [
            'search' => $search,
            'type' => $type,
            'delivery' => $delivery,
            'when' => $when,
        ]);
        $schedule = [];
        if ($search === '' && $type === '' && $delivery === '' && $when === 'upcoming' && $page === 0) {
            $schedule = self::landing_schedule();
        }

        return [
            'events' => $items,
            'hasresults' => !empty($items),
            'title' => get_string('eventspagetitle', 'theme_nextgen'),
            'resultlabel' => get_string('eventresults', 'theme_nextgen', $total),
            'search' => $search,
            'type' => $type,
            'delivery' => $delivery,
            'when' => $when,
            'periods' => self::select_options([
                'upcoming' => get_string('eventperiodupcoming', 'theme_nextgen'),
                'all' => get_string('eventperiodall', 'theme_nextgen'),
                'past' => get_string('eventperiodpast', 'theme_nextgen'),
            ], $when),
            'resultcount' => $total,
            'hasfilters' => $search !== '' || $type !== '' || $delivery !== '' || $when !== 'upcoming',
            'reseturl' => (new \moodle_url('/events.php'))->out(false),
            'eventsurl' => (new \moodle_url('/events.php'))->out(false),
            'filterurl' => $filterurl->out(false),
            'eventtypes' => self::select_options(
                ['' => get_string('eventanytype', 'theme_nextgen')] + self::types(),
                $type
            ),
            'eventdeliveries' => self::select_options(
                ['' => get_string('eventanydelivery', 'theme_nextgen')] + self::deliveries(),
                $delivery
            ),
            'paging' => $total > self::PERPAGE
                ? (new \paging_bar($total, $page, self::PERPAGE, $filterurl))->export_for_template($OUTPUT)
                : null,
            'canmanage' => self::can_manage(),
            'manageurl' => (new \moodle_url('/eventmanage.php'))->out(false),
            'homeurl' => (new \moodle_url('/'))->out(false),
            'hasschedule' => !empty($schedule['hasevents']),
            'eventdays' => $schedule['eventdays'] ?? [],
        ];
    }

    /**
     * Event listing for staff, including hidden drafts and cancelled events.
     *
     * @return array
     */
    public static function management_context(): array {
        global $OUTPUT;

        $page = max(0, optional_param('managepage', 0, PARAM_INT));
        $records = self::calendar_events(true);
        usort($records, static fn(\stdClass $a, \stdClass $b): int =>
            (int) $b->timestart <=> (int) $a->timestart
        );
        $total = count($records);
        $items = [];
        foreach (array_slice($records, $page * self::MANAGEPERPAGE, self::MANAGEPERPAGE) as $record) {
            $item = self::event_card($record);
            $item['editurl'] = (new \moodle_url('/eventmanage.php', ['id' => (int) $record->id]))->out(false);
            $items[] = $item;
        }

        $manageurl = new \moodle_url('/eventmanage.php');
        return [
            'title' => get_string('eventmanage', 'theme_nextgen'),
            'events' => $items,
            'hasresults' => !empty($items),
            'addurl' => (new \moodle_url('/eventmanage.php', ['create' => 1]))->out(false),
            'eventsurl' => (new \moodle_url('/events.php'))->out(false),
            'paging' => $total > self::MANAGEPERPAGE
                ? (new \paging_bar($total, $page, self::MANAGEPERPAGE, $manageurl, 'managepage'))->export_for_template($OUTPUT)
                : null,
        ];
    }

    /**
     * Data for the public event details page.
     *
     * @param int $id Calendar event id.
     * @return array
     */
    public static function detail_context(int $id): array {
        global $CFG, $DB;

        $event = $DB->get_record('event', ['id' => $id, 'eventtype' => 'site'], '*', MUST_EXIST);
        $metadata = self::metadata($event);
        if (self::status($event, $metadata) === 0 && !self::can_manage()) {
            throw new \moodle_exception('notfound');
        }

        $now = time();
        $cancelled = self::status($event, $metadata) === 2;
        $past = (int) $event->timestart + (int) $event->timeduration < $now;
        $description = self::description_without_metadata((string) $event->description);
        $format = (int) ($event->format ?? FORMAT_HTML);
        return [
            'event' => self::event_card($event),
            'description' => format_text($description, $format, [
                'context' => \context_system::instance(),
            ]),
            'iscancelled' => $cancelled,
            'ispast' => $past,
            'registrationurl' => self::safe_registration_url((string) ($metadata['registrationurl'] ?? '')),
            'hasregistration' => !$cancelled && !$past
                && self::safe_registration_url((string) ($metadata['registrationurl'] ?? '')) !== '',
            'canmanage' => self::can_manage() && self::is_editable_event($event),
            'manageurl' => (new \moodle_url('/eventmanage.php', ['id' => $id]))->out(false),
            'eventsurl' => (new \moodle_url('/events.php'))->out(false),
            'homeurl' => (new \moodle_url('/'))->out(false),
        ];
    }

    /**
     * Convert a Moodle calendar event into a safe, display-ready card context.
     *
     * @param \stdClass $event Calendar event record.
     * @return array
     */
    public static function event_card(\stdClass $event): array {
        $metadata = self::metadata($event);
        $start = (int) $event->timestart;
        $end = $start + (int) $event->timeduration;
        $status = self::status($event, $metadata);
        $venue = (string) ($event->location ?? '');
        $imageurl = self::safe_event_image_url((string) ($metadata['imageurl'] ?? ''));

        return [
            'id' => (int) $event->id,
            'title' => format_string((string) $event->name, true, [
                'context' => \context_system::instance(),
                'escape' => false,
            ]),
            'summary' => (string) ($metadata['summary'] ?? self::description_summary($event)),
            'imageurl' => $imageurl,
            'hasimage' => $imageurl !== '',
            'type' => self::types()[$metadata['eventtype'] ?? 'other'] ?? self::types()['other'],
            'delivery' => self::deliveries()[$metadata['delivery'] ?? 'online'] ?? self::deliveries()['online'],
            'startdate' => userdate($start, get_string('strftimedaydate', 'langconfig')),
            'day' => userdate($start, '%d'),
            'month' => userdate($start, '%b'),
            'starttime' => userdate($start, get_string('strftimetime', 'langconfig')),
            'endtime' => userdate($end, get_string('strftimetime', 'langconfig')),
            'venue' => $venue,
            'hasvenue' => trim($venue) !== '',
            'cost' => (string) ($metadata['cost'] ?? ''),
            'hascost' => trim((string) ($metadata['cost'] ?? '')) !== '',
            'url' => (new \moodle_url('/event.php', ['id' => (int) $event->id]))->out(false),
            'isupcoming' => $end >= time(),
            'iscancelled' => $status === 2,
            'statuslabel' => self::status_label($status),
            'statusclass' => [0 => 'draft', 1 => 'published', 2 => 'cancelled'][$status] ?? 'draft',
            'ispublic' => $status > 0,
            'editable' => self::is_editable_event($event),
        ];
    }

    /**
     * Load a manually managed site event for editing.
     *
     * @param int $id Calendar event id.
     * @return \stdClass
     */
    public static function editable_event(int $id): \stdClass {
        global $DB;

        $event = $DB->get_record('event', ['id' => $id, 'eventtype' => 'site'], '*', MUST_EXIST);
        if (!self::is_editable_event($event)) {
            throw new \moodle_exception('nopermissions', 'error');
        }
        return $event;
    }

    /**
     * Whether an event is a manually managed site-calendar entry.
     *
     * Events owned by a Moodle component or activity must be managed there.
     *
     * @param \stdClass $event Calendar event record.
     * @return bool
     */
    private static function is_editable_event(\stdClass $event): bool {
        return $event->eventtype === 'site'
            && empty($event->component)
            && empty($event->modulename);
    }

    /**
     * Load site-calendar records, including hidden events only when requested.
     *
     * @param bool $includehidden
     * @return \stdClass[]
     */
    private static function calendar_events(bool $includehidden = false): array {
        global $DB;

        $select = "eventtype = :eventtype";
        $params = ['eventtype' => 'site'];
        if (!$includehidden) {
            $select .= ' AND visible = :visible';
            $params['visible'] = 1;
        }
        return array_values($DB->get_records_select('event', $select, $params, 'timestart ASC'));
    }

    /**
     * Read NextGen-specific fields stored in a comment in the calendar description.
     *
     * @param \stdClass $event Calendar event record.
     * @return array
     */
    private static function metadata(\stdClass $event): array {
        $description = (string) ($event->description ?? '');
        $pattern = '/<!--\s*' . preg_quote(self::METADATA_MARKER, '/') . '([A-Za-z0-9+\/=]+)\s*-->/';
        if (!preg_match($pattern, $description, $matches)) {
            return [];
        }
        $decoded = base64_decode($matches[1], true);
        if ($decoded === false) {
            return [];
        }
        $metadata = json_decode($decoded, true);
        return is_array($metadata) ? $metadata : [];
    }

    /**
     * Remove the private metadata comment from an event description.
     *
     * @param string $description
     * @return string
     */
    private static function description_without_metadata(string $description): string {
        $pattern = '/<!--\s*' . preg_quote(self::METADATA_MARKER, '/') . '[A-Za-z0-9+\/=]+\s*-->\s*/';
        return (string) preg_replace($pattern, '', $description, 1);
    }

    /**
     * Store structured listing fields in the calendar description itself.
     *
     * @param string $description Plain-text event description.
     * @param array $metadata
     * @return string
     */
    private static function description_with_metadata(string $description, array $metadata): string {
        $encoded = base64_encode((string) json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return '<!--' . self::METADATA_MARKER . $encoded . '--><p>' . nl2br(s($description)) . '</p>';
    }

    /**
     * Convert a calendar description to plain text for the form and event summary.
     *
     * @param \stdClass $event
     * @return string
     */
    private static function description_text(\stdClass $event): string {
        $description = self::description_without_metadata((string) ($event->description ?? ''));
        $format = (int) ($event->format ?? FORMAT_HTML);
        $html = format_text($description, $format, [
            'context' => \context_system::instance(),
        ]);
        $html = preg_replace('/<\s*br\s*\/?>/i', "\n", $html);
        $html = preg_replace('/<\/(p|div|li)>/i', "\n", (string) $html);
        $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim((string) preg_replace('/[ \t]*\n[ \t]*/', "\n", $text));
    }

    /**
     * Provide a short summary for calendar events without NextGen metadata.
     *
     * @param \stdClass $event
     * @return string
     */
    private static function description_summary(\stdClass $event): string {
        $summary = trim((string) preg_replace('/\s+/', ' ', self::description_text($event)));
        if ($summary === '') {
            return get_string('eventtypeother', 'theme_nextgen');
        }
        return \core_text::substr($summary, 0, 280);
    }

    /**
     * Get event status from NextGen metadata or Moodle visibility.
     *
     * @param \stdClass $event
     * @param array|null $metadata
     * @return int
     */
    private static function status(\stdClass $event, ?array $metadata = null): int {
        $metadata = $metadata ?? self::metadata($event);
        $status = (int) ($metadata['status'] ?? (!empty($event->visible) ? 1 : 0));
        return in_array($status, [0, 1, 2], true) ? $status : 0;
    }

    /**
     * Label an event's publication state.
     *
     * @param int $status
     * @return string
     */
    private static function status_label(int $status): string {
        return get_string([
            0 => 'eventstatusdraft',
            1 => 'eventstatuspublished',
            2 => 'eventstatuscancelled',
        ][$status] ?? 'eventstatusdraft', 'theme_nextgen');
    }

    /**
     * Turn an options array into Mustache select-option contexts.
     *
     * @param array<string, string> $options
     * @param string $selected
     * @return array<int, array{value: string, label: string, selected: bool}>
     */
    private static function select_options(array $options, string $selected): array {
        $items = [];
        foreach ($options as $value => $label) {
            $items[] = [
                'value' => $value,
                'label' => $label,
                'selected' => (string) $value === $selected,
            ];
        }
        return $items;
    }

    /**
     * Only allow web URLs for external event registration.
     *
     * @param string $url
     * @return string
     */
    public static function safe_registration_url(string $url): string {
        return self::safe_http_url($url);
    }

    /**
     * Only allow web URLs for event images.
     *
     * @param string $url
     * @return string
     */
    public static function safe_event_image_url(string $url): string {
        return self::safe_http_url($url);
    }

    /**
     * Only allow HTTP and HTTPS URLs.
     *
     * @param string $url
     * @return string
     */
    private static function safe_http_url(string $url): string {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        $clean = clean_param($url, PARAM_URL);
        $scheme = strtolower((string) parse_url($clean, PHP_URL_SCHEME));
        return in_array($scheme, ['http', 'https'], true) ? $clean : '';
    }
}
