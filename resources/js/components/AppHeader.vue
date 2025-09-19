<script setup lang="ts">
const logout = () => {
    console.log('Logging out...')
    // Add your logout logic here
}
</script>
<template>
    <v-app-bar app flat class="border-b" height="64">
        <v-spacer></v-spacer>

        <!-- Search -->
        <v-text-field prepend-inner-icon="mdi-magnify" placeholder="Search..." variant="outlined" density="compact"
            hide-details style="max-width: 300px" class="me-4">
        </v-text-field>

        <!-- Action Buttons -->
        <v-btn icon="mdi-bell-outline" variant="text" class="me-2">
            <v-icon>mdi-bell-outline</v-icon>
            <v-badge color="error" content="3" offset-x="12" offset-y="12">
            </v-badge>
        </v-btn>

        <v-btn icon="mdi-email-outline" variant="text" class="me-2">
            <v-icon>mdi-email-outline</v-icon>
        </v-btn>

        <!-- User Menu -->
        <v-menu>
            <template v-slot:activator="{ props }">
                <v-btn v-bind="props" class="me-4" variant="text">
                    <v-avatar size="32">
                        <v-img src="https://randomuser.me/api/portraits/men/85.jpg"></v-img>
                    </v-avatar>
                    <v-icon class="ml-2">mdi-chevron-down</v-icon>
                </v-btn>
            </template>
            <v-list>
                <v-list-item>
                    <v-list-item-title>Profile</v-list-item-title>
                </v-list-item>
                <v-list-item>
                    <v-list-item-title>Settings</v-list-item-title>
                </v-list-item>
                <v-divider></v-divider>
                <v-list-item @click="logout">
                    <v-list-item-title>Logout</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-app-bar>
</template>