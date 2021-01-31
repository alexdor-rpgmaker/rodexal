import GameRow from '~/resources/js/components/GameRow.vue'
import { shallowMount } from '@vue/test-utils'

describe('GameRow', () => {
  const propsData = {
    game: {
      id: 15,
      title: 'Adventure of RuTiPa',
      authors: [
        {
          id: 25,
          rank: 'juror',
          username: 'Jack',
          role: 'Programmer'
        },
        {
          id: null,
          rank: null,
          username: 'Jones',
          role: 'Musician'
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
      downloadLinks: [
        { url: 'my-game-mac.zip', platform: 'mac' },
        { url: 'my-game-windows.zip', platform: 'windows' }
      ],
      awards: [
        {
          status: 'awarded',
          award_level: 'gold',
          category_name: 'Alex'
        },
        {
          status: 'awarded',
          award_level: 'bronze',
          category_name: 'Ambiance'
        },
        {
          status: 'nominated',
          award_level: null,
          category_name: 'Meilleurs graphismes'
        }
      ]
    }
  }

  describe('With basic data', () => {
    let wrapper

    beforeEach(() => {
      wrapper = shallowMount(GameRow, {
        propsData
      })
    })

    it('displays a game screnshot with a link', () => {
      const gameScreenshotLink = wrapper.find('.screenshot-link')
      expect(gameScreenshotLink.exists()).toEqual(true)
      expect(gameScreenshotLink.attributes('href')).toEqual(
        'https://former-app/?p=jeu&id=15'
      )
      expect(wrapper.find('.screenshot').exists()).toEqual(true)
    })

    it('displays the title of the game with a link', () => {
      const gameTitleLink = wrapper.find('.title-link')
      expect(gameTitleLink.exists()).toEqual(true)
      expect(gameTitleLink.attributes('href')).toEqual(
        'https://former-app/?p=jeu&id=15'
      )
      expect(gameTitleLink.text()).toEqual('Adventure of RuTiPa')
    })

    it('displays the session name', () => {
      expect(wrapper.find('.session').text()).toEqual('Session 2017')
    })

    it('displays the software', () => {
      expect(wrapper.find('.software').text()).toEqual('RPG Maker 2003')
    })

    it('displays the genre', () => {
      expect(wrapper.find('.genre').text()).toEqual('Adventure')
    })

    it('displays the creation group along with the authors', () => {
      const gameMakers = wrapper.find('.makers')
      expect(gameMakers.text()).toEqual('IndieDev Team : Jack, Jones')
      const linksHref = gameMakers
        .findAll('a')
        .wrappers.map(a => a.attributes('href'))
      expect(linksHref).toEqual([
        'https://former-app/?p=profil&membre=25'
      ])
    })

    it('displays the download links', () => {
      const downloadLinks = wrapper.find('.download-links')
      expect(downloadLinks.text()).toEqual('(Mac) (Win)')
      const imageSource = downloadLinks.find('img').attributes('src')
      expect(imageSource).toEqual(
        'https://former-app/design/divers/disquette-verte.gif'
      )
    })

    it('displays the awards', () => {
      const awardedCategories = wrapper.find('.awarded-categories')
      const nominatedCategories = wrapper.find('.nominated-categories')

      expect(awardedCategories.text()).toEqual('Victoire : Alex (or), Ambiance (bronze)')
      expect(nominatedCategories.text()).toEqual(
        'Nominations : Meilleurs graphismes'
      )
    })
  })

  describe('When there is no screenshot', () => {
    it('does not display an image', () => {
      const propsDataWithoutScreenshots = { ...propsData }
      propsDataWithoutScreenshots.game.screenshots = []
      const wrapper = shallowMount(GameRow, {
        propsData: propsDataWithoutScreenshots
      })

      expect(wrapper.find('.screenshot').exists()).toEqual(false)
    })
  })

  describe('When there is no creation group', () => {
    it('only displays the authors', () => {
      const propsDataWithoutGroup = { ...propsData }
      propsDataWithoutGroup.game.creationGroup = null
      const wrapper = shallowMount(GameRow, {
        propsData: propsDataWithoutGroup
      })

      const gameMakers = wrapper.find('.makers')
      expect(gameMakers.text()).toEqual('Jack, Jones')
    })
  })

  describe('When there is no awards', () => {
    it('does not display awards', () => {
      const propsDataWithoutGroup = { ...propsData }
      propsDataWithoutGroup.game.awards = []
      const wrapper = shallowMount(GameRow, {
        propsData: propsDataWithoutGroup
      })

      const awardedCategories = wrapper.find('.awarded-categories')
      const nominatedCategories = wrapper.find('.nominated-categories')

      expect(awardedCategories.exists()).toEqual(false)
      expect(nominatedCategories.exists()).toEqual(false)
    })
  })
})
