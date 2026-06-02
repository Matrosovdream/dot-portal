import { definePreset } from '@primevue/themes';
import Aura from '@primevue/themes/aura';

/**
 * DOT Portal theme — PrimeVue Aura tuned to the Metronic palette the legacy
 * dashboard uses (primary #1B84FF, hover #056EE9, subtle #E9F3FF). Keeping the
 * accent identical means the SPA feels like a continuation of Metronic while
 * we run both in parallel before the cutover.
 */
export const DotPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50:  '#E9F3FF',
            100: '#d1e6ff',
            200: '#a4ceff',
            300: '#76b5ff',
            400: '#499dff',
            500: '#1B84FF',
            600: '#107EFF',
            700: '#056EE9',
            800: '#006AE6',
            900: '#0b3566',
            950: '#051a33',
        },
        colorScheme: {
            light: {
                primary: {
                    color: '{primary.500}',
                    contrastColor: '#ffffff',
                    hoverColor: '{primary.700}',
                    activeColor: '{primary.800}',
                },
                highlight: {
                    background: '{primary.50}',
                    focusBackground: '{primary.100}',
                    color: '{primary.700}',
                    focusColor: '{primary.800}',
                },
            },
            dark: {
                primary: {
                    color: '{primary.400}',
                    contrastColor: '{surface.900}',
                    hoverColor: '{primary.300}',
                    activeColor: '{primary.200}',
                },
                highlight: {
                    background: 'color-mix(in srgb, {primary.400}, transparent 84%)',
                    focusBackground: 'color-mix(in srgb, {primary.400}, transparent 76%)',
                    color: 'rgba(255,255,255,.87)',
                    focusColor: 'rgba(255,255,255,.87)',
                },
            },
        },
    },
});

export default DotPreset;
