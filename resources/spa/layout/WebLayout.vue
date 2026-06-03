<script setup>
// Web template — simple public/marketing chrome for unauthenticated pages
// that are neither the auth flow nor the logged-in portal. Intentionally
// minimal for now (header + content + footer); real styling comes later.
import { useRouter } from 'vue-router';
import Button from 'primevue/button';

const router = useRouter();
</script>

<template>
    <div class="web-layout">
        <header class="web-header">
            <router-link :to="{ name: 'web-home' }" class="web-brand">DOT Portal</router-link>
            <nav class="web-nav">
                <Button label="Sign in" size="small" @click="router.push({ name: 'login' })" />
            </nav>
        </header>

        <main class="web-main">
            <router-view />
        </main>

        <footer class="web-footer">
            <span class="web-copy">© DOT Portal</span>
            <nav class="web-foot-links">
                <a href="#">Terms</a>
                <a href="#">Plans</a>
                <a href="#">Contact Us</a>
            </nav>
        </footer>
    </div>
</template>

<style scoped>
.web-layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background: var(--p-surface-0);
    color: var(--p-text-color);
}

.web-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 2rem;
    border-bottom: 1px solid var(--p-surface-200);
}
.web-brand {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--p-primary-color);
    text-decoration: none;
}
.web-nav {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.web-main {
    flex: 1 1 auto;
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    padding: 2.5rem 2rem;
}

.web-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--p-surface-200);
    font-size: 0.875rem;
    color: var(--p-text-muted-color);
}
.web-foot-links {
    display: flex;
    gap: 1.5rem;
}
.web-foot-links a {
    color: var(--p-text-muted-color);
    text-decoration: none;
}
.web-foot-links a:hover {
    color: var(--p-primary-color);
}

@media (max-width: 640px) {
    .web-header,
    .web-main,
    .web-footer {
        padding-left: 1.25rem;
        padding-right: 1.25rem;
    }
}
</style>
