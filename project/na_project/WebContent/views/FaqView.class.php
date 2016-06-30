<?php
class FaqView {
    
    public static function show() {
        $_SESSION['styles'][] = 'FaqStyles.css';

        HeaderView::show("FAQ");
        self::showBody();
        FooterView::show();
    }
    
    public static function showBody() {
        ?>
<div class="row">
    <div class="col-md-12">

        <h2 class="hidden">Table of Contents</h2>
        <ul id="toc">
            <li><a href="#use">Should I start using this site?</a></li>
            <li><a href="#purpose">Why does this site exist?</a></li>
            <li><a href="#function">What does this site do?</a></li>
            <li><a href="#privacy">Who can see my data?</a></li>
            <li><a href="#future">Is there more to this site than keeping track of measurements?</a></li>
        </ul>

        <h2 class="hidden">Questions and Answers</h2>

        <p id="use">
            <span class="question">Should I use this site?</span>
            Not yet. You can play with it if you like, but the data is deleted regularly.
            This site is still going through too many changes.
            Once this site is ready for use, I will update this answer.
        </p>

        <p id="purpose">
            <span class="question">Why does this site exist?</span>
            This site was created for an assignment in a Web Technologies course I took while studying to earn a degree in Computer Science.
            Although it's not fully fleshed out yet, it is on the internet so that potential employers and the curious can check it out.
        </p>
    
        <p id="function">
            <span class="question">What does this site do?</span>
            The purpose of this site is to provide a health tracking tool that targets things that people with diabetes may find helpful.
            Once you sign up, you can keep track of various measurements from any device.
        </p>
        
        <p id="privacy">
            <span class="question">Who can see my data?</span>
            Your measurement data is always kept private. Your profile information and profile picture can eventually be set to private,
            once that feature has been implemented. For now, it is always public.
        </p>
        
        <p id="future">
            <span class="question">Is there more to this site than keeping track of measurements?</span>
            The measurement tracking functions are all that are currently provided. In the short term, more health tracking features
            and measurements will be added. In the distant future, I hope to allow you to enhance the calorie tracking function to
            allow you to create meal plans, specify the calorie content of foods you eat, and share this information with other memembers.
        </p>

        <p><a href="#top">Back to Top</a></p>
        
    </div>

</div><!-- end of row -->

<?php
    }
}
?>