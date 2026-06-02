<script setup>
import { ref } from 'vue';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { authApi, fieldErrors, errorMessage } from '@/api';

const email = ref('');
const errors = ref({});
const submitting = ref(false);
const sent = ref(false);
const statusMsg = ref('');

async function submit() {
    errors.value = {};
    submitting.value = true;
    try {
        const res = await authApi.passwordEmail({ email: email.value });
        statusMsg.value = res?.message || 'If that email exists, a reset link has been sent.';
        sent.value = true;
    } catch (e) {
        if (e.response?.status === 422) errors.value = fieldErrors(e);
        else if (e.response?.status === 429) errors.value = { email: ['Too many attempts. Please try again shortly.'] };
        else errors.value = { email: [errorMessage(e, 'Could not send reset link')] };
    } finally {
        submitting.value = false;
    }
}

const err = (f) => errors.value[f]?.[0];
</script>

<template>
    <div class="auth-page">
        <Card class="auth-card">
            <template #title>
                <div class="auth-head">
                    <div class="auth-brand">DOT Portal</div>
                    <div class="auth-sub">Reset your password</div>
                </div>
            </template>
            <template #content>
                <Message v-if="sent" severity="success" :closable="false">{{ statusMsg }}</Message>

                <form v-else class="auth-form" @submit.prevent="submit">
                    <div class="field">
                        <label>Email</label>
                        <InputText v-model="email" type="email" :invalid="!!err('email')" autocomplete="email" />
                        <small v-if="err('email')" class="err">{{ err('email') }}</small>
                    </div>
                    <Button type="submit" label="Email reset link" :loading="submitting" />
                </form>

                <div class="auth-links">
                    <router-link :to="{ name: 'login' }">Back to sign in</router-link>
                </div>
            </template>
        </Card>
    </div>
</template>
