<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @param string $class the css class to give at the chart
 * @param string $labels a string with all the label names, divided by ,
 * @param string $series a string with the series of data, divided by ,
 */

echo '<div class="'. $class .'" align="center"></div>';

echo '<script>new Chartist.Pie(\'.'. $class .'\', {series: [' .$series .']}, {showLabel: false,donut: true,donutWidth: 60,donutSolid: true,startAngle: 270,total: 12,height: 300});</script>';