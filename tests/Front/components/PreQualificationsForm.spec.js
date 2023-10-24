import { shallowMount } from '@vue/test-utils'
import PreQualificationsForm from '~/resources/js/components/PreQualificationsForm.vue'

describe('PreQualificationsForm', () => {
  const gameId = 115
  const initPreQualification = {
    questionnaire: {
      notAutonomous: {
        activated: true,
        explanation: 'Game needs RTP to be launched'
      },
      finalThoughtExplanation: 'Game is OK'
    }
  }
  const propsData = {
    gameId,
    preQualification: initPreQualification,
    questionsOnDisqualifyingSubjects: [
      {
        id: 'notAutonomous',
        label: 'Jeu non autonome',
        word: 'autonome'
      }
    ],
    questionsOnNotDisqualifyingSubjects: [
      {
        id: 'painfulHandling',
        label: 'Maniabilité très contraignante',
        description: 'Si le jeu est très compliqué à manier, veuillez cocher cette case.'
      }
    ],
    initMethod: 'PUT',
    initAction: '/game/advice',
    initRedirection: '/'
  }

  describe('When component is mounted', () => {
    describe('with props', () => {
      it('method, action and redirection data take their initial values', () => {
        const wrapper = shallowMount(PreQualificationsForm, {
          propsData
        })

        expect(wrapper.vm.$data.method).toEqual('PUT')
        expect(wrapper.vm.$data.action).toEqual('/game/advice')
        expect(wrapper.vm.$data.redirection).toEqual('/')
      })
    })

    describe('with no props', () => {
      it('method, action and redirection take default values', () => {
        const wrapper = shallowMount(PreQualificationsForm, {
          propsData: {
            gameId,
            preTest: initPreQualification,
            questionsOnDisqualifyingSubjects: [],
            questionsOnNotDisqualifyingSubjects: []
          }
        })

        expect(wrapper.vm.$data.method).toEqual('POST')
        expect(wrapper.vm.$data.action).toEqual('/')
        expect(wrapper.vm.$data.redirection).toEqual('')
      })
    })
  })

  describe('.editing()', () => {
    describe('When method is POST', () => {
      it('returns false', () => {
        const wrapper = shallowMount(PreQualificationsForm, {
          propsData: { ...propsData, initMethod: 'POST' }
        })

        expect(wrapper.vm.editing).toEqual(false)
      })
    })

    describe('When method is PUT', () => {
      it('returns true', () => {
        const wrapper = shallowMount(PreQualificationsForm, {
          propsData: { ...propsData, initMethod: 'PUT' }
        })

        expect(wrapper.vm.editing).toEqual(true)
      })
    })
  })

  describe('.displayFinalThoughtExplanation(), for POST form', () => {
    describe('When finalThought is ok', () => {
      it('returns false', async () => {
        const wrapper = shallowMount(PreQualificationsForm, {
          propsData: { ...propsData, initMethod: 'POST' }
        })

        const input = wrapper.find('#finalThought-ok')
        await input.setChecked()

        expect(wrapper.vm.fields.finalThought).toEqual('ok')
        expect(wrapper.vm.displayFinalThoughtExplanation).toEqual(false)
      })
    })

    describe('When finalThought is some-problems', () => {
      it('returns true', async () => {
        const wrapper = shallowMount(PreQualificationsForm, {
          propsData: { ...propsData, initMethod: 'POST' }
        })

        const input = wrapper.find('#finalThought-some-problems')
        await input.setChecked()

        expect(wrapper.vm.fields.finalThought).toEqual('some-problems')
        expect(wrapper.vm.displayFinalThoughtExplanation).toEqual(true)
      })
    })

    describe('When finalThought is not-ok', () => {
      it('returns true', async () => {
        const wrapper = shallowMount(PreQualificationsForm, {
          propsData: { ...propsData, initMethod: 'POST' }
        })

        const input = wrapper.find('#finalThought-not-ok')
        await input.setChecked()

        expect(wrapper.vm.fields.finalThought).toEqual('not-ok')
        expect(wrapper.vm.displayFinalThoughtExplanation).toEqual(true)
      })
    })
  })

  describe('.fillFields()', () => {
    describe('When method is POST', () => {
      it('returns questionnaire with activated false and finalThought null', () => {
        const wrapper = shallowMount(PreQualificationsForm, {
          propsData: { ...propsData, initMethod: 'POST' }
        })

        wrapper.vm.fillFields()

        expect(wrapper.vm.$data.fields).toEqual({
          questionnaire: {
            notAutonomous: {
              activated: false,
              explanation: null
            },
            painfulHandling: {
              activated: false,
              explanation: null
            }
          },
          gameId,
          finalThought: null,
          finalThoughtExplanation: null
        })
      })
    })

    describe('When method is PUT', () => {
      it('returns the given pre-test', () => {
        const wrapper = shallowMount(PreQualificationsForm, {
          propsData: { ...propsData, initMethod: 'PUT' }
        })

        wrapper.vm.fillFields()

        expect(wrapper.vm.$data.fields).toEqual(initPreQualification)
      })
    })
  })
})
