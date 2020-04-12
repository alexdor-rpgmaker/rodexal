import axios from 'axios'
import Games from '~/resources/js/components/Games.vue'
import { shallowMount } from '@vue/test-utils'

jest.mock('axios')
global.axios = axios

const windowScroll = jest.fn()
window.scrollTo = windowScroll

describe('Games', () => {
  const propsData = {
    session: null
  }

  beforeEach(() => {
    const apiResponseBody = {
      pagination: {},
      data: []
    }
    axios.mockResolvedValue({
      data: apiResponseBody
    })
  })

  describe('When component is mounted', () => {
    it('fetches games from API', () => {
      const fetchGames = jest.fn()
      shallowMount(Games, {
        propsData,
        methods: {
          fetchGames
        }
      })

      expect(fetchGames).toHaveBeenCalled()
    })
  })

  describe('Page buttons', () => {
    describe('when current page is the first and the last', () => {
      it('does not display buttons', () => {
        const wrapper = shallowMount(Games, {
          data: () => ({
            page: 1,
            totalPagesCount: 1
          })
        })

        expect(wrapper.find('.previous').exists()).toEqual(false)
        expect(wrapper.find('.next').exists()).toEqual(false)
      })
    })

    describe('when current page is the first but not the last', () => {
      it('displays next button', () => {
        const wrapper = shallowMount(Games, {
          data: () => ({
            page: 1,
            totalPagesCount: 10
          })
        })

        expect(wrapper.find('.previous').exists()).toEqual(false)
        expect(wrapper.find('.next').exists()).toEqual(true)
      })
    })

    describe('when current page is neither the first nor the last', () => {
      it('displays both buttons', () => {
        const wrapper = shallowMount(Games, {
          data: () => ({
            page: 5,
            totalPagesCount: 10
          })
        })

        expect(wrapper.find('.previous').exists()).toEqual(true)
        expect(wrapper.find('.next').exists()).toEqual(true)
      })
    })

    describe('when current page is the last and not the first', () => {
      it('displays previous button', () => {
        const wrapper = shallowMount(Games, {
          data: () => ({
            page: 10,
            totalPagesCount: 10
          })
        })

        expect(wrapper.find('.previous').exists()).toEqual(true)
        expect(wrapper.find('.next').exists()).toEqual(false)
      })
    })
  })

  describe('.resultsCount()', () => {
    describe('when results count is 0', () => {
      it('displays Aucun résultat', () => {
        const wrapper = shallowMount(Games, {
          data: () => ({
            totalResultsCount: 10,
            resultsCountOnThisPage: 0
          })
        })

        expect(wrapper.vm.resultsCount).toEqual('Aucun résultat')
      })
    })

    describe('when results count is 1', () => {
      it('displays 1 résultat', () => {
        const wrapper = shallowMount(Games, {
          data: () => ({
            totalResultsCount: 10,
            resultsCountOnThisPage: 1
          })
        })

        expect(wrapper.vm.resultsCount).toEqual('1 résultat')
      })
    })

    describe('when results count equals total results count (less than two pages)', () => {
      it('displays X résultats', () => {
        const wrapper = shallowMount(Games, {
          data: () => ({
            totalResultsCount: 2,
            resultsCountOnThisPage: 2
          })
        })

        expect(wrapper.vm.resultsCount).toEqual('2 résultats')
      })
    })

    describe('when results count does not equal total results count (more than one page)', () => {
      it('displays X résultats sur Y', () => {
        const wrapper = shallowMount(Games, {
          data: () => ({
            totalResultsCount: 5,
            resultsCountOnThisPage: 2
          })
        })

        expect(wrapper.vm.resultsCount).toEqual('2 résultats sur 5')
      })
    })
  })

  describe('.fetchGames()', () => {
    function createWrapperWithParams(params) {
      const wrapper = shallowMount(Games, params)
      const apiResponseBody = {
        pagination: {
          page: 2,
          totalPagesCount: 5,
          totalResultsCount: 150,
          resultsCountOnThisPage: 30
        },
        data: [{ title: 'game-1' }, { title: 'game-2' }]
      }
      axios.mockClear().mockResolvedValue({
        data: apiResponseBody
      })
      return wrapper
    }

    it('stores pagination information in component', async () => {
      const wrapper = createWrapperWithParams({ propsData })

      await wrapper.vm.fetchGames()

      expect(wrapper.vm.$data.page).toEqual(2)
      expect(wrapper.vm.$data.totalPagesCount).toEqual(5)
      expect(wrapper.vm.$data.totalResultsCount).toEqual(150)
      expect(wrapper.vm.$data.resultsCountOnThisPage).toEqual(30)
    })

    it('stores games information in component', async () => {
      const wrapper = createWrapperWithParams({ propsData })

      await wrapper.vm.fetchGames()

      expect(wrapper.vm.$data.games).toEqual([
        { title: 'game-1' },
        { title: 'game-2' }
      ])
    })

    describe('without parameters', () => {
      it('fetches games from API only with page and session sort parameters', async () => {
        const wrapper = createWrapperWithParams({ propsData })

        await wrapper.vm.fetchGames()

        expect(axios).toHaveBeenCalledTimes(1)
        expect(axios).toHaveBeenCalledWith({
          params: {
            page: 1,
            sort: 'session:asc'
          },
          url: 'https://former-app/api/v0/jeux.php'
        })
      })
    })

    describe('with a session props', () => {
      it('fetches games from API with page, session and session sort parameters', async () => {
        const wrapper = createWrapperWithParams({
          propsData: { ...propsData, session: '17' }
        })

        await wrapper.vm.fetchGames()

        expect(axios).toHaveBeenCalledTimes(1)
        expect(axios).toHaveBeenCalledWith({
          params: {
            page: 1,
            session_id: '17',
            sort: 'session:asc'
          },
          url: 'https://former-app/api/v0/jeux.php'
        })
      })
    })

    describe('with all parameters', () => {
      it('fetches games from API with all other parameters', async () => {
        const wrapper = createWrapperWithParams({
          propsData,
          data: () => ({
            query: 'rutipa',
            selectedSoftware: 'RPG Maker 2003',
            selectedSession: '15',
            selectedSort: 'title'
          })
        })

        await wrapper.vm.fetchGames()

        expect(axios).toHaveBeenCalledTimes(1)
        expect(axios).toHaveBeenCalledWith({
          params: {
            page: 1,
            q: 'rutipa',
            software: 'RPG Maker 2003',
            session_id: '15',
            sort: 'title:asc'
          },
          url: 'https://former-app/api/v0/jeux.php'
        })
      })
    })
  })

  describe('.search()', () => {
    it('resets page to 1 and fetches games', async () => {
      const fetchGames = jest.fn()
      const wrapper = shallowMount(Games, {
        propsData,
        data: () => ({ page: 3 }),
        methods: {
          fetchGames
        }
      })

      await wrapper.vm.search()

      expect(fetchGames).toHaveBeenCalled()
      expect(wrapper.vm.$data.page).toEqual(1)
    })
  })

  describe('.previousPage()', () => {
    it('decreases page to 2, fetches games and scrolls window', async () => {
      const fetchGames = jest.fn()
      const wrapper = shallowMount(Games, {
        propsData,
        data: () => ({ page: 3 }),
        methods: {
          fetchGames
        }
      })

      await wrapper.vm.previousPage()

      expect(fetchGames).toHaveBeenCalled()
      expect(wrapper.vm.$data.page).toEqual(2)
      expect(windowScroll).toHaveBeenCalledWith(0, 0)
    })
  })

  describe('.nextPage()', () => {
    it('increases page to 4, fetches games and scrolls window', async () => {
      const fetchGames = jest.fn()
      const wrapper = shallowMount(Games, {
        propsData,
        data: () => ({ page: 3 }),
        methods: {
          fetchGames
        }
      })

      await wrapper.vm.nextPage()

      expect(fetchGames).toHaveBeenCalled()
      expect(wrapper.vm.$data.page).toEqual(4)
      expect(windowScroll).toHaveBeenCalledWith(0, 0)
    })
  })

  describe('.sessionName(id)', () => {
    it('returns the accurate session name', () => {
      const wrapper = shallowMount(Games, {
        propsData
      })

      expect(wrapper.vm.sessionName(3)).toEqual('Session 2003-2004')
      expect(wrapper.vm.sessionName(17)).toEqual('Session 2017-2018')
      expect(wrapper.vm.sessionName(19)).toEqual('Session 2019')
    })
  })

  describe('.formatGameForList(gameDto)', () => {
    it('returns the accurate game object', () => {
      const gameDto = {
        id: 1,
        title: 'Adventure of Lolo',
        authors: [
          {
            id: 25,
            username: 'Jack'
          }
        ],
        session: {
          id: '17',
          name: 'Session 2017'
        },
        software: 'RPG Maker 2003',
        genre: 'Adventure',
        creation_group: 'IndieDev Team',
        screenshots: [{ url: 'img.jpg' }],
        download_links: [{ url: 'img.jpg', platform: 'windows' }]
      }

      const wrapper = shallowMount(Games, {
        propsData
      })

      const game = wrapper.vm.formatGameForList(gameDto)

      expect(game).toEqual({
        id: 1,
        title: 'Adventure of Lolo',
        authors: [
          {
            id: 25,
            username: 'Jack'
          }
        ],
        session: {
          id: '17',
          name: 'Session 2017'
        },
        software: 'RPG Maker 2003',
        genre: 'Adventure',
        creationGroup: 'IndieDev Team',
        screenshots: [{ url: 'img.jpg' }],
        downloadLinks: [{ url: 'img.jpg', platform: 'windows' }]
      })
    })
  })
})