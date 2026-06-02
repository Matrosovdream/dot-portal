import { computed, reactive } from 'vue';

const STORAGE_KEY = 'dotportal.layout';

const persisted = readPersisted();

const layoutConfig = reactive({
    darkTheme: persisted.darkTheme ?? false,
    menuMode: 'static', // 'static' | 'overlay'
});

const layoutState = reactive({
    staticMenuDesktopInactive: persisted.staticMenuDesktopInactive ?? false,
    overlayMenuActive: false,
    staticMenuMobileActive: false,
    menuHoverActive: false,
    activeMenuItem: null,
});

function readPersisted() {
    try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY)) ?? {};
    } catch {
        return {};
    }
}

function persist() {
    try {
        localStorage.setItem(
            STORAGE_KEY,
            JSON.stringify({
                darkTheme: layoutConfig.darkTheme,
                staticMenuDesktopInactive: layoutState.staticMenuDesktopInactive,
            }),
        );
    } catch {
        /* storage disabled — non-fatal */
    }
}

function applyDarkClass() {
    document.documentElement.classList.toggle('app-dark', layoutConfig.darkTheme);
}

// Apply persisted dark mode as early as the module is imported.
applyDarkClass();

export function useLayout() {
    const setActiveMenuItem = (item) => {
        layoutState.activeMenuItem = item?.value ?? item;
    };

    const toggleDarkMode = () => {
        const apply = () => {
            layoutConfig.darkTheme = !layoutConfig.darkTheme;
            applyDarkClass();
            persist();
        };
        if (!document.startViewTransition) {
            apply();
            return;
        }
        document.startViewTransition(apply);
    };

    const toggleMenu = () => {
        if (layoutConfig.menuMode === 'overlay') {
            layoutState.overlayMenuActive = !layoutState.overlayMenuActive;
        }
        if (window.innerWidth > 991) {
            layoutState.staticMenuDesktopInactive = !layoutState.staticMenuDesktopInactive;
            persist();
        } else {
            layoutState.staticMenuMobileActive = !layoutState.staticMenuMobileActive;
        }
    };

    const closeMobileMenu = () => {
        layoutState.overlayMenuActive = false;
        layoutState.staticMenuMobileActive = false;
    };

    const isSidebarActive = computed(
        () => layoutState.overlayMenuActive || layoutState.staticMenuMobileActive,
    );

    const isDarkTheme = computed(() => layoutConfig.darkTheme);

    return {
        layoutConfig,
        layoutState,
        toggleMenu,
        toggleDarkMode,
        closeMobileMenu,
        isSidebarActive,
        isDarkTheme,
        setActiveMenuItem,
    };
}
