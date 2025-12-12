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

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <div class="relative min-h-screen w-full bg-background flex items-center justify-center p-4 overflow-hidden">

        <Head title="Log in - Elaach" />

        <!-- Faint Grocery Icons Background Texture -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Oil Bottle - Top Left -->
            <div class="absolute -top-16 -left-16 w-40 h-40 text-muted-foreground opacity-[0.05]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M12 2v4M10 6h4v2h-4M10 8v10c0 1.1.9 2 2 2s2-.9 2-2V8M9 6h6l1-2H8l1 2M9 14h6M8 22h8" />
                </svg>
            </div>

            <!-- Milk Bottle - Top Right -->
            <div class="absolute -top-12 right-0 w-48 h-48 text-muted-foreground opacity-[0.05]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path
                        d="M8 2h8v2H8zM7 4h10v14c0 1.1-.9 2-2 2h-6c-1.1 0-2-.9-2-2V4zM10 6v12M14 6v12M9 10h6M9 14h6" />
                </svg>
            </div>

            <!-- Carrot - Bottom Left -->
            <div class="absolute bottom-0 -left-20 w-44 h-44 text-muted-foreground opacity-[0.05]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M8 4l6 12H8c-1 0-2 1-2 2v2h10v-2c0-1-1-2-2-2h-6l6-12M14 2l2-1 1 2" />
                </svg>
            </div>

            <!-- Seed - Bottom Right -->
            <div class="absolute bottom-12 right-4 w-32 h-32 text-muted-foreground opacity-[0.05]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M12 2v12M6 8c-2-1-4-1-4 2s2 5 4 6M18 8c2-1 4-1 4 2s-2 5-4 6" />
                </svg>
            </div>

            <!-- Turnip - Top Center -->
            <div class="absolute top-20 left-1/3 w-36 h-36 text-muted-foreground opacity-[0.05]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M10 6c-2 0-3 2-3 4 0 3 2 7 5 9s5 0 5-3c0-2-1-4-3-4M11 4l-1-2M13 4l1-2M12 4l1-2" />
                </svg>
            </div>
        </div>
        <!-- Login Card -->
        <div class="w-full max-w-sm relative z-10">
            <div class="bg-card rounded-xl border shadow-sm">
                <!-- Header -->
                <div class="text-center pt-10 pb-8 px-8 border-b">
                    <!-- Logo -->
                    <div class="mb-6 flex justify-center">
                        <img src="/images/logo/login_page_logo_light.png" alt="Elaach Logo"
                            class="h-24 w-auto object-contain dark:hidden" />
                        <img src="/images/logo/login_page_logo_dark.png" alt="Elaach Logo"
                            class="h-24 w-auto object-contain hidden dark:block" />
                    </div>

                    <h1 class="text-xl font-semibold mb-1">
                        Elaach
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        Grocery Admin Dashboard
                    </p>
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
