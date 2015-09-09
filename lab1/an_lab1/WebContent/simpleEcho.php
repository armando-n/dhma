<!DOCTYPE html >
<html>
<head>
<meta charset="utf-8" />
<title>Echo of a form</title>
</head>
<body>

<h1>Echo of a form submission</h1>

<header>
    <img src="images/logo.png" alt="DHMA Logo" width="99" height="58" />
    <nav id="main-nav">
        <h2>Site Navigation</h2>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="login.html">Login</a></li>
            <li><a href="signup.html">Sign Up</a></li>
        </ul>
    </nav>
</header>

<section>
    <h2>Submitted Form Information</h2>
    <pre><?php print_r($_POST); ?></pre>
</section>

<footer>
    <h2>Site Map</h2>
    <ul>
        <li>
            <h3>Main Site</h3>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="signup.html">Sign Up</a></li>
            </ul>
        </li>
        <li>
            <h3>Members</h3>
            <ul>
                <li><a href="login.html">Login</a></li>
                <li><a href="profile.html">My Profile</a></li>
            </ul>
        </li>
        <li>
            <h3>Help</h3>
            <ul>
                <li><a href="faq.html">FAQ</a></li>
            </ul>
        </li>
    </ul>
</footer>

</body>
</html>