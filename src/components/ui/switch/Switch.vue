<script setup lang="ts">
import { ref, type HTMLAttributes, computed } from 'vue'
import {
  SwitchRoot,
  type SwitchRootEmits,
  type SwitchRootProps,
  SwitchThumb,
  useForwardPropsEmits,
} from 'radix-vue'
import { cn } from '@/lib/utils'

const props = defineProps<SwitchRootProps & { class?: HTMLAttributes['class'] }>()

const emits = defineEmits<SwitchRootEmits>()

const isChecked = ref(props.checked || props.defaultChecked || false)  // Initialize state

const handleClick = () => {
  isChecked.value = !isChecked.value
  emits('update:checked', isChecked.value)
}

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwarded = useForwardPropsEmits(delegatedProps, emits)
</script>


<template>
  <SwitchRoot
    v-bind="forwarded"
    :class="cn(
      'peer inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50',
      { 'bg-gray-400': !forwarded.checked, 'bg-orange-500': forwarded.checked },  // Dynamic background color
      props.class,
    )"
    @click="forwarded.checked = !forwarded.checked" 
  >
    <SwitchThumb
      :class="cn(
        'pointer-events-none block h-5 w-5 rounded-full bg-white shadow-lg ring-0 transition-transform',  // Always white thumb
        forwarded.checked ? 'bg-orange-500' : 'bg-white',  // Thumb color
        { 'translate-x-5': forwarded.checked, 'translate-x-0': !forwarded.checked }  // Position change on click
      )"
    />
  </SwitchRoot>
</template>
