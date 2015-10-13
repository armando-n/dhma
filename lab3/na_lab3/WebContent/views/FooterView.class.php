<?php
if (!isset($_SESSION))
    session_start();

class FooterView {
    
    public static function show() {
        ?>
<footer>
    <h2>Site Map</h2>
    <ul>
        <li>
            <h3>Main Site</h3>
            <ul>
                <li><a href="home">Home</a></li>
                <li><a href="members">Member List</a></li><?php
                if (!isset($_SESSION['profile'])) { ?>
                <li><a href="signup">Sign Up</a></li><?php
                } ?>
            </ul>
        </li>
        <li>
            <h3>Members</h3>
            <ul>
                <li><a href="past-measurements">Past Measurements</a></li>
                <li><a href="profile_view">Profile</a></li>
<?php // odd spacing here is for proper spacing when Viewing Page Source (behavior seems inconsistent)
                if (isset($_SESSION['profile'])): ?>
                <li><a href="login_logout">Logout</a></li><?php
                else: ?>
                <li><a href="login_view">Login</a></li><?php
                endif; ?> 
            </ul>
        </li>
        <li>
            <h3>Help</h3>
            <ul>
                <li><a href="faq">FAQ</a></li>
            </ul>
        </li>
    </ul>
</footer>

</body>
</html>
<?php
    }
}
?>