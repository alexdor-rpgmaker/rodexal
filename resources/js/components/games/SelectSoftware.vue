<template>
  <div class="software-form-group">
    <div class="form-group row">
      <!--suppress HtmlFormInputWithoutLabel -->
      <select
          id="software-list"
          class="form-control"
          v-model="selectedSoftware"
          :disabled="!registrationAllowed"
      >
        <option
            v-for="software in softwareList"
            :value="software"
            :key="software"
        >
          {{ software }}
        </option>
        <option value="other">
          Autre (précisez)
        </option>
      </select>
    </div>
    <template v-if="otherSoftwareInputDisplayed">
      <div class="form-group row">
        <!--suppress HtmlFormInputWithoutLabel -->
        <input
            type="text"
            id="other-software"
            class="form-control"
            v-model="otherSoftware"
            :disabled="!registrationAllowed"
        />
      </div>
    </template>

    <!-- This input is the only one taken into account for the form -->
    <!--suppress HtmlFormInputWithoutLabel -->
    <input
        type="hidden"
        id="software"
        name="software"
        class="input_text_large"
        :value="resolvedSoftware"
    />
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedSoftware: '',
      otherSoftware: ''
    }
  },
  props: {
    initialSoftware: {
      type: String
    },
    softwareList: {
      type: Array,
      required: true
    },
    registrationAllowed: {
      type: Boolean,
      required: true
    }
  },
  beforeMount() {
    if (this.initialSoftware) {
      if (this.softwareList.includes(this.initialSoftware)) {
        this.selectedSoftware = this.initialSoftware
      } else {
        this.selectedSoftware = 'other'
        this.otherSoftware = this.initialSoftware
      }
    } else {
      this.selectedSoftware = this.softwareList[0]
    }
  },
  computed: {
    selectedSoftwareIsInDropdown() {
      return this.selectedSoftware && this.softwareList.includes(this.selectedSoftware)
    },
    otherSoftwareInputDisplayed() {
      return this.selectedSoftware && !this.selectedSoftwareIsInDropdown
    },
    resolvedSoftware() {
      if (this.selectedSoftwareIsInDropdown) {
        return this.selectedSoftware
      }

      return this.otherSoftware
    }
  }
}
</script>

<style lang="scss" scoped>
.form-group {
  margin-bottom: 10px;
}
</style>
