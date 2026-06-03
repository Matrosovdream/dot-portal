<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';

const router = useRouter();
const route = useRoute();
const auth = useAuthStore();

const form = ref({ email: '', password: '' });
const errors = ref({});
const submitting = ref(false);

async function submit() {
    errors.value = {};
    submitting.value = true;
    try {
        await auth.login(form.value);
        router.replace(route.query.next || '/dashboard');
    } catch (e) {
        errors.value = e.response?.data?.errors ?? { _: [e.response?.data?.message ?? 'Login failed'] };
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <div class="auth-head">
        <h1>Sign in</h1>
    </div>

    <form class="auth-form" @submit.prevent="submit">
        <div class="field">
            <label>Email</label>
            <InputText v-model="form.email" type="email" autocomplete="email" required />
            <small v-if="errors.email" class="err">{{ errors.email[0] }}</small>
        </div>
        <div class="field">
            <label>Password</label>
            <Password v-model="form.password" :feedback="false" toggle-mask required />
            <small v-if="errors.password" class="err">{{ errors.password[0] }}</small>
        </div>
        <small v-if="errors._" class="err">{{ errors._[0] }}</small>
        <Button type="submit" label="Sign in" :loading="submitting" />
        <div class="auth-links">
            <router-link :to="{ name: 'forgot-password' }">Forgot password?</router-link>
            <router-link :to="{ name: 'register' }">Create account</router-link>
        </div>
    </form>
</template>
