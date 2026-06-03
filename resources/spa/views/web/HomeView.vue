<script setup>
// Placeholder public landing page. The web template is wired and reachable;
// real content/styling lands in a later pass.
</script>

<template>
    <section class="web-home">
        <h1>Welcome to DOT Portal</h1>
        <p>This is the public web template — a simple skeleton we’ll build out later.</p>
        <p class="web-home__hint">
            Authenticated pages use the dashboard shell; sign-in/registration use the auth template.
        </p>
    </section>
</template>

<style scoped>
.web-home {
    max-width: 640px;
}
.web-home h1 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 1rem;
}
.web-home p {
    font-size: 1.05rem;
    line-height: 1.6;
    color: var(--p-text-color);
    margin: 0 0 0.75rem;
}
.web-home__hint {
    color: var(--p-text-muted-color);
    font-size: 0.95rem;
}
</style>
