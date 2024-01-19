<?php
/**
 * Block edit form class for the block_lesson_remaining_time plugin.
 *
 * @package   block_lesson_remaining_time
 * @copyright 2024, Josh Curtiss <josh@curtiss.me>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_lesson_remaining_time_edit_form extends block_edit_form {
   protected function specific_definition($mform) {
      $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

      $mform->addElement('text', 'config_required_time', get_string('config_required_time', 'block_lesson_remaining_time'));
      $mform->setType('config_required_time', PARAM_TEXT);
      $mform->setDefault('config_required_time', get_string('required_time', 'block_lesson_remaining_time'));

      $mform->addElement('text', 'config_remaining_time', get_string('config_remaining_time', 'block_lesson_remaining_time'));
      $mform->setType('config_remaining_time', PARAM_TEXT);
      $mform->setDefault('config_remaining_time', get_string('remaining_time', 'block_lesson_remaining_time'));

      $mform->addElement('text', 'config_complete', get_string('config_complete', 'block_lesson_remaining_time'));
      $mform->setType('config_complete', PARAM_TEXT);
      $mform->setDefault('config_complete', get_string('complete', 'block_lesson_remaining_time'));

      $mform->addElement('select', 'config_display_progress_bar', get_string('config_display_progress_bar', 'block_lesson_remaining_time'), [
         '1' => get_string('display', 'block_lesson_remaining_time'),
         '0' => get_string('hide', 'block_lesson_remaining_time'),
      ]);
      $mform->setType('config_display_progress_bar', PARAM_INT);
      $mform->setDefault('config_display_progress_bar', true);
   }
}