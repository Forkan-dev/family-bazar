<script setup lang="ts">
import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { register } from '@/routes';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { useDarkMode } from '@/composables/useDarkMode';

const { isDark } = useDarkMode();

const logoSrc = () => isDark.value
    ? '/images/logo/login_page_logo_dark.png'
    : '/images/logo/login_page_logo_light.png';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <div class="relative min-h-screen w-full bg-background flex items-center justify-center p-4 overflow-hidden">

        <Head title="Log in - Elaach" />

        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Background Image -->
            <img src="/images/logo/login_page_doodles.jpg" alt="Background Pattern"
                class="absolute inset-0 w-full h-full object-cover opacity-20" />
            <!-- Overlay to blend with background -->
            <div class="absolute inset-0 bg-background/40 dark:bg-background/60"></div>
        </div>
        <!-- Login Card -->
        <div class="w-full max-w-sm relative z-10">
            <div class="bg-card rounded-xl border shadow-sm">
                <!-- Header -->
                <div class="text-center pt-6 pb-6 px-8 border-b">
                    <!-- Logo -->
                    <div class="mb-0 flex justify-center">
                        <div class="flex items-center justify-center w-40 h-40 rounded-full bg-white shadow-sm border-2"
                            style="border-color: #63493f;">
                            <img :src="logoSrc()" alt="Elaach Logo" class="h-36 w-auto object-contain" />
                        </div>
                    </div>

                    <!-- <h1 class="text-xl font-semibold mb-1">
                        Elaach
                    </h1> -->
                    <!--  <p class="text-sm text-muted-foreground">
                        Grocery Admin Dashboard
                    </p> -->
                </div>

                <!-- Form -->
                <div class="p-8">
                    <!-- Status Message -->
                    <div v-if="status"
                        class="mb-6 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-300">
                        {{ status }}
                    </div>

                    <Form v-bind="AuthenticatedSessionController.store.form()" :reset-on-success="['password']"
                        v-slot="{ errors, processing }" class="space-y-4">
                        <!-- Email Field -->
                        <div class="space-y-2">
                            <Label for="email" class="text-sm font-medium">
                                Email
                            </Label>
                            <Input id="email" type="email" name="email" required autofocus :tabindex="1"
                                autocomplete="email" placeholder="admin@elaach.com" />
                            <InputError v-if="errors.email" :message="errors.email"
                                class="text-xs text-red-500 dark:text-red-400" />
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <Label for="password" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Password
                                </Label>
                                <TextLink v-if="canResetPassword" :href="request()"
                                    class="text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium transition-colors"
                                    :tabindex="5">
                                    Forgot?
                                </TextLink>
                            </div>
                            <Input id="password" type="password" name="password" required :tabindex="2"
                                autocomplete="current-password" placeholder="••••••••" />
                            <InputError v-if="errors.password" :message="errors.password" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center space-x-2.5 pt-1">
                            <Checkbox id="remember" name="remember" :tabindex="3" />
                            <Label for="remember" class="text-sm font-medium cursor-pointer select-none">
                                Remember me
                            </Label>
                        </div>

                        <!-- Submit Button -->
                        <Button type="submit" class="w-full" :tabindex="4" :disabled="processing"
                            data-test="login-button">
                            <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin mr-2" />
                            <span>{{ processing ? 'Signing in...' : 'Sign In' }}</span>
                        </Button>
                    </Form>

                    <!-- Sign Up Link -->
                    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                        Need an account?
                        <TextLink :href="register()"
                            class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium transition-colors"
                            :tabindex="5">
                            Sign up
                        </TextLink>
                    </div>
                </div>

                <!-- Footer -->
                <div <div class="bg-muted border-t px-8 py-4 text-center">
                    <p class="text-xs text-muted-foreground">
                        🔒 Secure • Encrypted • GDPR Compliant
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
