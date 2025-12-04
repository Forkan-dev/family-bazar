<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import ThemeToggle from '@/components/ThemeToggle.vue'
import { Bell, ChevronDown, Mail, Search } from 'lucide-vue-next'

const logout = () => {
    console.log('Logging out...')
    // Add your logout logic here
}
</script>
<template>
    <header class="sticky top-0 z-40 flex h-16 items-center justify-between border-b bg-background px-4">
        <div class="flex-1" />

        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="relative max-w-sm">
                <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                <Input type="search" placeholder="Search..." class="pl-8 w-64" />
            </div>

            <!-- Theme Toggle -->
            <ThemeToggle />

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <Button variant="ghost" size="sm" class="relative">
                    <Bell class="h-4 w-4" />
                    <Badge variant="destructive"
                        class="absolute -top-1 -right-1 h-5 w-5 p-0 text-xs flex items-center justify-center">
                        3
                    </Badge>
                </Button>

                <Button variant="ghost" size="sm">
                    <Mail class="h-4 w-4" />
                </Button>
            </div>

            <!-- User Menu -->
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="ghost" class="flex items-center space-x-2">
                        <Avatar class="h-8 w-8">
                            <AvatarImage src="https://randomuser.me/api/portraits/men/85.jpg" alt="User avatar" />
                            <AvatarFallback>JD</AvatarFallback>
                        </Avatar>
                        <ChevronDown class="h-4 w-4" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-56">
                    <DropdownMenuItem>
                        Profile
                    </DropdownMenuItem>
                    <DropdownMenuItem>
                        Settings
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="logout">
                        Logout
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </header>
</template>
