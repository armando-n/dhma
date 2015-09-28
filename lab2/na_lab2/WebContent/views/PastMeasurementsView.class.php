<?php 
class PastMeasurementsView{
    
    public static function show($measurements = null) {
        if (is_null($measurements)) {
            ?><p>Error: unable to show measurements. Data is missing.</p><?php
            return;
        }
        
        HeaderView::show("Your Past Measurements", true);
        PastMeasurementsView::showBody($user, $uData);
        FooterView::show(true);
    }
    
    public static function showBody($measurements) {
        ?>
<section>
    <h2>Glucose Measurements</h2>
    <table>
        <tr>
            <th>Gluclose Levels</th>
            <th>Date and Time</th>
            <th>Units</th>
        </tr><?php
        foreach ($measurements["glucose"] as $glucose) {
            ?>
            
        <tr>
         	<td><?=$glucose->getMeasurement()?></td>
           	<td><?=$glucose->getDate() . ' ' . $glucose->getTime()?></td>
           	<td><?=$glucose->getUnits()?></td>
        </tr>
<?php
        }
        ?>
    </table>
</section>        
        
<?php
    }
}
?>