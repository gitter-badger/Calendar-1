<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WeekView
 *
 * @author ermolaev
 */
class ViewWeekSelect extends ViewWeekCalendar {
    private $bug_id;

    public function __construct( $p_week, $p_user, $p_is_full_time, $p_days_events, $p_bug_id ) {
        parent::__construct( $p_week, $p_user, $p_is_full_time, $p_days_events, plugin_page( 'event_insert' ) . '&bug_id=' . $p_bug_id );
        $this->bug_id = (int)$p_bug_id;
    }

    protected function print_spacer_top() {
        echo '';
    }

    protected function print_spacer_bottom() {
        echo '<div class="space-10">';
        echo '</div>';
    }

    protected function print_headline() {

        echo '<div class="widget-header widget-header-small">';

        echo '<h4 class="widget-title lighter">';
        echo '<i class="ace-icon fa fa-list-alt"></i>';
        echo plugin_lang_get( 'insert_event' );
        echo '</h4>';

        echo '</div>';
    }

    protected function print_menu_top() {
        echo '<div class="widget-toolbox padding-8 clearfix">';
        echo '<div class="btn-toolbar">';

        echo '<div class="btn-group pull-left">';
        if( WeekCalendar::$full_time_is == FALSE ) {
            print_small_button( plugin_page( 'event_insert_page' ) . "&for_user=" . $this->for_user . "&week=" . $this->week . "&full_time=TRUE" . '&id=' . $this->bug_id, "0-24" );
        } else {
            print_small_button( plugin_page( 'event_insert_page' ) . "&for_user=" . $this->for_user . "&week=" . $this->week . '&id=' . $this->bug_id, gmdate( "H", plugin_config_get( 'time_day_start' ) ) . "-" . gmdate( "H", plugin_config_get( 'time_day_finish' ) ) );
        }
        echo '</div>';

        echo '<div class="btn-group pull-right">';
        if( WeekCalendar::$full_time_is == FALSE ) {
            print_small_button( plugin_page( 'event_insert_page' ) . "&for_user=" . $this->for_user . "&week=" . ($this->week - 1) . '&id=' . $this->bug_id, plugin_lang_get( 'previous_period' ) );
            print_small_button( plugin_page( 'event_insert_page' ) . "&for_user=" . $this->for_user . "&week=" . (int)date( "W" ) . '&id=' . $this->bug_id, plugin_lang_get( 'week' ) );
            print_small_button( plugin_page( 'event_insert_page' ) . "&for_user=" . $this->for_user . "&week=" . ($this->week + 1) . '&id=' . $this->bug_id, plugin_lang_get( 'next_period' ) );
        } else {
            print_small_button( plugin_page( 'event_insert_page' ) . "&for_user=" . $this->for_user . "&week=" . ($this->week - 1) . "&full_time=TRUE" . '&id=' . $this->bug_id, plugin_lang_get( 'previous_period' ) );
            print_small_button( plugin_page( 'event_insert_page' ) . "&for_user=" . $this->for_user . "&week=" . (int)date( "W" ) . "&full_time=TRUE" . '&id=' . $this->bug_id, plugin_lang_get( 'week' ) );
            print_small_button( plugin_page( 'event_insert_page' ) . "&for_user=" . $this->for_user . "&week=" . ($this->week + 1) . "&full_time=TRUE" . '&id=' . $this->bug_id, plugin_lang_get( 'next_period' ) );
        }
        echo '</div>';

        echo '</div>';
        echo '</div>';
    }

    protected function print_menu_bottom() {

        echo '<div class="widget-toolbox padding-8 clearfix">';
        echo '<div class="btn-toolbar">';

        echo '<div class="btn-group pull-left">';
        if( access_compare_level( access_get_project_level(), plugin_config_get( 'report_event_threshold' ) ) ) {
            if( self::$full_time_is == TRUE ) {
                print_small_button( plugin_page( 'event_add_page' ) . '&bug_id='. $this->bug_id ."&full_time=TRUE", plugin_lang_get( 'add_new_event' ) );
            } else {
                print_small_button( plugin_page( 'event_add_page' ) . '&bug_id='. $this->bug_id, plugin_lang_get( 'add_new_event' ) );
            }
        }
        echo '</div>';

        echo '<div class="btn-group pull-right">';

        echo '<form id="filter-queries-form" class="form-inline pull-left padding-left-8"  method="get" name="list_queries" action="' . plugin_page( 'event_insert_page' ) . '">';
        # CSRF protection not required here - form does not result in modifications
        echo '<input type="hidden" name="page" value="Calendar/event_insert_page" />';
        echo '<input type="hidden" name="week" value="' . $this->week . '" />';
        echo '<input type="hidden" name="full_time" value="' . (int)self::$full_time_is . '" />';
        echo '<input type="hidden" name="id" value="' . $this->bug_id . '" />';

        echo '<label class="inline">' . plugin_lang_get( 'filter_text' ) . '</label>';
        echo '<select name="for_user">';
        echo '<option value="' . auth_get_current_user_id() . '">[' . lang_get( 'reset_query' ) . ']</option>';
        if( $this->for_user == 0 ) {
            echo '<option selected="selected" value="0">[' . plugin_lang_get( 'select_all_users' ) . ']</option>';
        } else {
            echo '<option value="0">[' . plugin_lang_get( 'select_all_users' ) . ']</option>';
        }

        print_user_option_list( $this->for_user, helper_get_current_project(), plugin_config_get( 'report_event_threshold' ) );

        echo '</select>';
        echo '</form>';

        echo '</div>';

        echo '</div>';
        echo '</div>';
    }

}
