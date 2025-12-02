import { useStorage } from '@vueuse/core';
import { computed } from 'vue';

type Theme = 'dark' | 'light' | 'system';

export function useDarkMode() {
    const theme = useStorage<Theme>('theme', 'system');

    const systemPrefersDark = computed(() => {
        if (typeof window === 'undefined') return false;
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    });

    const isDark = computed(() => {
        if (theme.value === 'system') {
            return systemPrefersDark.value;
        }
        return theme.value === 'dark';
    });

    const toggleTheme = () => {
        theme.value = isDark.value ? 'light' : 'dark';
        updateDocumentClass();
    };

    const setTheme = (newTheme: Theme) => {
        theme.value = newTheme;
        updateDocumentClass();
    };

    const updateDocumentClass = () => {
        if (typeof document === 'undefined') return;

        const root = document.documentElement;
        root.classList.remove('light', 'dark');

        if (theme.value === 'system') {
            root.classList.add(systemPrefersDark.value ? 'dark' : 'light');
        } else {
            root.classList.add(theme.value);
        }
    };

    // Initialize on first load
    if (typeof window !== 'undefined') {
        updateDocumentClass();

        // Listen for system theme changes
        window
            .matchMedia('(prefers-color-scheme: dark)')
            .addEventListener('change', () => {
                if (theme.value === 'system') {
                    updateDocumentClass();
                }
            });
    }

    return {
        theme,
        isDark,
        toggleTheme,
        setTheme,
        systemPrefersDark,
    };
}
