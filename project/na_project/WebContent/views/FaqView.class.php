<?php
class FaqView {
    
    public static function show() {
        HeaderView::show("FAQ");
        self::showBody();
        FooterView::show();
    }
    
    public static function showBody() {
        ?>
<div class="row">
    <div class="col-md-9">
    
        <p id="purpose">
            <strong>What is the purpose of this site?</strong>
            The purpose of this site is to provide a tool that people with diabetes may find helpful.
            Once you sign up, you can keep track of various measurements from any device.
        </p>
        
        <p id="privacy">
            <strong>Who can see my data?</strong>
            Your measurement data is kept private. Your profile information and profile picture can be
            made public if you like, but it is set to private by default.
        </p>
        
        <p id="future">
            <strong>Is there more to this sign than keeping track of measurements?</strong>
            The measurement tracking functions are all that are currently provided. In the future, we
            hope to allow you to enhance the calorie tracking function to allow you to create meal plans,
            specify the calorie content of foods you eat, and share this information with other memembers.
        </p>
        
    </div>

</div><!-- end of row -->

<?php
    }
}
?>