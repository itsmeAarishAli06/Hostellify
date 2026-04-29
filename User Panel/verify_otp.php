<?php
session_start();

// - Always need otp_email
// - If not yet verified, also need otp + expiry
// - If verified, only need otp_email (otp is already unset)

if (!isset($_SESSION['otp_email'])) {
    header("Location: forgot_password.php");
    exit;
}
$otp_verified = $_SESSION['otp_verified'] ?? false;
if (!$otp_verified && !isset($_SESSION['otp'], $_SESSION['otp_expiry'])) {
    header("Location: forgot_password.php");
    exit;
}

$error   = $_SESSION['otp_error']   ?? '';
$success = $_SESSION['otp_success'] ?? '';
unset($_SESSION['otp_error'], $_SESSION['otp_success']);

// Mask email for display: aa***@gmail.com
$raw_email = $_SESSION['otp_email'];
$parts     = explode('@', $raw_email);
$name      = $parts[0];
$masked    = substr($name, 0, 2) . str_repeat('*', max(strlen($name) - 2, 3)) . '@' . ($parts[1] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HostelHub — <?= $otp_verified ? 'New Password' : 'Verify OTP' ?></title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --blue: #1a56db; --blue-dark: #0f2b6e;
  --text: #0c1a3a; --muted: #64748b;
  --border: #cbd5e1; --input-bg: #ffffff;
  --bg: #f1f5fd; --label: #475569;
}
html, body { height: 100vh; overflow: hidden; font-family: 'Plus Jakarta Sans', sans-serif; }
.wrapper { display: grid; grid-template-columns: 1fr 1fr; height: 100vh; overflow: hidden; }

/* LEFT */
.panel-left { background: var(--bg); display: flex; align-items: center; justify-content: center; padding: 40px 48px; overflow: hidden; position: relative; height: 100vh; }
.panel-left::before { content: ''; position: absolute; width: 480px; height: 480px; border-radius: 50%; background: radial-gradient(circle, #dbeafe55 0%, transparent 70%); top: -160px; left: -160px; pointer-events: none; }
.form-box { width: 100%; max-width: 420px; position: relative; z-index: 1; }

.badge { display: inline-flex; align-items: center; gap: 8px; background: #fff; border: 1.5px solid #c7d7f8; border-radius: 100px; padding: 5px 16px 5px 8px; font-size: .75rem; font-weight: 700; color: var(--blue); letter-spacing: .06em; text-transform: uppercase; margin-bottom: 18px; box-shadow: 0 2px 10px rgba(26,86,219,.1); animation: slideUp .5s ease .1s both; }
.badge-dot { width: 22px; height: 22px; background: var(--blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.form-h1 { font-family: 'DM Serif Display', serif; font-size: 1.8rem; color: var(--text); letter-spacing: -.4px; line-height: 1.2; margin-bottom: 6px; animation: slideUp .5s ease .18s both; }
.form-sub { font-size: .85rem; color: var(--muted); line-height: 1.6; margin-bottom: 20px; animation: slideUp .5s ease .26s both; }

/* Steps */
.steps { display: flex; align-items: center; margin-bottom: 18px; animation: slideUp .5s ease .3s both; }
.step { display: flex; align-items: center; gap: 8px; flex: 1; }
.step-num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .72rem; font-weight: 800; flex-shrink: 0; }
.step.done .step-num  { background: #22c55e; color: #fff; }
.step.active .step-num { background: var(--blue); color: #fff; box-shadow: 0 0 0 4px rgba(26,86,219,.15); }
.step.inactive .step-num { background: #e2e8f0; color: #94a3b8; }
.step-label { font-size: .7rem; font-weight: 700; letter-spacing: .03em; white-space: nowrap; }
.step.done .step-label   { color: #16a34a; }
.step.active .step-label { color: var(--blue); }
.step.inactive .step-label { color: #94a3b8; }
.step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 8px; }
.step-line.done-line { background: #22c55e; }

/* Email chip */
.email-chip { display: inline-flex; align-items: center; gap: 6px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 100px; padding: 5px 14px 5px 8px; font-size: .78rem; font-weight: 700; color: #1e40af; margin-bottom: 16px; animation: slideUp .5s ease .33s both; }

/* Alerts */
.server-err, .server-ok { display: flex; align-items: center; gap: 8px; border-radius: 10px; padding: 10px 14px; margin-bottom: 14px; font-size: .8rem; font-weight: 600; animation: slideUp .3s ease both; }
.server-err { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
.server-ok  { background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; }

/* OTP boxes */
.otp-section { animation: slideUp .5s ease .38s both; margin-bottom: 16px; }
.otp-label { font-size: .75rem; font-weight: 700; color: var(--label); letter-spacing: .04em; text-transform: uppercase; display: block; margin-bottom: 10px; }
.otp-boxes { display: flex; gap: 10px; justify-content: center; margin-bottom: 6px; }
.otp-box { width: 52px; height: 56px; background: #fff; border: 2px solid var(--border); border-radius: 12px; text-align: center; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--text); outline: none; transition: border-color .2s, box-shadow .2s, transform .15s; caret-color: var(--blue); box-shadow: 0 1px 4px rgba(0,0,0,.05); }
.otp-box:focus { border-color: var(--blue); box-shadow: 0 0 0 4px rgba(26,86,219,.12); transform: translateY(-2px); }
.otp-box.filled { border-color: var(--blue); background: #eff6ff; }
.otp-box.err    { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.12); background: #fef2f2; }
.otp-err { display: none; font-size: .7rem; font-weight: 600; color: #ef4444; text-align: center; margin-top: 6px; padding: 5px 10px; background: #fef2f2; border-radius: 6px; border: 1px solid #fecaca; }
.otp-err.show { display: block; }

/* Resend */
.resend-row { text-align: center; margin: 10px 0 16px; font-size: .78rem; color: var(--muted); animation: slideUp .5s ease .44s both; }
#resendBtn { background: none; border: none; cursor: pointer; color: var(--blue); font-family: 'Plus Jakarta Sans', sans-serif; font-size: .78rem; font-weight: 700; padding: 0; }
#resendBtn:disabled { color: #94a3b8; cursor: default; }

/* Password fields */
.pwd-section { animation: slideUp .5s ease .38s both; }
.inp-group { margin-bottom: 14px; }
.inp-label { font-size: .75rem; font-weight: 700; color: var(--label); letter-spacing: .04em; text-transform: uppercase; display: block; margin-bottom: 6px; }
.inp-wrap { position: relative; }
.inp-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #94a3b8; pointer-events: none; transition: color .2s; display: flex; align-items: center; justify-content: center; }
.inp-wrap:focus-within .inp-icon { color: var(--blue); }
.inp-field { display: block; width: 100%; height: 46px; padding: 0 44px 0 46px; background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 10px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .9rem; font-weight: 500; color: var(--text); outline: none; transition: border-color .2s, box-shadow .2s; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
.inp-field::placeholder { color: #b0bcd4; font-weight: 400; }
.inp-field:focus { border-color: var(--blue); box-shadow: 0 0 0 4px rgba(26,86,219,.1); }
.inp-field.err-state { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.15); }
.eye-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 4px; display: flex; align-items: center; transition: color .2s; }
.eye-btn:hover { color: var(--blue); }
.err-msg { display: none; font-size: .7rem; font-weight: 600; color: #ef4444; margin-top: 5px; padding: 5px 10px; background: #fef2f2; border-radius: 6px; border: 1px solid #fecaca; }
.err-msg.show { display: block; }

/* Strength */
.strength-bar { margin-top: 7px; }
.strength-track { height: 4px; background: #e2e8f0; border-radius: 10px; overflow: hidden; margin-bottom: 4px; }
.strength-fill { height: 100%; border-radius: 10px; transition: width .3s ease, background .3s ease; width: 0%; }
.strength-txt { font-size: .68rem; font-weight: 600; color: var(--muted); }

/* Button */
.submit-btn { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; height: 48px; background: linear-gradient(135deg, #0f2b6e 0%, #1a56db 55%, #2563eb 100%); border: none; border-radius: 11px; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .92rem; font-weight: 700; letter-spacing: .02em; cursor: pointer; position: relative; overflow: hidden; transition: transform .15s, box-shadow .25s; box-shadow: 0 5px 20px rgba(26,86,219,.38); animation: slideUp .5s ease .5s both; }
.submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(26,86,219,.5); }
.submit-btn:active { transform: translateY(0); }
.submit-btn::after { content: ''; position: absolute; inset: 0; background: linear-gradient(100deg, transparent 38%, rgba(255,255,255,.18) 52%, transparent 66%); transform: translateX(-120%); transition: transform .55s ease; }
.submit-btn:hover::after { transform: translateX(120%); }
.btn-icon { width: 24px; height: 24px; background: rgba(255,255,255,.18); border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: transform .2s; }
.submit-btn:hover .btn-icon { transform: translateX(3px); }

.back-row { text-align: center; margin-top: 14px; font-size: .82rem; color: var(--muted); animation: slideUp .5s ease .56s both; }
.back-row a { color: var(--blue); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
.back-row a:hover { text-decoration: underline; }

/* RIGHT */
.panel-right { background: linear-gradient(150deg, #071b4e 0%, #0d2562 28%, #1344b5 62%, #2563eb 100%); position: relative; display: flex; align-items: center; justify-content: center; padding: 50px 48px; overflow: hidden; height: 100vh; }
.orb { position: absolute; border-radius: 50%; filter: blur(90px); pointer-events: none; animation: orbFloat 10s ease-in-out infinite; }
.orb-1 { width: 400px; height: 400px; background: rgba(59,130,246,.4); top: -120px; right: -100px; }
.orb-2 { width: 250px; height: 250px; background: rgba(29,78,216,.45); bottom: -40px; left: -40px; animation-delay: -3.5s; }
.orb-3 { width: 140px; height: 140px; background: rgba(96,165,250,.35); top: 45%; right: 20%; animation-delay: -7s; }
@keyframes orbFloat { 0%,100%{transform:translate(0,0)} 40%{transform:translate(-20px,-25px)} 70%{transform:translate(15px,15px)} }
.dots { position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.1) 1px, transparent 1px); background-size: 30px 30px; pointer-events: none; }
.right-content { position: relative; z-index: 2; text-align: center; max-width: 360px; }
.right-ico { width: 80px; height: 80px; background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.22); border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; backdrop-filter: blur(10px); animation: popIn .6s cubic-bezier(.34,1.56,.64,1) .3s both; }
@keyframes popIn { from{transform:scale(.4);opacity:0} to{transform:scale(1);opacity:1} }
.right-title { font-family: 'DM Serif Display', serif; font-size: 2rem; line-height: 1.2; color: #fff; letter-spacing: -.4px; margin-bottom: 12px; animation: slideUp .6s ease .42s both; }
.right-title em { font-style: italic; color: #93c5fd; }
.right-desc { font-size: .85rem; line-height: 1.7; color: rgba(255,255,255,.58); margin-bottom: 28px; animation: slideUp .6s ease .52s both; }
.tips { animation: slideUp .6s ease .62s both; text-align: left; }
.tips-title { font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-bottom: 14px; text-align: center; }
.tip { display: flex; align-items: flex-start; gap: 10px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); border-radius: 10px; padding: 11px 14px; margin-bottom: 8px; backdrop-filter: blur(6px); }
.tip-ico { color: #93c5fd; flex-shrink: 0; margin-top: 1px; }
.tip-txt { font-size: .74rem; color: rgba(255,255,255,.65); line-height: 1.4; }

@keyframes slideUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
.ripple-el { position: absolute; border-radius: 50%; background: rgba(255,255,255,.35); width: 10px; height: 10px; transform: scale(0); pointer-events: none; animation: rippleGo .55s linear; }
@keyframes rippleGo { to{transform:scale(7);opacity:0} }
@media (max-width: 900px) { .wrapper{grid-template-columns:1fr} .panel-right{display:none} .panel-left{padding:40px 32px} }
@media (max-width: 600px) { .panel-left{padding:30px 24px} .form-h1{font-size:1.4rem} .otp-box{width:44px;height:50px;font-size:1.2rem} }
</style>
</head>
<body>
<div class="wrapper">
  <div class="panel-left">
    <div class="form-box">
      
      <a href="login.php">Back to login !</a>
      <div class="badge">
        <div class="badge-dot">
          <?php if ($otp_verified): ?>
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          <?php else: ?>
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <?php endif; ?>
        </div>
        <?= $otp_verified ? 'Set New Password' : 'Verify OTP' ?>
      </div>

      <h1 class="form-h1"><?= $otp_verified ? 'Create a new password' : 'Enter your OTP' ?></h1>
      <p class="form-sub">
        <?= $otp_verified
          ? 'OTP verified! Choose a strong new password for your account.'
          : 'We sent a 6-digit code to your email. It expires in 10 minutes.' ?>
      </p>

      <!-- Steps -->
      <div class="steps">
        <div class="step done">
          <div class="step-num">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <span class="step-label">Email Sent</span>
        </div>
        <div class="step-line done-line"></div>
        <div class="step <?= $otp_verified ? 'done' : 'active' ?>">
          <div class="step-num">
            <?php if ($otp_verified): ?>
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            <?php else: ?>2<?php endif; ?>
          </div>
          <span class="step-label">Verify OTP</span>
        </div>
        <div class="step-line <?= $otp_verified ? 'done-line' : '' ?>"></div>
        <div class="step <?= $otp_verified ? 'active' : 'inactive' ?>">
          <div class="step-num">3</div>
          <span class="step-label">New Password</span>
        </div>
      </div>

      <!-- Email chip -->
      <div class="email-chip">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
        </svg>
        <?= htmlspecialchars($masked) ?>
      </div>

      <?php if ($error): ?>
      <div class="server-err">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <?php if ($success): ?>
      <div class="server-ok">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <?= htmlspecialchars($success) ?>
      </div>
      <?php endif; ?>


      <?php if (!$otp_verified): ?>
      <!-- ── STEP 2: Enter OTP ── -->
      <form id="otpForm" method="POST" action="../Backend/backend.php" novalidate>
        <input type="hidden" name="action" value="verify_otp">
        <input type="hidden" name="otp"    id="otp_combined">

        <div class="otp-section">
          <label class="otp-label">Enter 6-Digit OTP</label>
          <div class="otp-boxes">
            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
          </div>
          <div class="otp-err" id="otpErr">Please enter the complete 6-digit OTP.</div>
        </div>

        <div class="resend-row">
          Didn't get the code?
          <button type="button" id="resendBtn" disabled>Resend in <span id="countdown">60</span>s</button>
        </div>

        <button type="submit" class="submit-btn" id="verifyBtn">
          Verify OTP
          <span class="btn-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </span>
        </button>
      </form>

      <?php else: ?>
      <!-- ── STEP 3: New Password ── -->
      <form id="pwdForm" method="POST" action="../Backend/backend.php" novalidate>
        <input type="hidden" name="action" value="reset_password">

        <div class="pwd-section">
          <div class="inp-group">
            <label class="inp-label" for="new_pwd">New Password</label>
            <div class="inp-wrap">
              <span class="inp-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
              </span>
              <input class="inp-field" name="new_password" type="password" id="new_pwd" placeholder="Min. 8 characters" required>
              <button type="button" class="eye-btn" onclick="togglePwd('new_pwd','eye1')" aria-label="Toggle">
                <svg id="eye1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            <div class="strength-bar">
              <div class="strength-track"><div class="strength-fill" id="strengthFill"></div></div>
              <span class="strength-txt" id="strengthTxt">Enter a password</span>
            </div>
            <div class="err-msg" id="newPwdErr">Password must be at least 8 characters.</div>
          </div>

          <div class="inp-group">
            <label class="inp-label" for="confirm_pwd">Confirm New Password</label>
            <div class="inp-wrap">
              <span class="inp-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
              </span>
              <input class="inp-field" name="confirm_password" type="password" id="confirm_pwd" placeholder="Re-enter your password" required>
              <button type="button" class="eye-btn" onclick="togglePwd('confirm_pwd','eye2')" aria-label="Toggle">
                <svg id="eye2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            <div class="err-msg" id="confirmPwdErr">Passwords do not match.</div>
          </div>
        </div>

        <button type="submit" class="submit-btn" id="resetBtn">
          Update Password
          <span class="btn-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
          </span>
        </button>
      </form>
      <?php endif; ?>

      <p class="back-row">
        <a href="forgot_password.php">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
          </svg>
          Start over
        </a>
      </p>

    </div>
  </div>

  <!-- RIGHT -->
  <div class="panel-right">
    <div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div>
    <div class="dots"></div>
    <div class="right-content">
      <div class="right-ico">
        <?php if ($otp_verified): ?>
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
        </svg>
        <?php else: ?>
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
        <?php endif; ?>
      </div>
      <h2 class="right-title">
        <?= $otp_verified ? 'Almost <em>there</em>,<br>you\'re secure.' : 'Check your <em>inbox</em>,<br>OTP is waiting.' ?>
      </h2>
      <p class="right-desc">
        <?= $otp_verified
          ? 'Create a strong password you haven\'t used before. Mix letters, numbers, and symbols.'
          : 'The OTP was sent to your email. Check spam if you don\'t see it within a minute.' ?>
      </p>
      <div class="tips">
        <p class="tips-title"><?= $otp_verified ? 'Password tips' : 'Security reminder' ?></p>
        <?php if ($otp_verified): ?>
        <div class="tip"><span class="tip-ico"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span><span class="tip-txt">Use at least 8 characters with uppercase and lowercase letters.</span></div>
        <div class="tip"><span class="tip-ico"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span><span class="tip-txt">Add numbers or symbols like @, #, ! for extra strength.</span></div>
        <div class="tip"><span class="tip-ico"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span><span class="tip-txt">Avoid your name, email, or common words.</span></div>
        <?php else: ?>
        <div class="tip"><span class="tip-ico"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span><span class="tip-txt">HostelHub will never ask for your OTP via phone or chat.</span></div>
        <div class="tip"><span class="tip-ico"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span><span class="tip-txt">OTP is valid for 10 minutes only. Request a new one if expired.</span></div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<script>
<?php if (!$otp_verified): ?>
// ── OTP box behaviour ──
const boxes = document.querySelectorAll('.otp-box');

boxes.forEach((box, i) => {
  box.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value) {
      this.classList.add('filled');
      if (i < 5) boxes[i + 1].focus();
    } else {
      this.classList.remove('filled');
    }
    updateCombined();
    clearErr();
  });

  box.addEventListener('keydown', function(e) {
    if (e.key === 'Backspace' && !this.value && i > 0) {
      boxes[i - 1].value = '';
      boxes[i - 1].classList.remove('filled');
      boxes[i - 1].focus();
      updateCombined();
    }
  });

  box.addEventListener('paste', function(e) {
    e.preventDefault();
    const digits = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
    digits.split('').forEach((ch, idx) => {
      if (boxes[idx]) { boxes[idx].value = ch; boxes[idx].classList.add('filled'); }
    });
    const next = Math.min(digits.length, 5);
    boxes[next].focus();
    updateCombined();
    clearErr();
  });

  box.addEventListener('focus', function() { this.select(); });
});

function updateCombined() {
  document.getElementById('otp_combined').value = [...boxes].map(b => b.value).join('');
}
function clearErr() {
  document.getElementById('otpErr').classList.remove('show');
  boxes.forEach(b => b.classList.remove('err'));
}

document.getElementById('otpForm').addEventListener('submit', function(e) {
  const otp = document.getElementById('otp_combined').value;
  if (otp.length < 6) {
    document.getElementById('otpErr').classList.add('show');
    boxes.forEach(b => { if (!b.value) b.classList.add('err'); });
    e.preventDefault();
    shake();
    return;
  }
  const btn = document.getElementById('verifyBtn');
  btn.disabled = true;
  btn.innerHTML = spin() + ' Verifying...';
});

// Countdown resend
let sec = 60;
const cd  = document.getElementById('countdown');
const rbtn = document.getElementById('resendBtn');
const tmr = setInterval(() => {
  sec--;
  cd.textContent = sec;
  if (sec <= 0) {
    clearInterval(tmr);
    rbtn.disabled = false;
    rbtn.textContent = 'Resend OTP';
  }
}, 1000);

rbtn.addEventListener('click', () => {
  window.location.href = '../Backend/backend.php?action=resend_otp';
});

<?php else: ?>
// ── Password strength ──
document.getElementById('new_pwd').addEventListener('input', function() {
  const v = this.value;
  let score = 0;
  if (v.length >= 8) score++;
  if (/[A-Z]/.test(v)) score++;
  if (/[0-9]/.test(v)) score++;
  if (/[^a-zA-Z0-9]/.test(v)) score++;
  const map = [
    { w:'0%',   c:'#e2e8f0', t:'Enter a password' },
    { w:'25%',  c:'#ef4444', t:'Weak' },
    { w:'50%',  c:'#f97316', t:'Fair' },
    { w:'75%',  c:'#eab308', t:'Good' },
    { w:'100%', c:'#22c55e', t:'Strong 💪' },
  ];
  const s = v.length === 0 ? 0 : Math.max(score, 1);
  const fill = document.getElementById('strengthFill');
  const txt  = document.getElementById('strengthTxt');
  fill.style.width = map[s].w;
  fill.style.background = map[s].c;
  txt.textContent = map[s].t;
  txt.style.color = s === 0 ? 'var(--muted)' : map[s].c;
  this.classList.remove('err-state');
  document.getElementById('newPwdErr').classList.remove('show');
});

document.getElementById('confirm_pwd').addEventListener('input', function() {
  this.classList.remove('err-state');
  document.getElementById('confirmPwdErr').classList.remove('show');
});

document.getElementById('pwdForm').addEventListener('submit', function(e) {
  const np = document.getElementById('new_pwd').value;
  const cp = document.getElementById('confirm_pwd').value;
  let ok = true;
  if (np.length < 8) {
    document.getElementById('new_pwd').classList.add('err-state');
    document.getElementById('newPwdErr').classList.add('show');
    ok = false;
  }
  if (np !== cp) {
    document.getElementById('confirm_pwd').classList.add('err-state');
    document.getElementById('confirmPwdErr').classList.add('show');
    ok = false;
  }
  if (!ok) { e.preventDefault(); shake(); return; }
  const btn = document.getElementById('resetBtn');
  btn.disabled = true;
  btn.innerHTML = spin() + ' Updating...';
});
<?php endif; ?>

function togglePwd(id, icoId) {
  const inp = document.getElementById(id);
  const ico = document.getElementById(icoId);
  const show = inp.type === 'password';
  inp.type = show ? 'text' : 'password';
  ico.innerHTML = show
    ? `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`
    : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
}
function shake() {
  const fb = document.querySelector('.form-box');
  fb.style.animation = 'none'; void fb.offsetWidth; fb.style.animation = 'shake .4s ease';
}
function spin() {
  return `<svg style="animation:spin 1s linear infinite" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg>`;
}

const st = document.createElement('style');
st.textContent = '@keyframes shake{0%,100%{transform:translateX(0)}20%,60%{transform:translateX(-8px)}40%,80%{transform:translateX(8px)}} @keyframes spin{to{transform:rotate(360deg)}}';
document.head.appendChild(st);
</script>
</body>
</html>