<template>
  <div id="games-list">
    <div class="container">
      <div class="row justify-content-center">
        <div id="games-wrapper" class="col-md-12">
          <form @submit.prevent="search" class="games-form">
            <div class="form-row">
              <div class="col-md-4 mb-2">
                <label for="query">Recherche</label>
                <input
                  id="query"
                  name="query"
                  class="form-control"
                  type="text"
                  v-model="query"
                  placeholder="Aventure, Humour, RuTiPa's Quest, ..."
                />
              </div>

              <div class="col-md-3 mb-2">
                <label for="session">Session</label>
                <select id="session" name="session" class="custom-select" v-model="selectedSession">
                  <option :value="null">(Toutes les sessions)</option>
                  <option
                    :value="session"
                    v-for="session in sessions"
                    :key="session"
                  >{{ sessionName(session) }}</option>
                </select>
              </div>

              <div class="col-md-3 mb-2">
                <label for="software">Logiciels</label>
                <select id="software" name="software" class="custom-select" v-model="selectedSoftware">
                  <option :value="null">(Tous les logiciels)</option>
                  <option :value="software" v-for="software in softwares" :key="software">{{ software }}</option>
                </select>
              </div>

              <div class="col-md-1 form-button-wrapper">
                <button class="bouton" type="submit">Rechercher</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <p class="mb-4">Nombre de jeux : <strong>{{ gamesCount }}</strong>.</p>
    </div>
    <table class="table">
      <tr class="tableau_legend">
        <th></th>
        <th class="title" @click="sortBy('title')">Titre du Jeu</th>
        <th class="session" @click="sortBy('session')">Session</th>
        <th class="author">Auteur(s)</th>
        <th class="software" @click="sortBy('software')">Support</th>
        <th class="genre" @click="sortBy('genre')">Genre</th>
        <th class="download">Téléch.</th>
      </tr>
      <game-row
        class="tr"
        :game="game"
        v-for="game in games"
        :key="game.id"
      />
    </table>
    <button
      v-if="page > 1"
      @click="previousPage"
      class="previous btn btn-primary mt-4"
      type="button"
    >Résultats précédents</button>
    <button
      v-if="notLastPage"
      @click="nextPage"
      class="next btn btn-primary mt-4"
      type="button"
    >Résultats suivants</button>
  </div>
</template>

<script>
import GameRow from './GameRow.vue'

export default {
  components: {
    GameRow
  },
  props: {
    session: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      games: [],
      query: null,
      page: 1,
      totalPagesCount: 1,
      resultsCountOnThisPage: null,
      totalResultsCount: null,
      selectedSoftware: null,
      selectedSession: null,
      selectedSort: 'session',
      sortDirection: 'asc',
      sessions: [
        1,
        2,
        3,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        19,
        20
      ],
      softwares: [
        'adventure game studio',
        'AINSI',
        'Autre',
        'Clickteam Fusion 2.5',
        'Game Maker Studio 2',
        'Geex / Rpg Maker Xp',
        'Klik & Game',
        'MMF2',
        'MPEG-1/2 Audio Layer',
        'Mugen',
        'Multimedia Fusion 2',
        'RMVX',
        "RPG m'écoeure 2003",
        'RPG maker',
        'RPG Maker 2000',
        'RPG Maker 2003',
        'Rpg maker 95',
        'RPG Maker MV',
        'RPG Maker VX',
        'RPG Maker VX Ace',
        'RPG Maker XP',
        'The Games Factory 1.06',
        'Unity'
      ]
    }
  },
  async mounted() {
    if (this.session) {
      this.selectedSession = this.session
    }
    await this.fetchGames()
  },
  computed: {
    notLastPage() {
      return this.page < this.totalPagesCount
    },
    gamesCount() {
      if (this.resultsCountOnThisPage === this.totalResultsCount) {
        return this.resultsCountOnThisPage
      }

      return `${this.resultsCountOnThisPage} sur ${this.totalResultsCount}`
    }
  },
  methods: {
    async fetchGames() {
      const params = {
        page: this.page,
        sort: 'title:asc'
      }
      if (this.query) {
        params['q'] = this.query
      }
      if (this.selectedSoftware) {
        params['software'] = this.selectedSoftware
      }
      if (this.selectedSession) {
        params['session_id'] = this.selectedSession
      }
      if (this.selectedSort) {
        params.sort = `${this.selectedSort}:${this.sortDirection}`

        if (this.selectedSort !== 'title') {
          params.sort += ',title:asc'
        }
      }

      const request = await axios({
        url: '/api/v0/games',
        params
      })

      this.page = request.data.current_page
      this.totalPagesCount = request.data.last_page
      this.totalResultsCount = request.data.total
      this.resultsCountOnThisPage = request.data.data.length

      this.games = request.data.data.map(this.formatGameForList)
    },
    async search() {
      this.page = 1
      await this.fetchGames()
    },
    async previousPage() {
      window.scrollTo(0, 0)
      this.page -= 1
      await this.fetchGames()
    },
    async nextPage() {
      window.scrollTo(0, 0)
      this.page += 1
      await this.fetchGames()
    },
    async sortBy(sortParam) {
      if(this.selectedSort === sortParam) {
        this.sortDirection = this.sortDirection !== 'asc' ? 'asc' : 'desc'
      }
      this.selectedSort = sortParam
      await this.search()
    },
    sessionName(id) {
      if (id === 3) return 'Session 2003-2004'
      else if (id === 17) return 'Session 2017-2018'

      return `Session ${id + 2000}`
    },
    formatGameForList(gameDto) {
      return {
        id: gameDto.id,
        title: gameDto.title,
        genre: gameDto.genre,
        authors: gameDto.authors,
        session: gameDto.session,
        software: gameDto.software,
        screenshots: gameDto.screenshots,
        description: gameDto.description,
        creationGroup: gameDto.creation_group,
        downloadLinks: gameDto.download_links,
        awards: gameDto.awards
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#games-list {
  .games-form {
    text-align: center;
  }

  .form-button-wrapper {
    padding-left: 20px;
    margin-top: 32px;
  }

  .title, .session, .software, .genre {
    cursor: pointer;
  }

  .author {
    width: 160px;
  }
  .software, .genre {
    width: 130px;
  }
  .download {
    width: 50px;
  }
}
</style>
