<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moments - By MQLUS</title>
    <meta name="theme-color" content="#090a16">
    <meta name="description" content="Moments by MQlus creates cinematic event reels and unforgettable visual stories in Indore.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=DM%20Sans:700,600,500,400&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assest/moments.css?v=<?php echo filemtime(__DIR__ . '/../assest/moments.css'); ?>">
</head>

<body>
    <div class="page-loader" id="pageLoader" aria-live="polite" aria-label="Loading page">
        <div class="page-loader-card">
            <img src="images/logo2.png" width="180" height="60" alt="Moments by MQlus">
            <span class="page-loader-spinner" aria-hidden="true"></span>
            <span class="page-loader-text">Getting your moments ready...</span>
        </div>
    </div>
    <!-- ============================================================
    SITE HEADER
    ============================================================ -->
    <header class="site-header" id="siteHeader" role="banner">
        <div class="header-inner">
            <div class="header-logo">
                <a href="moments">
                    <img src="images/logo2.png" alt="Moments" width="248" height="83" fetchpriority="high" decoding="async">
                </a>
            </div>

            <nav class="header-nav" aria-label="Primary navigation">
                <ul>
                    <li><a href="moments">Home</a></li>
                    <li style="position:relative;">
                        <a href="#sliderTrack">Work</a>
                    </li>
                    <li><a href="#pricing">Services</a></li>
                    <li><a href="#review">Review</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>

            <div class="header-cta">
                <a href="#contact">
                    <span>Book Now</span>
                    <span class="arrow">↗</span>
                </a>
            </div>

            <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="mobile-menu" id="mobileMenu">
            <a href="moments">Home</a>
            <a href="#sliderTrack">Work</a>
            <a href="#pricing">Service</a>
            <a href="#review">Review</a>
            <a href="#contact">Contact</a>
            <a class="mobile-cta" href="#contact">GET IN TOUCH ↗</a>
        </div>
    </header>