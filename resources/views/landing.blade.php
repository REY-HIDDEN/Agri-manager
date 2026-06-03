<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AgriManager - Agriculture Product Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #2d6a4f;
            --primary-dark: #1b4332;
            --primary-light: #52b788;
            --accent: #d4a373;
            --accent-light: #e9c46a;
            --text-dark: #1a1a2e;
            --text-gray: #6c757d;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --shadow: 0 4px 24px rgba(0,0,0,0.08);
            --shadow-lg: 0 12px 48px rgba(0,0,0,0.12);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ======== NAVBAR ======== */
        .navbar-landing {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 16px 0;
            transition: all 0.4s ease;
            background: transparent;
        }

        .navbar-landing.scrolled {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 10px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }

        .navbar-landing .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.3s;
        }

        .navbar-landing .navbar-brand .brand-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }

        .navbar-landing.scrolled .navbar-brand {
            color: var(--primary-dark);
        }

        .navbar-landing.scrolled .navbar-brand .brand-icon {
            background: var(--primary);
            color: white;
        }

        .navbar-landing .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 18px !important;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .navbar-landing.scrolled .nav-link {
            color: var(--text-dark) !important;
        }

        .navbar-landing .nav-link:hover {
            color: var(--white) !important;
            background: rgba(255,255,255,0.1);
        }

        .navbar-landing.scrolled .nav-link:hover {
            color: var(--primary) !important;
            background: rgba(45,106,79,0.08);
        }

        .btn-login-nav {
            background: rgba(255,255,255,0.15);
            color: white !important;
            border: 1.5px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
            padding: 8px 24px !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            transition: all 0.3s;
        }

        .btn-login-nav:hover {
            background: white !important;
            color: var(--primary-dark) !important;
            border-color: white !important;
            transform: translateY(-1px);
        }

        .navbar-landing.scrolled .btn-login-nav {
            background: var(--primary);
            color: white !important;
            border-color: var(--primary);
        }

        .navbar-landing.scrolled .navbar-toggler {
            color: var(--text-dark) !important;
        }

        .navbar-landing.scrolled .btn-login-nav:hover {
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            color: white !important;
        }

        /* ======== HERO SLIDESHOW ======== */
        .hero {
            position: relative;
            height: 100vh;
            min-height: 650px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero-slideshow {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .hero-slide.active {
            opacity: 1;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(27,67,50,0.85) 0%,
                rgba(45,106,79,0.7) 40%,
                rgba(0,0,0,0.4) 100%
            );
            z-index: 1;
        }

        .hero-pattern {
            position: absolute;
            inset: 0;
            z-index: 1;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(82,183,136,0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(212,163,115,0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 80%, rgba(233,196,106,0.08) 0%, transparent 50%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            width: 100%;
            padding: 120px 0 80px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 6px 16px;
            border-radius: 50px;
            color: rgba(255,255,255,0.9);
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 24px;
            animation: fadeInUp 0.8s ease;
        }

        .hero-badge i {
            color: var(--accent-light);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.2rem);
            font-weight: 800;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease 0.1s both;
        }

        .hero h1 .highlight {
            background: linear-gradient(135deg, var(--accent-light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: rgba(255,255,255,0.8);
            max-width: 560px;
            line-height: 1.7;
            margin-bottom: 32px;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.3s both;
        }

        .btn-hero-primary {
            padding: 14px 36px;
            background: var(--white);
            color: var(--primary-dark);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            color: var(--primary-dark);
        }

        .btn-hero-secondary {
            padding: 14px 36px;
            background: transparent;
            color: var(--white);
            border: 1.5px solid rgba(255,255,255,0.4);
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.7);
            color: var(--white);
            transform: translateY(-2px);
        }

        /* Hero indicators */
        .hero-indicators {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            display: flex;
            gap: 12px;
        }

        .hero-indicators button {
            width: 40px;
            height: 4px;
            border-radius: 2px;
            border: none;
            background: rgba(255,255,255,0.3);
            cursor: pointer;
            transition: all 0.4s;
            padding: 0;
        }

        .hero-indicators button.active {
            background: var(--white);
            width: 60px;
        }

        .hero-indicators button:hover {
            background: rgba(255,255,255,0.6);
        }

        /* Hero scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 80px;
            right: 40px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            animation: fadeInUp 1s ease 1s both;
        }

        .scroll-indicator .mouse {
            width: 24px;
            height: 38px;
            border: 2px solid rgba(255,255,255,0.4);
            border-radius: 12px;
            position: relative;
        }

        .scroll-indicator .mouse::after {
            content: '';
            position: absolute;
            top: 6px;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            height: 8px;
            background: rgba(255,255,255,0.6);
            border-radius: 2px;
            animation: scrollWheel 2s ease infinite;
        }

        @keyframes scrollWheel {
            0%, 100% { transform: translateX(-50%) translateY(0); opacity: 1; }
            50% { transform: translateX(-50%) translateY(10px); opacity: 0.3; }
        }

        /* ======== SECTION HEADERS ======== */
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(45,106,79,0.08);
            color: var(--primary);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .section-header h2 {
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .section-header p {
            font-size: 1.05rem;
            color: var(--text-gray);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ======== FEATURES ======== */
        .features-section {
            padding: 100px 0;
            background: var(--white);
        }

        .feature-card {
            background: var(--white);
            border: 1px solid #f0f0f0;
            border-radius: 20px;
            padding: 36px 28px;
            text-align: center;
            transition: all 0.4s;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: transparent;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 20px;
            transition: all 0.4s;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        .feature-card h5 {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 12px;
        }

        .feature-card p {
            font-size: 0.9rem;
            color: var(--text-gray);
            line-height: 1.6;
            margin: 0;
        }

        /* ======== STATS ======== */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(255,255,255,0.05) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(255,255,255,0.05) 0%, transparent 50%);
        }

        .stat-item {
            text-align: center;
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        .stat-item .stat-number {
            font-size: clamp(2.2rem, 4vw, 3rem);
            font-weight: 800;
            color: var(--white);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-item .stat-number::after {
            content: '+';
            color: var(--accent-light);
        }

        .stat-item .stat-label {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.7);
            font-weight: 500;
        }

        .stat-divider {
            width: 1px;
            height: 60px;
            background: rgba(255,255,255,0.15);
            align-self: center;
        }

        /* ======== HOW IT WORKS ======== */
        .how-section {
            padding: 100px 0;
            background: var(--light-bg);
        }

        .step-card {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.3rem;
            margin: 0 auto 20px;
            box-shadow: 0 4px 20px rgba(45,106,79,0.3);
            position: relative;
            z-index: 1;
        }

        .step-card h5 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .step-card p {
            font-size: 0.9rem;
            color: var(--text-gray);
            line-height: 1.6;
        }

        .step-connector {
            position: absolute;
            top: 28px;
            left: 60%;
            right: -20%;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-light), rgba(45,106,79,0.2));
            z-index: 0;
        }

        /* ======== TESTIMONIALS ======== */
        .testimonials-section {
            padding: 100px 0;
            background: var(--white);
        }

        .testimonial-card {
            background: var(--light-bg);
            border-radius: 20px;
            padding: 36px;
            height: 100%;
            transition: all 0.4s;
            border: 1px solid transparent;
        }

        .testimonial-card:hover {
            transform: translateY(-4px);
            border-color: var(--primary-light);
            box-shadow: var(--shadow);
        }

        .testimonial-card .stars {
            color: #f4b942;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .testimonial-card .quote {
            font-size: 0.95rem;
            color: var(--text-gray);
            line-height: 1.7;
            margin-bottom: 20px;
            font-style: italic;
        }

        .testimonial-card .author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testimonial-card .author-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .testimonial-card .author-info strong {
            font-size: 0.9rem;
            display: block;
        }

        .testimonial-card .author-info span {
            font-size: 0.8rem;
            color: var(--text-gray);
        }

        /* ======== CTA ======== */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
        }

        .cta-content h2 {
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 800;
            color: var(--white);
            margin-bottom: 16px;
        }

        .cta-content p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .btn-cta {
            padding: 16px 44px;
            background: var(--white);
            color: var(--primary-dark);
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1.05rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.2);
            color: var(--primary-dark);
        }

        .btn-cta-outline {
            padding: 16px 44px;
            background: transparent;
            color: var(--white);
            border: 1.5px solid rgba(255,255,255,0.4);
            border-radius: 14px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-left: 16px;
        }

        .btn-cta-outline:hover {
            border-color: var(--white);
            background: rgba(255,255,255,0.1);
            color: var(--white);
        }

        /* ======== FOOTER ======== */
        .footer {
            background: #111;
            color: rgba(255,255,255,0.6);
            padding: 60px 0 30px;
        }

        .footer h5 {
            color: var(--white);
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .footer p {
            font-size: 0.85rem;
            line-height: 1.7;
        }

        .footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer ul li {
            margin-bottom: 10px;
        }

        .footer ul li a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
        }

        .footer ul li a:hover {
            color: var(--primary-light);
        }

        .footer-divider {
            border-color: rgba(255,255,255,0.08);
            margin: 30px 0;
        }

        .footer-bottom {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
        }

        .footer-bottom .social-links a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            transition: all 0.3s;
            margin-left: 8px;
        }

        .footer-bottom .social-links a:hover {
            background: var(--primary);
            color: white;
        }

        /* ======== ABOUT SECTION ======== */
        .about-section {
            padding: 100px 0;
            background: var(--light-bg);
        }

        .about-image-wrapper {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            height: 100%;
            min-height: 400px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
        }

        .about-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            mix-blend-mode: overlay;
            opacity: 0.6;
        }

        .about-image-wrapper .about-floating-card {
            position: absolute;
            bottom: 24px;
            left: 24px;
            right: 24px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px 24px;
        }

        .about-floating-card .d-flex {
            align-items: center;
            gap: 16px;
        }

        .about-floating-card i {
            font-size: 2rem;
            color: var(--primary);
        }

        .about-floating-card strong {
            font-size: 0.95rem;
            color: var(--text-dark);
            display: block;
        }

        .about-floating-card small {
            font-size: 0.8rem;
            color: var(--text-gray);
        }

        .about-content {
            padding-left: 40px;
        }

        .about-content .about-list {
            list-style: none;
            padding: 0;
            margin-top: 24px;
        }

        .about-content .about-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
            font-size: 0.95rem;
            color: var(--text-gray);
        }

        .about-content .about-list li i {
            color: var(--primary);
            font-size: 0.9rem;
            margin-top: 4px;
        }

        /* ======== ANIMATIONS ======== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ======== RESPONSIVE ======== */
        @media (max-width: 991px) {
            .navbar-landing {
                background: rgba(27,67,50,0.95);
                backdrop-filter: blur(20px);
            }

            .navbar-landing.scrolled {
                background: rgba(255,255,255,0.98);
            }

            .about-content {
                padding-left: 0;
                margin-top: 30px;
            }

            .scroll-indicator { display: none; }

            .stat-divider { display: none; }

            .step-connector { display: none; }
        }

        @media (max-width: 767px) {
            .hero h1 { font-size: 2rem; }
            .hero p { font-size: 0.95rem; }
            .hero-buttons { flex-direction: column; }
            .btn-hero-primary, .btn-hero-secondary {
                width: 100%;
                justify-content: center;
            }
            .hero-indicators { bottom: 24px; }
            .features-section, .how-section, .testimonials-section,
            .about-section, .cta-section { padding: 60px 0; }
            .section-header { margin-bottom: 36px; }
            .btn-cta-outline { margin-left: 0; margin-top: 12px; }
            .about-image-wrapper { min-height: 280px; }
        }
    </style>
</head>
<body>

    <!-- ======== NAVBAR ======== -->
    <nav class="navbar-landing navbar navbar-expand-lg" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <span class="brand-icon"><i class="fas fa-seedling"></i></span>
                AgriManager
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
                    style="color: rgba(255,255,255,0.8); font-size: 1.5rem;">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i> Sign Up
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn-login-nav" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Sign In
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ======== HERO SECTION ======== -->
    <section class="hero" id="hero">
        <div class="hero-slideshow" id="heroSlideshow">
            <!-- Slides are built by JS -->
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-pattern"></div>

        <div class="hero-content">
            <div class="container">
                <div class="hero-badge">
                    <i class="fas fa-leaf"></i> Empowering Farmers Digitally
                </div>
                <h1>Manage Your <span class="highlight">Agriculture</span><br>Products &amp; Sales Seamlessly</h1>
                <p>A comprehensive digital platform for farmers to manage agricultural products, track sales, manage buyers, record deliveries, and generate insightful reports — all in one place.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        Get Started <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#features" class="btn-hero-secondary">
                        <i class="fas fa-play-circle"></i> Explore Features
                    </a>
                </div>
            </div>
        </div>

        <div class="hero-indicators" id="heroIndicators"></div>

        <div class="scroll-indicator">
            <div class="mouse"></div>
            <span>Scroll</span>
        </div>
    </section>

    <!-- ======== FEATURES SECTION ======== -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-header">
                <div class="section-tag"><i class="fas fa-star"></i> Powerful Features</div>
                <h2>Everything You Need to Manage Your Farm</h2>
                <p>From product management to profit reports, we provide all the tools to digitize your agricultural operations.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(45,106,79,0.1); color: var(--primary);">
                            <i class="fas fa-apple-alt"></i>
                        </div>
                        <h5>Product Management</h5>
                        <p>Easily add, update, and track your agricultural products with real-time stock monitoring and inventory management.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(212,163,115,0.15); color: var(--accent);">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5>Buyer Management</h5>
                        <p>Maintain a comprehensive database of your buyers with contact details, purchase history, and communication records.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(82,183,136,0.12); color: var(--primary-light);">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h5>Sales Tracking</h5>
                        <p>Record every sale with automatic calculations, multi-product support, and real-time stock deduction.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(233,196,106,0.15); color: #b8860b;">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h5>Delivery Management</h5>
                        <p>Track deliveries with status updates — from Pending to In Transit to Delivered — and maintain delivery history.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(27,67,50,0.1); color: var(--primary-dark);">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5>Profit &amp; Sales Reports</h5>
                        <p>Generate detailed daily, monthly, and custom reports including profit calculations, revenue analysis, and buyer insights.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(212,163,115,0.12); color: var(--accent);">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>Role-Based Access</h5>
                        <p>Secure login with role-based access control. Administrators and Sales Officers each have tailored permissions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== STATS SECTION ======== -->
    <section class="stats-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="500">0</div>
                        <div class="stat-label">Farmers Using</div>
                    </div>
                </div>
                <div class="stat-divider d-none d-lg-block"></div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="10000">0</div>
                        <div class="stat-label">Products Managed</div>
                    </div>
                </div>
                <div class="stat-divider d-none d-lg-block"></div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="5000">0</div>
                        <div class="stat-label">Transactions</div>
                    </div>
                </div>
                <div class="stat-divider d-none d-lg-block"></div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="98">0</div>
                        <div class="stat-label">% Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== HOW IT WORKS ======== -->
    <section class="how-section" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <div class="section-tag"><i class="fas fa-cogs"></i> Simple Process</div>
                <h2>How It Works</h2>
                <p>Get started in minutes with our intuitive workflow designed for farmers and agricultural businesses.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-connector"></div>
                        <h5>Sign In</h5>
                        <p>Log in securely to your account. New users can be added by the administrator.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-connector"></div>
                        <h5>Add Products &amp; Buyers</h5>
                        <p>Register your agricultural products and maintain your buyer database.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-connector"></div>
                        <h5>Record Sales</h5>
                        <p>Create sales with multiple products — subtotals and totals are calculated automatically.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h5>Track &amp; Report</h5>
                        <p>Monitor deliveries and generate profit reports to make informed decisions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== ABOUT SECTION ======== -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&h=600&fit=crop"
                             alt="Agriculture farming"
                             loading="lazy">
                        <div class="about-floating-card">
                            <div class="d-flex">
                                <i class="fas fa-quote-right"></i>
                                <div>
                                    <strong>Empowering Eastern Province Farmers</strong>
                                    <small>Digitizing agriculture since 2024</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="section-tag"><i class="fas fa-info-circle"></i> About the System</div>
                        <h2 style="font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; margin-bottom: 16px;">
                            Digitizing Agriculture for a Better Tomorrow
                        </h2>
                        <p style="color: var(--text-gray); line-height: 1.7; margin-bottom: 8px;">
                            Farmers in Eastern Province face challenges managing agricultural products and sales records.
                            Sales aren't properly recorded, buyers are hard to track, transaction records get lost,
                            and manual record-keeping causes errors.
                        </p>
                        <p style="color: var(--text-gray); line-height: 1.7; margin-bottom: 8px;">
                            The Agriculture Product Management System solves these problems by providing a complete
                            digital platform for product management, sales tracking, delivery management, and reporting.
                        </p>
                        <ul class="about-list">
                            <li><i class="fas fa-check-circle"></i> Eliminate manual record-keeping errors</li>
                            <li><i class="fas fa-check-circle"></i> Real-time profit calculation and reporting</li>
                            <li><i class="fas fa-check-circle"></i> Secure role-based access for your team</li>
                            <li><i class="fas fa-check-circle"></i> Mobile-friendly design for on-the-go access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== TESTIMONIALS ======== -->
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <div class="section-header">
                <div class="section-tag"><i class="fas fa-comments"></i> Testimonials</div>
                <h2>What Farmers Say</h2>
                <p>Hear from the farmers and agricultural businesses who use our system every day.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="quote">"This system has completely transformed how I manage my farm products. The sales tracking and profit reports save me hours every week."</p>
                        <div class="author">
                            <div class="author-avatar">JM</div>
                            <div class="author-info">
                                <strong>John Mugabo</strong>
                                <span>Maize Farmer, Eastern Province</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="quote">"Managing buyers used to be a nightmare. Now I have all my buyer information and purchase history at my fingertips. Highly recommended!"</p>
                        <div class="author">
                            <div class="author-avatar">AM</div>
                            <div class="author-info">
                                <strong>Alice Muhayimana</strong>
                                <span>Vegetable Grower, Kigali</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="quote">"The profit reports alone make this system invaluable. I can finally see exactly which products are most profitable for my business."</p>
                        <div class="author">
                            <div class="author-avatar">PN</div>
                            <div class="author-info">
                                <strong>Pierre Niyonzima</strong>
                                <span>Coffee Exporter, Northern Province</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== CTA SECTION ======== -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Transform Your Farm Management?</h2>
                <p>Join hundreds of farmers who have already digitized their agricultural operations. Get started today and take control of your products, sales, and profits.</p>
                <div>
                    <a href="{{ route('register') }}" class="btn-cta">
                        Get Started Now <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#features" class="btn-cta-outline">
                        <i class="fas fa-info-circle"></i> Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== FOOTER ======== -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5><i class="fas fa-seedling me-2" style="color: var(--primary-light);"></i>AgriManager</h5>
                    <p>A comprehensive agriculture product management system designed to empower farmers with digital tools for managing products, sales, buyers, deliveries, and reports.</p>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>Features</h5>
                    <ul>
                        <li><a href="#features">Products</a></li>
                        <li><a href="#features">Buyers</a></li>
                        <li><a href="#features">Sales</a></li>
                        <li><a href="#features">Reports</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5>Contact</h5>
                    <ul>
                        <li><i class="fas fa-envelope me-2" style="color: var(--primary-light);"></i> support@agrimanager.com</li>
                        <li><i class="fas fa-phone me-2" style="color: var(--primary-light);"></i> +250 788 000 000</li>
                        <li><i class="fas fa-map-marker-alt me-2" style="color: var(--primary-light);"></i> Eastern Province, Rwanda</li>
                    </ul>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center">
                <span>&copy; {{ date('Y') }} AgriManager. All rights reserved.</span>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ======== SCRIPTS ======== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ======== HERO IMAGE SLIDESHOW ========
        (function() {
            const images = [
                {
                    url: 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=1920&h=1080&fit=crop',
                    alt: 'Fresh agricultural produce'
                },
                {
                    url: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1920&h=1080&fit=crop',
                    alt: 'Golden wheat field at sunset'
                },
                {
                    url: 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=1920&h=1080&fit=crop',
                    alt: 'Fresh vegetables harvest'
                },
                {
                    url: 'https://images.unsplash.com/photo-1590682680695-43b964a3ae17?w=1920&h=1080&fit=crop',
                    alt: 'Farmers working in field'
                },
                {
                    url: 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=1920&h=1080&fit=crop',
                    alt: 'Fresh fruit harvest'
                },
                {
                    url: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=1920&h=1080&fit=crop',
                    alt: 'Organic farming'
                }
            ];

            const container = document.getElementById('heroSlideshow');
            const indicators = document.getElementById('heroIndicators');
            let currentSlide = 0;
            let slideInterval;

            // Build slides
            images.forEach((img, index) => {
                const div = document.createElement('div');
                div.className = `hero-slide${index === 0 ? ' active' : ''}`;
                div.style.backgroundImage = `url('${img.url}')`;
                div.setAttribute('role', 'img');
                div.setAttribute('aria-label', img.alt);
                container.appendChild(div);

                // Build indicator
                const btn = document.createElement('button');
                btn.className = index === 0 ? 'active' : '';
                btn.setAttribute('aria-label', `Slide ${index + 1}`);
                btn.addEventListener('click', () => goToSlide(index));
                indicators.appendChild(btn);
            });

            function goToSlide(index) {
                const slides = container.querySelectorAll('.hero-slide');
                const dots = indicators.querySelectorAll('button');

                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));

                slides[index].classList.add('active');
                dots[index].classList.add('active');
                currentSlide = index;

                resetInterval();
            }

            function nextSlide() {
                goToSlide((currentSlide + 1) % images.length);
            }

            function resetInterval() {
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            }

            // Start auto-rotation
            slideInterval = setInterval(nextSlide, 5000);

            // Pause on hover
            container.addEventListener('mouseenter', () => clearInterval(slideInterval));
            container.addEventListener('mouseleave', resetInterval);
        })();

        // ======== NAVBAR SCROLL EFFECT ========
        (function() {
            const navbar = document.getElementById('navbar');
            let ticking = false;

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        if (window.scrollY > 60) {
                            navbar.classList.add('scrolled');
                        } else {
                            navbar.classList.remove('scrolled');
                        }
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        })();

        // ======== SMOOTH SCROLL FOR ANCHOR LINKS ========
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Close mobile nav
                    const navCollapse = document.getElementById('navMenu');
                    if (navCollapse.classList.contains('show')) {
                        bootstrap.Collapse.getInstance(navCollapse)?.hide();
                    }
                }
            });
        });

        // ======== STAT COUNTER ANIMATION ========
        (function() {
            const statNumbers = document.querySelectorAll('.stat-number');
            let animated = false;

            function animateCounters() {
                if (animated) return;
                const statsSection = document.querySelector('.stats-section');
                const rect = statsSection.getBoundingClientRect();

                if (rect.top < window.innerHeight - 100) {
                    animated = true;
                    statNumbers.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-target'));
                        const duration = 2000;
                        const steps = 60;
                        const increment = target / steps;
                        let current = 0;
                        let step = 0;

                        const timer = setInterval(() => {
                            step++;
                            current += increment;
                            if (step >= steps) {
                                counter.textContent = target.toLocaleString();
                                clearInterval(timer);
                            } else {
                                counter.textContent = Math.round(current).toLocaleString();
                            }
                        }, duration / steps);
                    });
                }
            }

            window.addEventListener('scroll', animateCounters);
            window.addEventListener('load', animateCounters);
        })();

        // ======== REVEAL ON SCROLL ========
        (function() {
            const cards = document.querySelectorAll('.feature-card, .step-card, .testimonial-card');

            // Set initial styles via JS for progressive enhancement
            cards.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            cards.forEach(el => observer.observe(el));
        })();
    </script>
</body>
</html>
