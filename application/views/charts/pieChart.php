<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @param string $class the css class to give at the chart
 * @param string $labels a string with all the label names, divided by ,
 * @param string $series a string with the series of data, divided by ,
 */

echo '<div class="'. $class .'" align="center"></div>';

echo '<script>var data = {labels: [\'' .$labels .'\'],series: []};var options = {stretch: true,labelInterpolationFnc: function(value) {return value[0]}};var responsiveOptions = [[\'screen and (min-width: 640px)\',{chartPadding: 30,labelOffset: 100,labelDirection: \'explode\',labelInterpolationFnc: function(value) {return value;}}],[\'screen and (min-width: 1024px)\', {labelOffset: 0,chartPadding: 0,height: 400}]];new Chartist.Pie(\'.'. $class .'\', data, options, responsiveOptions);</script>';