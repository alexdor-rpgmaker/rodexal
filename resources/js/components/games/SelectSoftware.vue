<template>
  <div class="software-form-group">
    <div class="row mb-2">
      <select
        id="software-list"
        v-model="selectedSoftware"
        class="form-select"
        :disabled="!registrationAllowed"
      >
        <option
          v-for="software in softwareList"
          :key="software"
          :value="software"
        >
          {{ software }}
        </option>
        <option value="other">
          Autre (précisez)
        </option>
      </select>
    </div>
    <template v-if="otherSoftwareInputDisplayed">
      <div class="row">
        <input
          id="other-software"
          v-model="otherSoftware"
          type="text"
          class="form-control"
          :disabled="!registrationAllowed"
        >
      </div>
    </template>

    <!-- This input is the only one taken into account for the form -->
    <input
      id="software"
      type="hidden"
      name="software"
      class="input_text_large"
      :value="resolvedSoftware"
    >
  </div>
</template>

<script setup>
import { computed, onBeforeMount, ref } from 'vue'

const props = defineProps({
  initialSoftware: {
    type: String,
    required: false,
    default: null
  },
  softwareList: {
    type: Array,
    required: true
  },
  registrationAllowed: {
    type: Boolean,
    required: true
  }
})

const selectedSoftware = ref('')
const otherSoftware = ref('')

const selectedSoftwareIsInDropdown = computed(() =>
  selectedSoftware.value && props.softwareList.includes(selectedSoftware.value)
)

const otherSoftwareInputDisplayed = computed(() =>
  selectedSoftware.value && !selectedSoftwareIsInDropdown.value
)

const resolvedSoftware = computed(() =>
  selectedSoftwareIsInDropdown.value
    ? selectedSoftware.value
    : otherSoftware.value
)

onBeforeMount(() => {
  if (props.initialSoftware) {
    if (props.softwareList.includes(props.initialSoftware)) {
      selectedSoftware.value = props.initialSoftware
    } else {
      selectedSoftware.value = 'other'
      otherSoftware.value = props.initialSoftware
    }
  } else {
    selectedSoftware.value = props.softwareList[0]
  }
})
</script>
