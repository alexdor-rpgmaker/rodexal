// TODO
// import Jukebox from '~/resources/js/components/Jukebox.vue'
import { shallowMount } from '@vue/test-utils'

describe('Jukebox', () => {
  const methods = {
    fetchMusics: jest.fn(),
    initializeAmplitude: jest.fn(),
    playCallback: jest.fn(),
    durationChangeCallback: jest.fn(),
    songChangeCallback: jest.fn(),
    toggleRepeat: jest.fn(),
    toggleShuffle: jest.fn(),
    changeMusicFromSelector: jest.fn(),
    changeSongPlayedPercentage: jest.fn(),
    changeVolume: jest.fn(),
    previous: jest.fn(),
    playPause: jest.fn(),
    next: jest.fn(),
    formatMusicForJukebox: jest.fn(),
    formatMusicForAmplitude: jest.fn()
  }

  describe('When component is mounted', () => {
    it.skip('should do something', () => {
      const wrapper = shallowMount('Jukebox', {
        methods
      })

      expect(wrapper.vm.$data).toEqual('something')
    })
  })
})
