<?php
class HomeView {
    
    public static function show() {
        HeaderView::show("Diabetic Health Management Assistant");
        HomeView::showBody();
        FooterView::show();
    }
    
    public static function showBody() {
        ?>
<div class="jumbotron">
    <h1>DHMA</h1>
    <p>
        Track measurements important to diabetics: blood glucose levels,
        exercise, sleep, blood pressure, calories consumed, and weight.
    </p>
</div>
<div class="row">
    <div class="col-md-9">
        
        <div class="row">
        
            <div class="col-md-12">
            <!-- <section id="site-info"> -->
                <h3>Overview</h3>
                <p>
                    This web site serves as a helpful tool which diabetics can use to assist
                    in keeping track of blood glucose level measurements, as well as a few
                    other measurements that are important to someone with diabetes. Charts
                    allow you to see trends over time. Targets can be set. Recommendations
                    from your doctor can be set up once and show up whenever your targets
                    are not reached. The other measurements that can be kept track of are
                    blood pressure, weight, calories consumed, sleep, and exercise.
                </p>
            </div>
            
        </div>

        <section id="screenshots">
            <div class="row">
                <div class="col-md-offset 2 col-md-8">
                    <h3>Screenshots</h3>
                </div>
            </div>
            <div class="row">    
                <div class="col-sm-6">
                    <a href="images/screenshots/ss_measurements-default_light.png">
                        <img src="images/screenshots/ss_measurements-default_light.png" alt="Screenshot of default measurements view (light)" class="img-responsive img-thumbnail" />
                    </a>
                    <p>Measurements View (light)</p>
                </div>
                <div class="col-sm-6">
                    <a href="images/screenshots/ss_measurements-default_dark.png">
                        <img src="images/screenshots/ss_measurements-default_dark.png" alt="Screenshot of default measurements view (dark)" class="img-responsive img-thumbnail" />
                    </a>
                    <p>Measurements View (dark)</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <a href="images/screenshots/ss_measurements-edit_light.png">
                        <img src="images/screenshots/ss_measurements-edit_light.png" alt="Screenshot of edit measurements view (light)" class="img-responsive img-thumbnail" />
                    </a>
                    <p>Editing Measurements (light)</p>
                </div>
                <div class="col-sm-6">
                    <a href="images/screenshots/ss_measurements-edit_dark.png">
                        <img src="images/screenshots/ss_measurements-edit_dark.png" alt="Screenshot of edit measurements view (dark)" class="img-responsive img-thumbnail" />
                    </a>
                    <p>Editing Measurements (dark)</p>
                </div>
            </div>
        </section>
        
    </div>
    
    <div class="col-md-3">
        <aside id="faq">
            <h2>Frequently Asked Questions</h2>
            <ul class="list-unstyled list-group">
                <li class="list-group-item"><a href="faq#purpose">What is the purpose of this site??</a></li>
                <li class="list-group-item"><a href="faq#privacy">Who can see my information?</a></li>
            </ul>
            <a href="faq">More Questions ...</a>
        </aside>
    </div>

</div><!-- end of row --
        
       

<?php
    }
}
?>