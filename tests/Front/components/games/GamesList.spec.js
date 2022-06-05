import axios from 'axios'
import GamesList from '~/resources/js/components/games/GamesList.vue'
import { shallowMount } from '@vue/test-utils'

jest.mock('axios')
global.axios = axios

const windowScroll = jest.fn()
window.scrollTo = windowScroll

describe('GamesList', () => {
  const propsData = {
    session: null
  }

  beforeEach(() => {
    const apiResponseBody = {
      meta: {},
      data: []
    }
    axios.mockResolvedValue({
      data: apiResponseBody
    })
  })

  describe('When component is mounted', () => {
    it('fetches games from API', () => {
      const fetchGames = jest.fn()
      shallowMount(GamesList, {
        propsData,
        methods: {
          fetchGames
        }
      })

      expect(fetchGames).toHaveBeenCalled()
    })
  })

  describe('Page buttons', () => {
    describe('when there is only one page', () => {
      it('has no navigation bar', () => {
        const wrapper = shallowMount(GamesList, {
          data: () => ({
            page: 1,
            totalPagesCount: 1
          })
        })

        expect(wrapper.find('ul.pagination').exists()).toEqual(false)
      })
    })

    describe('when current page is the first but not the last', () => {
      it('disables previous button', () => {
        const wrapper = shallowMount(GamesList, {
          data: () => ({
            page: 1,
            totalPagesCount: 10
          })
        })

        expect(wrapper.find('.previous').classes('disabled')).toEqual(true)
        expect(wrapper.find('.next').classes('disabled')).toEqual(false)
      })
    })

    describe('when current page is neither the first nor the last', () => {
      it('does not disable buttons', () => {
        const wrapper = shallowMount(GamesList, {
          data: () => ({
            page: 5,
            totalPagesCount: 10
          })
        })

        expect(wrapper.find('.previous').classes('disabled')).toEqual(false)
        expect(wrapper.find('.next').classes('disabled')).toEqual(false)
      })
    })

    describe('when current page is the last and not the first', () => {
      it('disables next button', () => {
        const wrapper = shallowMount(GamesList, {
          data: () => ({
            page: 10,
            totalPagesCount: 10
          })
        })

        expect(wrapper.find('.previous').classes('disabled')).toEqual(false)
        expect(wrapper.find('.next').classes('disabled')).toEqual(true)
      })
    })
  })

  describe('.gamesCount()', () => {
    describe('when results count equals total results count (less than two pages)', () => {
      it('displays X', () => {
        const wrapper = shallowMount(GamesList, {
          data: () => ({
            totalResultsCount: 2,
            resultsCountOnThisPage: 2
          })
        })

        expect(wrapper.vm.gamesCount).toEqual(2)
      })
    })

    describe('when results count does not equal total results count (more than one page)', () => {
      it('displays X sur Y', () => {
        const wrapper = shallowMount(GamesList, {
          data: () => ({
            totalResultsCount: 5,
            resultsCountOnThisPage: 2
          })
        })

        expect(wrapper.vm.gamesCount).toEqual('2 sur 5')
      })
    })
  })

  describe('.fetchGames()', () => {
    function createWrapperWithParams(params) {
      const wrapper = shallowMount(GamesList, params)
      const apiResponseBody = {
        meta: {
          current_page: 2,
          last_page: 5,
          total: 150
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
      expect(wrapper.vm.$data.resultsCountOnThisPage).toEqual(2)
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
            sort: 'awards_count:desc,title:asc'
          },
          url: '/api/v0/games'
        })
      })
    })

    describe('with a session props', () => {
      it('fetches games from API with page, session and session sort parameters', async () => {
        const wrapper = createWrapperWithParams({
          propsData: { ...propsData, session: 17 }
        })

        await wrapper.vm.fetchGames()

        expect(axios).toHaveBeenCalledTimes(1)
        expect(axios).toHaveBeenCalledWith({
          params: {
            page: 1,
            session_id: 17,
            sort: 'awards_count:desc,title:asc'
          },
          url: '/api/v0/games'
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
            selectedSort: 'title',
            sortDirection: 'asc',
            withDownloadLinks: true
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
            sort: 'title:asc',
            download_links: 'any'
          },
          url: '/api/v0/games'
        })
      })
    })
  })

  describe('.search()', () => {
    it('resets page to 1 and fetches games', async () => {
      const fetchGames = jest.fn()
      const wrapper = shallowMount(GamesList, {
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
      const wrapper = shallowMount(GamesList, {
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
      const wrapper = shallowMount(GamesList, {
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

  describe('.sortBy(sortParam)', () => {
    it('sorts by title in ascending direction', async () => {
      const search = jest.fn()
      const wrapper = shallowMount(GamesList, {
        propsData,
        data: () => ({ selectedSort: 'session', sortDirection: 'asc' }),
        methods: {
          search
        }
      })

      await wrapper.vm.sortBy('title')

      expect(search).toHaveBeenCalled()
      expect(wrapper.vm.$data.selectedSort).toEqual('title')
      expect(wrapper.vm.$data.sortDirection).toEqual('asc')
    })

    it('sorts by title in descending direction', async () => {
      const search = jest.fn()
      const wrapper = shallowMount(GamesList, {
        propsData,
        data: () => ({ selectedSort: 'title', sortDirection: 'asc' }),
        methods: {
          search
        }
      })

      await wrapper.vm.sortBy('title')

      expect(search).toHaveBeenCalled()
      expect(wrapper.vm.$data.selectedSort).toEqual('title')
      expect(wrapper.vm.$data.sortDirection).toEqual('desc')
    })
  })

  describe('.sessionName(id)', () => {
    it('returns the accurate session name', () => {
      const wrapper = shallowMount(GamesList, {
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
        download_links: [{ url: 'img.jpg', platform: 'windows' }],
        awards: [
          {
            status: 'awarded',
            award_level: null,
            category_name: "Alex d'or"
          },
          {
            status: 'nominated',
            award_level: null,
            category_name: 'Alex du gameplay'
          }
        ]
      }

      const wrapper = shallowMount(GamesList, {
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
        downloadLinks: [{ url: 'img.jpg', platform: 'windows' }],
        awards: [
          {
            status: 'awarded',
            award_level: null,
            category_name: "Alex d'or"
          },
          {
            status: 'nominated',
            award_level: null,
            category_name: 'Alex du gameplay'
          }
        ]
      })
    })
  })
})
