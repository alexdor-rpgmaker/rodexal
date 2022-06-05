<template>
  <!--suppress HtmlFormInputWithoutLabel -->
  <select id="session-change" v-model="selectedSessionId" @change="changeSession">
    <option
        v-for="sessionId in sessionIds"
        :value="sessionId"
        :key="sessionId"
      >
      Session {{ nameFromId(sessionId) }}
    </option>
  </select>
</template>

<script>
import sessionName from '../session-name'

// noinspection JSUnusedGlobalSymbols
export default {
  props: {
    initialSessionId: {
      type: Number,
      required: false
    },
    sessionIds: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      selectedSessionId: null
    }
  },
  beforeMount() {
    this.selectedSessionId = this.initialSessionId || this.sessionIds[this.sessionIds.length - 1]
  },
  methods: {
    nameFromId(sessionId) {
      return sessionName(sessionId)
    },
    changeSession() {
      const searchParams = new URLSearchParams(window.location.search)
      searchParams.set('session_id', this.selectedSessionId)
      window.location.search = searchParams.toString()
    }
  }
}
</script>

<style lang="scss" scoped>
#session-change {
  margin-bottom: 16px;
}
</style>
