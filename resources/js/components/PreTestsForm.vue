<template>
  <form
    method="POST"
    @submit.prevent="submit"
  >
    <h2>Check-up</h2>
    <template v-if="!loaded">
      Chargement...
      <img
        src="https://www.alexdor.info/design/divers/Alex.gif"
        alt="Alex Rutipa qui marche"
      >
    </template>
    <template v-else>
      <div
        v-for="question in questions"
        :key="question.id"
        class="row"
      >
        <template v-if="fields.questionnaire[question.id]">
          <div class="col-sm-12">
            <div class="form-check">
              <input
                :id="question.id"
                v-model="fields.questionnaire[question.id].activated"
                class="form-check-input"
                type="checkbox"
                :name="question.id"
              >
              <label
                class="form-check-label"
                :for="question.id"
              >{{ question.label }}</label>
              <div
                v-if="question.fieldDescription"
                class="field-description"
              >
                {{ question.fieldDescription }}
              </div>
              <div
                v-if="question.word"
                class="field-description"
              >
                => Définition de
                <a :href="'/dictionnaire#' + question.word">{{ question.word }}</a>
              </div>
              <div
                v-if="errors && errors[question.id]"
                class="text-danger"
              >
                {{ errors[question.label][0] }}
              </div>
            </div>
            <div
              v-if="fields.questionnaire[question.id].activated"
              class="checkbox-precision"
            >
              <label
                :for="'explanation-' + question.id"
                class="form-label"
              >Précisions</label>
              <input
                :id="'explanation-' + question.id"
                v-model="fields.questionnaire[question.id].explanation"
                type="text"
                :name="'explanation-' + question.id"
                style="width: 100%"
              >
              <div
                v-if="errors && errors[question.id + 'Explanation']"
                class="text-danger"
              >
                {{ errors[question.id + 'Explanation'][0] }}
              </div>
            </div>
          </div>
        </template>
      </div>

      <h2>Verdict</h2>
      <div class="row">
        <div class="col-sm-12">
          <template v-if="editing">
            <input
              v-model="fields.finalThought"
              type="hidden"
              name="finalThought"
            >
            <span v-if="fields.finalThought === 'ok'">Conforme</span>
            <span v-else>Non conforme</span>
          </template>
          <template v-else>
            <div class="form-check form-check-inline">
              <input
                id="finalThought-ok"
                v-model="fields.finalThought"
                class="form-check-input"
                type="radio"
                name="finalThought"
                value="ok"
              >
              <label
                class="form-check-label"
                for="finalThought-ok"
              >Conforme</label>
            </div>
            <div class="form-check form-check-inline">
              <input
                id="finalThought-not-ok"
                v-model="fields.finalThought"
                class="form-check-input"
                type="radio"
                name="finalThought"
                value="not-ok"
              >
              <label
                class="form-check-label"
                for="finalThought-not-ok"
              >Non conforme</label>
            </div>
          </template>
          <div
            v-if="errors && errors.finalThought"
            class="text-danger"
          >
            {{ errors.finalThought[0] }}
          </div>
          <div
            v-if="fields.finalThought === 'not-ok'"
            class="final-thought-precision"
          >
            <label
              for="finalThoughtPrecision"
              class="form-label"
            >Précisions</label>

            <textarea
              id="finalThoughtPrecision"
              v-model="fields.finalThoughtExplanation"
              class="form-control"
              name="finalThoughtPrecision"
            />
            <div
              v-if="errors && errors.finalThoughtExplanation"
              class="text-danger"
            >
              {{ errors.finalThoughtExplanation[0] }}
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-12 text-center">
          <button
            type="submit"
            class="submit bouton mb-0"
          >
            Envoyer
          </button>
        </div>
      </div>
    </template>
  </form>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  questions: {
    type: Array,
    required: true
  },
  gameId: {
    type: Number,
    required: true
  },
  preTest: {
    type: Object,
    required: false,
    default: null
  },
  initMethod: {
    type: String,
    required: false,
    default: 'POST'
  },
  initAction: {
    type: String,
    required: false,
    default: null
  },
  initRedirection: {
    type: String,
    required: false,
    default: null
  }
})

const loaded = ref(true)
const success = ref(false)
const errors = ref({})
const fields = ref(fillFields())
const method = ref(props.initMethod)
const action = ref(props.initAction || '/')
const redirection = ref(props.initRedirection || '')
const editing = computed(() => method.value === 'PUT')

function submit() {
  if (loaded.value) {
    loaded.value = false
    success.value = false
    errors.value = {}
    axios({
      method: method.value,
      url: action.value,
      data: fields.value
    })
      .then(() => {
        if (redirection.value !== '') {
          window.location.replace(redirection.value)
        }
        fields.value = fillFields()
        success.value = true
        loaded.value = true
      })
      .catch(error => {
        loaded.value = true
        if (error.response && error.response.status === 422) {
          errors.value = error.response.data.errors || {}
        }
      })
  }
}

function fillFields() {
  const questionnaire = {}
  props.questions.forEach(question => {
    questionnaire[question.id] = {
      activated: false,
      explanation: null
    }
  })

  return props.initMethod === 'PUT'
    ? props.preTest
    : {
      questionnaire,
      gameId: props.gameId,
      finalThought: null,
      finalThoughtExplanation: null
    }
}
</script>

<style lang="scss" scoped>
h2 {
  font-size: 30px;
  margin-top: 30px;
  padding-top: 10px;
  border-top: 1px dotted;
}

.checkbox-precision {
  margin-top: 10px;
  margin-left: 20px;
}

.field-description {
  color: #888;
  font-style: italic;
}

.final-thought-precision {
  margin-top: 10px;
}
</style>
