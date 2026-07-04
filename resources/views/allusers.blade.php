<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StandsWeb — Team Portal Access</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
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
      padding: 0;
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
      line-height: 1.6;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 110px 24px 50px 24px;
      background-attachment: fixed;
    }

    /* NAVBAR styling (Exactly as welcome.blade.php) */
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
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border2);
      transition: all .3s;
    }

    .nav-sc {
      background: rgba(252, 252, 250, .96);
      box-shadow: 0 2px 20px rgba(0, 0, 0, .04);
    }

    .nbrand {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: var(--fd);
      font-size: 19px;
      font-weight: 700;
      color: var(--t1);
      letter-spacing: -.3px;
    }

    .nav-btns {
      display: flex;
      align-items: center;
      gap: 12px;
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
      transition: all .2s;
      outline: none;
    }

    .btn-support:hover {
      background: var(--paper2);
      border-color: var(--t1);
      color: var(--t1);
    }

    /* CARD CONTAINER */
    .portal-card {
      background: rgba(252, 252, 250, 0.85);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1.5px solid var(--border);
      border-radius: var(--r-lg);
      padding: 48px 40px;
      width: 100%;
      max-width: 500px;
      box-shadow: var(--shadow-lg);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .portal-logo {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
      font-family: var(--fd);
      font-size: 23px;
      font-weight: 700;
      color: var(--t1);
      letter-spacing: -.3px;
    }

    .portal-logo-ico {
      width: 38px;
      height: 38px;
      background: var(--ink);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 18px;
    }

    .portal-title {
      font-family: var(--fd);
      font-size: 28px;
      font-weight: 700;
      color: var(--t1);
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .portal-subtitle {
      font-size: 14.5px;
      color: var(--t3);
      margin-bottom: 36px;
    }

    .psgrid {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .psbtn {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 16px 20px;
      border-radius: var(--r);
      border: 1.5px solid;
      cursor: pointer;
      background: #fff;
      font-family: var(--fb);
      width: 100%;
      transition: all .2s;
      text-align: left;
    }

    .psbtn.adm {
      border-color: rgba(255, 77, 28, .2);
      color: var(--t1);
    }

    .psbtn.adm:hover {
      border-color: var(--accent);
      background: rgba(255, 77, 28, .04);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(255, 77, 28, 0.12);
    }

    .psbtn.sal {
      border-color: rgba(26, 86, 255, .2);
      color: var(--t1);
    }

    .psbtn.sal:hover {
      border-color: var(--blue);
      background: rgba(26, 86, 255, .04);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(26, 86, 255, 0.12);
    }

    .psbtn.dev {
      border-color: rgba(0, 195, 127, .2);
      color: var(--t1);
    }

    .psbtn.dev:hover {
      border-color: var(--green);
      background: rgba(0, 195, 127, .04);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0, 195, 127, 0.12);
    }

    .psico {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 19px;
      flex-shrink: 0;
    }

    .psbtn.adm .psico {
      background: rgba(255, 77, 28, .1);
      color: var(--accent);
    }

    .psbtn.sal .psico {
      background: rgba(26, 86, 255, .1);
      color: var(--blue);
    }

    .psbtn.dev .psico {
      background: rgba(0, 195, 127, .1);
      color: var(--green);
    }

    .pstitle {
      font-size: 14.5px;
      font-weight: 700;
      color: var(--t1);
      margin-bottom: 2px;
    }

    .pssub {
      font-size: 12px;
      color: var(--t3);
    }

    .psarr {
      margin-left: auto;
      font-size: 14px;
      color: var(--t4);
      transition: all .2s;
      flex-shrink: 0;
    }

    .psbtn:hover .psarr {
      color: var(--t1);
      transform: translateX(4px);
    }

    .back-home {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 36px;
      font-size: 13.5px;
      font-weight: 600;
      color: var(--t3);
      transition: color .2s;
      text-decoration: none;
    }

    .back-home:hover {
      color: var(--accent);
    }

    /* Modal & Form styles */
    .f-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--t2);
      margin-bottom: 7px;
      text-align: left;
    }

    .f-input {
      width: 100%;
      padding: 11px 14px;
      border-radius: var(--r-sm);
      background: var(--paper);
      border: 1.5px solid var(--border);
      font-family: var(--fb);
      font-size: 14.5px;
      color: var(--t1);
      outline: none;
      transition: all .2s;
    }

    .f-input:focus {
      border-color: var(--accent);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(255, 77, 28, .08);
    }

    .mbg {
      position: fixed;
      inset: 0;
      background: rgba(7, 8, 12, .72);
      backdrop-filter: blur(8px);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .mbg.open {
      display: flex;
      animation: mfade .18s ease;
    }

    @keyframes mfade {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .lbox {
      background: #fff;
      border-radius: var(--r-lg);
      width: 400px;
      box-shadow: var(--shadow-lg);
      overflow: hidden;
      animation: mslide .22s cubic-bezier(.34, 1.56, .64, 1);
    }

    @keyframes mslide {
      from {
        opacity: 0;
        transform: scale(.94) translateY(14px);
      }
      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    .lmh {
      padding: 24px 26px 18px;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      border-bottom: 1px solid var(--border);
    }

    .lmhico {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }

    .lmhtit {
      font-family: var(--fd);
      font-size: 18px;
      font-weight: 700;
      color: var(--t1);
      margin-bottom: 1px;
    }

    .lmhsub {
      font-size: 12.5px;
      color: var(--t3);
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
      color: var(--t3);
    }

    .lmbody {
      padding: 22px 26px;
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
      border: 1px solid;
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
      transition: all .2s;
    }

    .lmfoot {
      padding: 14px 26px;
      background: var(--paper);
      border-top: 1px solid var(--border);
      text-align: center;
      font-size: 12px;
      color: var(--t4);
    }

    @media(max-width:1060px) {
      nav {
        padding: 0 24px;
      }
    }

    @media(max-width:480px) {
      .portal-card {
        padding: 32px 20px;
      }
      
      body {
        padding: 90px 16px 40px 16px;
      }
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav id="nav">
    <div class="nbrand">
      <a href="{{ url('/') }}" style="display: flex; align-items: center;">
        <img src="{{ asset('./logo.png') }}" alt="Logo" class="brand-logo" width="195">
      </a>
    </div>
    <div class="nav-btns">
      <button class="btn-support" onclick="location.href='{{ route('support.create') }}'">Get Support</button>
    </div>
  </nav>

  <div class="portal-card">
    <!-- <div class="portal-logo">
      <div class="portal-logo-ico">
        <i class="bi bi-rocket-takeoff-fill"></i>
      </div>
      <span>Standsweb</span>
    </div> -->
    
    <h1 class="portal-title">Team Portal</h1>
    <p class="portal-subtitle">Secure access for administrative and team modules</p>

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

    <a href="{{ url('/') }}" class="back-home">
      <i class="bi bi-arrow-left"></i> Back to Homepage
    </a>
  </div>

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
            <input type="email" name="email" class="f-input" id="lemail" placeholder="yourname@standsweb.com" required>
          </div>
          <div style="margin-bottom:16px">
            <label class="f-label">Password</label>
            <input type="password" name="password" class="f-input" id="lpass" placeholder="Enter your password" required>
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

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    window.addEventListener('scroll', () => {
      const nav = document.getElementById('nav');
      if (nav) {
        nav.classList.toggle('nav-sc', window.scrollY > 10);
      }
    });

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

</body>
</html>
