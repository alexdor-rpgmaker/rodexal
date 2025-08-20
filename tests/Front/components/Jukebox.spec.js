// import Jukebox from '@/components/Jukebox.vue'
import { shallowMount } from '@vue/test-utils'

describe('Jukebox', () => {
  const methods = {
    fetchMusics: vi.fn(),
    initializeAmplitude: vi.fn(),
    playCallback: vi.fn(),
    durationChangeCallback: vi.fn(),
    songChangeCallback: vi.fn(),
    toggleRepeat: vi.fn(),
    toggleShuffle: vi.fn(),
    changeMusicFromSelector: vi.fn(),
    changeSongPlayedPercentage: vi.fn(),
    changeVolume: vi.fn(),
    previous: vi.fn(),
    playPause: vi.fn(),
    next: vi.fn(),
    formatMusicForJukebox: vi.fn(),
    formatMusicForAmplitude: vi.fn()
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
