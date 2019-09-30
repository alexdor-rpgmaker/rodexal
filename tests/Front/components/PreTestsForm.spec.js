import { shallowMount } from '@vue/test-utils'
import PreTestsForm from '~/resources/js/components/PreTestsForm.vue'

describe('PreTestsForm', () => {
  const gameId = 115
  const initPreTest = {
    questionnaire: {
      notAutonomous: {
        activated: true,
        explanation: 'Game needs RTP to be launched'
      },
      finalThoughtPrecision: 'Game is OK'
    }
  }
  const propsData = {
    gameId,
    preTest: initPreTest,
    questions: [
      {
        id: 'notAutonomous',
        label: "Le jeu n'est pas autonome",
        word: 'autonome'
      }
    ],
    initMethod: 'PUT',
    initAction: '/game/advice',
    initRedirection: '/'
  }

  describe('When component is mounted', () => {
    describe('with props', () => {
      it('method, action and redirection data take their initial values', () => {
        const wrapper = shallowMount(PreTestsForm, {
          propsData
        })

        expect(wrapper.vm.$data.method).toEqual('PUT')
        expect(wrapper.vm.$data.action).toEqual('/game/advice')
        expect(wrapper.vm.$data.redirection).toEqual('/')
      })
    })

    describe('with no props', () => {
      it('method, action and redirection take default values', () => {
        const wrapper = shallowMount(PreTestsForm, {
          propsData: {
            gameId,
            preTest: initPreTest,
            questions: []
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
        const wrapper = shallowMount(PreTestsForm, {
          propsData: { ...propsData, initMethod: 'POST' }
        })

        expect(wrapper.vm.editing).toEqual(false)
      })
    })

    describe('When method is PUT', () => {
      it('returns true', () => {
        const wrapper = shallowMount(PreTestsForm, {
          propsData: { ...propsData, initMethod: 'PUT' }
        })

        expect(wrapper.vm.editing).toEqual(true)
      })
    })
  })

  describe('.fillFields()', () => {
    describe('When method is POST', () => {
      it('returns questionnaire with activated false and finalThought null', () => {
        const wrapper = shallowMount(PreTestsForm, {
          propsData: { ...propsData, initMethod: 'POST' }
        })

        wrapper.vm.fillFields()

        expect(wrapper.vm.$data.fields).toEqual({
          questionnaire: {
            notAutonomous: {
              activated: false,
              explanation: null
            }
          },
          gameId,
          finalThought: null
        })
      })
    })

    describe('When method is PUT', () => {
      it('returns the given pre-test', () => {
        const wrapper = shallowMount(PreTestsForm, {
          propsData: { ...propsData, initMethod: 'PUT' }
        })

        wrapper.vm.fillFields()

        expect(wrapper.vm.$data.fields).toEqual(initPreTest)
      })
    })
  })
})
