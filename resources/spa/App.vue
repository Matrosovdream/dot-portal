<script setup>
// Root component. The active route decides the chrome:
//  - /login renders LoginView standalone
//  - everything else renders inside the AppLayout shell (Sakai-style).
// Auth bootstrap + the global Toast/ConfirmDialog live in those layers.
</script>

<template>
    <router-view />
</template>
