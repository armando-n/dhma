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
    <div>
        <a href="demo" class="btn btn-info btn-lg">
            Try Demo!
        </a>
    </div>
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
                    allow you to see trends over time. The other measurements that can be kept
                    track of are blood pressure, weight, calories consumed, sleep, and exercise.
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
                <div class="col-sm-6 col-lg-4">
                    <a href="images/screenshots/ss_measurements-default.png">
                        <img src="images/screenshots/ss_measurements-default.png" alt="Screenshot of default measurements view" class="img-responsive img-thumbnail" />
                    </a>
                    <p>Measurements View</p>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="images/screenshots/ss_measurements-edit.png">
                        <img src="images/screenshots/ss_measurements-edit.png" alt="Screenshot of edit measurements view" class="img-responsive img-thumbnail" />
                    </a>
                    <p>Editing Measurements</p>
                </div>
                <div class="col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-0">
                    <a href="images/screenshots/ss_measurements-options.png">
                        <img src="images/screenshots/ss_measurements-options.png" alt="Screenshot of measurements options view" class="img-responsive img-thumbnail" />
                    </a>
                    <p>Measurements Options</p>
                </div>
            </div>
        </section>
        
    </div>
    
    <div class="col-md-3">
        <aside id="faq">
            <h2>Frequently Asked Questions</h2>
            <ul class="list-unstyled list-group">
                <li class="list-group-item"><a href="faq#use">Should I use this site?</a></li>
                <li class="list-group-item"><a href="faq#purpose">Why does this site exist?</a></li>
            </ul>
            <a href="faq">More Questions ...</a>
        </aside>
    </div>

</div><!-- end of row -->
        
       

<?php
    }
}
?>