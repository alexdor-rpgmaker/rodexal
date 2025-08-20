<template>
  <form
    method="POST"
    @submit.prevent="submit"
  >
    <template v-if="!loaded">
      Chargement...
      <img
        src="https://www.alexdor.info/design/divers/Alex.gif"
        alt="Alex Rutipa qui marche"
      >
    </template>
    <template v-else>
      <h3>Critères disqualifiants</h3>

      <div
        v-for="question in questionsOnDisqualifyingSubjects"
        :key="question.id"
        class="row question-block"
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
              class="checkbox-explanation"
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

      <hr>

      <h3>Critères non disqualifiants</h3>
      <p>Ces critères seront jugés si le jeu fait son retour dans une session prochaine.</p>

      <div
        v-for="question in questionsOnNotDisqualifyingSubjects"
        :key="question.id"
        class="row question-block"
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
              class="checkbox-explanation"
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
            <span v-if="fields.finalThought === 'ok'">Conforme, sans aucun souci</span>
            <span v-if="fields.finalThought === 'some-problems'">Conforme avec des soucis non disqualifiants mais problématiques</span>
            <span v-if="fields.finalThought === 'not-ok'">Non conforme</span>
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
              >
                Conforme, sans aucun souci
              </label>
            </div>

            <div class="form-check form-check-inline">
              <input
                id="finalThought-some-problems"
                v-model="fields.finalThought"
                class="form-check-input"
                type="radio"
                name="finalThought"
                value="some-problems"
              >
              <label
                class="form-check-label"
                for="finalThought-some-problems"
              >
                Conforme avec des soucis non disqualifiants mais problématiques
              </label>
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
              >
                Non conforme
              </label>
            </div>
          </template>
          <div
            v-if="errors && errors.finalThought"
            class="text-danger"
          >
            {{ errors.finalThought[0] }}
          </div>
          <div
            v-if="displayFinalThoughtExplanation"
            class="final-thought-explanation"
          >
            <label
              for="finalThoughtExplanation"
              class="form-label"
            >Précisions</label>

            <textarea
              id="finalThoughtExplanation"
              v-model="fields.finalThoughtExplanation"
              class="form-control"
              name="finalThoughtExplanation"
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
import axios from 'axios'
import { computed, ref } from 'vue'

const props = defineProps({
  questionsOnDisqualifyingSubjects: { type: Array, required: true },
  questionsOnNotDisqualifyingSubjects: { type: Array, required: true },
  gameId: { type: Number, required: true },
  preQualification: { type: Object, required: false, default: null },
  initMethod: { type: String, required: false, default: 'POST' },
  initAction: { type: String, required: false, default: null },
  initRedirection: { type: String, required: false, default: null }
})

const loaded = ref(true)
const errors = ref({})
const success = ref(false)
const fields = ref(fillFields())
const method = ref(props.initMethod)
const action = ref(props.initAction || '/')
const redirection = ref(props.initRedirection || '')

const editing = computed(() => method.value === 'PUT')
const displayFinalThoughtExplanation = computed(() =>
  ['some-problems', 'not-ok'].includes(fields.value.finalThought)
)

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
  props.questionsOnDisqualifyingSubjects.forEach(question => {
    questionnaire[question.id] = {
      activated: false,
      explanation: null
    }
  })
  props.questionsOnNotDisqualifyingSubjects.forEach(question => {
    questionnaire[question.id] = {
      activated: false,
      explanation: null
    }
  })

  return props.initMethod === 'PUT'
    ? props.preQualification
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

.question-block {
  margin-bottom: 20px;
}

.checkbox-explanation {
  margin-top: 10px;
  margin-left: 26px;
}

.field-description {
  color: #888;
  padding: 2px 0 5px 2px;
  font-style: italic;
  font-size: 0.9em;
}

.final-thought-explanation {
  margin-top: 10px;
}
</style>
