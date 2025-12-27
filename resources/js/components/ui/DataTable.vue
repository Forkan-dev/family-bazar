<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { cn } from '@/lib/utils'
import { Link } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight, Plus, Search, Eye, Edit, Trash2 } from 'lucide-vue-next'
import { computed, ref } from 'vue'

interface Column {
    key: string
    label: string
    sortable?: boolean
    searchable?: boolean
    class?: string
    render?: (item: any) => string
}

interface Action {
    label: string
    icon?: any
    variant?: 'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link'
    class?: string
    show?: (item: any) => boolean
    action: (item: any) => void
}

interface Props {
    title: string
    description?: string
    columns: Column[]
    data: any[]
    actions?: Action[]
    createUrl?: string
    createText?: string
    searchable?: boolean
    perPage?: number
    currentPage?: number
    total?: number
    loading?: boolean
    class?: string
}

const props = withDefaults(defineProps<Props>(), {
    createText: 'Add New',
    searchable: true,
    perPage: 10,
    currentPage: 1,
    total: 0,
    loading: false,
    data: () => [],
})

const redirectUrl = ( id: number) => {
    emit('redirectUrl', id);
}

const emit = defineEmits<{
    search: [query: string]
    paginate: [page: number]
    sort: [column: string, direction: 'asc' | 'desc'],
    redirectUrl: [id: number]
}>()

const searchQuery = ref('')
const sortColumn = ref('')
const sortDirection = ref<'asc' | 'desc'>('asc')

const filteredData = computed(() => {
    if (!searchQuery.value) return props.data

    return props.data.filter(item =>
        props.columns.some(column => {
            if (!column.searchable) return false
            const value = item[column.key]
            return value?.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
        })
    )
})

const totalPages = computed(() => Math.ceil(props.total / props.perPage))

const paginationNumbers = computed(() => {
    const pages = []
    const maxVisiblePages = 5

    if (totalPages.value <= maxVisiblePages) {
        // Show all pages if total pages is small
        for (let i = 1; i <= totalPages.value; i++) {
            pages.push(i)
        }
    } else {
        // Smart pagination with ellipsis logic
        const startPage = Math.max(1, props.currentPage - 2)
        const endPage = Math.min(totalPages.value, props.currentPage + 2)

        // Always show first page
        if (startPage > 1) {
            pages.push(1)
            if (startPage > 2) pages.push('...')
        }

        // Show pages around current page
        for (let i = startPage; i <= endPage; i++) {
            pages.push(i)
        }

        // Always show last page
        if (endPage < totalPages.value) {
            if (endPage < totalPages.value - 1) pages.push('...')
            pages.push(totalPages.value)
        }
    }

    return pages
})

const handleSearch = (query: string) => {
    searchQuery.value = query
    emit('search', query)
}

const handleSort = (column: string) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortColumn.value = column
        sortDirection.value = 'asc'
    }
    emit('sort', column, sortDirection.value)
}

const handlePageChange = (page: number) => {
    emit('paginate', page)
}

const getActionClass = (action: Action) => {
    const baseClasses = 'h-8 w-8 p-0'
    return cn(baseClasses, action.class)
}

const shouldShowAction = (action: Action, item: any) => {
    return action.show ? action.show(item) : true
}
</script>

<template>
    <div :class="cn('space-y-6', props.class)">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">{{ title }}</h1>
                <p v-if="description" class="text-muted-foreground mt-1">{{ description }}</p>
            </div>
            <Link v-if="createUrl" :href="createUrl">
            <Button>
                <Plus class="h-4 w-4 mr-2" />
                {{ createText }}
            </Button>
            </Link>
        </div>

        <!-- Search and Filters -->
        <div v-if="searchable" class="flex items-center space-x-4">
            <div class="relative flex-1 max-w-sm">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input v-model="searchQuery" placeholder="Search..." class="pl-10" @input="handleSearch(searchQuery)" />
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-card border rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th v-for="column in columns" :key="column.key"
                                :class="cn('px-6 py-2 text-left text-xs text-black  uppercase font-semibold', column.class)">
                                <button v-if="column.sortable" @click="handleSort(column.key)"
                                    class="flex items-center space-x-1 hover:text-foreground uppercase">
                                    <span>{{ column.label }}</span>
                                    <ChevronLeft class="h-3 w-3 transition-transform" :class="{
                                        'rotate-90': sortColumn === column.key && sortDirection === 'asc',
                                        '-rotate-90': sortColumn === column.key && sortDirection === 'desc'
                                    }" />
                                </button>
                                <span v-else>{{ column.label }}</span>
                            </th>
                            <th v-if="actions && actions.length > 0"
                                class="px-6 py-2 text-right text-sm uppercase font-semibold text-black">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading" class="border-b">
                            <td :colspan="columns.length + (actions?.length ? 1 : 0)"
                                class="px-6 py-8 text-center text-muted-foreground">
                                Loading...
                            </td>
                        </tr>
                        <tr v-else-if="!data || data.length === 0" class="border-b">
                            <td :colspan="columns.length + (actions?.length ? 1 : 0)"
                                class="px-6 py-8 text-center text-muted-foreground">
                                No data available
                            </td>
                        </tr>
                        <tr v-else v-for="(item, index) in (data || [])" :key="index"
                            class="border-b hover:bg-muted/50">
                            <td v-for="column in columns" :key="column.key"
                                :class="cn('px-6 py-2 text-sm cursor-pointer', column.class)" @click="redirectUrl(item.id)">
                                <span v-if="column.render" v-html="column.render(item)"></span>
                                <span v-else>{{ item[column.key] }}</span>
                            </td>
                            <td v-if="actions && actions.length > 0" class="px-6 py-2 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <Button v-for="action in actions" v-show="shouldShowAction(action, item)"
                                        :key="action.label" :variant="action.variant || 'ghost'"
                                        :class="getActionClass(action)" @click="action.action(item)">
                                        <component v-if="action.icon" :is="action.icon" class="h-4 w-4" />
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex items-center justify-between px-6 py-4 border-t">
                <div class="text-sm text-muted-foreground">
                    Showing {{ ((currentPage - 1) * perPage) + 1 }} to {{ Math.min(currentPage * perPage, total) }} of
                    {{ total }} results
                </div>
                <div class="flex items-center space-x-1">
                    <Button variant="outline" size="sm" :disabled="currentPage <= 1"
                        @click="handlePageChange(currentPage - 1)">
                        <ChevronLeft class="h-4 w-4" />
                    </Button>

                    <template v-for="page in paginationNumbers" :key="page">
                        <Button v-if="page === '...'" variant="ghost" size="sm" disabled class="px-2">
                            ...
                        </Button>
                        <Button v-else :variant="page === currentPage ? 'default' : 'outline'" size="sm" class="px-3"
                            @click="handlePageChange(page as number)">
                            {{ page }}
                        </Button>
                    </template>

                    <Button variant="outline" size="sm" :disabled="currentPage >= totalPages"
                        @click="handlePageChange(currentPage + 1)">
                        <ChevronRight class="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
