<template>
  <slot v-if="hasPermission" />
  <slot v-else name="fallback" />
</template>

<script setup>
import { computed } from 'vue';
import { useRole } from '../composables/useRole';

const props = defineProps({
  permission: {
    type: String,
    required: true
  }
});

const { can } = useRole();

const hasPermission = computed(() => can(props.permission));
</script>
