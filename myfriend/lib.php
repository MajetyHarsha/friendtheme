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

defined('MOODLE_INTERNAL') || die();

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_myfriend_get_main_scss_content($theme) {
    global $CFG;
    $scss = '';
    $filename = 'myfriend.scss'; // Main SCSS file for this theme.

    // Prepend variables from settings if any - example structure
    // $scss .= theme_myfriend_get_pre_scss($theme);

    // Main theme SCSS.
    // Simpler approach for a starter theme:
    $mainscss = file_get_contents($CFG->dirroot . '/theme/myfriend/scss/' . $filename);
    $scss .= $mainscss;

    // Post-scss from settings if any - example structure
    // $scss .= theme_myfriend_get_extra_scss($theme);

    return $scss;
}
