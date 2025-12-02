<script setup lang="ts">
import { Button } from './Button.vue'
import type { ButtonVariants } from './index'
import { computed } from 'vue'

interface Props {
  variant?: ButtonVariants['variant'] | 'outlined' | 'flat' | 'tonal'
  size?: ButtonVariants['size'] | 'large'
  disabled?: boolean
  loading?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  size: 'default'
})

// Map Vuetify variants to shadcn variants
const mappedVariant = computed(() => {
  const variantMap: Record<string, ButtonVariants['variant']> = {
    'outlined': 'outline',
    'flat': 'default',
    'tonal': 'secondary'
  }
  return (variantMap[props.variant as string] || props.variant) as ButtonVariants['variant']
})

// Map Vuetify sizes to shadcn sizes
const mappedSize = computed(() => {
  const sizeMap: Record<string, ButtonVariants['size']> = {
    'large': 'lg'
  }
  return (sizeMap[props.size as string] || props.size) as ButtonVariants['size']
})
</script>

<template>
    <Button
      :variant="mappedVariant"
      :size="mappedSize"
      :disabled="disabled || loading"
      :class="props.class"
    >
        <slot />
    </Button>
</template>
