<?php 
    /* find base url */
    $directory = $GLOBALS['base_url'];
?>

<div id="search">
	<?php print $content; ?>
</div>
<!-- DESKTOP HEADER -->
<header id="desktop">
    <!-- LOGO -->
    <a href="/"><img id="site-logo" src="<?php print $directory;  ?>/sites/nctl/files/images/global/nctl-logo.jpg" alt="National Center for Technologyical Literacy | Museum of Science, Boston" /></a>
    <!-- UTILITY NAV -->
    <section id="utility-nav">
    	<img id="mobile-search-icon" src="<?php print $directory; ?>/sites/nctl/files/images/global/search.png" alt="Search" />
        <a href="contact">Contact Us</a>
        <a href="http://www.mos.org" target="_blank">MOS Home</a>
    </section>
    <!-- NAVIGATION -->
    <nav>
        <a id="about" href="about">About</a>
        <a id="programs" href="programs">Programs</a>
        <a id="resources" href="resources">Advocacy</a>
        <a id="news" href="news">News</a>
    </nav>
    <div id="dropdown-nav">
        <!-- ABOUT Nav -->
        <div id="about" class="nav-wrapper">
            <div class="nav-column">
                <p><a href="about">About Us</a></p>
                <p><a href="yannis-message">Yannis Message</a></p>
            </div>
            <div class="nav-column">
                <p><a href="stem-equity">STEM Equity</a></p>
                <p><a href="milestones">Milestones</a></p>
            </div>
            <div class="nav-column">
                <p><a href="supporters">Supporters</a></p>
                <p><a href="press-room">Press Room</a></p>
            </div>
            <div class="nav-column">
                <p><a href="contact-us">Contact Us</a></p>
            </div>
        </div>
        <!-- PROGRAMS Nav -->
        <div id="programs" class="nav-wrapper">
            <div class="nav-column">
                <p><a href="museum-programs">Museum Programs</a></p>
                <p><a href="elementary-curriculum">Elementary Curriculum</a></p>
            </div>
            <div class="nav-column">
                <p><a href="middle-school-curriculum">Middle School Curriculum</a></p>
                <p><a href="high-school-curriculum">High School Curriculum</a></p>
            </div>
            <div class="nav-column">
                <p><a href="out-of-school-time">Out of School Time (OST)</a></p>
                <p><a href="traveling-programs">Traveling Programs</a></p>
            </div>
            <div class="nav-column">
                <p><a href="professional-development">Educator Professional Development</a></p>
                <p><a href="educator-resource-center">Educator Resource Center</a></p>
            </div>
        </div>
        <!-- ADVOCACY Nav -->
        <div id="resources" class="nav-wrapper">
            <div class="nav-column">
                <p><a href="advocacy">Advocacy</a></p>
                <p><a href="achievements">Achievements</a></p>
            </div>
            <div class="nav-column">
                <p><a href="resources">Resources</a></p>
                <p><a href="videos">Videos</a></p>
            </div>
        </div>
    </div>
</header>
<!-- MOBILE HEADER -->
<header id="mobile">
    <img id="menu" src="<?php print $directory; ?>/sites/nctl/files/images/global/menu.png" alt="Main Menu" />
    <img id="mobile-search-icon" src="<?php print $directory; ?>/sites/nctl/files/images/global/search_sm.png" alt="Search" />
    <!-- LOGO -->
    <a href="/"><img id="site-logo" src="<?php print $directory; ?>/sites/nctl/files/images/global/nctl-logo.jpg" alt="National Center for Technologyical Literacy | Museum of Science, Boston" /></a>
    <nav>
        <a id="about" href="/about">About</a>
        <a id="programs" href="/programs">Programs</a>
        <a id="resources" href="/resources">Resources</a>
        <a id="news" href="/news">News</a>
        <a id="news" href="/contact">Contact Us</a>
        <a id="news" href="http://www.mos.org" target="_blank">MOS Home</a>
    </nav>
</header>
