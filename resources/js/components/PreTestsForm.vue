<template>
  <form method="POST" @submit.prevent="submit">
    <h2>Check-up</h2>
    <template v-if="!loaded">Chargement...
      <img src="https://www.alexdor.info/design/divers/Alex.gif" alt="Alex Rutipa qui marche">
    </template>
    <template v-else>
      <div class="row" v-for="question in questions" :key="question.id">
        <template v-if="fields.questionnaire[question.id]">
          <div class="col-sm-12">
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  :name="question.id"
                  :id="question.id"
                  v-model="fields.questionnaire[question.id].activated"
              >
              <label class="form-check-label" :for="question.id">{{ question.label }}</label>
              <div
                  class="field-description"
                  v-if="question.fieldDescription"
              >{{ question.fieldDescription }}
              </div>
              <div class="field-description" v-if="question.word">
                => Définition de
                <a :href="'/dictionnaire#' + question.word">{{ question.word }}</a>
              </div>
              <div
                  v-if="errors && errors[question.id]"
                  class="text-danger"
              >{{ errors[question.label][0] }}
              </div>
            </div>
            <div class="checkbox-precision" v-if="fields.questionnaire[question.id].activated">
              <label :for="'explanation-' + question.id" class="form-label">Précisions</label>
              <input
                  type="text"
                  :name="'explanation-' + question.id"
                  :id="'explanation-' + question.id"
                  v-model="fields.questionnaire[question.id].explanation"
                  style="width: 100%"
              >
              <div
                  v-if="errors && errors[question.id + 'Explanation']"
                  class="text-danger"
              >{{ errors[question.id + 'Explanation'][0] }}
              </div>
            </div>
          </div>
        </template>
      </div>

      <h2>Verdict</h2>
      <div class="row">
        <div class="col-sm-12">
          <template v-if="editing">
            <input type="hidden" name="finalThought" v-model="fields.finalThought">
            <span v-if="fields.finalThought === 'ok'">Conforme</span>
            <span v-else>Non conforme</span>
          </template>
          <template v-else>
            <div class="form-check form-check-inline">
              <input
                  class="form-check-input"
                  type="radio"
                  name="finalThought"
                  id="finalThought-ok"
                  v-model="fields.finalThought"
                  value="ok"
              >
              <label class="form-check-label" for="finalThought-ok">Conforme</label>
            </div>
            <div class="form-check form-check-inline">
              <input
                  class="form-check-input"
                  type="radio"
                  name="finalThought"
                  id="finalThought-not-ok"
                  v-model="fields.finalThought"
                  value="not-ok"
              >
              <label class="form-check-label" for="finalThought-not-ok">Non conforme</label>
            </div>
          </template>
          <div v-if="errors && errors.finalThought" class="text-danger">{{ errors.finalThought[0] }}</div>
          <div class="final-thought-precision" v-if="fields.finalThought === 'not-ok'">
            <label for="finalThoughtPrecision" class="form-label">Précisions</label>

            <textarea
                class="form-control"
                name="finalThoughtPrecision"
                id="finalThoughtPrecision"
                v-model="fields.finalThoughtExplanation"
            ></textarea>
            <div
                v-if="errors && errors.finalThoughtExplanation"
                class="text-danger"
            >{{ errors.finalThoughtExplanation[0] }}
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-12 text-center">
          <button type="submit" class="submit bouton mb-0">Envoyer</button>
        </div>
      </div>
    </template>
  </form>
</template>

<script>
import FormMixin from '../FormMixin'

// noinspection JSUnusedGlobalSymbols
export default {
  props: {
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
      required: false
    },
    initMethod: {
      type: String,
      required: false
    },
    initAction: {
      type: String,
      required: false
    },
    initRedirection: {
      type: String,
      required: false
    }
  },
  mixins: [FormMixin],
  data() {
    return {
      fields: this.fillFields(),
      method: this.initMethod || 'POST',
      action: this.initAction || '/',
      redirection: this.initRedirection || ''
    }
  },
  computed: {
    editing() {
      return this.method === 'PUT'
    }
  },
  methods: {
    fillFields() {
      const questionnaire = {}
      this.questions.forEach(question => {
        questionnaire[question.id] = {
          activated: false,
          explanation: null
        }
      })
      // noinspection UnnecessaryLocalVariableJS
      const fields =
          this.initMethod === 'PUT'
            ? this.preTest
            : {
                questionnaire,
                gameId: this.gameId,
                finalThought: null,
                finalThoughtExplanation: null
              }
      return fields
    }
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
