<script setup lang="ts">
import { computed } from 'vue'

</script>

<template>
    <v-btn size="sm" class="text-none text-subtitle-1 cstm-btn" color="#5865f2" variant="flat">
        <slot />
    </v-btn>
</template>

<style scoped>
.cstm-btn {
    padding: 4px 3px;
    padding-right: 10px;
    margin: 2px 2px;
    font-size: 14px !important;
}
</style>
