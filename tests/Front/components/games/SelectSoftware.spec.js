import SelectSoftware from '~/resources/js/components/games/SelectSoftware.vue'
import { shallowMount } from '@vue/test-utils'

describe('SelectSoftware', () => {
  let wrapper

  const softwareList = ['RPG Maker 2003', 'RPG Maker XP', 'RPG Maker MV']

  describe('Classic select list', () => {
    beforeEach(() => {
      wrapper = shallowMount(SelectSoftware, {
        propsData: {
          softwareList,
          registrationAllowed: true,
          initialSoftware: 'RPG Maker XP'
        }
      })
    })

    it('contains 4 options : 3 software and 1 "other"', () => {
      expect(wrapper.findAll('option').length).toEqual(4)
    })

    it('selected option is "RPG Maker XP"', () => {
      expect(wrapper.find('option:checked').element.value).toEqual('RPG Maker XP')
    })
  })

  describe('Visibility of other software input', () => {
    beforeEach(() => {
      wrapper = shallowMount(SelectSoftware, {
        propsData: {
          softwareList,
          registrationAllowed: true,
          initialSoftware: 'RPG Maker XP'
        }
      })
    })

    describe('When software in the list is selected', () => {
      it('does not display the input', async () => {
        await wrapper.find('select').setValue('RPG Maker XP')

        expect(wrapper.find('#other-software').exists()).toEqual(false)
      })
    })

    describe('When "other" value is selected', () => {
      it('displays the input', async () => {
        await wrapper.find('select').setValue('other')

        expect(wrapper.find('#other-software').exists()).toEqual(true)
      })
    })
  })

  describe('Other software input', () => {
    describe('When there is no initial software value', () => {
      beforeEach(() => {
        wrapper = shallowMount(SelectSoftware, {
          propsData: {
            softwareList,
            registrationAllowed: true,
            initialSoftware: null
          }
        })
      })

      it('does not display the other software input', () => {
        const otherSoftwareInput = wrapper.find('#other-software')
        expect(otherSoftwareInput.exists()).toEqual(false)
      })

      it('resolves the software value to the first software in the list', () => {
        expect(wrapper.find('#software').element.value).toEqual(softwareList[0])
      })
    })

    describe('When the initial software value is in the list', () => {
      beforeEach(() => {
        wrapper = shallowMount(SelectSoftware, {
          propsData: {
            softwareList,
            registrationAllowed: true,
            initialSoftware: 'RPG Maker XP'
          }
        })
      })

      it('does not display the other software input', () => {
        const otherSoftwareInput = wrapper.find('#other-software')
        expect(otherSoftwareInput.exists()).toEqual(false)
      })

      it('resolves the software value to the initial value (RPG Maker XP)', () => {
        expect(wrapper.find('#software').element.value).toEqual('RPG Maker XP')
      })
    })

    describe('When the initial software value is not in the list', () => {
      beforeEach(() => {
        wrapper = shallowMount(SelectSoftware, {
          propsData: {
            softwareList,
            registrationAllowed: true,
            initialSoftware: 'RPG Maker XXX'
          }
        })
      })

      it('displays the other software input', () => {
        const otherSoftwareInput = wrapper.find('#other-software')
        expect(otherSoftwareInput.exists()).toEqual(true)
      })

      it('resolves the software value to the initial value (RPG Maker XXX)', () => {
        expect(wrapper.find('#software').element.value).toEqual('RPG Maker XXX')
      })
    })

    describe('When text is written in then other software input', () => {
      beforeEach(() => {
        wrapper = shallowMount(SelectSoftware, {
          propsData: {
            softwareList,
            registrationAllowed: true,
            initialSoftware: 'Other software'
          }
        })
        wrapper.find('#other-software').setValue('Random software')
      })

      it('keeps the other software input displayed', () => {
        const otherSoftwareInput = wrapper.find('#other-software')
        expect(otherSoftwareInput.exists()).toEqual(true)
      })

      it('resolves the software value to the written value (Random software)', () => {
        expect(wrapper.find('#software').element.value).toEqual('Random software')
      })
    })
  })
})
