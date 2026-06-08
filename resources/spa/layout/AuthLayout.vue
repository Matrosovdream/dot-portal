<script setup>
// Auth template — split-screen chrome recreated from the legacy Metronic
// `resources/views/layouts/guest.blade.php`: branding/visual panel on the
// LEFT (over a full-bleed background photo), white form card on the RIGHT.
// Auth-flow views render only their heading + form into <router-view/>; the
// shared form helper classes (.auth-head/.auth-form/.field/.err/.auth-links)
// are defined once below so every child view stays consistent.
import Toast from 'primevue/toast';

// Served straight from public/ (not bundled by Vite) — same assets the old
// Metronic auth pages used.
const bg = '/assets/admin/media/auth/bg10.jpeg';
const logo = '/assets/admin/media/auth/agency.png';
</script>

<template>
    <div class="auth-layout" :style="{ backgroundImage: `url(${bg})` }">
        <!-- LEFT: branding / visual panel -->
        <aside class="auth-aside">
            <div class="auth-aside__inner">
                <img class="auth-logo" :src="logo" alt="DOT Portal" />
                <h1 class="auth-headline">Welcome to DOT Portal</h1>
                <p class="auth-lead">
                    Manage your business in one place — sign in to get started.
                </p>
            </div>
        </aside>

        <!-- RIGHT: form card -->
        <main class="auth-main">
            <div class="auth-card">
                <div class="auth-card__body">
                    <router-view />
                </div>
                <footer class="auth-foot">
                    <a href="#">Terms</a>
                    <a href="#">Plans</a>
                    <a href="#">Contact Us</a>
                </footer>
            </div>
        </main>

        <Toast position="top-right" />
    </div>
</template>

<style scoped>
/* ---------- Layout chrome ---------- */
.auth-layout {
    display: flex;
    min-height: 100vh;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
}

/* LEFT branding panel — sits over the background photo. */
.auth-aside {
    flex: 1 1 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2.5rem;
}
.auth-aside__inner {
    max-width: 480px;
    text-align: center;
}
.auth-logo {
    width: 100%;
    max-width: 300px;
    height: auto;
    margin: 0 auto 2.5rem;
    display: block;
}
.auth-headline {
    font-size: 2rem;
    line-height: 1.2;
    font-weight: 700;
    color: var(--p-surface-800);
    margin: 0 0 1rem;
}
.auth-lead {
    font-size: 1rem;
    line-height: 1.6;
    color: var(--p-surface-600);
    margin: 0;
}

/* RIGHT form column. */
.auth-main {
    flex: 1 1 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
}
.auth-card {
    width: 100%;
    max-width: 560px;
    min-height: 0;
    background: var(--p-surface-0);
    border-radius: 1rem;
    box-shadow: 0 10px 40px rgba(15, 23, 42, 0.12);
    padding: 3rem 3.5rem 2rem;
    display: flex;
    flex-direction: column;
}
.auth-card__body {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    max-width: 440px;
    width: 100%;
    margin: 0 auto;
}
.auth-foot {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    padding-top: 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
}
.auth-foot a {
    color: var(--p-primary-color);
    text-decoration: none;
}
.auth-foot a:hover {
    text-decoration: underline;
}

/* Stack on tablet / mobile. */
@media (max-width: 991px) {
    .auth-layout {
        flex-direction: column;
        background-attachment: scroll;
    }
    .auth-aside {
        padding: 2.5rem 1.5rem 0;
    }
    .auth-logo {
        max-width: 180px;
        margin-bottom: 1.25rem;
    }
    .auth-headline {
        font-size: 1.5rem;
    }
    .auth-lead {
        display: none;
    }
    .auth-main {
        padding: 1.5rem;
    }
}
</style>

<!--
  Shared auth FORM styles — global (not scoped) so they reach the child views
  rendered inside <router-view/>, but namespaced under .auth-layout so they
  never leak into the rest of the app.
-->
<style>
.auth-layout .auth-head {
    text-align: center;
    margin-bottom: 2rem;
}
.auth-layout .auth-head h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--p-text-color);
    margin: 0 0 0.5rem;
}
.auth-layout .auth-sub {
    color: var(--p-text-muted-color);
    font-size: 0.95rem;
    margin: 0;
}
.auth-layout .auth-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.auth-layout .field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.auth-layout .field label {
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--p-text-color);
}
/* Make PrimeVue inputs fill the field width. */
.auth-layout .field .p-inputtext,
.auth-layout .field .p-password,
.auth-layout .field .p-password input,
.auth-layout .field .p-select {
    width: 100%;
}
.auth-layout .err {
    color: var(--p-red-500, #e24c4c);
    font-size: 0.8rem;
}
.auth-layout .auth-links {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
.auth-layout .auth-links a {
    color: var(--p-primary-color);
    text-decoration: none;
}
.auth-layout .auth-links a:hover {
    text-decoration: underline;
}
.auth-layout .text-muted {
    color: var(--p-text-muted-color);
}
</style>
