<template>
  <div id="games-list">
    <div id="games-form">
      <form @submit.prevent="search">
        <div class="form-row">
          <div class="col-md-6 mb-2">
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

          <div class="col-md-6 mb-2">
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
        </div>

        <div class="form-row">
          <div class="col-md-6 mb-2">
            <label for="software">Logiciels</label>
            <select id="software" name="software" class="custom-select" v-model="selectedSoftware">
              <option :value="null">(Tous les logiciels)</option>
              <option :value="software" v-for="software in softwares" :key="software">{{ software }}</option>
            </select>
          </div>

          <div class="col-md-6 mb-2">
            <label for="sort">Trier par</label>
            <select id="sort" name="sort" class="custom-select" v-model="selectedSort">
              <option :value="key" v-for="(name, key) in sortings" :key="key">{{ name }}</option>
            </select>
          </div>
        </div>

        <button class="btn btn-primary mt-4" type="submit">Rechercher</button>
      </form>
    </div>
    <p>{{ resultsCount }}.</p>
    <table class="table">
      <tr class="tableau_legend">
        <th></th>
        <th>Nom du Jeu</th>
        <th>Session</th>
        <th class="author">Auteur(s)</th>
        <th class="software">Support</th>
        <th class="genre">Genre</th>
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
    session: String
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
      sortings: {
        title: 'Titre',
        session: 'Session',
        software: 'Support',
        genre: 'Genre',
        created_at: "Date d'inscription",
        size: 'Taille'
      },
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
    resultsCount() {
      if (this.resultsCountOnThisPage === 0) {
        return 'Aucun résultat'
      } else if (this.resultsCountOnThisPage === 1) {
        return '1 résultat'
      } else if (this.resultsCountOnThisPage === this.totalResultsCount) {
        return `${this.resultsCountOnThisPage} résultats`
      }

      return `${this.resultsCountOnThisPage} résultats sur ${this.totalResultsCount}`
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
        params.sort = `${this.selectedSort}:asc`

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
        downloadLinks: gameDto.download_links
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#games-list {
  text-align: center;

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
