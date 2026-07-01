/* =========================================================
   Rainy Mochi — Tema Biru Muda & Broken White (senada awan)
   ========================================================= */
:root{
  --sky-blue: #AEE1F9;
  --sky-blue-deep: #7EC1E8;
  --sky-blue-soft: #DCF0FC;
  --broken-white: #F8F6F1;
  --broken-white-deep: #F1EEE6;
  --cloud-shadow: #C9DEEA;
  --text-dark: #3C4A54;
  --text-muted: #7A8A94;
  --accent-pink: #F7C8D9;
}

body{
  background: linear-gradient(180deg, var(--sky-blue-soft) 0%, var(--broken-white) 45%, var(--broken-white) 100%);
  color: var(--text-dark);
  font-family: 'Segoe UI', Roboto, Arial, sans-serif;
  min-height: 100vh;
}

/* ===== Navbar ===== */
.navbar-mochi{
  background: linear-gradient(90deg, var(--sky-blue-deep), var(--sky-blue));
  box-shadow: 0 2px 12px rgba(126,193,232,0.35);
}
.navbar-mochi .navbar-brand{
  font-weight: 700;
  color: var(--text-dark) !important;
  letter-spacing: .5px;
}
.navbar-mochi .nav-link{
  color: var(--text-dark) !important;
  font-weight: 500;
}
.navbar-mochi .nav-link:hover{
  color: #fff !important;
}

/* ===== Cards / Panels ===== */
.card-mochi{
  background: var(--broken-white);
  border: 1px solid var(--cloud-shadow);
  border-radius: 18px;
  box-shadow: 0 8px 24px rgba(126,193,232,0.18);
}
.card-mochi .card-header{
  background: linear-gradient(90deg, var(--sky-blue), var(--sky-blue-soft));
  border-bottom: 1px solid var(--cloud-shadow);
  border-radius: 18px 18px 0 0 !important;
  font-weight: 600;
  color: var(--text-dark);
}

/* ===== Login Page ===== */
.login-wrapper{
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: radial-gradient(circle at 20% 20%, var(--sky-blue-soft), var(--broken-white) 60%);
}
.login-card{
  width: 100%;
  max-width: 400px;
  background: var(--broken-white);
  border-radius: 22px;
  border: 1px solid var(--cloud-shadow);
  box-shadow: 0 14px 40px rgba(126,193,232,0.30);
  padding: 2.4rem 2rem;
}
.login-card h3{
  color: var(--text-dark);
  font-weight: 700;
}
.login-logo{
  width: 64px; height: 64px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--sky-blue), var(--accent-pink));
  display:flex; align-items:center; justify-content:center;
  margin: 0 auto 1rem auto;
  font-size: 28px;
}

/* ===== Buttons ===== */
.btn-mochi{
  background: linear-gradient(90deg, var(--sky-blue-deep), var(--sky-blue));
  border: none;
  color: #fff;
  font-weight: 600;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(126,193,232,0.35);
}
.btn-mochi:hover{
  background: linear-gradient(90deg, var(--sky-blue), var(--sky-blue-deep));
  color: #fff;
}
.btn-outline-mochi{
  border: 1.5px solid var(--sky-blue-deep);
  color: var(--sky-blue-deep);
  background: #fff;
  font-weight: 600;
  border-radius: 10px;
}
.btn-outline-mochi:hover{
  background: var(--sky-blue-soft);
  color: var(--text-dark);
}

/* ===== Table ===== */
.table-mochi thead{
  background: var(--sky-blue);
  color: var(--text-dark);
}
.table-mochi tbody tr:hover{
  background: var(--sky-blue-soft);
}
.table-mochi img.produk-thumb{
  width: 56px; height: 56px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid var(--cloud-shadow);
}

/* ===== Badges ===== */
.badge-mochi{
  background: var(--accent-pink);
  color: var(--text-dark);
  font-weight: 600;
  border-radius: 8px;
  padding: .4em .7em;
}

/* ===== Misc ===== */
.section-title{
  color: var(--text-dark);
  font-weight: 700;
  border-left: 5px solid var(--sky-blue-deep);
  padding-left: 12px;
  margin-bottom: 1.2rem;
}
.footer-mochi{
  color: var(--text-muted);
  text-align: center;
  padding: 1.5rem 0;
  font-size: .9rem;
}
.form-control:focus, .form-select:focus{
  border-color: var(--sky-blue-deep);
  box-shadow: 0 0 0 .2rem rgba(126,193,232,0.25);
}
.preview-img{
  max-width: 160px;
  border-radius: 12px;
  border: 1px solid var(--cloud-shadow);
  margin-top: 8px;
}

/* ===== Print (Report PDF) ===== */
@media print{
  .no-print{ display:none !important; }
  body{ background: #fff !important; }
  .card-mochi{ box-shadow:none; border:none; }
}
