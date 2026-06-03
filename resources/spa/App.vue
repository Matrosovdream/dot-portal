<script setup>
// Root component. The matched route's parent decides the chrome — one of three
// page templates, each a layout component with its own nested <router-view/>:
//  - WebLayout   → public/marketing pages
//  - AuthLayout  → sign-in / registration / recovery (split-screen)
//  - AppLayout   → the authenticated portal shell (Sakai-style)
// Auth bootstrap + the global Toast/ConfirmDialog live in those layers.
</script>

<template>
    <router-view />
</template>
