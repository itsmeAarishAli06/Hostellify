<?php
  if (isset($_GET['status']) && $_GET['status'] === "blocked") {
    echo "<script>alert('You are blocked , So pls contact to the admin via contact form !');</script>";
  }
?>
<!DOCTYPE html>

<html lang="en">

  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HostelHub — Sign In</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --blue:      #1a56db;
  --blue-dark: #0f2b6e;
  --text:      #0c1a3a;
  --muted:     #64748b;
  --border:    #cbd5e1;
  --input-bg:  #ffffff;
  --bg:        #f1f5fd;
  --label:     #475569;
}
html, body {
  height: 100vh;
  overflow: hidden;
  font-family: 'Plus Jakarta Sans', sans-serif;

}
.wrapper {
  display: grid;
  grid-template-columns: 1fr 1fr;
  height: 100vh;
  overflow: hidden;
}
/* ═══════════ LEFT PANEL — Form side ═══════════ */

.panel-left {
  background: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 48px;
  overflow: hidden;
  position: relative;
  height: 100vh;
}
.panel-left::before {
  content: '';
  position: absolute;
  width: 480px; height: 480px;
  border-radius: 50%;
  background: radial-gradient(circle, #dbeafe55 0%, transparent 70%);
  top: -160px; left: -160px;
  pointer-events: none;
}
.form-box {
  width: 100%;
  max-width: 420px;
  position: relative; z-index: 1;
}
/* Badge */

.badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: #fff;
  border: 1.5px solid #c7d7f8;
  border-radius: 100px;
  padding: 5px 16px 5px 8px;
  font-size: 0.75rem; font-weight: 700;
  color: var(--blue); letter-spacing: 0.06em; text-transform: uppercase;
  margin-bottom: 18px;
  box-shadow: 0 2px 10px rgba(26,86,219,0.1);
  animation: slideUp .5s ease .1s both;
}
.badge-dot {
  width: 22px; height: 22px;
  background: var(--blue); border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
}
.form-h1 {
  font-family: 'DM Serif Display', serif;
  font-size: 1.8rem; color: var(--text);
  letter-spacing: -0.4px; line-height: 1.2;
  margin-bottom: 6px;
  animation: slideUp .5s ease .18s both;
}
.form-sub {
  font-size: 0.85rem; color: var(--muted);
  line-height: 1.6; margin-bottom: 28px;
  animation: slideUp .5s ease .26s both;
}
/* ─── INPUT GROUPS ─── */

.inp-group {
  margin-bottom: 18px;
}
.inp-group:nth-child(1) { animation: slideUp .5s ease .34s both; }
.inp-group:nth-child(2) { animation: slideUp .5s ease .42s both; }
.inp-label-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 6px;
}
.inp-label {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--label);
  letter-spacing: 0.04em;
  text-transform: uppercase;
}
.forgot-link {
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--blue);
  text-decoration: none;
  letter-spacing: 0.01em;
}
.forgot-link:hover { text-decoration: underline; }
.inp-wrap { position: relative; }
.inp-icon {
  position: absolute;
  left: 14px; top: 50%;
  transform: translateY(-50%);
  width: 18px; height: 18px;
  color: #94a3b8;
  pointer-events: none;
  transition: color .2s;
  display: flex; align-items: center; justify-content: center;
}
.inp-wrap:focus-within .inp-icon { color: var(--blue); }
.inp-field {
  display: block;
  width: 100%;
  height: 46px;
  padding: 0 44px 0 46px;
  background: var(--input-bg);
  border: 1.5px solid var(--border);
  border-radius: 10px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.9rem;
  font-weight: 500;
  color: var(--text);
  outline: none;
  transition: border-color .2s, box-shadow .2s;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
.inp-field::placeholder {
  color: #b0bcd4;
  font-weight: 400;
}
.inp-field:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 4px rgba(26,86,219,0.1);
}
.eye-btn {
  position: absolute;
  right: 12px; top: 50%;
  transform: translateY(-50%);
  background: none; border: none; cursor: pointer;
  color: #94a3b8; padding: 4px;
  display: flex; align-items: center;
  transition: color .2s;
}
.eye-btn:hover { color: var(--blue); }
/* ─── Error message ─── */
.err-msg {
  display: none;
  font-size: 0.7rem; font-weight: 600;
  color: #ef4444;
  margin-top: 5px;
  padding: 5px 10px;
  background: #fef2f2;
  border-radius: 6px;
  border: 1px solid #fecaca;
  animation: slideUp .25s ease;
}
.err-msg.show { display: block; }
/* ═══════════ ROLE SELECTOR ═══════════ */
.role-group {
  margin-bottom: 18px;
  animation: slideUp .5s ease .48s both;
}
.role-label-row {
  margin-bottom: 8px;
}
.role-options {

  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
}
.role-option {
  position: relative;
}
.role-option input[type="radio"] {
  position: absolute;
  opacity: 0;
  width: 0; height: 0;
}
.role-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px 8px;
  background: #fff;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  cursor: pointer;
  transition: border-color .2s, box-shadow .2s, background .2s, transform .15s;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
  user-select: none;
}
.role-card:hover {
  border-color: #93c5fd;
  box-shadow: 0 0 0 3px rgba(26,86,219,0.08);
  transform: translateY(-1px);
}
.role-option input:checked + .role-card {
  border-color: var(--blue);
  background: #eff6ff;
  box-shadow: 0 0 0 3px rgba(26,86,219,0.12);
}
.role-ico {
  width: 32px; height: 32px;
  border-radius: 8px;
  background: #f1f5fd;
  display: flex; align-items: center; justify-content: center;
  color: #94a3b8;
  transition: background .2s, color .2s;
}
.role-option input:checked + .role-card .role-ico {
  background: #dbeafe;
  color: var(--blue);
}
.role-name {
  font-size: 0.7rem;
  font-weight: 700;
  color: var(--muted);
  letter-spacing: 0.02em;
  text-align: center;
  transition: color .2s;
}
.role-option input:checked + .role-card .role-name {
  color: var(--blue);
}
.role-tick {
  position: absolute;
  top: 6px; right: 6px;
  width: 14px; height: 14px;
  background: var(--blue);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  opacity: 0;
  transform: scale(0);
  transition: opacity .2s, transform .2s;
}
.role-option input:checked ~ .role-tick {
  opacity: 1;
  transform: scale(1);
}
.role-err {
  display: none;
  font-size: 0.7rem; font-weight: 600;
  color: #ef4444;
  margin-top: 5px;
  padding: 5px 10px;
  background: #fef2f2;
  border-radius: 6px;
  border: 1px solid #fecaca;
}
.role-err.show { display: block; }
/* ─── Remember row ─── */
.remember-row {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 20px;
  animation: slideUp .5s ease .56s both;
}
.remember-chk {
  appearance: none; -webkit-appearance: none;
  width: 18px; height: 18px;
  min-width: 18px;
  border: 2px solid var(--border); border-radius: 5px;
  background: #fff; cursor: pointer;
  position: relative; transition: all .2s;
}
.remember-chk:checked { background: var(--blue); border-color: var(--blue); }
.remember-chk:checked::after {
  content: '';
  position: absolute;
  left: 3px; top: 0px;
  width: 8px; height: 5px;
  border-left: 2px solid #fff;
  border-bottom: 2px solid #fff;
  transform: rotate(-45deg);
}
.remember-txt { font-size: 0.8rem; color: var(--muted); font-weight: 500; }
/* ─── Submit btn ─── */
.submit-btn {
  display: flex; align-items: center; justify-content: center; gap: 10px;
  width: 100%; height: 48px;
  background: linear-gradient(135deg, #0f2b6e 0%, #1a56db 55%, #2563eb 100%);
  border: none; border-radius: 11px;
  color: #fff;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.92rem; font-weight: 700; letter-spacing: 0.02em;
  cursor: pointer;
  position: relative; overflow: hidden;
  transition: transform .15s, box-shadow .25s;
  box-shadow: 0 5px 20px rgba(26,86,219,0.38);
  animation: slideUp .5s ease .63s both;
}
.submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(26,86,219,0.5); }
.submit-btn:active { transform: translateY(0); }
.submit-btn::after {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(100deg, transparent 38%, rgba(255,255,255,0.18) 52%, ransparent 66%);
  transform: translateX(-120%);
  transition: transform .55s ease;
}
.submit-btn:hover::after { transform: translateX(120%); }
.btn-icon {
  width: 24px; height: 24px;
  background: rgba(255,255,255,0.18);
  border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  transition: transform .2s;
}
.submit-btn:hover .btn-icon { transform: translateX(3px); }
/* ─── Sign-up row ─── */
.signup-row {
  text-align: center; margin-top: 16px;
  font-size: 0.82rem; color: var(--muted);
  animation: slideUp .5s ease .7s both;
}
.signup-row a {

  color: var(--blue); font-weight: 700;

  text-decoration: none; position: relative;

}



.signup-row a::after {

  content: '';

  position: absolute; left: 0; bottom: -1px;

  width: 0; height: 2px;

  background: var(--blue); border-radius: 2px;

  transition: width .28s ease;

}



.signup-row a:hover::after { width: 100%; }



/* ═══════════ RIGHT PANEL — Decorative ═══════════ */

.panel-right {

  background: linear-gradient(150deg, #071b4e 0%, #0d2562 28%, #1344b5 62%, #2563eb 100%);

  position: relative;

  display: flex; align-items: center; justify-content: center;

  padding: 50px 48px;

  overflow: hidden;

  height: 100vh;

}



.orb {

  position: absolute; border-radius: 50%;

  filter: blur(90px); pointer-events: none;

  animation: orbFloat 10s ease-in-out infinite;

}



.orb-1 { width: 400px; height: 400px; background: rgba(59,130,246,.4);  top: -120px; right: -100px; animation-delay: 0s; }

.orb-2 { width: 250px; height: 250px; background: rgba(29,78,216,.45);  bottom: -40px; left: -40px; animation-delay: -3.5s; }

.orb-3 { width: 140px; height: 140px; background: rgba(96,165,250,.35); top: 45%; right: 20%; animation-delay: -7s; }



@keyframes orbFloat {

  0%,100% { transform: translate(0,0); }

  40%     { transform: translate(-20px,-25px); }

  70%     { transform: translate(15px,15px); }

}



.dots {

  position: absolute; inset: 0;

  background-image: radial-gradient(rgba(255,255,255,.1) 1px, transparent 1px);

  background-size: 30px 30px; pointer-events: none;

}



.right-content {

  position: relative; z-index: 2;

  text-align: center; max-width: 360px;

}



.welcome-ico {

  width: 80px; height: 80px;

  background: rgba(255,255,255,.13);

  border: 1px solid rgba(255,255,255,.22);

  border-radius: 24px;

  display: flex; align-items: center; justify-content: center;

  margin: 0 auto 24px;

  backdrop-filter: blur(10px);

  animation: popIn .6s cubic-bezier(.34,1.56,.64,1) .3s both;

}



@keyframes popIn { from { transform: scale(.4); opacity: 0; } to { transform: scale(1); opacity: 1; } }



.right-title {

  font-family: 'DM Serif Display', serif;

  font-size: 2rem; line-height: 1.2;

  color: #fff; letter-spacing: -0.4px;

  margin-bottom: 12px;

  animation: slideUp .6s ease .42s both;

}



.right-title em { font-style: italic; color: #93c5fd; }



.right-desc {

  font-size: 0.85rem; line-height: 1.7;

  color: rgba(255,255,255,.58);

  margin-bottom: 36px;

  animation: slideUp .6s ease .52s both;

}



/* Stats grid */

.stats-grid {

  display: grid; grid-template-columns: 1fr 1fr; gap: 12px;

  animation: slideUp .6s ease .62s both;

}



.stat-card {

  background: rgba(255,255,255,.1);

  border: 1px solid rgba(255,255,255,.16);

  border-radius: 14px; padding: 16px 12px;

  backdrop-filter: blur(8px); text-align: center;

}



.stat-num {

  font-size: 1.5rem; font-weight: 800;

  color: #fff; letter-spacing: -1px;

  display: block; line-height: 1;

}



.stat-desc {

  font-size: 0.7rem; font-weight: 600;

  color: rgba(255,255,255,.6);

  margin-top: 4px; display: block;

}



/* ─── Animations ─── */

@keyframes slideUp {

  from { opacity: 0; transform: translateY(16px); }

  to   { opacity: 1; transform: translateY(0); }

}



@keyframes shake {

  0%,100% { transform: translateX(0); }

  20%,60% { transform: translateX(-8px); }

  40%,80% { transform: translateX(8px); }

}



@keyframes spin { to { transform: rotate(360deg); } }







/* Ripple */

.ripple-el {

  position: absolute; border-radius: 50%;

  background: rgba(255,255,255,.35);

  width: 10px; height: 10px;

  transform: scale(0); pointer-events: none;

  animation: rippleGo .55s linear;

}



@keyframes rippleGo { to { transform: scale(7); opacity: 0; } }



/* ─── Responsive ─── */

@media (max-width: 900px) {

  .wrapper { grid-template-columns: 1fr; }

  .panel-right { display: none; }

  .panel-left { padding: 40px 32px; }

  .form-h1 { font-size: 1.6rem; }

}



@media (max-width: 600px) {

  .panel-left { padding: 30px 24px; }

  .form-box { max-width: 100%; }

  .form-h1 { font-size: 1.4rem; }

  .form-sub { font-size: 0.8rem; margin-bottom: 22px; }

  .inp-field { height: 44px; font-size: 0.88rem; }

  .submit-btn { height: 44px; font-size: 0.9rem; }

  .role-options { grid-template-columns: repeat(3, 1fr); gap: 6px; }

}

</style>

</head>

<body>



<div class="wrapper">

  <!-- ══════════ LEFT — Form ══════════ -->

  <div class="panel-left">

    <div class="form-box">

      <div class="badge">
        <div class="badge-dot">

          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">

            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>

          </svg>

        </div>
        Secure Login
      </div>
      <h1 class="form-h1">Welcome back!</h1>

      <p class="form-sub">Sign in to access your dashboard and manage your hostel bookings.</p>



      <form id="loginForm" method="POST" action="../Backend/backend.php" novalidate>

        <!-- Email -->

        <div class="inp-group">

          <div class="inp-label-row">

            <label class="inp-label" for="log_email">Email Address</label>

          </div>

          <div class="inp-wrap">

            <span class="inp-icon">

              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>

              </svg>

            </span>

            <input class="inp-field" name="email" type="email" id="log_email" placeholder="you@example.com" autocomplete="email" required>

          </div>

          <div class="err-msg" id="emailErr">Please enter a valid email address.</div>

        </div>



        <!-- Password -->

        <div class="inp-group">

          <div class="inp-label-row">

            <label class="inp-label" for="log_password">Password / OTP</label>

            <a href="forgot_password.php" class="forgot-link">Forgot password?</a>

          </div>

          <div class="inp-wrap">

            <span class="inp-icon">

              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>

              </svg>

            </span>

            <input class="inp-field" name="password" type="password" id="log_password" placeholder="Enter your password" autocomplete="current-password" required>

            <button type="button" class="eye-btn" onclick="toggleLoginPwd()" aria-label="Toggle password">

              <svg id="loginEyeIco" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>

              </svg>

            </button>

          </div>

          <div class="err-msg" id="pwdErr">Password must be at least 6 characters.</div>

        </div>



        <!-- ══ ROLE SELECTOR ══ -->

        <div class="role-group">

          <div class="role-label-row inp-label-row">

            <span class="inp-label">I am a</span>

          </div>

          <div class="role-options">



            <!-- Admin -->

            <label class="role-option">

              <input type="radio" name="user_role" value="admin">

              <div class="role-card">

                <div class="role-ico">

                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>

                  </svg>

                </div>

                <span class="role-name">Admin</span>

              </div>

              <div class="role-tick">

                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>

              </div>

            </label>



            <!-- Hostel Owner -->

            <label class="role-option">

              <input type="radio" name="user_role" value="hostel_owner">

              <div class="role-card">

                <div class="role-ico">

                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>

                  </svg>

                </div>

                <span class="role-name">Hostel Owner</span>

              </div>

              <div class="role-tick">

                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
            </label>

            <!-- Student -->

            <label class="role-option">
              <input type="radio" name="user_role" value="student">
              <div class="role-card">
                <div class="role-ico">

                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                  </svg>
                </div>
                <span class="role-name">Student</span>
              </div>
              <div class="role-tick">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
            </label>
          </div>
          <div class="role-err" id="roleErr">Please select your role to continue.</div>
        </div>

        <!-- Remember me -->
        <!-- CTA -->

        <button type="submit" name="login_btn" class="submit-btn" id="loginBtn">
          Sign In to HostelHub
          <span class="btn-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
          </span>
        </button>
      </form>

      <p class="signup-row">Don't have an account? <a href="register.php">Create one free</a></p>



    </div>

  </div>



  <!-- ══════════ RIGHT — Decorative ══════════ -->

  <div class="panel-right">

    <div class="orb orb-1"></div>

    <div class="orb orb-2"></div>

    <div class="orb orb-3"></div>

    <div class="dots"></div>



    <div class="right-content">

      <div class="welcome-ico">

        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>

        </svg>

      </div>



      <h2 class="right-title">Your <em>journey</em><br>continues here.</h2>

      <p class="right-desc">Access your bookings, track applications, and connect with your hostel community — all in one place.</p>



      <div class="stats-grid">

        <div class="stat-card">
          <span class="stat-num">500+</span>
          <span class="stat-desc">Happy Students</span>
        </div>

        <div class="stat-card">
          <span class="stat-num">50+</span>
          <span class="stat-desc">Verified Hostels</span>
        </div>

        <div class="stat-card">
          <span class="stat-num">10+</span>
          <span class="stat-desc">Cities Covered</span>
        </div>

        <div class="stat-card">
          <span class="stat-num">4.9★</span>
          <span class="stat-desc">Average Rating</span>
        </div>

      </div>
    </div>
  </div>

</div>







<script>

/* ── Eye toggle ── */

function toggleLoginPwd() {

  const inp = document.getElementById('log_password');
  const ico = document.getElementById('loginEyeIco');
  const show = inp.type === 'password';
  inp.type = show ? 'text' : 'password';

  ico.innerHTML = show
    ? `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`

    : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
}



/* ── Ripple ── */

document.getElementById('loginBtn').addEventListener('click', function(e) {

  if (this.disabled) return;

  const el = document.createElement('span');
  el.className = 'ripple-el';
  const rect = this.getBoundingClientRect();

  el.style.left = (e.clientX - rect.left - 5)+'px';
  el.style.top  = (e.clientY - rect.top  - 5)+'px';

  this.appendChild(el);
  el.addEventListener('animationend', () => el.remove());

});



/* ── Submit ── */

document.getElementById('loginForm').addEventListener('submit', function(e) {

  const email   = document.getElementById('log_email').value.trim();
  const pwd     = document.getElementById('log_password').value;
  const role    = document.querySelector('input[name="user_role"]:checked');
  const emailErr = document.getElementById('emailErr');
  const pwdErr   = document.getElementById('pwdErr');
  const roleErr  = document.getElementById('roleErr');
  let ok = true;

  // Email validation

  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {

    emailErr.classList.add('show');

    document.getElementById('log_email').style.borderColor = '#ef4444';
    document.getElementById('log_email').style.boxShadow  = '0 0 0 3px rgba(239,68,68,.15)';
    ok = false;

  } else {

    emailErr.classList.remove('show');
    document.getElementById('log_email').style.borderColor = '';
    document.getElementById('log_email').style.boxShadow  = '';
  }

  // Password validation

  if (pwd.length < 6) {

    pwdErr.classList.add('show');
    document.getElementById('log_password').style.borderColor = '#ef4444';
    document.getElementById('log_password').style.boxShadow  = '0 0 0 3px rgba(239,68,68,.15)';

    ok = false;

  } else {

    pwdErr.classList.remove('show');

    document.getElementById('log_password').style.borderColor = '';

    document.getElementById('log_password').style.boxShadow  = '';

  }



  // Role validation

  if (!role) {

    roleErr.classList.add('show');

    ok = false;

  } else {

    roleErr.classList.remove('show');

  }



  if (!ok) {

    e.preventDefault();

    const fb = document.querySelector('.form-box');

    fb.style.animation = 'none';
    void fb.offsetWidth;
    fb.style.animation = 'shake .4s ease';

  }

  // If ok === true, form submits normally to PHP

});



/* ── Clear errors on input ── */

['log_email','log_password'].forEach(id => {

  document.getElementById(id).addEventListener('input', function() {

    this.style.borderColor = '';

    this.style.boxShadow   = '';

  });

});



/* ── Clear role error on selection ── */

document.querySelectorAll('input[name="user_role"]').forEach(radio => {

  radio.addEventListener('change', () => {

    document.getElementById('roleErr').classList.remove('show');

  });

});



/* ── Extra keyframes ── */

const st = document.createElement('style');

st.textContent = `

  @keyframes shake {
    0%,100%{transform:translateX(0)}
    20%,60%{transform:translateX(-8px)}
    40%,80%{transform:translateX(8px)}
  }

  @keyframes spin { to{transform:rotate(360deg)} }

`;

document.head.appendChild(st);

</script>

</body>

</html>