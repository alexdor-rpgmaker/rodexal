import { shallowMount } from '@vue/test-utils'
import SessionChange from '@/components/SessionChange.vue'

describe('Session Change - A dropdown to choose the competition session (year)', () => {
  describe('When component is mounted', () => {
    describe('with initialSessionId', () => {
      it('returns the initial session ID as currentSessionId', () => {
        const wrapper = shallowMount(SessionChange, {
          propsData: {
            initialSessionId: 23,
            sessionIds: [19, 20, 21, 22, 23, 25]
          }
        })

        expect(wrapper.vm.selectedSessionId).toEqual(23)
      })
    })

    describe('without initialSessionId', () => {
      it('returns the latest session ID as currentSessionId', () => {
        const wrapper = shallowMount(SessionChange, {
          propsData: {
            sessionIds: [19, 20, 21, 22, 23, 25]
          }
        })

        expect(wrapper.vm.selectedSessionId).toEqual(25)
      })
    })
  })
})
