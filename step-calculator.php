<?php
/**
 * Plugin Name: Step Calculator
 * Plugin URI: http://www.mywebsite.com/my-first-plugin
 * Description: Plugin that help you embeded a step calculator.
 * Version: 1.0.0
 * Author: Shay Pepper
 * Author URI: http://www.mywebsite.com
 */

function stepCalculator_shortcode() {
    $date = new DateTime();
    $calculator_js_url = plugin_dir_url( __FILE__ ) . 'js/stepCalculator.js';
    $calculator_style_url = plugin_dir_url( __FILE__ ) . 'css/style.css';

    $calculator_js_path = plugin_dir_path( __FILE__ ).'js/stepCalculator.js';
    $calculator_style_path = plugin_dir_path( __FILE__ ).'css/style.css';

    wp_enqueue_script( 'step-calculator', $calculator_js_url , array( 'jquery' ), filemtime($calculator_js_path), true );
    wp_enqueue_style( 'style',  $calculator_style_url,'', filemtime($calculator_style_path));

    //Load select2
    wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
    wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );

    //Load language
    load_plugin_textdomain( 'step-calculator', FALSE, dirname(plugin_basename(__FILE__)). '/languages/' );
    wp_localize_script( 'step-calculator', 'sc_object',
        array(
            'data_url' => plugin_dir_url( __FILE__ ) . 'data.json',
            'choose_activity_from_list' => __('choose_activity_from_list', 'step-calculator'),
            'result_no_found' =>  __('result_no_found', 'step-calculator'),
            'minutes' =>  __('minutes', 'step-calculator'),
            'steps' =>  __('steps', 'step-calculator'),
            'remove_line' =>  __('remove_line', 'step-calculator'),
            'error_choose_activity' =>  __('error_choose_activity', 'step-calculator'),
            'error_choose_specific_activity' =>  __('error_choose_specific_activity', 'step-calculator'),
            'error_choose_duration' =>  __('error_choose_duration', 'step-calculator'),
        )
    );

    $calculator_body = "

    <div id='container'>
        <div class='grid-container'>
            <div class='grid-item'>
                <label>".__('activity_type', 'step-calculator')."</label>
            </div>
            <div class='grid-item select-group'>         
                <select id='category' class='select'>
                    <option value='' disabled selected>".__('choose_activity_type', 'step-calculator')."</option>
                </select>                 
            </div>
            <div class='grid-item'>
                <label>".__('specific_activity', 'step-calculator')."</label>       
            </div>
            <div class='grid-item select-group'>  
            <select id='activity' class='select'>
                <option value='' disabled selected>".__('choose_activity_from_list', 'step-calculator')."</option>
            </select>
            </div>
            <div class='grid-item'>
                <label>".__('duration_of_activity', 'step-calculator')."</label>
                </div>
                <div class='grid-item durationField'>
                     <input id='timeDropDown' type='number' min='1' pattern='\d*' width ='50px!important'>  ".__('minutes', 'step-calculator')." 
                     
                </div>
            </div>     
            <button type='button' id='buttonAdd'>".__('add_to_list', 'step-calculator')."</button>
            <span id='error'></span>
       

        <table id='activityTable'>
            <tr id=\"tableHeader\">
                <th>".__('activity', 'step-calculator')."</th>
                <th>".__('duration', 'step-calculator')."</th>
                <th>".__('total_steps', 'step-calculator')."</th>
                <th><button type='button' id=\"buttonDeleteAll\">".__('reset_table', 'step-calculator')."</button></th>
            </tr>
              <tfoot>
                <tr>
                  <td>".__('total', 'step-calculator')."</td>
                  <td id='durationLabel'></td>
                  <td id='stepsLabel'></td>
                  <td></td>
                </tr>
              </tfoot>
        </table>
       
    </div>
    <div id=\"hue-test\" class=\"modal fade modal fade in hue-modal\">
        <div class=\"modal-dialog\">
            <div class=\"modal-content\">
                <div class=\"modal-body text-center\">
                ".__('rotate_phone_text', 'step-calculator')."
                  </div>
            </div>
        </div>
    </div>
    <div class='hue-modal-backdrop'></div>
    
   ";

    return $calculator_body;
}

// register shortcode
add_shortcode('stepCalculator', 'stepCalculator_shortcode');