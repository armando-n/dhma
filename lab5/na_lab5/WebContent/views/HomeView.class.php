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

        <div class="row">
            <div class="col-md-offset 2 col-md-8">
                <h3>Screenshots</h3>
                    Coming soon... When this web site is more complete, screen shots will be
                    placed here.
                <!-- These images don't exist yet, but they will.
                <img src="/images/charts.png" alt="Charts tracking measurements" width="400" height="300" /><br />
                <img src="/images/tables.png" alt="Tables showing past measurements" width="400" height="300" /><br />
                <img src="/images/input.png" alt="Inputting measurement data" width="400" height = "300" />
                 -->
            <!-- </section> -->
            </div>
        </div>
        
    </div>
    
    <div class="col-md-3">
        <aside id="faq">
            <h2>Frequently Asked Questions</h2>
            <ul class="list-unstyled list-group">
                <li class="list-group-item"><a href="faq.html#registration">Why do I need to register?</a></li>
                <li class="list-group-item"><a href="faq.html#privacy">Who can see my information?</a></li>
            </ul>
            <a href="faq.html">More Questions ...</a>
        </aside>
    </div>

</div><!-- end of row -->


<div class="row">
    <div class="col-md-12">
        <h2><a href="tests.html">Tests</a></h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>About DHMA</h2>
    </div>
</div>
        
       

<?php
    }
}
?>