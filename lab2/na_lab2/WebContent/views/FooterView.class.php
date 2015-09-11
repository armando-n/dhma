<?php
class FooterView {
    public static function show($loggedIn = false) {
        $loggedIn = isset($_GET["loggedin"]);
        ?>
<footer>
    <h2>Site Map</h2>
    <ul>
        <li>
            <h3>Main Site</h3>
            <ul>
                <li><a href="home">Home</a></li>
                <li><a href="signup">Sign Up</a></li>
            </ul>
        </li>
        <li>
            <h3>Members</h3>
            <ul>
<?php // odd spacing here is for proper spacing when Viewing Page Source (behavior seems inconsistent)
                if ($loggedIn) { ?>
                <li><a href="logout">Logout</a></li><?php
                } else { ?>
                <li><a href="login">Login</a></li>
                <li><a href="profile">My Profile</a></li><?php
                } ?> 
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