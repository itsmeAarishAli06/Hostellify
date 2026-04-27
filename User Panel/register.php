<?php
session_start();

require_once "../config.php";

$_SESSION['email_taken'] = false ;
// echo $_SESSION['email_taken'];
$otp_sent  = isset($_GET['otp']) && $_GET['otp'] === 'sent';

// Pre-fill fields after error so user doesn't retype everything

$old = [
    'name'    => $_POST['name']    ?? '',
    'email'   => $_POST['email']   ?? '',
    'contact' => $_POST['contact'] ?? '',
    'address' => $_POST['address'] ?? '',
    'role'    => $_POST['user_type'] ?? 'student',
];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HostelHub — Create Account</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --blue:      #1a56db;
  --blue-dark: #0f2b6e;
  --blue-btn:  #1344b5;
  --text:      #0c1a3a;
  --muted:     #64748b;
  --border:    #cbd5e1;
  --input-bg:  #ffffff;
  --bg:        #f1f5fd;
  --label:     #475569;
}

html, body { height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; }

.wrapper {
  display: grid;
  grid-template-columns: 1fr 1fr;
  height: 100vh;
  overflow: hidden;
}

.panel-left {
  background: linear-gradient(150deg, #071b4e 0%, #0d2562 28%, #1344b5 62%, #2563eb 100%);
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px 28px;
  overflow: hidden;
}

.orb {
  position: absolute; border-radius: 50%;
  filter: blur(90px); pointer-events: none;
  animation: orbFloat 10s ease-in-out infinite;
}
.orb-1 { width: 340px; height: 340px; background: rgba(59,130,246,0.4);  top: -120px; left: -100px; animation-delay: 0s; }
.orb-2 { width: 220px; height: 220px; background: rgba(29,78,216,0.45);  bottom: -40px; right: -40px; animation-delay: -3.5s; }
.orb-3 { width: 120px; height: 120px; background: rgba(96,165,250,0.35); top: 45%; left: 25%; animation-delay: -7s; }
@keyframes orbFloat {
  0%,100% { transform: translate(0,0); }
  40%     { transform: translate(20px,-25px); }
  70%     { transform: translate(-15px,15px); }
}

.dots {
  position: absolute; inset: 0;
  background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
  background-size: 30px 30px;
  pointer-events: none;
}

.panel-left-content {
  position: relative; z-index: 2;
  max-width: 340px; width: 100%;
}

.brand {
  font-size: 1.2rem; font-weight: 800;
  color: #fff; letter-spacing: -0.4px;
  margin-bottom: 18px;
  animation: slideUp .6s ease .05s both;
}
.brand small {
  display: block; margin-top: 2px;
  font-size: 0.65rem; font-weight: 400;
  color: rgba(255,255,255,0.45); letter-spacing: 0.02em;
}

.city-art {
  width: 100%; margin-bottom: 18px;
  animation: slideUp .7s ease .2s both;
  filter: drop-shadow(0 12px 36px rgba(0,0,0,0.3));
  max-height: 130px;
}

.left-title {
  font-family: 'DM Serif Display', serif;
  font-size: 1.65rem; line-height: 1.15;
  color: #fff; letter-spacing: -0.5px;
  margin-bottom: 8px;
  animation: slideUp .6s ease .35s both;
}
.left-title em { font-style: italic; color: #93c5fd; }

.left-desc {
  font-size: 0.75rem; line-height: 1.6;
  color: rgba(255,255,255,0.58);
  margin-bottom: 16px;
  animation: slideUp .6s ease .45s both;
}

.pills {
  display: flex; gap: 8px; flex-wrap: wrap;
  animation: slideUp .6s ease .58s both;
}
.pill {
  display: flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 100px; padding: 6px 12px;
  color: #fff; font-size: 0.65rem; font-weight: 600;
  backdrop-filter: blur(8px);
}
.pill-dot {
  width: 5px; height: 5px; border-radius: 50%;
  background: #34d399; box-shadow: 0 0 6px #34d399;
  animation: blink 2s ease-in-out infinite;
}
@keyframes blink { 0%,100% { opacity:1; } 50% { opacity:.3; } }

.panel-right {
  background: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 25px 30px;
  overflow: hidden;
  position: relative;
}

.panel-right::before {
  content: '';
  position: absolute;
  width: 380px; height: 380px; border-radius: 50%;
  background: radial-gradient(circle, #dbeafe55 0%, transparent 70%);
  top: -120px; right: -120px; pointer-events: none;
}

.form-box {
  width: 100%; max-width: 380px;
  position: relative; z-index: 1;
}

.badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: #fff; border: 1.5px solid #c7d7f8;
  border-radius: 100px; padding: 4px 12px 4px 6px;
  font-size: 0.65rem; font-weight: 700;
  color: var(--blue); letter-spacing: 0.05em; text-transform: uppercase;
  margin-bottom: 12px;
  box-shadow: 0 2px 10px rgba(26,86,219,0.1);
  animation: slideUp .5s ease .15s both;
}
.badge-dot {
  width: 18px; height: 18px; background: var(--blue);
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.badge-dot svg { width: 9px; height: 9px; }

.form-h1 {
  font-family: 'DM Serif Display', serif;
  font-size: 1.45rem; color: var(--text);
  letter-spacing: -0.4px; line-height: 1.2;
  margin-bottom: 3px;
  animation: slideUp .5s ease .22s both;
}
.form-sub {
  font-size: 0.75rem; color: var(--muted);
  line-height: 1.5; margin-bottom: 14px;
  animation: slideUp .5s ease .3s both;
}

/* Alert Banners */
.alert {
  display: flex; align-items: flex-start; gap: 10px;
  border-radius: 9px; padding: 10px 14px;
  margin-bottom: 14px; font-size: 0.72rem;
  animation: slideUp .4s ease both;
}
.alert-error {
  background: #fef2f2; border: 1.5px solid #fca5a5;
}
.alert-success {
  background: #ecfdf5; border: 1.5px solid #6ee7b7;
}
.alert-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
.alert-title {
  display: block; font-weight: 700;
  font-size: 0.76rem; margin-bottom: 1px;
}
.alert-error   .alert-title { color: #991b1b; }
.alert-success .alert-title { color: #065f46; }
.alert-error   p { color: #b91c1c; margin: 0; }
.alert-success p { color: #047857; margin: 0; }
.alert a { color: var(--blue); font-weight: 700; text-decoration: none; }
.alert a:hover { text-decoration: underline; }

.inp-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.inp-group { margin-bottom: 10px; }
.inp-group:nth-child(1) { animation: slideUp .5s ease .36s both; }
.inp-group:nth-child(2) { animation: slideUp .5s ease .42s both; }
.inp-group:nth-child(3) { animation: slideUp .5s ease .48s both; }
.inp-group:nth-child(4) { animation: slideUp .5s ease .54s both; }
.inp-group:nth-child(5) { animation: slideUp .5s ease .60s both; }

.inp-label {
  display: block;
  font-size: 0.65rem; font-weight: 700;
  color: var(--label); letter-spacing: 0.03em;
  text-transform: uppercase; margin-bottom: 4px;
}

.inp-wrap { position: relative; }

.inp-icon {
  position: absolute; left: 10px; top: 50%;
  transform: translateY(-50%);
  width: 15px; height: 15px; color: #94a3b8;
  pointer-events: none; transition: color .2s;
  display: flex; align-items: center; justify-content: center;
}
.inp-wrap:focus-within .inp-icon { color: var(--blue); }

.inp-field {
  display: block; width: 100%;
  height: 38px;
  padding: 0 11px 0 32px;
  background: var(--input-bg);
  border: 1.3px solid var(--border);
  border-radius: 9px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.8rem; font-weight: 500;
  color: var(--text); outline: none;
  transition: border-color .2s, box-shadow .2s;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.inp-field::placeholder { color: #b0bcd4; font-weight: 400; }
.inp-field:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 2.8px rgba(26,86,219,0.1);
}
.inp-field.error {
  border-color: #ef4444;
  box-shadow: 0 0 0 2.8px rgba(239,68,68,0.15);
}

.inp-textarea {
  display: block; width: 100%;
  padding: 8px 11px 8px 32px;
  background: var(--input-bg);
  border: 1.3px solid var(--border);
  border-radius: 9px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.8rem; font-weight: 500;
  color: var(--text); outline: none;
  resize: none; height: 60px; line-height: 1.4;
  transition: border-color .2s, box-shadow .2s;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.inp-textarea::placeholder { color: #b0bcd4; font-weight: 400; }
.inp-textarea:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 2.8px rgba(26,86,219,0.1);
}
.inp-wrap.textarea-wrap .inp-icon { top: 12px; transform: none; }

.role-row {
  display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
  margin-bottom: 10px;
  animation: slideUp .5s ease .60s both;
}

.role-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 12px;
  background: var(--input-bg);
  border: 1.3px solid var(--border);
  border-radius: 9px; cursor: pointer;
  transition: all .2s;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.role-btn:hover { border-color: var(--blue); background: #eff6ff; }
.role-btn.selected {
  border-color: var(--blue); background: #eff6ff;
  box-shadow: 0 0 0 2.8px rgba(26,86,219,0.1);
}

.role-radio {
  width: 15px; height: 15px; min-width: 15px;
  border: 2px solid var(--border); border-radius: 50%;
  position: relative; transition: border-color .2s; flex-shrink: 0;
}
.role-btn.selected .role-radio { border-color: var(--blue); }
.role-btn.selected .role-radio::after {
  content: ''; position: absolute; inset: 2px;
  background: var(--blue); border-radius: 50%;
}

.role-icon {
  width: 24px; height: 24px; border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.85rem; flex-shrink: 0;
}
.role-icon.student { background: #dbeafe; }
.role-icon.owner   { background: #d1fae5; }

.role-text { line-height: 1.1; }
.role-text strong { display: block; font-size: 0.72rem; font-weight: 700; color: var(--text); }
.role-text span   { font-size: 0.62rem; color: var(--muted); font-weight: 400; }

.terms-row {
  display: flex; align-items: flex-start; gap: 9px;
  margin-bottom: 14px;
  animation: slideUp .5s ease .66s both;
}
.terms-chk {
  appearance: none; -webkit-appearance: none;
  width: 16px; height: 16px; min-width: 16px;
  border: 2px solid var(--border); border-radius: 4px;
  background: #fff; cursor: pointer;
  position: relative; margin-top: 1px; transition: all .2s;
}
.terms-chk:checked { background: var(--blue); border-color: var(--blue); }
.terms-chk:checked::after {
  content: ''; position: absolute;
  left: 2px; top: 0px;
  width: 8px; height: 5px;
  border-left: 2px solid #fff; border-bottom: 2px solid #fff;
  transform: rotate(-45deg);
}
.terms-text { font-size: 0.7rem; color: var(--muted); line-height: 1.5; }
.terms-text a { color: var(--blue); font-weight: 700; text-decoration: none; }
.terms-text a:hover { text-decoration: underline; }

.submit-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; height: 42px;
  background: linear-gradient(135deg, #0f2b6e 0%, #1a56db 55%, #2563eb 100%);
  border: none; border-radius: 10px; color: #fff;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.8rem; font-weight: 700; letter-spacing: 0.02em;
  cursor: pointer; position: relative; overflow: hidden;
  transition: transform .15s, box-shadow .25s;
  box-shadow: 0 4px 16px rgba(26,86,219,0.35);
  animation: slideUp .5s ease .72s both;
}
.submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(26,86,219,0.45); }
.submit-btn:active { transform: translateY(0); }
.submit-btn::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(100deg, transparent 38%, rgba(255,255,255,0.18) 52%, transparent 66%);
  transform: translateX(-120%); transition: transform .55s ease;
}
.submit-btn:hover::after { transform: translateX(120%); }

.btn-icon {
  width: 22px; height: 22px;
  background: rgba(255,255,255,0.18); border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  transition: transform .2s;
}
.submit-btn:hover .btn-icon { transform: translateX(3px); }

.signin-row {
  text-align: center; margin-top: 12px;
  font-size: 0.72rem; color: var(--muted);
  animation: slideUp .5s ease .78s both;
}
.signin-row a {
  color: var(--blue); font-weight: 700;
  text-decoration: none; position: relative;
}
.signin-row a::after {
  content: ''; position: absolute; left: 0; bottom: -1px;
  width: 0; height: 1.5px; background: var(--blue);
  border-radius: 2px; transition: width .28s ease;
}
.signin-row a:hover::after { width: 100%; }

@keyframes slideUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes shake {
  0%,100% { transform: translateX(0); }
  20%,60% { transform: translateX(-8px); }
  40%,80% { transform: translateX(8px); }
}

.ripple-el {
  position: absolute; border-radius: 50%;
  background: rgba(255,255,255,.35);
  width: 10px; height: 10px;
  transform: scale(0); pointer-events: none;
  animation: rippleGo .55s linear;
}
@keyframes rippleGo { to { transform: scale(7); opacity: 0; } }

@media (max-width: 840px) {
  .wrapper { grid-template-columns: 1fr; }
  .panel-left { display: none; }
  .panel-right { padding: 35px 20px; }
}
@media (max-width: 420px) {
  .inp-row { grid-template-columns: 1fr; }
  .role-row { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="wrapper">

  <!-- LEFT PANEL -->
  <div class="panel-left">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="dots"></div>

    <div class="panel-left-content">
      <div class="brand">
        HostelHub
        <small>Premium Student Accommodation</small>
      </div>

      <svg class="city-art" viewBox="0 0 430 250" fill="none" xmlns="http://www.w3.org/2000/svg">
        <line x1="0" y1="240" x2="430" y2="240" stroke="rgba(255,255,255,.18)" stroke-width="1.5"/>
        <rect x="58" y="55" width="126" height="185" rx="5" fill="rgba(255,255,255,.07)" stroke="rgba(255,255,255,.22)" stroke-width="1.2"/>
        <g fill="rgba(147,197,253,.55)">
          <rect x="74" y="74"  width="20" height="15" rx="2"/> <rect x="105" y="74"  width="20" height="15" rx="2"/> <rect x="136" y="74"  width="20" height="15" rx="2"/>
          <rect x="74" y="100" width="20" height="15" rx="2"/> <rect x="105" y="100" width="20" height="15" rx="2"/> <rect x="136" y="100" width="20" height="15" rx="2"/>
          <rect x="74" y="126" width="20" height="15" rx="2"/> <rect x="105" y="126" width="20" height="15" rx="2"/> <rect x="136" y="126" width="20" height="15" rx="2"/>
          <rect x="74" y="152" width="20" height="15" rx="2"/> <rect x="105" y="152" width="20" height="15" rx="2"/> <rect x="136" y="152" width="20" height="15" rx="2"/>
        </g>
        <rect x="105" y="74"  width="20" height="15" rx="2" fill="rgba(253,224,71,.92)"/>
        <rect x="74"  y="126" width="20" height="15" rx="2" fill="rgba(253,224,71,.92)"/>
        <rect x="136" y="152" width="20" height="15" rx="2" fill="rgba(253,224,71,.92)"/>
        <rect x="105" y="100" width="20" height="15" rx="2" fill="rgba(253,224,71,.6)"/>
        <rect x="108" y="203" width="32" height="37" rx="3" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.22)" stroke-width="1"/>
        <circle cx="136" cy="222" r="2" fill="rgba(255,255,255,.5)"/>
        <rect x="248" y="96" width="138" height="144" rx="5" fill="rgba(255,255,255,.06)" stroke="rgba(255,255,255,.18)" stroke-width="1.2"/>
        <g fill="rgba(147,197,253,.5)">
          <rect x="262" y="111" width="22" height="16" rx="2"/> <rect x="296" y="111" width="22" height="16" rx="2"/> <rect x="330" y="111" width="22" height="16" rx="2"/>
          <rect x="262" y="139" width="22" height="16" rx="2"/> <rect x="296" y="139" width="22" height="16" rx="2"/> <rect x="330" y="139" width="22" height="16" rx="2"/>
          <rect x="262" y="167" width="22" height="16" rx="2"/> <rect x="296" y="167" width="22" height="16" rx="2"/> <rect x="330" y="167" width="22" height="16" rx="2"/>
        </g>
        <rect x="296" y="111" width="22" height="16" rx="2" fill="rgba(253,224,71,.92)"/>
        <rect x="330" y="167" width="22" height="16" rx="2" fill="rgba(253,224,71,.92)"/>
        <rect x="262" y="139" width="22" height="16" rx="2" fill="rgba(253,224,71,.7)"/>
        <rect x="295" y="210" width="28" height="30" rx="3" fill="rgba(255,255,255,.09)" stroke="rgba(255,255,255,.18)" stroke-width="1"/>
        <rect x="204" y="76" width="34" height="164" rx="3" fill="rgba(255,255,255,.05)" stroke="rgba(255,255,255,.14)" stroke-width="1"/>
        <g fill="rgba(147,197,253,.4)">
          <rect x="210" y="90"  width="18" height="12" rx="2"/>
          <rect x="210" y="113" width="18" height="12" rx="2"/>
          <rect x="210" y="136" width="18" height="12" rx="2"/>
          <rect x="210" y="159" width="18" height="12" rx="2"/>
        </g>
        <rect x="210" y="113" width="18" height="12" rx="2" fill="rgba(253,224,71,.78)"/>
        <ellipse cx="32"  cy="226" rx="20" ry="25" fill="rgba(52,211,153,.18)" stroke="rgba(52,211,153,.3)" stroke-width="1"/>
        <line x1="32" y1="226" x2="32" y2="240" stroke="rgba(255,255,255,.2)" stroke-width="2"/>
        <ellipse cx="406" cy="228" rx="16" ry="20" fill="rgba(52,211,153,.14)" stroke="rgba(52,211,153,.25)" stroke-width="1"/>
        <line x1="406" y1="228" x2="406" y2="240" stroke="rgba(255,255,255,.15)" stroke-width="1.5"/>
        <circle cx="390" cy="28" r="16" fill="rgba(255,255,255,.07)" stroke="rgba(255,255,255,.16)" stroke-width="1"/>
        <circle cx="397" cy="23" r="11" fill="rgba(7,27,78,.96)"/>
        <circle cx="350" cy="16" r="1.5" fill="rgba(255,255,255,.65)"/>
        <circle cx="318" cy="34" r="1"   fill="rgba(255,255,255,.45)"/>
        <circle cx="22"  cy="24" r="1.5" fill="rgba(255,255,255,.55)"/>
        <circle cx="52"  cy="11" r="1"   fill="rgba(255,255,255,.38)"/>
        <circle cx="170" cy="13" r="1.5" fill="rgba(255,255,255,.5)"/>
        <circle cx="228" cy="32" r="1"   fill="rgba(255,255,255,.32)"/>
      </svg>

      <h2 class="left-title">Your <em>home</em> away<br>from home awaits.</h2>
      <p class="left-desc">Discover verified hostels across 10+ cities. Book fast, move in smooth — trusted by 200+ students.</p>

      <div class="pills">
        <div class="pill"><div class="pill-dot"></div>50+ Hostels</div>
        <div class="pill"><div class="pill-dot"></div>10+ Cities</div>
        <div class="pill"><div class="pill-dot"></div>Free to Join</div>
      </div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="panel-right">
    <div class="form-box">

      <div class="badge">
        <div class="badge-dot">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        Join HostelHub
      </div>

      <h1 class="form-h1">Create your account</h1>
      <p class="form-sub">Start finding your perfect hostel today. It's completely free.</p>

      <!-- OTP SENT BANNER -->
      <?php if ($otp_sent): ?>
      <div class="alert alert-success">
        <span class="alert-icon">📩</span>
        <div>
          <strong class="alert-title">OTP Sent Successfully!</strong>
          <p>Check your email for the verification code. <a href="login.php">Login here →</a></p>
        </div>
      </div>
      <?php endif; ?>

      <!-- EMAIL TAKEN BANNER -->
      <?php   if (isset($_SESSION['email_taken']) && $_SESSION['email_taken']  && !(isset($_GET['otp']) && $_GET['otp'] === 'sent')): ?>
      <div class="alert alert-error">
        <span class="alert-icon">⚠️</span>
        <div>
          <strong class="alert-title">Email Already Registered</strong>
          <p>This email is already in use. <a href="login.php">Sign in instead?</a></p>
        </div>
      </div>
      <?php endif; ?>

      <!-- FORM — only show if OTP not sent yet -->
      <?php if (!$otp_sent): ?>
      <form id="regForm" method="POST" action="../Backend/backend.php" novalidate>

        <!-- Row 1: Full Name + Email -->
        <div class="inp-row">
          <div class="inp-group">
            <label class="inp-label" for="reg_name">Full Name</label>
            <div class="inp-wrap">
              <span class="inp-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
              </span>
              <input class="inp-field" type="text" id="reg_name" name="name"
                     value="<?= htmlspecialchars($old['name']) ?>"
                     placeholder="John Doe" autocomplete="name" required>
            </div>
          </div>

          <div class="inp-group">
            <label class="inp-label" for="reg_email">Email Address</label>
            <div class="inp-wrap">
              <span class="inp-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                </svg>
              </span>
              <!-- Add .error class if email was taken -->
              <input class="inp-field <?= $_SESSION['email_taken'] ? 'error' : '' ?>" type="email" id="reg_email" name="email"
                     value="<?= htmlspecialchars($old['email']) ?>"
                     placeholder="you@email.com" autocomplete="email" required>
            </div>
          </div>
        </div>

        <!-- Contact Number -->
        <div class="inp-group">
          <label class="inp-label" for="reg_contact">Contact Number</label>
          <div class="inp-wrap">
            <span class="inp-icon">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.09a16 16 0 006 6l.86-.85a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
              </svg>
            </span>
            <input class="inp-field" type="tel" id="reg_contact" name="contact"
                   value="<?= htmlspecialchars($old['contact']) ?>"
                   placeholder="+92 300 1234567" autocomplete="tel" required>
          </div>
        </div>

        <!-- Address -->
        <div class="inp-group">
          <label class="inp-label" for="reg_address">Address</label>
          <div class="inp-wrap textarea-wrap">
            <span class="inp-icon">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
              </svg>
            </span>
            <textarea class="inp-textarea" id="reg_address" name="address"
                      placeholder="House No., Street, City…" required><?= htmlspecialchars($old['address']) ?></textarea>
          </div>
        </div>

        <!-- Account Type -->
        <div style="margin-bottom:6px; animation: slideUp .5s ease .60s both;">
          <label class="inp-label">I am a</label>
        </div>

        <div class="role-row" id="roleRow">
          <div class="role-btn <?= $old['role'] === 'student' ? 'selected' : '' ?>" id="roleStudent" onclick="selectRole('student')">
            <div class="role-radio"></div>
            <div class="role-icon student">🎓</div>
            <div class="role-text">
              <strong>Student</strong>
              <span>Looking for a hostel</span>
            </div>
          </div>

          <div class="role-btn <?= $old['role'] === 'owner' ? 'selected' : '' ?>" id="roleOwner" onclick="selectRole('owner')">
            <div class="role-radio"></div>
            <div class="role-icon owner">🏢</div>
            <div class="role-text">
              <strong>Hostel Owner</strong>
              <span>List my property</span>
            </div>
          </div>
        </div>
        <input type="hidden" name="user_type" id="reg_user_type" value="<?= htmlspecialchars($old['role']) ?>">

        <!-- Terms -->
        <div class="terms-row">
          <input type="checkbox" class="terms-chk" id="reg_terms">
          <p class="terms-text">I agree to HostelHub's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></p>
        </div>

        <!-- CTA -->
        <button type="submit" name="reg_btn" class="submit-btn" id="submitBtn">
          Create Free Account
          <span class="btn-icon">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
          </span>
        </button>

      </form>
      <?php endif; ?>

      <p class="signin-row">Already have an account? <a href="login.php">Login here !</a></p>

    </div>
  </div>
</div>

<script>
/* ── Role Selector ── */
function selectRole(role) {
  document.getElementById('roleStudent').classList.toggle('selected', role === 'student');
  document.getElementById('roleOwner').classList.toggle('selected',   role === 'owner');
  document.getElementById('reg_user_type').value = role;
}

/* ── Ripple ── */
const btn = document.getElementById('submitBtn');
if (btn) {
  btn.addEventListener('click', function(e) {
    const el = document.createElement('span');
    el.className = 'ripple-el';
    const rect = this.getBoundingClientRect();
    el.style.left = (e.clientX - rect.left - 5) + 'px';
    el.style.top  = (e.clientY - rect.top  - 5) + 'px';
    this.appendChild(el);
    el.addEventListener('animationend', () => el.remove());
  });
}

/* ── Client-side Validation ── */
const form = document.getElementById('regForm');
if (form) {
  form.addEventListener('submit', function(e) {
    const name    = document.getElementById('reg_name').value.trim();
    const email   = document.getElementById('reg_email').value.trim();
    const contact = document.getElementById('reg_contact').value.trim();
    const address = document.getElementById('reg_address').value.trim();
    const terms   = document.getElementById('reg_terms').checked;

    let ok = true;

    if (!name)                                                 { highlightError('reg_name');    ok = false; }
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email))  { highlightError('reg_email');   ok = false; }
    if (!contact || !/^[\d\s\+\-\(\)]{7,15}$/.test(contact))  { highlightError('reg_contact'); ok = false; }
    if (!address)                                              { highlightError('reg_address'); ok = false; }
    if (!terms) {
      document.getElementById('reg_terms').style.outline = '2px solid #ef4444';
      ok = false;
    }

    if (!ok) {
      e.preventDefault();
      const fb = document.querySelector('.form-box');
      fb.style.animation = 'none';
      void fb.offsetWidth;
      fb.style.animation = 'shake .4s ease';
    }
  });
}

function highlightError(id) {
  const el = document.getElementById(id);
  el.classList.add('error');
  el.focus();
  el.addEventListener('input', () => el.classList.remove('error'), { once: true });
}

/* ── Auto-focus email field if email was taken (PHP-driven) ── */
<?php if ($_SESSION['email_taken']): ?>
document.getElementById('reg_email').focus();
<?php endif; ?>
</script>
</body>
</html>