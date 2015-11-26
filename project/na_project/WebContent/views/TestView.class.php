<?php

class TestView {
    
    public static function show() {
        if (!isset($_SESSION['styles']))
            $_SESSION['styles'] = array();
        if (!isset($_SESSION['scripts']))
            $_SESSION['scripts'] = array();
        $_SESSION['libraries'][] = 'highcharts.js';
        HeaderView::show("Test");
        ?>
        
        <section class="row">
        	<div id="chartArea" class="col-sm-12" style="height: 400px">
                
            </div>
            <button id="button" class="autocompare">Set extremes</button>
        </section>
        
<!--         <script src="https://code.highcharts.com/highcharts.js"></script> -->
<!--         <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
        <script>
            $(function () {
                $('#chartArea').highcharts({
    
                    series: [{
                        data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
                    }]
    
                });
    
    
                // the button action
                $('#button').click(function () {
                    var chart = $('#chartArea').highcharts();
                    chart.xAxis[0].setExtremes(0, 5);
                });
            });
        </script>
        <?php
        FooterView::show();
    }
    
}

?>