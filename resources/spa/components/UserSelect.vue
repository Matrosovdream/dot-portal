<script setup>
// Type-to-search owner picker for the Operations listings (admin/manager only).
// Searches users via /admin/user-options and emits the selected user id.
import { ref, watch } from 'vue';
import AutoComplete from 'primevue/autocomplete';
import { usersApi } from '@/api';

const props = defineProps({
    modelValue: { type: [Number, String], default: null },
    placeholder: { type: String, default: 'Filter by user' },
});
const emit = defineEmits(['update:modelValue']);

const model = ref(null); // selected suggestion object while picking, or the typed query string
const suggestions = ref([]);

async function search(event) {
    const q = (event.query || '').trim();
    try {
        const body = await usersApi.search(q);
        suggestions.value = (body.data ?? []).map((u) => ({
            ...u,
            label: u.email ? `${u.fullname} (${u.email})` : u.fullname,
        }));
    } catch {
        suggestions.value = [];
    }
}

function onSelect(event) {
    emit('update:modelValue', event.value.id);
}

// Emit null when the input is emptied (typed value is a string; cleared is '' / null).
watch(model, (val) => {
    if ((val == null || val === '') && props.modelValue != null) {
        emit('update:modelValue', null);
    }
});

// Let a parent reset the picker (e.g. clear-all-filters).
watch(
    () => props.modelValue,
    (v) => {
        if (v == null) model.value = null;
    },
);
</script>

<template>
    <AutoComplete
        v-model="model"
        :suggestions="suggestions"
        optionLabel="label"
        :placeholder="placeholder"
        dropdown
        :delay="300"
        :minLength="0"
        @complete="search"
        @item-select="onSelect"
        @clear="$emit('update:modelValue', null)"
    />
</template>
