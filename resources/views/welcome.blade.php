<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StandsWeb — Start Your Project</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- favicon png -->
  <link rel="icon" type="image/png" href="{{ asset('./logo.png') }}">
    
  <style>
    :root {
      --ink: #07080c;
      --ink2: #1a1c24;
      --paper: #fcfcfa;
      --paper2: #f5f4ef;
      --paper3: #e9e7df;
      --accent: #ff4d1c;
      --blue: #1a56ff;
      --green: #00c37f;
      --gold: #f5a623;
      --t1: #07080c;
      --t2: #3a3d4a;
      --t3: #6b6f82;
      --t4: #a0a3b1;
      --border: rgba(7, 8, 12, .08);
      --border2: rgba(7, 8, 12, .05);
      --shadow: 0 4px 20px rgba(7, 8, 12, .05), 0 1px 3px rgba(7, 8, 12, .03);
      --shadow-lg: 0 30px 70px rgba(7, 8, 12, .12), 0 5px 20px rgba(7, 8, 12, .06);
      --r: 16px;
      --r-sm: 10px;
      --r-lg: 24px;
      --fd: 'Clash Display', sans-serif;
      --fb: 'Instrument Sans', sans-serif;
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    html {
      scroll-behavior: smooth
    }

    body {
      font-family: var(--fb);
      background-color: var(--paper);
      background-image: 
        linear-gradient(var(--border) 1px, transparent 1px),
        linear-gradient(90deg, var(--border) 1px, transparent 1px);
      background-size: 40px 40px;
      color: var(--t1);
      overflow-x: hidden;
      line-height: 1.6
    }

    a {
      text-decoration: none;
      color: inherit
    }

    ::selection {
      background: rgba(255, 77, 28, .15)
    }

    /* NAV */
    nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      height: 68px;
      padding: 0 48px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(252, 252, 250, .85);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border2);
      transition: all .3s
    }

    .nav-sc {
      background: rgba(252, 252, 250, .96);
      box-shadow: 0 2px 20px rgba(0, 0, 0, .04)
    }

    .nbrand {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: var(--fd);
      font-size: 19px;
      font-weight: 700;
      color: var(--t1);
      letter-spacing: -.3px
    }

    .nlogo {
      width: 32px;
      height: 32px;
      background: var(--ink);
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .nav-btns {
      display: flex;
      align-items: center;
      gap: 12px
    }

    .btn-support {
      padding: 9px 18px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      background: transparent;
      border: 1.5px solid var(--border);
      color: var(--t2);
      cursor: pointer;
      transition: all .2s
    }

    .btn-support:hover {
      background: var(--paper2);
      border-color: var(--t1);
      color: var(--t1)
    }

    .btn-cta {
      background: var(--ink);
      color: #fff;
      padding: 10px 22px;
      border-radius: 11px;
      font-size: 14px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all .2s
    }

    .btn-cta:hover {
      background: var(--accent);
      transform: translateY(-1px);
      box-shadow: 0 8px 20px rgba(255, 77, 28, .2)
    }

    /* ORDER PAGE */
    .order-page {
      min-height: 100vh;
      padding: 120px 48px 80px;
      background: radial-gradient(circle at top right, rgba(255, 77, 28, 0.04), transparent 40%), radial-gradient(circle at bottom left, rgba(26, 86, 255, 0.03), transparent 40%)
    }

    .page-inner {
      max-width: 1100px;
      margin: 0 auto
    }

    .page-head {
      text-align: center;
      margin-bottom: 56px
    }

    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 77, 28, .08);
      color: var(--accent);
      padding: 6px 14px;
      border-radius: 30px;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 16px
    }

    .page-title {
      font-family: var(--fd);
      font-size: clamp(32px, 5vw, 56px);
      font-weight: 700;
      letter-spacing: -.03em;
      color: var(--t1);
      line-height: 1.1;
      margin-bottom: 12px
    }

    .page-subtitle {
      font-size: 17px;
      color: var(--t3);
      max-width: 600px;
      margin: 0 auto
    }

    /* FORM WRAP */
    .form-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--r-lg);
      box-shadow: var(--shadow-lg);
      overflow: hidden;
      display: grid;
      grid-template-columns: 320px 1fr
    }

    .form-sidebar {
      background: var(--ink);
      color: #fff;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: space-between
    }

    .fs-head {
      margin-bottom: auto
    }

    .fs-title {
      font-family: var(--fd);
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 12px
    }

    .fs-desc {
      font-size: 14px;
      color: rgba(255, 255, 255, .5);
      line-height: 1.6;
      margin-bottom: 32px
    }

    .fs-feat {
      display: flex;
      flex-direction: column;
      gap: 20px
    }

    .f-item {
      display: flex;
      gap: 12px
    }

    .f-ico {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: rgba(255, 255, 255, .08);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      color: var(--accent);
      flex-shrink: 0
    }

    .f-text {
      font-size: 13.5px;
      font-weight: 600;
      color: rgba(255, 255, 255, .8);
      line-height: 1.3
    }

    .f-sub {
      font-size: 11.5px;
      color: rgba(255, 255, 255, .4);
      margin-top: 2px;
      font-weight: 400
    }

    .form-body {
      padding: 48px
    }

    .form-section {
      margin-bottom: 40px
    }

    .fs-label {
      font-family: var(--fd);
      font-size: 14px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .1em;
      color: var(--accent);
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 10px
    }

    .fs-label::after {
      content: '';
      height: 1px;
      flex: 1;
      background: var(--border)
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px
    }

    .full {
      grid-column: 1 / -1
    }

    .f-group {
      margin-bottom: 18px
    }

    .f-label {
      display: block;
      font-size: 12.5px;
      font-weight: 600;
      color: var(--t2);
      margin-bottom: 7px
    }

    .f-label.req::after {
      content: ' *';
      color: var(--accent)
    }

    .f-input,
    .f-select,
    .f-area {
      width: 100%;
      padding: 11px 14px;
      border-radius: var(--r-sm);
      background: var(--paper);
      border: 1.5px solid var(--border);
      font-family: var(--fb);
      font-size: 14.5px;
      color: var(--t1);
      outline: none;
      transition: all .2s
    }

    .f-input:focus,
    .f-select:focus,
    .f-area:focus {
      border-color: var(--accent);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(255, 77, 28, .08)
    }

    .f-area {
      resize: vertical;
      min-height: 100px
    }

    /* Multi-row styles */
    .multi-row {
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 6px;
    }

    .phone-wrap {
      display: flex;
      flex: 1;
      min-width: 0;
      border: 1.5px solid var(--border);
      border-radius: var(--r-sm);
      overflow: hidden;
      background: var(--paper);
    }

    .country-sel {
      border: none;
      border-right: 1.5px solid var(--border);
      background: var(--paper2);
      color: var(--t2);
      padding: 0 8px;
      font-size: 13px;
      outline: none;
      width: 85px;
    }

    .phone-num-inp {
      border: none;
      padding: 11px 14px;
      font-size: 14.5px;
      flex: 1;
      min-width: 0;
      outline: none;
      background: transparent;
      color: var(--t1);
    }

    .row-action-btn {
      background: none;
      border: 1.5px solid var(--border);
      border-radius: var(--r-sm);
      width: 42px;
      height: 42px;
      cursor: pointer;
      color: var(--t3);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .2s;
    }

    .row-add-btn {
      color: var(--accent);
      border-color: var(--accent);
    }

    .row-add-btn:hover {
      background: rgba(255, 77, 28, .08);
    }

    .row-remove-btn:hover {
      color: #ef4444;
      border-color: #ef4444;
      background: rgba(239, 68, 68, .08);
    }

    /* Select2 Customization */
    .select2-container--default .select2-selection--multiple {
      border: 1.5px solid var(--border) !important;
      border-radius: var(--r-sm) !important;
      background-color: var(--paper) !important;
      padding: 6px 10px !important;
      min-height: 48px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
      border-color: var(--accent) !important;
      box-shadow: 0 0 0 4px rgba(255, 77, 28, .08) !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
      background-color: var(--accent) !important;
      border: none !important;
      color: #fff !important;
      border-radius: 20px !important;
      padding: 3px 12px !important;
      font-size: 13px !important;
      font-weight: 600 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
      color: #fff !important;
      margin-right: 7px !important;
    }

    .submit-wrap {
      margin-top: 16px;
      padding-top: 28px;
      border-top: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .s-info {
      font-size: 12.5px;
      color: var(--t3);
      display: flex;
      align-items: center;
      gap: 8px
    }

    .s-btn {
      background: var(--ink);
      color: #fff;
      padding: 14px 32px;
      border-radius: var(--r-sm);
      font-size: 15px;
      font-weight: 700;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all .2s
    }

    .s-btn:hover {
      background: var(--accent);
      transform: translateY(-1px);
      box-shadow: 0 10px 24px rgba(255, 77, 28, .25)
    }

    /* FOOTER */
    footer {
      background: var(--ink);
      color: #fff;
      padding: 64px 48px 0
    }

    .footer-inner {
      max-width: 1160px;
      margin: 0 auto
    }

    .ftop {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 56px;
      padding-bottom: 52px
    }

    .flogo {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 14px;
      font-family: var(--fd);
      font-size: 19px;
      font-weight: 700;
      color: #fff
    }

    .flogo-ico {
      width: 32px;
      height: 32px;
      background: var(--accent);
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .fdesc {
      font-size: 13.5px;
      color: rgba(255, 255, 255, .42);
      line-height: 1.7;
      max-width: 260px;
      margin-bottom: 20px
    }

    .fsoc {
      display: flex;
      gap: 7px
    }

    .socbtn {
      width: 34px;
      height: 34px;
      border-radius: 9px;
      background: rgba(255, 255, 255, .07);
      border: 1px solid rgba(255, 255, 255, .1);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: rgba(255, 255, 255, .55);
      transition: all .18s;
      cursor: pointer
    }

    .socbtn:hover {
      background: var(--accent);
      border-color: var(--accent);
      color: #fff
    }

    .fch {
      font-size: 11.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .1em;
      color: rgba(255, 255, 255, .35);
      margin-bottom: 16px
    }

    .fclinks {
      display: flex;
      flex-direction: column;
      gap: 10px
    }

    .fclink {
      font-size: 13.5px;
      color: rgba(255, 255, 255, .55);
      transition: color .18s;
      display: flex;
      align-items: center;
      gap: 6px
    }

    .fclink:hover {
      color: #fff
    }

    .fclink i {
      font-size: 11px
    }

    .pstrip {
      border-top: 1px solid rgba(255, 255, 255, .08);
      padding: 28px 0
    }

    .pslabel {
      font-size: 10.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .12em;
      color: rgba(255, 255, 255, .22);
      text-align: center;
      margin-bottom: 14px
    }

    .psgrid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px
    }

    .psbtn {
      display: flex;
      align-items: center;
      gap: 11px;
      padding: 15px 18px;
      border-radius: var(--r);
      border: 1px solid;
      cursor: pointer;
      background: transparent;
      font-family: var(--fb);
      width: 100%;
      transition: all .2s;
      text-align: left
    }

    .psbtn.adm {
      border-color: rgba(255, 77, 28, .28);
      background: rgba(255, 77, 28, .05)
    }

    .psbtn.adm:hover {
      border-color: var(--accent);
      background: rgba(255, 77, 28, .11);
      transform: translateY(-2px);
      box-shadow: 0 8px 22px rgba(255, 77, 28, .18)
    }

    .psbtn.sal {
      border-color: rgba(26, 86, 255, .28);
      background: rgba(26, 86, 255, .05)
    }

    .psbtn.sal:hover {
      border-color: var(--blue);
      background: rgba(26, 86, 255, .11);
      transform: translateY(-2px);
      box-shadow: 0 8px 22px rgba(26, 86, 255, .18)
    }

    .psbtn.dev {
      border-color: rgba(0, 195, 127, .28);
      background: rgba(0, 195, 127, .05)
    }

    .psbtn.dev:hover {
      border-color: var(--green);
      background: rgba(0, 195, 127, .11);
      transform: translateY(-2px);
      box-shadow: 0 8px 22px rgba(0, 195, 127, .18)
    }

    .psico {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
      flex-shrink: 0
    }

    .psbtn.adm .psico {
      background: rgba(255, 77, 28, .14);
      color: var(--accent)
    }

    .psbtn.sal .psico {
      background: rgba(26, 86, 255, .14);
      color: var(--blue)
    }

    .psbtn.dev .psico {
      background: rgba(0, 195, 127, .14);
      color: var(--green)
    }

    .pstitle {
      font-size: 13.5px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 1px
    }

    .pssub {
      font-size: 11.5px;
      color: rgba(255, 255, 255, .38)
    }

    .psarr {
      margin-left: auto;
      font-size: 13px;
      color: rgba(255, 255, 255, .28);
      transition: all .2s;
      flex-shrink: 0
    }

    .psbtn:hover .psarr {
      color: #fff;
      transform: translateX(3px)
    }

    .fbot {
      border-top: 1px solid rgba(255, 255, 255, .07);
      padding: 18px 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12px;
      color: rgba(255, 255, 255, .28);
      flex-wrap: wrap;
      gap: 8px
    }

    .fbot a {
      color: rgba(255, 255, 255, .38);
      transition: color .18s
    }

    .fbot a:hover {
      color: #fff
    }

    /* Modal and Helper */
    .mbg {
      position: fixed;
      inset: 0;
      background: rgba(7, 8, 12, .72);
      backdrop-filter: blur(8px);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px
    }

    .mbg.open {
      display: flex;
      animation: mfade .18s ease
    }

    @keyframes mfade {
      from {
        opacity: 0
      }

      to {
        opacity: 1
      }
    }

    .lbox {
      background: #fff;
      border-radius: var(--r-lg);
      width: 400px;
      box-shadow: var(--shadow-lg);
      overflow: hidden;
      animation: mslide .22s cubic-bezier(.34, 1.56, .64, 1)
    }

    @keyframes mslide {
      from {
        opacity: 0;
        transform: scale(.94)translateY(14px)
      }

      to {
        opacity: 1;
        transform: scale(1)translateY(0)
      }
    }

    .lmh {
      padding: 24px 26px 18px;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      border-bottom: 1px solid var(--border)
    }

    .lmhico {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0
    }

    .lmhtit {
      font-family: var(--fd);
      font-size: 18px;
      font-weight: 700;
      color: var(--t1);
      margin-bottom: 1px
    }

    .lmhsub {
      font-size: 12.5px;
      color: var(--t3)
    }

    .lmclose {
      width: 30px;
      height: 30px;
      border-radius: 8px;
      background: var(--paper);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 13px;
      color: var(--t3)
    }

    .lmbody {
      padding: 22px 26px
    }

    .rtag {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 10.5px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 20px;
      margin-bottom: 16px;
      text-transform: uppercase;
      letter-spacing: .07em;
      border: 1px solid
    }

    .lsubmit {
      width: 100%;
      padding: 12px;
      border-radius: var(--r-sm);
      font-size: 14.5px;
      font-weight: 700;
      border: none;
      cursor: pointer;
      background: var(--ink);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all .2s
    }

    .lmfoot {
      padding: 14px 26px;
      background: var(--paper);
      border-top: 1px solid var(--border);
      text-align: center;
      font-size: 12px;
      color: var(--t4)
    }

    @media(max-width:1060px) {
      .form-card {
        grid-template-columns: 1fr
      }

      .form-sidebar {
        display: none
      }

      .order-page {
        padding-left: 24px;
        padding-right: 24px
      }

      footer {
        padding-left: 24px;
        padding-right: 24px;
        padding-bottom: 40px;
      }

      nav {
        padding: 0 24px
      }

      .ftop {
        grid-template-columns: 1fr 1fr;
        gap: 40px;
      }
    }

    @media(max-width:640px) {
      .form-grid {
        grid-template-columns: 1fr
      }

      .form-body {
        padding: 24px
      }

      .page-inner {
        padding: 0;
      }

      .page-title {
        font-size: 36px;
      }

      .ftop {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .flogo,
      .fdesc,
      .fsoc {
        justify-content: center;
        margin-left: auto;
        margin-right: auto;
      }

      .psgrid {
        grid-template-columns: 1fr
      }

      .submit-wrap {
        flex-direction: column;
        gap: 20px;
        text-align: center;
      }

      .s-btn {
        width: 100%;
        justify-content: center;
      }
    }

    .is-invalid {
      border-color: #ef4444 !important;
      box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
    }

    .field-error {
      color: #ef4444;
      font-size: 11.5px;
      font-weight: 600;
      margin-top: 6px;
      display: block;
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav id="nav">
    <div class="nbrand">

        <img src="{{ asset('./logo.png') }}" alt="Logo" class="brand-logo" width="195">
        <!-- <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
        </svg> -->
    </div>
    <div class="nav-btns">
      <button class="btn-support" onclick="location.href='{{ route('support.create') }}'">Get Support</button>
      <button class="btn-cta" onclick="document.getElementById('orderForm').scrollIntoView({behavior:'smooth'})">
        <i class="bi bi-plus-circle"></i> Start Project
      </button>
    </div>
  </nav>

  <!-- ORDER PAGE -->
  <main class="order-page">
    <div class="page-inner">
      <div class="page-head">
        <div class="eyebrow"><i class="bi bi-lightning-fill"></i> Fast Delivery System</div>
        <h1 class="page-title">Ready to bring your<br>idea to life?</h1>
        <p class="page-subtitle">Complete the order form below to initiate your project. Our experts will review the
          details and reach out within 24 hours.</p>
      </div>

      <div class="form-card" id="orderForm">
        <!-- Sidebar -->
        <aside class="form-sidebar">
          <div class="fs-head">
            <h2 class="fs-title">What happens next?</h2>
            <p class="fs-desc">Our streamlined process ensures your product is built with precision and speed.</p>

            <div class="fs-feat">
              <div class="f-item">
                <div class="f-ico"><i class="bi bi-shield-check"></i></div>
                <div>
                  <div class="f-text">Secure & Confidential</div>
                  <div class="f-sub">100% data privacy guaranteed.</div>
                </div>
              </div>
              <div class="f-item">
                <div class="f-ico"><i class="bi bi-clock-history"></i></div>
                <div>
                  <div class="f-text">24h Review Period</div>
                  <div class="f-sub">Expert analysis of your reqs.</div>
                </div>
              </div>
              <div class="f-item">
                <div class="f-ico"><i class="bi bi-person-badge"></i></div>
                <div>
                  <div class="f-text">Dedicated Expert</div>
                  <div class="f-sub">Assigned PM for your project.</div>
                </div>
              </div>
              <div class="f-item">
                <div class="f-ico"><i class="bi bi-rocket-takeoff"></i></div>
                <div>
                  <div class="f-text">Fast Kickoff</div>
                  <div class="f-sub">Development starts in 7 days.</div>
                </div>
              </div>
            </div>
          </div>

          <div style="font-size:12px; color:rgba(255,255,255,.3); margin-top:40px;">
            <i class="bi bi-info-circle"></i> Need help? <a href="#" style="color:#fff;text-decoration:underline">Talk
              to Sales</a>
          </div>
        </aside>

        <!-- Main Body -->
        <div class="form-body">
          <form action="#" method="POST" id="mainOrder">
            @csrf

            <!-- Section 1 -->
            <div class="form-section">
              <div class="fs-label">Client Information</div>
              <div class="form-grid">
                <div class="f-group">
                  <label class="f-label req">Company Name</label>
                  <input type="text" name="company_name" class="f-input" placeholder="e.g. Orion Labs" required>
                </div>
                <div class="f-group">
                  <label class="f-label req">Contact Person</label>
                  <input type="text" name="client_name" class="f-input" placeholder="Your full name" required>
                </div>
                <div class="f-group full">
                  <label class="f-label req">Contact Emails</label>
                  <div id="email-list"></div>
                </div>
                <div class="f-group full">
                  <label class="f-label req">Contact Phones</label>
                  <div id="phone-list"></div>
                </div>
              </div>
            </div>

            <!-- Section 2 -->
            <div class="form-section">
              <div class="fs-label">Project Details</div>
              <div class="form-grid">
                <div class="f-group">
                  <label class="f-label req">Domain Name / URL</label>
                  <input type="text" name="domain_name" class="f-input" placeholder="example.com" required>
                </div>
                <div class="f-group">
                  <label class="f-label req">Budget (₹)</label>
                  <input type="number" name="order_value" class="f-input" placeholder="Amount in INR" required>
                </div>
                <div class="f-group">
                  <label class="f-label req">Lead Sources</label>
                  <select name="source_ids[]" class="f-select select2-sources" multiple="multiple" style="width: 100%">
                    @foreach($sources as $source)
                      <option value="{{ $source->id }}">{{ $source->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="f-group">
                  <label class="f-label req">Select Services</label>
                  <select name="service_ids[]" class="f-select select2-services" multiple="multiple" style="width: 100%"
                    required>
                    @foreach($services as $service)
                      <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <!-- Section 3 -->
            <div class="form-section">
              <div class="fs-label">Address Details</div>
              <div class="form-grid">
                <div class="f-group">
                  <label class="f-label req">City</label>
                  <input type="text" name="city" class="f-input" placeholder="e.g. Mumbai" required>
                </div>
                <div class="f-group">
                  <label class="f-label req">Region / State</label>
                  <input type="text" name="state" class="f-input" placeholder="e.g. Maharashtra" required>
                </div>
                <div class="f-group full">
                  <label class="f-label req">ZIP Code</label>
                  <input type="number" name="zip_code" class="f-input" placeholder="6-digit ZIP" required>
                </div>
                <div class="f-group full">
                  <label class="f-label req">Full Address</label>
                  <textarea name="full_address" class="f-area" placeholder="Complete office/home address"
                    required></textarea>
                </div>
              </div>
            </div>

            <!-- Section 4 -->
            <div class="form-section">
              <div class="fs-label">Additional Info</div>
              <div class="f-group full">
                <label class="f-label">Internal Notes / Requirements</label>
                <textarea name="notes" class="f-area"
                  placeholder="Tell us more about your project goals, specific features, or technology preferences..."></textarea>
              </div>
            </div>

            <div class="submit-wrap">
              <div class="s-info">
                <i class="bi bi-shield-lock-fill" style="color:var(--green)"></i>
                <span>Encryption Active · Direct to Team</span>
              </div>
              <button type="submit" class="s-btn">
                <span>Finalize & Submit Request</span>
                <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="footer-inner">
      <!-- PANEL LOGIN STRIP -->
      <div class="pstrip">
        <div class="pslabel">Team Portal Access</div>
        <div class="psgrid">
          <button class="psbtn adm" onclick="openL('admin')">
            <div class="psico"><i class="bi bi-shield-fill"></i></div>
            <div>
              <div class="pstitle">Admin Login</div>
              <div class="pssub">Full system access · Management</div>
            </div>
            <i class="bi bi-arrow-right psarr"></i>
          </button>
          <button class="psbtn sal" onclick="openL('sales')">
            <div class="psico"><i class="bi bi-graph-up-arrow"></i></div>
            <div>
              <div class="pstitle">Sales Login</div>
              <div class="pssub">Leads, orders & attendance</div>
            </div>
            <i class="bi bi-arrow-right psarr"></i>
          </button>
          <button class="psbtn dev" onclick="openL('dev')">
            <div class="psico"><i class="bi bi-code-slash"></i></div>
            <div>
              <div class="pstitle">Developer Login</div>
              <div class="pssub">Projects, tasks & commits</div>
            </div>
            <i class="bi bi-arrow-right psarr"></i>
          </button>
        </div>
      </div>
    </div>
  </footer>

  <!-- LOGIN MODAL -->
  <div class="mbg" id="lmodal" onclick="closeL()">
    <div class="lbox" onclick="event.stopPropagation()">
      <div class="lmh">
        <div style="display:flex;align-items:center;gap:13px">
          <div class="lmhico" id="lico"></div>
          <div>
            <div class="lmhtit" id="ltit">Login</div>
            <div class="lmhsub" id="lsub">Enter credentials to continue</div>
          </div>
        </div>
        <div class="lmclose" onclick="closeL()"><i class="bi bi-x-lg"></i></div>
      </div>
      <div class="lmbody">
        <span class="rtag" id="ltag"></span>
        <form id="loginForm" method="POST" action="">
          @csrf
          <div style="margin-bottom:18px">
            <label class="f-label">Email Address</label>
            <input type="email" name="email" class="f-input" id="lemail" placeholder="yourname@oriontech.in" required>
          </div>
          <div style="margin-bottom:16px">
            <label class="f-label">Password</label>
            <input type="password" name="password" class="f-input" id="lpass" placeholder="Enter your password"
              required>
          </div>
          <div style="margin-bottom:20px; display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="remember" id="remember" style="width:16px; height:16px; accent-color:var(--ink); cursor:pointer;">
            <label for="remember" style="font-size:13px; font-weight:600; color:var(--t2); cursor:pointer;">Remember Me</label>
          </div>
          <button type="submit" class="lsubmit" id="lbtn">
            <span id="lbtntxt">Sign In</span>
          </button>
        </form>
      </div>
      <div class="lmfoot">🔒 Secured with 256-bit encryption</div>
    </div>
  </div>

  <!-- JQuery & Select2 -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    window.addEventListener('scroll', () => document.getElementById('nav').classList.toggle('nav-sc', scrollY > 10))

    // Multi-row Phone & Email logic
    const COUNTRIES = [{ f: "🇦🇫", n: "Afghanistan", c: "+93" }, { f: "🇦🇱", n: "Albania", c: "+355" }, { f: "🇩🇿", n: "Algeria", c: "+213" }, { f: "🇦🇩", n: "Andorra", c: "+376" }, { f: "🇦🇴", n: "Angola", c: "+244" }, { f: "🇦🇷", n: "Argentina", c: "+54" }, { f: "🇦🇺", n: "Australia", c: "+61" }, { f: "🇦🇹", n: "Austria", c: "+43" }, { f: "🇧🇩", n: "Bangladesh", c: "+880" }, { f: "🇧🇪", n: "Belgium", c: "+32" }, { f: "🇧🇷", n: "Brazil", c: "+55" }, { f: "🇨🇦", n: "Canada", c: "+1" }, { f: "🇨🇳", n: "China", c: "+86" }, { f: "🇨🇴", n: "Colombia", c: "+57" }, { f: "🇩🇰", n: "Denmark", c: "+45" }, { f: "🇪🇬", n: "Egypt", c: "+20" }, { f: "🇫🇷", n: "France", c: "+33" }, { f: "🇩🇪", n: "Germany", c: "+49" }, { f: "🇬🇭", n: "Ghana", c: "+233" }, { f: "🇬🇷", n: "Greece", c: "+30" }, { f: "🇮🇳", n: "India", c: "+91" }, { f: "🇮🇩", n: "Indonesia", c: "+62" }, { f: "🇮🇷", n: "Iran", c: "+98" }, { f: "🇮🇶", n: "Iraq", c: "+964" }, { f: "🇮🇪", n: "Ireland", c: "+353" }, { f: "🇮🇱", n: "Israel", c: "+972" }, { f: "🇮🇹", n: "Italy", c: "+39" }, { f: "🇯🇵", n: "Japan", c: "+81" }, { f: "🇯🇴", n: "Jordan", c: "+962" }, { f: "🇰🇪", n: "Kenya", c: "+254" }, { f: "🇰🇼", n: "Kuwait", c: "+965" }, { f: "🇱🇧", n: "Lebanon", c: "+961" }, { f: "🇲🇾", n: "Malaysia", c: "+60" }, { f: "🇲🇽", n: "Mexico", c: "+52" }, { f: "🇲🇦", n: "Morocco", c: "+212" }, { f: "🇳🇵", n: "Nepal", c: "+977" }, { f: "🇳🇱", n: "Netherlands", c: "+31" }, { f: "🇳🇿", n: "New Zealand", c: "+64" }, { f: "🇳🇬", n: "Nigeria", c: "+234" }, { f: "🇳🇴", n: "Norway", c: "+47" }, { f: "🇴🇲", n: "Oman", c: "+968" }, { f: "🇵🇰", n: "Pakistan", c: "+92" }, { f: "🇵🇭", n: "Philippines", c: "+63" }, { f: "🇵🇱", n: "Poland", c: "+48" }, { f: "🇵🇹", n: "Portugal", c: "+351" }, { f: "🇶🇦", n: "Qatar", c: "+974" }, { f: "🇷🇺", n: "Russia", c: "+7" }, { f: "🇸🇦", n: "Saudi Arabia", c: "+966" }, { f: "🇸🇬", n: "Singapore", c: "+65" }, { f: "🇿🇦", n: "South Africa", c: "+27" }, { f: "🇪🇸", n: "Spain", c: "+34" }, { f: "🇱🇰", n: "Sri Lanka", c: "+94" }, { f: "🇸🇪", n: "Sweden", c: "+46" }, { f: "🇨🇭", n: "Switzerland", c: "+41" }, { f: "🇹🇼", n: "Taiwan", c: "+886" }, { f: "🇹🇭", n: "Thailand", c: "+66" }, { f: "🇹🇷", n: "Turkey", c: "+90" }, { f: "🇦🇪", n: "UAE", c: "+971" }, { f: "🇬🇧", n: "United Kingdom", c: "+44" }, { f: "🇺🇸", n: "USA", c: "+1" }, { f: "🇻🇳", n: "Vietnam", c: "+84" }, { f: "🇿🇲", n: "Zambia", c: "+260" }, { f: "🇿🇼", n: "Zimbabwe", c: "+263" }];
    const INDIA_IDX = COUNTRIES.findIndex(c => c.n === "India");

    function buildCountrySel(selectedIdx = null) {
      const sel = document.createElement('select');
      sel.className = 'country-sel';
      sel.name = 'country_code[]';
      COUNTRIES.forEach((c, i) => {
        const opt = document.createElement('option');
        opt.value = i; opt.textContent = c.f + ' ' + c.c; opt.title = c.n;
        sel.appendChild(opt);
      });
      sel.value = selectedIdx !== null ? selectedIdx : (INDIA_IDX >= 0 ? INDIA_IDX : 0);
      return sel;
    }

    function addPhoneRow(listId, val = '', codeIdx = null) {
      const list = document.getElementById(listId);
      const row = document.createElement('div');
      row.className = 'multi-row';
      const wrap = document.createElement('div');
      wrap.className = 'phone-wrap';
      wrap.appendChild(buildCountrySel(codeIdx));
      const inp = document.createElement('input');
      inp.type = 'tel'; inp.name = 'phone[]'; inp.className = 'phone-num-inp'; inp.placeholder = 'XXXXX XXXXX'; inp.value = val;
      inp.setAttribute('oninput', "this.value = this.value.replace(/\\D/g, '')");
      wrap.appendChild(inp);
      row.appendChild(wrap);
      list.appendChild(row);
      updateButtons(listId);
    }

    function addEmailRow(listId, val = '') {
      const list = document.getElementById(listId);
      const row = document.createElement('div');
      row.className = 'multi-row';
      const inp = document.createElement('input');
      inp.type = 'email'; inp.name = 'email[]'; inp.className = 'f-input'; inp.placeholder = 'email@company.com'; inp.value = val;
      row.appendChild(inp);
      list.appendChild(row);
      updateButtons(listId);
    }

    function updateButtons(listId) {
      const list = document.getElementById(listId);
      const rows = list.querySelectorAll('.multi-row');
      rows.forEach((row, i) => {
        let btn = row.querySelector('.row-action-btn');
        if (!btn) { btn = document.createElement('button'); btn.type = 'button'; btn.className = 'row-action-btn'; row.appendChild(btn); }
        if (i === rows.length - 1) {
          btn.className = 'row-action-btn row-add-btn'; btn.innerHTML = '<i class="bi bi-plus-lg"></i>';
          btn.onclick = () => { if (listId.includes('email')) addEmailRow(listId); else addPhoneRow(listId); };
        } else {
          btn.className = 'row-action-btn row-remove-btn'; btn.innerHTML = '<i class="bi bi-x-lg"></i>';
          btn.onclick = () => { row.remove(); updateButtons(listId); };
        }
      });
    }

    $(document).ready(function () {
      $('.select2-services').select2({ placeholder: "Choose services...", allowClear: true });
      $('.select2-sources').select2({ placeholder: "Where did you hear about us?", allowClear: true });
      addEmailRow('email-list');
      addPhoneRow('phone-list');

      // FORM VALIDATION & SUBMISSION
      const form = $('#mainOrder');
      
      form.on('submit', function (e) {
        e.preventDefault(); // Prevent standard submit

        let isValid = true;
        let firstErrorEl = null;

        // Clear previous errors
        $('.field-error').remove();
        $('.is-invalid').removeClass('is-invalid');
        $('.select2-selection').css('border-color', '');

        function markError(el, msg) {
          isValid = false;
          const $el = $(el);
          if (!firstErrorEl) firstErrorEl = el;
          $el.addClass('is-invalid');
          if ($el.hasClass('select2-services') || $el.hasClass('select2-sources')) {
            $el.next('.select2-container').find('.select2-selection').css('border-color', '#ef4444');
          }
          let errorSpan = $('<span class="field-error"></span>').text(msg);
          if ($el.closest('.f-group').length > 0) {
            errorSpan.appendTo($el.closest('.f-group'));
          } else if ($el.closest('.phone-wrap').length > 0) {
            errorSpan.appendTo($el.closest('.multi-row'));
          } else {
            errorSpan.insertAfter(el);
          }
          $el.one('input change', function () {
            $el.removeClass('is-invalid');
            if ($el.hasClass('select2-services') || $el.hasClass('select2-sources')) {
              $el.next('.select2-container').find('.select2-selection').css('border-color', '');
            }
            errorSpan.fadeOut(300, function () { $(this).remove(); });
          });
        }

        const requiredFields = [
          { name: 'company_name', label: 'Company Name' },
          { name: 'client_name', label: 'Contact Person' },
          { name: 'domain_name', label: 'Domain Name' },
          { name: 'order_value', label: 'Budget' },
          { name: 'city', label: 'City' },
          { name: 'state', label: 'Region / State' },
          { name: 'zip_code', label: 'Zip Code' },
          { name: 'full_address', label: 'Full Address' }
        ];

        requiredFields.forEach(f => {
          const el = $(`[name="${f.name}"]`);
          if (!el.val() || el.val().trim() === '') {
            markError(el[0], `${f.label} is required.`);
          }
        });

        const emails = $('input[name="email[]"]');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let hasValidEmail = false;
        emails.each(function () {
          const val = $(this).val().trim();
          if (val !== '') {
            if (!emailRegex.test(val)) {
              markError(this, 'Please enter a valid email address.');
            } else {
              hasValidEmail = true;
            }
          }
        });
        if (!hasValidEmail && emails.length > 0) {
          markError(emails[0], 'At least one valid email address is required.');
        }

        const phones = $('input[name="phone[]"]');
        let hasValidPhone = false;
        phones.each(function () {
          const val = $(this).val().trim();
          if (val !== '') {
            if (!/^\d+$/.test(val)) markError(this, 'Phone number must contain only digits.');
            else if (val.length < 8) markError(this, 'Phone number is too short.');
            else hasValidPhone = true;
          }
        });
        if (!hasValidPhone && phones.length > 0) markError(phones[0], 'At least one phone number is required.');

        const services = $('.select2-services');
        if (services.val() === null || services.val().length === 0) {
          markError(services[0], 'Please select at least one service.');
        }

        const zipField = $('input[name="zip_code"]');
        if (zipField.val() && zipField.val().length !== 6) markError(zipField[0], 'Zip Code must be exactly 6 digits.');

        if (!isValid) {
          if (firstErrorEl) {
            const scrollEl = $(firstErrorEl).hasClass('select2-hidden-accessible') ? $(firstErrorEl).next('.select2-container') : $(firstErrorEl);
            $('html, body').animate({ scrollTop: scrollEl.offset().top - 120 }, 500);
          }
          return;
        }

        // AJAX SUBMISSION
        const submitBtn = $('#mainOrder .s-btn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Processing...');

        $.ajax({
          url: "{{ route('order.inquiry.store') }}",
          method: "POST",
          data: form.serialize(),
          success: function(res) {
            if(res.success) {
              $('#successTitle').text(res.title);
              $('#successMsg').text(res.message);
              $('#successModal').addClass('open');
              form[0].reset();
              $('.select2-services, .select2-sources').val(null).trigger('change');
              $('#email-list, #phone-list').empty();
              addEmailRow('email-list');
              addPhoneRow('phone-list');
            }
          },
          error: function(xhr) {
            alert('Something went wrong. Please check your data and try again.');
          },
          complete: function() {
            submitBtn.prop('disabled', false).html(originalText);
          }
        });
      });
    });

    // Login modal logic
    const LC = {
      admin: { t: 'Admin Panel', tag: '🛡 ADMIN', tc: '#ff4d1c', ico: 'bi-shield-fill', ib: '#fff4f1', ic: '#ff4d1c', bb: '#ff4d1c', bt: 'Sign in as Admin', action: '{{ route('admin.login.post') }}' },
      sales: { t: 'Sales Panel', tag: '📊 SALES', tc: '#1a56ff', ico: 'bi-graph-up-arrow', ib: '#f0f4ff', ic: '#1a56ff', bb: '#1a56ff', bt: 'Sign in to Sales', action: '{{ route('sale.login.post') }}' },
      dev: { t: 'Developer Panel', tag: '💻 DEV', tc: '#00c37f', ico: 'bi-code-slash', ib: '#e8fdf4', ic: '#00c37f', bb: '#00c37f', bt: 'Sign in as Dev', action: '{{ route('developer.login.post') }}' }
    }
    function openL(p) {
      const c = LC[p]
      document.getElementById('ltit').textContent = c.t
      const tg = document.getElementById('ltag')
      tg.textContent = c.tag
      tg.style.cssText = `background:${c.tc}18;color:${c.tc};border-color:${c.tc}33`
      const ic = document.getElementById('lico')
      ic.innerHTML = `<i class="bi ${c.ico}" style="color:${c.ic}"></i>`
      ic.style.background = c.ib
      document.getElementById('lbtn').style.background = c.bb
      document.getElementById('lbtntxt').textContent = c.bt
      document.getElementById('loginForm').action = c.action
      document.getElementById('lmodal').classList.add('open')
    }
    function closeL() { $('.mbg').removeClass('open'); }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeL() })
  </script>

  <!-- SUCCESS MODAL -->
  <div class="mbg" id="successModal" onclick="closeL()">
    <div class="lbox" onclick="event.stopPropagation()" style="text-align:center; padding: 40px 30px;">
        <div style="width:80px;height:80px;background:var(--green);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:40px;margin:0 auto 24px;box-shadow:0 10px 30px rgba(0,195,127,0.3)">
            <i class="bi bi-check-lg"></i>
        </div>
        <h2 id="successTitle" style="font-family:var(--fd);font-size:24px;margin-bottom:12px;color:var(--t1)">Success!</h2>
        <p id="successMsg" style="color:var(--t3);font-size:15px;line-height:1.6;margin-bottom:32px">Your request has been captured.</p>
        <button class="s-btn" onclick="closeL()" style="width:100%;justify-content:center">
            Got it, Thanks!
        </button>
    </div>
  </div>
</body>
</html>