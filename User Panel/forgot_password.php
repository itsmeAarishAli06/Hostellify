<?php
session_start();

// If OTP already sent but not verified, go directly to verify page
if (isset($_SESSION['otp_email']) && !isset($_SESSION['otp_verified'])) {
    header("Location: verify_otp.php");
    exit;
}

$error   = $_SESSION['fp_error']   ?? '';
$prefill = $_SESSION['fp_prefill'] ?? '';
unset($_SESSION['fp_error'], $_SESSION['fp_prefill']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HostelHub — Forgot Password</title>
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
.panel-left {
  background: var(--bg);
  display: flex; align-items: center; justify-content: center;
  padding: 40px 48px; overflow: hidden; position: relative; height: 100vh;
}
.panel-left::before {
  content: ''; position: absolute; width: 480px; height: 480px; border-radius: 50%;
  background: radial-gradient(circle, #dbeafe55 0%, transparent 70%);
  top: -160px; left: -160px; pointer-events: none;
}
.form-box { width: 100%; max-width: 420px; position: relative; z-index: 1; }

.badge {
  display: inline-flex; align-items: center; gap: 8px; background: #fff;
  border: 1.5px solid #c7d7f8; border-radius: 100px; padding: 5px 16px 5px 8px;
  font-size: .75rem; font-weight: 700; color: var(--blue);
  letter-spacing: .06em; text-transform: uppercase; margin-bottom: 18px;
  box-shadow: 0 2px 10px rgba(26,86,219,.1); animation: slideUp .5s ease .1s both;
}
.badge-dot { width: 22px; height: 22px; background: var(--blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.form-h1 { font-family: 'DM Serif Display', serif; font-size: 1.8rem; color: var(--text); letter-spacing: -.4px; line-height: 1.2; margin-bottom: 6px; animation: slideUp .5s ease .18s both; }
.form-sub { font-size: .85rem; color: var(--muted); line-height: 1.6; margin-bottom: 28px; animation: slideUp .5s ease .26s both; }

/* Steps */
.steps { display: flex; align-items: center; margin-bottom: 28px; animation: slideUp .5s ease .3s both; }
.step { display: flex; align-items: center; gap: 8px; flex: 1; }
.step-num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .72rem; font-weight: 800; flex-shrink: 0; }
.step.active .step-num { background: var(--blue); color: #fff; box-shadow: 0 0 0 4px rgba(26,86,219,.15); }
.step.inactive .step-num { background: #e2e8f0; color: #94a3b8; }
.step-label { font-size: .7rem; font-weight: 700; letter-spacing: .03em; white-space: nowrap; }
.step.active .step-label { color: var(--blue); }
.step.inactive .step-label { color: #94a3b8; }
.step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 8px; }

/* Input */
.inp-group { margin-bottom: 18px; animation: slideUp .5s ease .34s both; }
.inp-label { font-size: .75rem; font-weight: 700; color: var(--label); letter-spacing: .04em; text-transform: uppercase; display: block; margin-bottom: 6px; }
.inp-wrap { position: relative; }
.inp-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #94a3b8; pointer-events: none; transition: color .2s; display: flex; align-items: center; justify-content: center; }
.inp-wrap:focus-within .inp-icon { color: var(--blue); }
.inp-field { display: block; width: 100%; height: 46px; padding: 0 16px 0 46px; background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 10px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .9rem; font-weight: 500; color: var(--text); outline: none; transition: border-color .2s, box-shadow .2s; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
.inp-field::placeholder { color: #b0bcd4; font-weight: 400; }
.inp-field:focus { border-color: var(--blue); box-shadow: 0 0 0 4px rgba(26,86,219,.1); }
.inp-field.err-state { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.15); }

/* Hint */
.hint-box { display: flex; align-items: flex-start; gap: 10px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 12px 14px; margin-bottom: 20px; animation: slideUp .5s ease .4s both; }
.hint-ico { color: var(--blue); flex-shrink: 0; margin-top: 1px; }
.hint-txt { font-size: .78rem; color: #1e40af; line-height: 1.55; }

/* Errors */
.server-err { display: flex; align-items: center; gap: 8px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 10px 14px; margin-bottom: 16px; font-size: .8rem; font-weight: 600; color: #dc2626; animation: slideUp .3s ease both; }
.err-msg { display: none; font-size: .7rem; font-weight: 600; color: #ef4444; margin-top: 5px; padding: 5px 10px; background: #fef2f2; border-radius: 6px; border: 1px solid #fecaca; }
.err-msg.show { display: block; }

/* Button */
.submit-btn { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; height: 48px; background: linear-gradient(135deg, #0f2b6e 0%, #1a56db 55%, #2563eb 100%); border: none; border-radius: 11px; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .92rem; font-weight: 700; letter-spacing: .02em; cursor: pointer; position: relative; overflow: hidden; transition: transform .15s, box-shadow .25s; box-shadow: 0 5px 20px rgba(26,86,219,.38); animation: slideUp .5s ease .46s both; }
.submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(26,86,219,.5); }
.submit-btn:active { transform: translateY(0); }
.submit-btn::after { content: ''; position: absolute; inset: 0; background: linear-gradient(100deg, transparent 38%, rgba(255,255,255,.18) 52%, transparent 66%); transform: translateX(-120%); transition: transform .55s ease; }
.submit-btn:hover::after { transform: translateX(120%); }
.btn-icon { width: 24px; height: 24px; background: rgba(255,255,255,.18); border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: transform .2s; }
.submit-btn:hover .btn-icon { transform: translateX(3px); }

.back-row { text-align: center; margin-top: 16px; font-size: .82rem; color: var(--muted); animation: slideUp .5s ease .52s both; }
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
.right-desc { font-size: .85rem; line-height: 1.7; color: rgba(255,255,255,.58); margin-bottom: 36px; animation: slideUp .6s ease .52s both; }
.how-it-works { animation: slideUp .6s ease .62s both; text-align: left; }
.how-title { font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-bottom: 16px; text-align: center; }
.how-step { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px; }
.how-step-num { width: 28px; height: 28px; flex-shrink: 0; background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.18); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .72rem; font-weight: 800; color: #93c5fd; }
.how-step-txt strong { display: block; font-size: .82rem; font-weight: 700; color: #fff; margin-bottom: 2px; }
.how-step-txt span { font-size: .74rem; color: rgba(255,255,255,.5); line-height: 1.4; }

@keyframes slideUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
.ripple-el { position: absolute; border-radius: 50%; background: rgba(255,255,255,.35); width: 10px; height: 10px; transform: scale(0); pointer-events: none; animation: rippleGo .55s linear; }
@keyframes rippleGo { to{transform:scale(7);opacity:0} }
@media (max-width: 900px) { .wrapper{grid-template-columns:1fr} .panel-right{display:none} .panel-left{padding:40px 32px} }
@media (max-width: 600px) { .panel-left{padding:30px 24px} .form-h1{font-size:1.4rem} }
</style>
</head>
<body>
<div class="wrapper">

  <div class="panel-left">
    <div class="form-box">
      <a href="login.php"> Back to login !</a>

      <div class="badge">
        <div class="badge-dot">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
          </svg>
        </div>
        Reset Password
      </div>

      <h1 class="form-h1">Forgot your password?</h1>
      <p class="form-sub">Enter your registered email and we'll send a 6-digit OTP to reset it.</p>

      <div class="steps">
        <div class="step active">
          <div class="step-num">1</div>
          <span class="step-label">Enter Email</span>
        </div>
        <div class="step-line"></div>
        <div class="step inactive">
          <div class="step-num">2</div>
          <span class="step-label">Verify OTP</span>
        </div>
        <div class="step-line"></div>
        <div class="step inactive">
          <div class="step-num">3</div>
          <span class="step-label">New Password</span>
        </div>
      </div>

      <?php if ($error): ?>
      <div class="server-err">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <div class="hint-box">
        <span class="hint-ico">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
        </span>
        <p class="hint-txt">The OTP will expire in <strong>10 minutes</strong>. Use your registered email address.</p>
      </div>

      <form id="fpForm" method="POST" action="../Backend/backend.php" novalidate>
        <input type="hidden" name="action" value="send_otp">

        <div class="inp-group">
          <label class="inp-label" for="fp_email">Registered Email Address</label>
          <div class="inp-wrap">
            <span class="inp-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
              </svg>
            </span>
            <input class="inp-field" name="email" type="email" id="fp_email"
                   placeholder="you@example.com"
                   value="<?= htmlspecialchars($prefill) ?>"
                   autocomplete="email" required>
          </div>
          <div class="err-msg" id="emailErr">Please enter a valid email address.</div>
        </div>

        <button type="submit" class="submit-btn" id="sendBtn">
          Send OTP to my Email
          <span class="btn-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
              <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
          </span>
        </button>
      </form>

      <p class="back-row">
        <a href="login.php">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
          </svg>
          Back to Sign In
        </a>
      </p>

    </div>
  </div>

  <div class="panel-right">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="dots"></div>
    <div class="right-content">
      <div class="right-ico">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/>
        </svg>
      </div>
      <h2 class="right-title">Reset in <em>3 easy</em><br>steps.</h2>
      <p class="right-desc">We'll verify your identity via email OTP and get you back in securely.</p>
      <div class="how-it-works">
        <p class="how-title">How it works</p>
        <div class="how-step">
          <div class="how-step-num">1</div>
          <div class="how-step-txt"><strong>Enter your email</strong><span>We check if it matches a registered account.</span></div>
        </div>
        <div class="how-step">
          <div class="how-step-num">2</div>
          <div class="how-step-txt"><strong>Check your inbox</strong><span>A 6-digit OTP arrives within seconds.</span></div>
        </div>
        <div class="how-step">
          <div class="how-step-num">3</div>
          <div class="how-step-txt"><strong>Set a new password</strong><span>Enter the OTP and choose your new password.</span></div>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
document.getElementById('fpForm').addEventListener('submit', function(e) {
  const email    = document.getElementById('fp_email').value.trim();
  const emailErr = document.getElementById('emailErr');
  const field    = document.getElementById('fp_email');

  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    emailErr.classList.add('show');
    field.classList.add('err-state');
    e.preventDefault();
    const fb = document.querySelector('.form-box');
    fb.style.animation = 'none'; void fb.offsetWidth; fb.style.animation = 'shake .4s ease';
    return;
  }
  emailErr.classList.remove('show');
  field.classList.remove('err-state');

  const btn = document.getElementById('sendBtn');
  btn.disabled = true;
  btn.innerHTML = '<svg style="animation:spin 1s linear infinite" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Sending OTP...';
});

document.getElementById('fp_email').addEventListener('input', function() {
  this.classList.remove('err-state');
  document.getElementById('emailErr').classList.remove('show');
});

document.getElementById('sendBtn').addEventListener('click', function(e) {
  const el = document.createElement('span');
  el.className = 'ripple-el';
  const rect = this.getBoundingClientRect();
  el.style.left = (e.clientX - rect.left - 5) + 'px';
  el.style.top  = (e.clientY - rect.top  - 5) + 'px';
  this.appendChild(el);
  el.addEventListener('animationend', () => el.remove());
});

const st = document.createElement('style');
st.textContent = '@keyframes shake{0%,100%{transform:translateX(0)}20%,60%{transform:translateX(-8px)}40%,80%{transform:translateX(8px)}} @keyframes spin{to{transform:rotate(360deg)}}';
document.head.appendChild(st);
</script>
</body>
</html>