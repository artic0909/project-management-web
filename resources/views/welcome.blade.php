<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orion Technologies — We Build Digital Products</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root {
  --ink:#07080c;--ink2:#1a1c24;
  --paper:#f5f4f0;--paper2:#eceae3;--paper3:#e2dfd6;
  --accent:#ff4d1c;--blue:#1a56ff;--green:#00c37f;--gold:#f5a623;
  --t1:#07080c;--t2:#3a3d4a;--t3:#6b6f82;--t4:#a0a3b1;
  --border:rgba(7,8,12,.1);--border2:rgba(7,8,12,.06);
  --shadow:0 4px 24px rgba(7,8,12,.08),0 1px 4px rgba(7,8,12,.04);
  --shadow-lg:0 24px 64px rgba(7,8,12,.14),0 4px 16px rgba(7,8,12,.08);
  --r:14px;--r-sm:9px;--r-lg:22px;
  --fd:'Clash Display',sans-serif;--fb:'Instrument Sans',sans-serif;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--fb);background:var(--paper);color:var(--t1);overflow-x:hidden;line-height:1.6}
a{text-decoration:none;color:inherit}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-thumb{background:var(--paper3);border-radius:10px}
::selection{background:rgba(255,77,28,.18)}

/* NAV */
nav{position:fixed;top:0;left:0;right:0;z-index:200;height:66px;padding:0 48px;display:flex;align-items:center;justify-content:space-between;background:rgba(245,244,240,.88);backdrop-filter:blur(18px);border-bottom:1px solid var(--border2);transition:all .3s}
nav.sc{background:rgba(245,244,240,.97);box-shadow:0 1px 18px rgba(7,8,12,.07)}
.nbrand{display:flex;align-items:center;gap:10px;font-family:var(--fd);font-size:19px;font-weight:700;color:var(--t1);letter-spacing:-.3px}
.nlogo{width:32px;height:32px;background:var(--ink);border-radius:9px;display:flex;align-items:center;justify-content:center}
.nlinks{display:flex;align-items:center;gap:4px}
.nlink{padding:7px 14px;border-radius:9px;font-size:14px;font-weight:500;color:var(--t2);transition:all .18s;cursor:pointer}
.nlink:hover{background:var(--paper2);color:var(--t1)}
.ncta{background:var(--ink);color:#fff;padding:9px 20px;border-radius:9px;font-size:14px;font-weight:600;border:none;cursor:pointer;font-family:var(--fb);display:flex;align-items:center;gap:7px;transition:all .2s}
.ncta:hover{background:var(--accent);transform:translateY(-1px);box-shadow:0 4px 16px rgba(255,77,28,.28)}
.nmob{display:none;width:36px;height:36px;border-radius:9px;background:var(--paper2);border:1px solid var(--border);align-items:center;justify-content:center;cursor:pointer;font-size:18px}

/* HERO */
.hero{min-height:100vh;padding:100px 48px 72px;display:flex;align-items:center;position:relative;overflow:hidden}
.hgrid{position:absolute;inset:0;z-index:0;background-image:linear-gradient(rgba(7,8,12,.042) 1px,transparent 1px),linear-gradient(90deg,rgba(7,8,12,.042) 1px,transparent 1px);background-size:52px 52px}
.hglow{position:absolute;inset:0;z-index:0;background:radial-gradient(ellipse 55% 55% at 72% 28%,rgba(255,77,28,.09) 0%,transparent 65%),radial-gradient(ellipse 40% 45% at 18% 72%,rgba(26,86,255,.065) 0%,transparent 65%)}
.hinner{max-width:1160px;margin:0 auto;position:relative;z-index:1;display:grid;grid-template-columns:1fr 455px;gap:52px;align-items:center;width:100%}

/* hero left */
.eyebrow{display:inline-flex;align-items:center;gap:7px;background:var(--paper2);border:1px solid var(--border);border-radius:40px;padding:5px 14px 5px 7px;font-size:12.5px;font-weight:600;color:var(--t2);margin-bottom:22px}
.edot{width:18px;height:18px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:9px;color:#fff}
.htitle{font-family:var(--fd);font-size:clamp(40px,5.4vw,66px);font-weight:700;line-height:1.06;letter-spacing:-.03em;color:var(--t1);margin-bottom:20px}
.htitle .ac{color:var(--accent)}
.hdesc{font-size:16px;color:var(--t2);line-height:1.72;max-width:440px;margin-bottom:30px}
.hbtns{display:flex;gap:11px;flex-wrap:wrap;margin-bottom:38px}
.bsolid{background:var(--ink);color:#fff;padding:13px 26px;border-radius:var(--r-sm);font-size:14.5px;font-weight:600;border:none;cursor:pointer;font-family:var(--fb);display:inline-flex;align-items:center;gap:8px;transition:all .22s}
.bsolid:hover{background:var(--accent);transform:translateY(-2px);box-shadow:0 8px 22px rgba(255,77,28,.28)}
.boutline{background:transparent;color:var(--t1);padding:12px 22px;border-radius:var(--r-sm);font-size:14.5px;font-weight:600;border:1.5px solid var(--border);cursor:pointer;font-family:var(--fb);display:inline-flex;align-items:center;gap:8px;transition:all .18s}
.boutline:hover{border-color:var(--t1);background:var(--paper2)}
.hstats{display:flex;gap:28px;padding-top:22px;border-top:1px solid var(--border)}
.hsv{font-family:var(--fd);font-size:24px;font-weight:700;color:var(--t1);letter-spacing:-.03em}
.hsl{font-size:12px;color:var(--t3);font-weight:500;margin-top:1px}

/* hero right */
.hright{position:relative;padding:22px 0}

/* ── FLOATING BADGES — properly contained ── */
.fbadge{
  position:absolute;
  background:#fff;
  border:1px solid var(--border);
  border-radius:12px;
  padding:10px 14px;
  box-shadow:var(--shadow);
  display:flex;align-items:center;gap:10px;
  font-size:13px;font-weight:600;color:var(--t1);
  white-space:nowrap;
  z-index:10;
  pointer-events:none;
  will-change:transform;
}
.fbadge.f1{
  top:-10px;
  right:-14px;
  animation:bob1 3.5s ease-in-out infinite;
}
.fbadge.f2{
  bottom:-10px;
  left:-14px;
  animation:bob2 3.5s ease-in-out infinite;
}
@keyframes bob1{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
@keyframes bob2{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.fbico{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
.fbsup{font-size:11px;color:var(--t3);font-weight:500;line-height:1.3}
.fbmain{font-size:13px;font-weight:700;color:var(--t1);line-height:1.3}

/* Form card */
.fcard{background:#fff;border:1px solid var(--border);border-radius:var(--r-lg);padding:30px;box-shadow:var(--shadow-lg);position:relative;z-index:2}
.fcbar{position:absolute;top:0;left:28px;right:28px;height:3px;background:linear-gradient(90deg,var(--accent),var(--blue));border-radius:3px 3px 0 0}
.fct{font-family:var(--fd);font-size:18px;font-weight:700;color:var(--t1);letter-spacing:-.25px;margin-bottom:4px}
.fcs{font-size:13px;color:var(--t3);margin-bottom:20px}

/* form inputs */
.frow{margin-bottom:13px}
.f2g{display:grid;grid-template-columns:1fr 1fr;gap:11px;margin-bottom:13px}
.fl{display:block;font-size:11.5px;font-weight:600;color:var(--t2);margin-bottom:5px;letter-spacing:.03em;text-transform:uppercase}
.fi,.fs,.ft{width:100%;background:var(--paper);border:1.5px solid var(--border);color:var(--t1);border-radius:var(--r-sm);padding:10px 12px;font-size:14px;font-family:var(--fb);outline:none;transition:all .18s}
.fi:focus,.fs:focus,.ft:focus{border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px rgba(255,77,28,.1)}
.fi::placeholder,.ft::placeholder{color:var(--t4)}
.fs{cursor:pointer;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%236b6f82' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 11px center;padding-right:32px}
.brow{display:flex;gap:5px;flex-wrap:wrap;margin-top:5px}
.bchip{padding:5px 11px;border-radius:20px;font-size:12px;font-weight:600;background:var(--paper2);border:1.5px solid var(--border);color:var(--t2);cursor:pointer;transition:all .16s}
.bchip:hover,.bchip.on{background:var(--accent);border-color:var(--accent);color:#fff}
.sbtn{width:100%;padding:13px;border-radius:var(--r-sm);background:var(--ink);color:#fff;font-size:14.5px;font-weight:700;border:none;cursor:pointer;font-family:var(--fb);display:flex;align-items:center;justify-content:center;gap:8px;transition:all .22s;margin-top:4px}
.sbtn:hover{background:var(--accent);transform:translateY(-1px);box-shadow:0 7px 22px rgba(255,77,28,.26)}
.fnote{text-align:center;font-size:11.5px;color:var(--t4);margin-top:9px}

/* SERVICES */
.srv-sec{padding:72px 48px;background:#fff}
.srv-inner{max-width:1160px;margin:0 auto}
.seclabel{font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--accent);margin-bottom:10px;display:flex;align-items:center;gap:6px}
.seclabel::before{content:'';width:18px;height:2px;background:var(--accent);border-radius:2px}
.sectitle{font-family:var(--fd);font-size:clamp(28px,3.5vw,42px);font-weight:700;color:var(--t1);letter-spacing:-.03em;margin-bottom:36px}
.srvgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.srvcard{background:var(--paper);border:1px solid var(--border);border-radius:var(--r);padding:24px;transition:all .25s;cursor:pointer}
.srvcard:hover{background:#fff;border-color:var(--ci,var(--accent));transform:translateY(-3px);box-shadow:var(--shadow)}
.srvico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:19px;margin-bottom:14px;background:var(--cibg);color:var(--ci)}
.srvname{font-family:var(--fd);font-size:16px;font-weight:700;color:var(--t1);margin-bottom:6px}
.srvdesc{font-size:13px;color:var(--t3);line-height:1.6}

/* HOW */
.how{padding:72px 48px;background:var(--ink)}
.how-inner{max-width:1160px;margin:0 auto}
.how .seclabel{color:var(--accent)}
.how .seclabel::before{background:var(--accent)}
.how .sectitle{color:#fff;margin-bottom:40px}
.steps{display:grid;grid-template-columns:repeat(4,1fr);gap:3px}
.step{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r);padding:24px 20px;transition:all .22s}
.step:hover{background:rgba(255,255,255,.07)}
.stepn{font-family:var(--fd);font-size:44px;font-weight:700;color:rgba(255,255,255,.06);letter-spacing:-.05em;line-height:1;margin-bottom:12px}
.stepico{width:38px;height:38px;border-radius:10px;background:rgba(255,77,28,.14);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:16px;margin-bottom:12px}
.stept{font-size:14px;font-weight:700;color:#fff;margin-bottom:6px}
.stepd{font-size:12.5px;color:rgba(255,255,255,.42);line-height:1.6}

/* INQUIRY */
.inq-sec{padding:72px 48px;background:var(--paper)}
.inq-inner{max-width:700px;margin:0 auto}

/* FOOTER */
footer{background:var(--ink);color:#fff;padding:64px 48px 0}
.footer-inner{max-width:1160px;margin:0 auto}
.ftop{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:56px;padding-bottom:52px}
.flogo{display:flex;align-items:center;gap:10px;margin-bottom:14px;font-family:var(--fd);font-size:19px;font-weight:700;color:#fff}
.flogo-ico{width:32px;height:32px;background:var(--accent);border-radius:9px;display:flex;align-items:center;justify-content:center}
.fdesc{font-size:13.5px;color:rgba(255,255,255,.42);line-height:1.7;max-width:260px;margin-bottom:20px}
.fsoc{display:flex;gap:7px}
.socbtn{width:34px;height:34px;border-radius:9px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;font-size:14px;color:rgba(255,255,255,.55);transition:all .18s;cursor:pointer}
.socbtn:hover{background:var(--accent);border-color:var(--accent);color:#fff}
.fch{font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:16px}
.fclinks{display:flex;flex-direction:column;gap:10px}
.fclink{font-size:13.5px;color:rgba(255,255,255,.55);transition:color .18s;display:flex;align-items:center;gap:6px}
.fclink:hover{color:#fff}
.fclink i{font-size:11px}

/* Panel login strip */
.pstrip{border-top:1px solid rgba(255,255,255,.08);padding:28px 0}
.pslabel{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.22);text-align:center;margin-bottom:14px}
.psgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.psbtn{display:flex;align-items:center;gap:11px;padding:15px 18px;border-radius:var(--r);border:1px solid;cursor:pointer;background:transparent;font-family:var(--fb);width:100%;transition:all .2s;text-align:left}
.psbtn.adm{border-color:rgba(255,77,28,.28);background:rgba(255,77,28,.05)}
.psbtn.adm:hover{border-color:var(--accent);background:rgba(255,77,28,.11);transform:translateY(-2px);box-shadow:0 8px 22px rgba(255,77,28,.18)}
.psbtn.sal{border-color:rgba(26,86,255,.28);background:rgba(26,86,255,.05)}
.psbtn.sal:hover{border-color:var(--blue);background:rgba(26,86,255,.11);transform:translateY(-2px);box-shadow:0 8px 22px rgba(26,86,255,.18)}
.psbtn.dev{border-color:rgba(0,195,127,.28);background:rgba(0,195,127,.05)}
.psbtn.dev:hover{border-color:var(--green);background:rgba(0,195,127,.11);transform:translateY(-2px);box-shadow:0 8px 22px rgba(0,195,127,.18)}
.psico{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.psbtn.adm .psico{background:rgba(255,77,28,.14);color:var(--accent)}
.psbtn.sal .psico{background:rgba(26,86,255,.14);color:var(--blue)}
.psbtn.dev .psico{background:rgba(0,195,127,.14);color:var(--green)}
.pstitle{font-size:13.5px;font-weight:700;color:#fff;margin-bottom:1px}
.pssub{font-size:11.5px;color:rgba(255,255,255,.38)}
.psarr{margin-left:auto;font-size:13px;color:rgba(255,255,255,.28);transition:all .2s;flex-shrink:0}
.psbtn:hover .psarr{color:#fff;transform:translateX(3px)}

.fbot{border-top:1px solid rgba(255,255,255,.07);padding:18px 0;display:flex;align-items:center;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.28);flex-wrap:wrap;gap:8px}
.fbot a{color:rgba(255,255,255,.38);transition:color .18s}
.fbot a:hover{color:#fff}

/* LOGIN MODAL */
.mbg{position:fixed;inset:0;background:rgba(7,8,12,.72);backdrop-filter:blur(8px);z-index:9999;display:none;align-items:center;justify-content:center;padding:20px}
.mbg.open{display:flex;animation:mfade .18s ease}
@keyframes mfade{from{opacity:0}to{opacity:1}}
.lbox{background:#fff;border-radius:var(--r-lg);width:100%;max-width:400px;box-shadow:var(--shadow-lg);overflow:hidden;animation:mslide .22s cubic-bezier(.34,1.56,.64,1)}
@keyframes mslide{from{opacity:0;transform:scale(.94)translateY(14px)}to{opacity:1;transform:scale(1)translateY(0)}}
.lmh{padding:24px 26px 18px;display:flex;align-items:flex-start;justify-content:space-between;border-bottom:1px solid var(--border)}
.lmhico{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
.lmhtit{font-family:var(--fd);font-size:18px;font-weight:700;color:var(--t1);margin-bottom:1px}
.lmhsub{font-size:12.5px;color:var(--t3)}
.lmclose{width:30px;height:30px;border-radius:8px;background:var(--paper);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;color:var(--t3);transition:all .18s}
.lmclose:hover{background:var(--paper2);color:var(--t1)}
.lmbody{padding:22px 26px}
.rtag{display:inline-flex;align-items:center;gap:5px;font-size:10.5px;font-weight:700;padding:3px 10px;border-radius:20px;margin-bottom:16px;text-transform:uppercase;letter-spacing:.07em;border:1px solid}
.lmrow{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;font-size:13px}
.lmrow label{display:flex;align-items:center;gap:6px;cursor:pointer;color:var(--t2);font-size:13px;font-weight:500}
.lmrow input[type=checkbox]{accent-color:var(--accent);width:13px;height:13px}
.lforgot{color:var(--accent);font-weight:600;font-size:13px}
.lsubmit{width:100%;padding:12px;border-radius:var(--r-sm);font-size:14.5px;font-weight:700;border:none;cursor:pointer;font-family:var(--fb);display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s;color:#fff}
.lmfoot{padding:14px 26px;background:var(--paper);border-top:1px solid var(--border);text-align:center;font-size:12px;color:var(--t4)}

/* TOAST */
#toast{position:fixed;bottom:26px;right:26px;z-index:99999;display:none}
.toastinner{background:var(--ink);color:#fff;padding:13px 18px;border-radius:12px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:9px;box-shadow:var(--shadow-lg);border-left:3px solid var(--accent)}

/* ANIMATIONS */
.rv{opacity:0;transform:translateY(20px);transition:opacity .5s ease,transform .5s ease}
.rv.in{opacity:1;transform:none}
.d1{transition-delay:.07s}.d2{transition-delay:.14s}.d3{transition-delay:.21s}.d4{transition-delay:.28s}.d5{transition-delay:.35s}.d6{transition-delay:.42s}
@keyframes popIn{from{transform:scale(0);opacity:0}to{transform:scale(1);opacity:1}}
@keyframes slideT{from{opacity:0;transform:translateX(28px)}to{opacity:1;transform:translateX(0)}}
@keyframes spin{to{transform:rotate(360deg)}}

/* RESPONSIVE */
@media(max-width:1060px){
  .hinner{grid-template-columns:1fr}
  .hero{padding:100px 28px 64px}
  .hright{max-width:520px}
  .steps{grid-template-columns:repeat(2,1fr)}
  .ftop{grid-template-columns:1fr 1fr;gap:32px}
}
@media(max-width:760px){
  nav{padding:0 20px}
  .nlinks{display:none}
  .nmob{display:flex}
  .hero,.srv-sec,.how,.inq-sec{padding-left:20px;padding-right:20px}
  footer{padding:52px 20px 0}
  .srvgrid{grid-template-columns:1fr 1fr}
  .steps{grid-template-columns:1fr}
  .ftop{grid-template-columns:1fr}
  .psgrid{grid-template-columns:1fr}
  .f2g{grid-template-columns:1fr}
  .fbot{justify-content:center;text-align:center}
}
@media(max-width:480px){
  .srvgrid{grid-template-columns:1fr}
  .hstats{flex-wrap:wrap;gap:16px}
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav id="nav">
  <div class="nbrand">
    <div class="nlogo">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
      </svg>
    </div>
    Orion Technologies
  </div>
  <div class="nlinks">
    <a class="nlink" href="#services">Services</a>
    <a class="nlink" href="#how">Process</a>
    <a class="nlink" href="#inquiry">Contact</a>
  </div>
  <button class="ncta" onclick="goInq()">
    <i class="bi bi-rocket-takeoff-fill"></i> Start a Project
  </button>
  <div class="nmob" onclick="goInq()">
    <i class="bi bi-arrow-down-circle-fill" style="color:var(--t2)"></i>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hgrid"></div>
  <div class="hglow"></div>
  <div class="hinner">

    <!-- LEFT -->
    <div>
      <div class="eyebrow">
        <div class="edot"><i class="bi bi-stars"></i></div>
        Trusted by 120+ Companies Across India
      </div>
      <h1 class="htitle">
        We Build<br>
        <span class="ac">Digital Products</span><br>
        That Scale.
      </h1>
      <p class="hdesc">Websites, mobile apps, SaaS platforms &amp; custom software — built for Indian businesses that want to grow fast.</p>
      <div class="hbtns">
        <button class="bsolid" onclick="goInq()"><i class="bi bi-send-fill"></i> Get a Free Quote</button>
        <button class="boutline" onclick="document.getElementById('how').scrollIntoView({behavior:'smooth'})"><i class="bi bi-play-circle-fill"></i> How It Works</button>
      </div>
      <div class="hstats">
        <div><div class="hsv">120+</div><div class="hsl">Projects Delivered</div></div>
        <div><div class="hsv">8 Yrs</div><div class="hsl">Experience</div></div>
        <div><div class="hsv">98%</div><div class="hsl">Satisfaction</div></div>
        <div><div class="hsv">24h</div><div class="hsl">Response</div></div>
      </div>
    </div>

    <!-- RIGHT — form card with floating badges -->
    <div class="hright">

      <!-- Floating badge — top right, always visible -->
      <div class="fbadge f1">
        <div class="fbico" style="background:#e8fdf4;color:#00c37f"><i class="bi bi-check-circle-fill"></i></div>
        <div>
          <div class="fbsup">Avg. Response</div>
          <div class="fbmain">Under 24 Hours</div>
        </div>
      </div>

      <!-- Floating badge — bottom left, always visible -->
      <div class="fbadge f2">
        <div class="fbico" style="background:#fff4f1;color:#ff4d1c"><i class="bi bi-shield-fill-check"></i></div>
        <div>
          <div class="fbsup">100% Secure</div>
          <div class="fbmain">NDA Protected</div>
        </div>
      </div>

      <!-- Form card -->
      <div class="fcard">
        <div class="fcbar"></div>
        <div class="fct">Quick Enquiry</div>
        <div class="fcs">Get a quote in 24 hours · No commitment</div>

        <div class="f2g">
          <div>
            <label class="fl">Your Name *</label>
            <input type="text" class="fi" placeholder="Full name">
          </div>
          <div>
            <label class="fl">Phone *</label>
            <input type="tel" class="fi" placeholder="+91 XXXXX XXXXX">
          </div>
        </div>

        <div class="frow">
          <label class="fl">Email</label>
          <input type="email" class="fi" placeholder="you@company.com">
        </div>

        <div class="frow">
          <label class="fl">What do you need?</label>
          <select class="fs">
            <option value="">Select a service…</option>
            <option>Website Design & Development</option>
            <option>Mobile App (iOS / Android)</option>
            <option>Custom Software / ERP</option>
            <option>E-Commerce Store</option>
            <option>UI/UX Design</option>
            <option>Other / Not Sure</option>
          </select>
        </div>

        <div class="frow">
          <label class="fl">Budget Range</label>
          <div class="brow">
            <div class="bchip" onclick="pickB(this)">Under ₹1L</div>
            <div class="bchip" onclick="pickB(this)">₹1L–5L</div>
            <div class="bchip" onclick="pickB(this)">₹5L–15L</div>
            <div class="bchip" onclick="pickB(this)">₹15L+</div>
          </div>
        </div>

        <button class="sbtn" onclick="qSubmit()">
          <i class="bi bi-send-fill"></i> Send Enquiry — It's Free
        </button>
        <div class="fnote"><i class="bi bi-shield-check" style="color:var(--green)"></i> Your data is private and never shared.</div>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section class="srv-sec" id="services">
  <div class="srv-inner">
    <div class="seclabel rv">What We Do</div>
    <div class="sectitle rv">Services We Offer</div>
    <div class="srvgrid">
      <div class="srvcard rv d1" style="--ci:#1a56ff;--cibg:rgba(26,86,255,.08)">
        <div class="srvico"><i class="bi bi-globe2"></i></div>
        <div class="srvname">Website Development</div>
        <div class="srvdesc">Fast, pixel-perfect websites — corporate, landing pages, portals & more.</div>
      </div>
      <div class="srvcard rv d2" style="--ci:#ff4d1c;--cibg:rgba(255,77,28,.08)">
        <div class="srvico"><i class="bi bi-phone-fill"></i></div>
        <div class="srvname">Mobile Apps</div>
        <div class="srvdesc">Native & cross-platform iOS/Android apps with full backend support.</div>
      </div>
      <div class="srvcard rv d3" style="--ci:#00c37f;--cibg:rgba(0,195,127,.08)">
        <div class="srvico"><i class="bi bi-bag-fill"></i></div>
        <div class="srvname">E-Commerce</div>
        <div class="srvdesc">Full-featured stores with payments, inventory & logistics integrations.</div>
      </div>
      <div class="srvcard rv d4" style="--ci:#8b5cf6;--cibg:rgba(139,92,246,.08)">
        <div class="srvico"><i class="bi bi-cpu-fill"></i></div>
        <div class="srvname">Custom Software</div>
        <div class="srvdesc">ERP systems, CRMs, dashboards & business automation tools.</div>
      </div>
      <div class="srvcard rv d5" style="--ci:#f5a623;--cibg:rgba(245,166,35,.08)">
        <div class="srvico"><i class="bi bi-palette-fill"></i></div>
        <div class="srvname">UI/UX Design</div>
        <div class="srvdesc">Research-led design, Figma prototypes & production-ready design systems.</div>
      </div>
      <div class="srvcard rv d6" style="--ci:#06b6d4;--cibg:rgba(6,182,212,.08)">
        <div class="srvico"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="srvname">Digital Marketing</div>
        <div class="srvdesc">SEO, Google Ads & performance campaigns to grow your online presence.</div>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how" id="how">
  <div class="how-inner">
    <div class="seclabel rv">Our Process</div>
    <div class="sectitle rv" style="color:#fff">From Idea to Launch in 4 Steps</div>
    <div class="steps">
      <div class="step rv d1">
        <div class="stepn">01</div>
        <div class="stepico"><i class="bi bi-chat-dots-fill"></i></div>
        <div class="stept">Discovery Call</div>
        <div class="stepd">Free 30-min call to understand your goals, users and requirements.</div>
      </div>
      <div class="step rv d2">
        <div class="stepn">02</div>
        <div class="stepico"><i class="bi bi-file-earmark-text-fill"></i></div>
        <div class="stept">Proposal & Scope</div>
        <div class="stepd">Detailed proposal with timeline, milestones & transparent pricing in 24hrs.</div>
      </div>
      <div class="step rv d3">
        <div class="stepn">03</div>
        <div class="stepico"><i class="bi bi-kanban-fill"></i></div>
        <div class="stept">Build & Review</div>
        <div class="stepd">Agile sprints with regular demos. You review progress at every milestone.</div>
      </div>
      <div class="step rv d4">
        <div class="stepn">04</div>
        <div class="stepico"><i class="bi bi-rocket-takeoff-fill"></i></div>
        <div class="stept">Launch & Support</div>
        <div class="stepd">We deploy, monitor, and provide 60-day post-launch support included.</div>
      </div>
    </div>
  </div>
</section>

<!-- INQUIRY FORM -->
<section class="inq-sec" id="inquiry">
  <div class="inq-inner">
    <div class="seclabel rv">Get In Touch</div>
    <div class="sectitle rv">Tell Us About Your Project</div>

    <div id="bigForm" style="background:#fff;border:1px solid var(--border);border-radius:var(--r-lg);padding:36px;box-shadow:var(--shadow)">

      <div class="f2g">
        <div>
          <label class="fl">Full Name *</label>
          <input type="text" class="fi" id="i_name" placeholder="Your full name">
        </div>
        <div>
          <label class="fl">Company</label>
          <input type="text" class="fi" placeholder="Company name">
        </div>
      </div>

      <div class="f2g">
        <div>
          <label class="fl">Email *</label>
          <input type="email" class="fi" id="i_email" placeholder="you@company.com">
        </div>
        <div>
          <label class="fl">Phone *</label>
          <input type="tel" class="fi" id="i_phone" placeholder="+91 XXXXX XXXXX">
        </div>
      </div>

      <div class="frow">
        <label class="fl">Services Required</label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:5px" id="srvChecks">
          <label class="schk"><input type="checkbox" style="accent-color:var(--accent)"> Website Development</label>
          <label class="schk"><input type="checkbox" style="accent-color:var(--accent)"> Mobile App</label>
          <label class="schk"><input type="checkbox" style="accent-color:var(--accent)"> E-Commerce</label>
          <label class="schk"><input type="checkbox" style="accent-color:var(--accent)"> Custom Software</label>
          <label class="schk"><input type="checkbox" style="accent-color:var(--accent)"> UI/UX Design</label>
          <label class="schk"><input type="checkbox" style="accent-color:var(--accent)"> Digital Marketing</label>
        </div>
      </div>

      <div class="frow">
        <label class="fl">Project Description *</label>
        <textarea class="ft" id="i_desc" rows="4" placeholder="What are you building? Who is it for? What problem does it solve?"></textarea>
      </div>

      <div class="f2g">
        <div>
          <label class="fl">Budget</label>
          <select class="fs">
            <option value="">Select range…</option>
            <option>Under ₹1,00,000</option>
            <option>₹1L – ₹5L</option>
            <option>₹5L – ₹15L</option>
            <option>₹15L – ₹50L</option>
            <option>₹50L+</option>
            <option>Not decided yet</option>
          </select>
        </div>
        <div>
          <label class="fl">Timeline</label>
          <select class="fs">
            <option value="">Select…</option>
            <option>ASAP</option>
            <option>1–2 Months</option>
            <option>2–4 Months</option>
            <option>4–6 Months</option>
            <option>Flexible</option>
          </select>
        </div>
      </div>

      <button class="sbtn" style="margin-top:8px" onclick="bigSub()">
        <i class="bi bi-send-fill"></i> Submit Project Enquiry
      </button>
      <div class="fnote" style="margin-top:10px"><i class="bi bi-shield-check" style="color:var(--green)"></i> 100% confidential · We respond within 24 hours</div>
    </div>

    <!-- Success -->
    <div id="bigOk" style="display:none;background:#fff;border:1px solid var(--border);border-radius:var(--r-lg);padding:56px 36px;text-align:center;box-shadow:var(--shadow)">
      <div style="width:68px;height:68px;border-radius:50%;background:rgba(0,195,127,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:28px;color:var(--green);animation:popIn .4s cubic-bezier(.34,1.56,.64,1)"><i class="bi bi-check-lg"></i></div>
      <div style="font-family:var(--fd);font-size:22px;font-weight:700;color:var(--t1);margin-bottom:8px">Enquiry Submitted! 🎉</div>
      <p style="font-size:14px;color:var(--t3);margin-bottom:24px">We'll review your project and reach out within 24 hours.</p>
      <button class="boutline" style="margin:0 auto" onclick="resetBig()">Submit Another</button>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="ftop">
      <div>
        <div class="flogo">
          <div class="flogo-ico">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
          </div>
          Orion Technologies
        </div>
        <p class="fdesc">Building world-class digital products for Indian businesses since 2016.</p>
        <div class="fsoc">
          <div class="socbtn"><i class="bi bi-linkedin"></i></div>
          <div class="socbtn"><i class="bi bi-twitter-x"></i></div>
          <div class="socbtn"><i class="bi bi-instagram"></i></div>
          <div class="socbtn"><i class="bi bi-github"></i></div>
          <div class="socbtn"><i class="bi bi-whatsapp"></i></div>
        </div>
      </div>
      <div>
        <div class="fch">Services</div>
        <div class="fclinks">
          <a class="fclink" href="#">Web Development</a>
          <a class="fclink" href="#">Mobile Apps</a>
          <a class="fclink" href="#">E-Commerce</a>
          <a class="fclink" href="#">Custom Software</a>
          <a class="fclink" href="#">UI/UX Design</a>
        </div>
      </div>
      <div>
        <div class="fch">Company</div>
        <div class="fclinks">
          <a class="fclink" href="#">About Us</a>
          <a class="fclink" href="#">Portfolio</a>
          <a class="fclink" href="#">Careers</a>
          <a class="fclink" href="#">Blog</a>
          <a class="fclink" href="#">Contact</a>
        </div>
      </div>
      <div>
        <div class="fch">Contact</div>
        <div class="fclinks">
          <a class="fclink" href="#"><i class="bi bi-telephone-fill"></i> +91 98765 43210</a>
          <a class="fclink" href="#"><i class="bi bi-envelope-fill"></i> hello@oriontech.in</a>
          <a class="fclink" href="#"><i class="bi bi-whatsapp"></i> WhatsApp Chat</a>
          <a class="fclink" href="#">Privacy Policy</a>
          <a class="fclink" href="#">Terms of Service</a>
        </div>
      </div>
    </div>

    <!-- PANEL LOGIN STRIP -->
    <div class="pstrip">
      <div class="pslabel">Team Portal Access</div>
      <div class="psgrid">
        <button class="psbtn adm" onclick="openL('admin')">
          <div class="psico"><i class="bi bi-shield-fill"></i></div>
          <div><div class="pstitle">Admin Login</div><div class="pssub">Full system access · Management</div></div>
          <i class="bi bi-arrow-right psarr"></i>
        </button>
        <button class="psbtn sal" onclick="openL('sales')">
          <div class="psico"><i class="bi bi-graph-up-arrow"></i></div>
          <div><div class="pstitle">Sales Login</div><div class="pssub">Leads, orders & attendance</div></div>
          <i class="bi bi-arrow-right psarr"></i>
        </button>
        <button class="psbtn dev" onclick="openL('dev')">
          <div class="psico"><i class="bi bi-code-slash"></i></div>
          <div><div class="pstitle">Developer Login</div><div class="pssub">Projects, tasks & commits</div></div>
          <i class="bi bi-arrow-right psarr"></i>
        </button>
      </div>
    </div>

    <div class="fbot">
      <div>© 2025 Orion Technologies Pvt. Ltd. · Made in India 🇮🇳</div>
      <div style="display:flex;gap:18px"><a href="#">Privacy</a><a href="#">Terms</a><a href="#">GST: 27AABCO1234F1Z5</a></div>
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
        <div class="frow">
          <label class="fl">Email Address</label>
          <input type="email" name="email" class="fi" id="lemail" placeholder="yourname@oriontech.in" required>
        </div>
        <div class="frow" style="margin-bottom:16px">
          <label class="fl">Password</label>
          <input type="password" name="password" class="fi" id="lpass" placeholder="Enter your password" required>
        </div>
        <div class="lmrow">
          <label><input type="checkbox" name="remember"> Remember me</label>
          <a class="lforgot" href="#">Forgot password?</a>
        </div>
        <button type="submit" class="lsubmit" id="lbtn">
          <i class="bi bi-box-arrow-in-right"></i>
          <span id="lbtntxt">Sign In</span>
        </button>
      </form>
    </div>
    <div class="lmfoot">🔒 Secured with 256-bit encryption</div>
  </div>
</div>

<!-- service check style -->
<style>
.schk{display:flex;align-items:center;gap:8px;padding:10px 12px;background:var(--paper);border:1.5px solid var(--border);border-radius:var(--r-sm);cursor:pointer;font-size:13px;font-weight:500;color:var(--t2);transition:border-color .16s}
.schk:hover{border-color:var(--accent)}
.schk:has(input:checked){border-color:var(--accent);background:rgba(255,77,28,.06);color:var(--accent);font-weight:600}
</style>

<!-- TOAST -->
<div id="toast"><div class="toastinner"><i id="tico" class="bi"></i><span id="tmsg"></span></div></div>

<script>
// Nav scroll
window.addEventListener('scroll',()=>document.getElementById('nav').classList.toggle('sc',scrollY>10))

// Scroll reveal
const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('in')}),{threshold:.1})
document.querySelectorAll('.rv').forEach(el=>obs.observe(el))

// helpers
function goInq(){document.getElementById('inquiry').scrollIntoView({behavior:'smooth'})}
function pickB(el){document.querySelectorAll('.bchip').forEach(c=>c.classList.remove('on'));el.classList.add('on')}
function qSubmit(){toast('✅','Enquiry sent! We\'ll reach out in 24 hrs.','#00c37f')}

// Big form
function bigSub(){
  const n=document.getElementById('i_name').value.trim()
  const e=document.getElementById('i_email').value.trim()
  const p=document.getElementById('i_phone').value.trim()
  const d=document.getElementById('i_desc').value.trim()
  if(!n){hl('i_name');return}
  if(!e){hl('i_email');return}
  if(!p){hl('i_phone');return}
  if(!d){hl('i_desc');return}
  document.getElementById('bigForm').style.display='none'
  document.getElementById('bigOk').style.display='block'
}
function hl(id){const el=document.getElementById(id);el.style.borderColor='var(--accent)';el.focus();setTimeout(()=>el.style.borderColor='',2000)}
function resetBig(){document.getElementById('bigForm').style.display='block';document.getElementById('bigOk').style.display='none'}

// Login modal
const LC={
  admin:{t:'Admin Panel',s:'Full system access',tag:'🛡 ADMIN',tc:'#ff4d1c',ico:'bi-shield-fill',ib:'#fff4f1',ic:'#ff4d1c',bb:'#ff4d1c',bt:'Sign in as Admin', action:'{{ route('admin.login.post') }}'},
  sales:{t:'Sales Panel',s:'Leads, orders & attendance',tag:'📊 SALES',tc:'#1a56ff',ico:'bi-graph-up-arrow',ib:'#f0f4ff',ic:'#1a56ff',bb:'#1a56ff',bt:'Sign in to Sales Panel', action:'{{ route('sale.login.post') }}'},
  dev:{t:'Developer Panel',s:'Projects, tasks & commits',tag:'💻 DEV',tc:'#00c37f',ico:'bi-code-slash',ib:'#e8fdf4',ic:'#00c37f',bb:'#00c37f',bt:'Sign in as Developer', action:'{{ route('developer.login.post') }}'}
}
let CP='admin'
function openL(p){
  CP=p;const c=LC[p]
  document.getElementById('ltit').textContent=c.t
  document.getElementById('lsub').textContent=c.s
  const tg=document.getElementById('ltag')
  tg.textContent=c.tag
  tg.style.cssText=`background:${c.tc}18;color:${c.tc};border-color:${c.tc}33`
  const ic=document.getElementById('lico')
  ic.innerHTML=`<i class="bi ${c.ico}" style="font-size:20px;color:${c.ic}"></i>`
  ic.style.background=c.ib
  document.getElementById('lbtn').style.background=c.bb
  document.getElementById('lbtntxt').textContent=c.bt
  document.getElementById('lemail').value=''
  document.getElementById('lpass').value=''
  document.getElementById('loginForm').action = c.action;
  document.getElementById('lmodal').classList.add('open')
  document.body.style.overflow='hidden'
  setTimeout(()=>document.getElementById('lemail').focus(),150)
}
function closeL(){document.getElementById('lmodal').classList.remove('open');document.body.style.overflow=''}
document.getElementById('loginForm').addEventListener('submit', function() {
  const b=document.getElementById('lbtn')
  b.innerHTML='<div style="width:17px;height:17px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite"></div> Signing in…'
  b.disabled=true
});
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeL()})


// Toast
let tid
function toast(ic,msg,col){
  clearTimeout(tid)
  document.getElementById('tmsg').textContent=msg
  const t=document.getElementById('toast')
  t.querySelector('.toastinner').style.borderLeftColor=col
  t.style.display='block'
  t.style.animation='none'
  void t.offsetWidth
  t.style.animation='slideT .25s ease'
  tid=setTimeout(()=>t.style.display='none',3500)
}

@if($errors->any())
document.addEventListener('DOMContentLoaded', () => {
    toast('⚠️', '{{ $errors->first() }}', '#f5a623');
});
@endif
</script>
</body>
</html>