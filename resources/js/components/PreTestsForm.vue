<template>
  <form method="POST" @submit.prevent="submit">
    <!-- 
Si «Conforme», le test complet est assigné au testeur.

class="form-control is-invalid" 

value="{{ old('label') }}" 
    -->
    <!-- <form method="POST" action="{{ url('qcm') }}"> -->
    <!-- {{-- @method('PUT') --}} -->
    <!-- @csrf -->
    <h2>Check-up</h2>
    <div class="form-group row" v-for="question in questions" :key="question.label">
      <div class="col-sm-12">
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            name="question.id"
            :id="question.id"
            v-model="fields[question.id]"
          >
          <label class="form-check-label" :for="question.id">
            {{question.label}}
            <span
              class="field-description"
              v-if="question.fieldDescription"
            >({{ question.fieldDescription }})</span>
          </label>
          <div
            v-if="errors && errors[question.id]"
            class="text-danger"
          >{{ errors[question.label][0] }}</div>
        </div>
        <div class="checkbox-precision" v-if="fields[question.id]">
          <label :for="'explanation-' + question.id">Précisions</label>
          <input
            type="text"
            :name="'explanation-' + question.id"
            :id="'explanation-' + question.id"
            v-model="fields[question.id + 'Explanation']"
            style="width: 100%"
          >
          <div
            v-if="errors && errors[question.id + 'Explanation']"
            class="text-danger"
          >{{ errors[question.id + 'Explanation'][0] }}</div>
        </div>
      </div>
    </div>

    <h2>Verdict</h2>
    <div class="form-group row">
      <div class="col-sm-12">
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="finalThought"
            id="finalThought-ok"
            value="true"
            v-model="fields.finalThought"
          >
          <label class="form-check-label" for="finalThought-ok">Conforme</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="finalThought"
            id="finalThought-not-ok"
            value="false"
            v-model="fields.finalThought"
          >
          <label class="form-check-label" for="finalThought-not-ok">Non conforme</label>
        </div>
        <div v-if="errors && errors.finalThought" class="text-danger">{{ errors.finalThought[0] }}</div>
        <div class="final-thought-precision" v-if="fields.finalThought === 'false'">
          <label for="finalThoughtPrecision">Précisions</label>
          
          <textarea
            class="form-control"
            name="finalThoughtPrecision"
            id="finalThoughtPrecision"
            v-model="fields.finalThoughtPrecision"
          ></textarea>
          <div
            v-if="errors && errors.finalThoughtPrecision"
            class="text-danger"
          >{{ errors.finalThoughtPrecision[0] }}</div>
        </div>
      </div>
    </div>

    <div class="form-group row submit-wrapper">
      <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-primary mb-0">Envoyer</button>
      </div>
    </div>
  </form>
</template>

<script>
import FormMixin from '../FormMixin'

export default {
  mixins: [FormMixin],
  data() {
    const fields = {}
    const questions = [
      {
        id: 'notAutonomous',
        label: "Le jeu n'est pas autonome"
      },
      {
        id: 'notLaunchable',
        label: 'Impossible de lancer le jeu'
      },
      {
        id: 'blockingBug',
        label: 'Bug bloquant inévitable'
      },
      {
        id: 'severalBugs',
        label: 'Présence abusive de bugs non bloquants'
      },
      {
        id: 'spellingMistakes',
        label: "Nombre abusif de fautes d'orthographe"
      },
      {
        id: 'tooHard',
        label: 'Difficulté abusive/mal calibrée',
        fieldDescription:
          'Nombre de game over injuste par heure de jeu, mauvaise maniabilité, explications manquantes...'
      },
      {
        id: 'tooShort',
        label: 'Jeu trop court',
        fieldDescription:
          'La totalité du jeu est observable en moins de 30 minutes'
      },
      {
        id: 'unplayableAlone',
        label: "Impossible d'apprécier seul la majeure partie du jeu",
        fieldDescription: 'Le multijoueur est nécessaire'
      }
    ]
    questions.forEach(question => {
      fields[question.id] = false
      fields[question.id + 'Explanation'] = ''
    })
    return {
      action: '/submit',
      questions,
      fields
    }
  }
}
</script>

<style>
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

.card .card-body .submit-wrapper {
  margin-top: 30px;
}
</style>