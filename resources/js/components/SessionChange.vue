<template>
  <select
    id="session-change"
    v-model="selectedSessionId"
    @change="changeSession"
  >
    <option
      v-for="sessionId in sessionIds"
      :key="sessionId"
      :value="sessionId"
    >
      Session {{ nameFromId(sessionId) }}
    </option>
  </select>
</template>

<script setup>
import { onBeforeMount, ref } from 'vue'
import sessionName from '@/session-name'

const props = defineProps({
  initialSessionId: {
    type: Number,
    required: false,
    default: null
  },
  sessionIds: {
    type: Array,
    required: true
  }
})

const selectedSessionId = ref(null)

onBeforeMount(() => {
  selectedSessionId.value = props.initialSessionId ?? props.sessionIds[props.sessionIds.length - 1]
})

function nameFromId(sessionId) {
  return sessionName(sessionId)
}

function changeSession() {
  const searchParams = new URLSearchParams(window.location.search)
  searchParams.set('session_id', selectedSessionId.value)
  window.location.search = searchParams.toString()
}
</script>

<style lang="scss" scoped>
#session-change {
  margin-bottom: 16px;
}
</style>
