<template>
  <form method="POST" @submit.prevent="submit">
    <template v-if="!loaded">Chargement...
      <img src="https://www.alexdor.info/design/divers/Alex.gif" alt="Alex Rutipa qui marche">
    </template>
    <template v-else>
      <h3>Critères disqualifiants</h3>

      <div class="row question-block" v-for="question in questionsOnDisqualifyingSubjects" :key="question.id">
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
            <div class="checkbox-explanation" v-if="fields.questionnaire[question.id].activated">
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

      <hr />

      <h3>Critères non disqualifiants</h3>
      <p>Ces critères seront jugés si le jeu fait son retour dans une session prochaine.</p>

      <div class="row question-block" v-for="question in questionsOnNotDisqualifyingSubjects" :key="question.id">
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
            <div class="checkbox-explanation" v-if="fields.questionnaire[question.id].activated">
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
            <span v-if="fields.finalThought === 'ok'">Conforme, sans aucun souci</span>
            <span v-if="fields.finalThought === 'some-problems'">Conforme avec des soucis non disqualifiants mais problématiques</span>
            <span v-if="fields.finalThought === 'not-ok'">Non conforme</span>
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
              <label class="form-check-label" for="finalThought-ok">
                Conforme, sans aucun souci
              </label>
            </div>

            <div class="form-check form-check-inline">
              <input
                  class="form-check-input"
                  type="radio"
                  name="finalThought"
                  id="finalThought-some-problems"
                  v-model="fields.finalThought"
                  value="some-problems"
              >
              <label class="form-check-label" for="finalThought-some-problems">
                Conforme avec des soucis non disqualifiants mais problématiques
              </label>
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
              <label class="form-check-label" for="finalThought-not-ok">
                Non conforme
              </label>
            </div>
          </template>
          <div v-if="errors && errors.finalThought" class="text-danger">{{ errors.finalThought[0] }}</div>
          <div class="final-thought-explanation" v-if="displayFinalThoughtExplanation">
            <label for="finalThoughtExplanation" class="form-label">Précisions</label>

            <textarea
                class="form-control"
                name="finalThoughtExplanation"
                id="finalThoughtExplanation"
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
    questionsOnDisqualifyingSubjects: {
      type: Array,
      required: true
    },
    questionsOnNotDisqualifyingSubjects: {
      type: Array,
      required: true
    },
    gameId: {
      type: Number,
      required: true
    },
    preQualification: {
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
    },
    displayFinalThoughtExplanation() {
      return ['some-problems', 'not-ok'].includes(this.fields.finalThought)
    }
  },
  methods: {
    fillFields() {
      const questionnaire = {}
      this.questionsOnDisqualifyingSubjects.forEach(question => {
        questionnaire[question.id] = {
          activated: false,
          explanation: null
        }
      })
      this.questionsOnNotDisqualifyingSubjects.forEach(question => {
        questionnaire[question.id] = {
          activated: false,
          explanation: null
        }
      })
      // noinspection UnnecessaryLocalVariableJS
      const fields =
          this.initMethod === 'PUT'
            ? this.preQualification
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
