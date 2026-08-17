/* global window */

import axios from 'axios'
import GamesList from '@/components/games/GamesList.vue'
import { flushPromises, shallowMount } from '@vue/test-utils'
import AxiosMockAdapter from 'axios-mock-adapter'

describe('GamesList', () => {
  const axiosMock = new AxiosMockAdapter(axios)

  const windowScroll = vi.fn()
  window.scrollTo = windowScroll

  const propsData = {
    initSession: null
  }

  beforeEach(async () => {
    axiosMock.reset()
  })

  describe('When component is mounted', () => {
    beforeEach(async () => {
      await mockGamesRequest({
        meta: {
          current_page: 2,
          last_page: 5,
          total: 10
        },
        data: [{ title: 'game-3', awards: [] }, { title: 'game-4', awards: [] }]
      })
    })

    it('stores information in component', async () => {
      // When
      const wrapper = shallowMount(GamesList, { propsData })
      await flushPromises()

      // Then
      expect(wrapper.vm.games).toEqual([
        { title: 'game-3', awards: [] },
        { title: 'game-4', awards: [] }
      ])
      expect(wrapper.vm.page).toEqual(2)
      expect(wrapper.vm.totalPagesCount).toEqual(5)
      expect(wrapper.vm.totalResultsCount).toEqual(10)
      expect(wrapper.vm.resultsCountOnThisPage).toEqual(2)
    })

    describe('without parameters', () => {
      it('fetches games from API (page 1 and default sort)', async () => {
        // When
        shallowMount(GamesList, { propsData })
        await flushPromises()

        // Then
        expect(axiosMock.history.get.length).toBe(1)
        expect(axiosMock.history.get[0].params).toEqual({
          page: 1,
          sort: 'awards_count:desc,title:asc'
        })
      })
    })

    describe('with a session props', () => {
      it('fetches games from API with page, session and session sort parameters', async () => {
        // When
        shallowMount(GamesList, { propsData: { initSession: 17 } })
        await flushPromises()

        // Then
        expect(axiosMock.history.get.length).toBe(1)
        expect(axiosMock.history.get[0].params).toEqual({
          page: 1,
          session_id: 17,
          sort: 'awards_count:desc,title:asc'
        })
      })
    })

    describe('with all parameters', () => {
      it('fetches games from API with all other parameters', async () => {
        const wrapper = shallowMount(GamesList, { propsData })
        await flushPromises()

        await mockGamesRequest({
          meta: {
            current_page: 2,
            last_page: 5,
            total: 10
          },
          data: [{ title: 'game-3', awards: [] }, { title: 'game-4', awards: [] }]
        })

        // TODO: Simulate user interaction instead of setting data directly
        wrapper.vm.query = 'rutipa'
        wrapper.vm.selectedSort = 'title'
        wrapper.vm.sortDirection = 'asc'
        wrapper.vm.selectedSession = '15'
        wrapper.vm.withDownloadLinks = true
        wrapper.vm.selectedSoftware = 'RPG Maker 2003'

        // When
        await wrapper.vm.fetchGames()

        // Then
        expect(axiosMock.history.get.length).toBe(2)
        expect(axiosMock.history.get[1].params).toEqual({
          page: 2,
          q: 'rutipa',
          software: 'RPG Maker 2003',
          session_id: '15',
          sort: 'title:asc',
          download_links: 'any'
        })
      })
    })
  })

  describe('Page buttons', () => {
    describe('when there is only one page', () => {
      it('has no navigation bar', async () => {
        await mockGamesRequest({
          meta: {
            current_page: 1,
            last_page: 1,
            total: 0
          },
          data: []
        })

        // When
        const wrapper = shallowMount(GamesList, { propsData })
        await flushPromises()

        // Then
        expect(wrapper.find('ul.pagination').exists()).toEqual(false)
      })
    })

    describe('when current page is the first and not the last', () => {
      it('disables previous button', async () => {
        mockGamesRequest({
          meta: {
            current_page: 1,
            last_page: 10,
            total: 200
          },
          data: []
        })

        // When
        const wrapper = shallowMount(GamesList, {
          propsData
        })
        await flushPromises()

        // Then
        expect(wrapper.find('.previous').classes('disabled')).toEqual(true)
        expect(wrapper.find('.next').classes('disabled')).toEqual(false)
      })
    })

    describe('when current page is neither the first nor the last', () => {
      it('does not disable buttons', async () => {
        mockGamesRequest({
          meta: {
            current_page: 5,
            last_page: 10,
            total: 200
          },
          data: []
        })

        // When
        const wrapper = shallowMount(GamesList, {
          propsData
        })
        await flushPromises()

        // Then
        expect(wrapper.find('.previous').classes('disabled')).toEqual(false)
        expect(wrapper.find('.next').classes('disabled')).toEqual(false)
      })
    })

    describe('when current page is the last and not the first', () => {
      it('disables next button', async () => {
        mockGamesRequest({
          meta: {
            current_page: 10,
            last_page: 10,
            total: 200
          },
          data: []
        })

        // When
        const wrapper = shallowMount(GamesList, {
          propsData
        })
        await flushPromises()

        // Then
        expect(wrapper.find('.previous').classes('disabled')).toEqual(false)
        expect(wrapper.find('.next').classes('disabled')).toEqual(true)
      })
    })
  })

  describe('.gamesCount()', () => {
    describe('when there is only one page of results', () => {
      it('only displays the number of games on this page', async () => {
        mockGamesRequest({
          meta: {
            current_page: 1,
            last_page: 1,
            total: 2
          },
          data: [{ title: 'game-1', awards: [] }, { title: 'game-2', awards: [] }]
        })

        // When
        const wrapper = shallowMount(GamesList, {
          propsData
        })
        await flushPromises()

        // Then
        expect(wrapper.vm.gamesCount).toEqual('2')
      })
    })

    describe('when there is more than one page', () => {
      it('displays the number of games on this page and the total on every pages', async () => {
        mockGamesRequest({
          meta: {
            current_page: 2,
            last_page: 4,
            total: 8
          },
          data: [{ title: 'game-3', awards: [] }, { title: 'game-4', awards: [] }]
        })

        // When
        const wrapper = shallowMount(GamesList, {
          propsData
        })
        await flushPromises()

        // Then
        expect(wrapper.vm.gamesCount).toEqual('2 sur 8')
      })
    })
  })

  describe('.search()', () => {
    it('resets page to 1 and fetches games', async () => {
      await mockGamesRequest({
        meta: {
          current_page: 3,
          last_page: 3,
          total: 6
        },
        data: [{ title: 'game-5', awards: [] }, { title: 'game-6', awards: [] }]
      })

      const wrapper = shallowMount(GamesList, {
        propsData
      })
      await flushPromises()

      // When
      await mockGamesRequest({
        meta: {
          current_page: 1,
          last_page: 3,
          total: 6
        },
        data: [{ title: 'game-1', awards: [] }, { title: 'game-2', awards: [] }]
      })
      await wrapper.vm.search()

      // Then
      expect(wrapper.vm.page).toEqual(1)
    })
  })

  describe('.previousPage()', () => {
    it('decreases page to 2, fetches games and scrolls window', async () => {
      await mockGamesRequest({
        meta: {
          current_page: 3,
          last_page: 3,
          total: 6
        },
        data: [{ title: 'game-5', awards: [] }, { title: 'game-6', awards: [] }]
      })

      const wrapper = shallowMount(GamesList, {
        propsData
      })

      // When
      await mockGamesRequest({
        meta: {
          current_page: 2,
          last_page: 3,
          total: 6
        },
        data: [{ title: 'game-3', awards: [] }, { title: 'game-4', awards: [] }]
      })
      await wrapper.vm.previousPage()

      // Then
      expect(wrapper.vm.page).toEqual(2)
      expect(windowScroll).toHaveBeenCalledWith(0, 0)
    })
  })

  describe('.nextPage()', () => {
    it('increases page to 4, fetches games and scrolls window', async () => {
      await mockGamesRequest({
        meta: {
          current_page: 3,
          last_page: 4,
          total: 8
        },
        data: [{ title: 'game-5', awards: [] }, { title: 'game-6', awards: [] }]
      })

      const wrapper = shallowMount(GamesList, {
        propsData
      })

      // When
      await mockGamesRequest({
        meta: {
          current_page: 4,
          last_page: 4,
          total: 8
        },
        data: [{ title: 'game-7', awards: [] }, { title: 'game-8', awards: [] }]
      })
      await wrapper.vm.nextPage()

      // Then
      expect(wrapper.vm.page).toEqual(4)
      expect(windowScroll).toHaveBeenCalledWith(0, 0)
    })
  })

  describe('.sortBy(sortParam)', () => {
    beforeEach(async () => {
      await mockGamesRequest({
        meta: {
          current_page: 2,
          last_page: 5,
          total: 10
        },
        data: [{ title: 'game-3', awards: [] }, { title: 'game-4', awards: [] }]
      })
    })

    it('sorts by title in ascending direction', async () => {
      const wrapper = shallowMount(GamesList, { propsData })
      await flushPromises()

      // TODO: Simulate user interaction instead of setting data directly
      wrapper.vm.selectedSort = 'session'
      wrapper.vm.sortDirection = 'asc'

      // When
      await mockGamesRequest({
        meta: {
          current_page: 2,
          last_page: 5,
          total: 10
        },
        data: [{ title: 'game-3', awards: [] }, { title: 'game-4', awards: [] }]
      })
      await wrapper.vm.sortBy('title')

      // Then
      expect(axiosMock.history.get.length).toBe(2)
      expect(axiosMock.history.get[1].params.sort).toEqual('title:asc')
    })

    it('sorts by title in descending direction', async () => {
      const wrapper = shallowMount(GamesList, { propsData })
      await flushPromises()

      // TODO: Simulate user interaction instead of setting data directly
      wrapper.vm.selectedSort = 'title'
      wrapper.vm.sortDirection = 'asc'

      // When
      await mockGamesRequest({
        meta: {
          current_page: 2,
          last_page: 5,
          total: 10
        },
        data: [{ title: 'game-4', awards: [] }, { title: 'game-3', awards: [] }]
      })
      await wrapper.vm.sortBy('title')

      // Then
      expect(axiosMock.history.get.length).toBe(2)
      expect(axiosMock.history.get[1].params.sort).toEqual('title:desc')
    })
  })

  describe('.sessionName(id)', () => {
    beforeEach(async () => {
      await mockGamesRequest({
        meta: {
          current_page: 1,
          last_page: 1,
          total: 0
        },
        data: []
      })
    })

    it.each([
      { input: '3', expected: 'Session 2003-2004' },
      { input: '17', expected: 'Session 2017-2018' },
      { input: '19', expected: 'Session 2019' },
      { input: '23', expected: 'Session 2023-2024' },
      { input: '25', expected: 'Session 2025-2026' },
      { input: '26', expected: 'Session 2026-2027' }
    ])('session $input is named $expected', ({ input, expected }) => {
      const wrapper = shallowMount(GamesList, {
        propsData
      })

      expect(wrapper.vm.sessionName(input)).toBe(expected)
    })
  })

  describe('.formatGameForList(gameDto)', () => {
    beforeEach(async () => {
      await mockGamesRequest({
        meta: {
          current_page: 1,
          last_page: 1,
          total: 0
        },
        data: []
      })
    })

    it('returns the accurate game object', () => {
      const wrapper = shallowMount(GamesList, {
        propsData
      })

      // When
      const game = wrapper.vm.formatGameForList({
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
            category_name: 'Alex d\'or'
          },
          {
            status: 'nominated',
            award_level: null,
            category_name: 'Alex du gameplay'
          }
        ]
      })

      // Then
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
            awardLevel: null,
            categoryName: 'Alex d\'or'
          },
          {
            status: 'nominated',
            awardLevel: null,
            categoryName: 'Alex du gameplay'
          }
        ]
      })
    })
  })

  function mockGamesRequest(responseMock = {}) {
    return axiosMock
      .onGet('/api/v0/games')
      .replyOnce(200, responseMock)
  }
})
