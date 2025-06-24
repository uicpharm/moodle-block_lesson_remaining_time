<?php
class block_lesson_remaining_time extends block_base {

   /**
    * Initializes the block.
    *
    * @return void
    */
   public function init() {
      $this->title = get_string('pluginname', 'block_lesson_remaining_time');
   }

   /**
    * Will return a value by checking these possibilities:
    *   1. The block config object
    *   2. The block lang strings
    */
   private function get_config_or_lang_string(string $id) {
      $out = $this->config->$id;
      // If config is unavailable, it returns null. Fall back to lang string.
      if ($out===null) $out = get_string($id, 'block_lesson_remaining_time');
      // If lang string is unavailable, it defaults to "[[id]]". Instead, if
      // unavailable, return empty string.
      if ($out==="[[$id]]") $out='';
      return $out;
   }

   /**
    * Returns an array of formats for which this block can be used.
    *
    * @return array
    */
   public function applicable_formats() {
      return array(
         'mod-lesson-view' => true,
      );
   }

   /**
    * Gets the block contents.
    *
    * @return string The block HTML.
    */
   public function get_content() {
      global $DB, $USER;

      // This should always be here for blocks to make sure they only run the first time they're called.
      if ($this->content !== null) return $this->content;

      // Only run if we are on the lesson/view screen.
      if (!stripos($_SERVER['SCRIPT_FILENAME'], 'lesson/view')) return null;

      // Look up lesson data
      $id = required_param('id', PARAM_INT);
      $sql = "SELECT cm.id AS course_module_id,  cm.module,  cm.instance, cm.section, l.*
         FROM {course_modules} cm
         JOIN {modules} m ON m.id = cm.module
         JOIN {lesson} l ON  ( l.id = cm.instance  AND  cm.course = l.course )
         WHERE cm.id = :id AND m.name = 'lesson'
      ";
      $lesson = $DB->get_record_sql($sql, array('id' => $id));

      // Lesson couldn't be found. Abort.
      if (!$lesson) return null;

      // If there is no requirement, abort.
      $required_minutes = (int)($lesson->completiontimespent / 60);
      if (!$required_minutes) return null;

      // Get this user's lesson timer data. Important to sort by `id` so the most recent is first.
      $sql = "SELECT lessontime, starttime
         FROM {lesson_timer}
         WHERE userid = :userid AND lessonid = :lessonid
         ORDER BY id DESC
      ";
      $lesson_timer = $DB->get_records_sql($sql, array('userid' => $USER->id, 'lessonid' => $lesson->id ));
      $lesson_time = 0;
      $start_time = 0;
      $prev_total = 0;
      if ($lesson_timer) {
         // We get the lesson/start times of the first record, the most recently created one.
         $lesson_time = current($lesson_timer)->lessontime;
         $start_time = current($lesson_timer)->starttime;
         $firstKey = key($lesson_timer);
         // Loop thru all the rest of the records, and add up the total time.
         foreach ($lesson_timer as $key=>$rec) {
            if ($key==$firstKey) continue;
            $prev_total += $rec->lessontime - $rec->starttime;
         }
      }

      // Prepare configs
      $lang = (object)[
         'requiredTime' => $this->get_config_or_lang_string('required_time'),
         'remainingTime' => $this->get_config_or_lang_string('remaining_time'),
         'minutes' => $this->get_config_or_lang_string('minutes'),
         'complete' => $this->get_config_or_lang_string('complete'),
      ];
      $display_progress_bar = (int)$this->get_config_or_lang_string('display_progress_bar');

      $this->content = new stdClass();
      $this->content->text = "
         <div>$lang->requiredTime: $required_minutes&nbsp;$lang->minutes</div>
         <div
            class='dynamic'
            data-lang='" . json_encode($lang) . "'
            data-required-min='$required_minutes'
            data-prev-total='$prev_total'
            data-lesson-time='$lesson_time'
            data-start-time='$start_time'>
         </div>
      " . ($display_progress_bar ? "<div class='progress'><div>&nbsp;</div></div>" : "");
      $this->page->requires->js('/blocks/lesson_remaining_time/js/lesson_remaining_time.js?%%version%%');
      $this->page->requires->css('/blocks/lesson_remaining_time/css/lesson_remaining_time.css?%%version%%');

      return $this->content;
   }

}
