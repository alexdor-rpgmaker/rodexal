<template>
  <div id="games-list">
    <div class="container">
      <form @submit.prevent="search" class="games-form">
        <div class="row">
          <div class="col">
            <div class="row gy-2">
              <div class="col-md-6">
                <label for="session" class="form-label">Session</label>
                <select id="session" name="session" class="form-select" v-model="selectedSession">
                  <option :value="null">(Toutes les sessions)</option>
                  <option
                      :value="session"
                      v-for="session in sessions"
                      :key="session"
                  >{{ sessionName(session) }}
                  </option>
                </select>
              </div>

              <div class="col-md-6">
                <label for="software" class="form-label">Logiciels</label>
                <select id="software" name="software" class="form-select" v-model="selectedSoftware">
                  <option :value="null">(Tous les logiciels)</option>
                  <option :value="software" v-for="software in softwares" :key="software">{{ software }}</option>
                </select>
              </div>

              <div class="col-md-12">
                <label for="query" class="form-label">Recherche</label>
                <input
                    id="query"
                    name="query"
                    class="form-control"
                    type="text"
                    v-model="query"
                    placeholder="Aventure, Humour, RuTiPa's Quest, ..."
                />
              </div>
            </div>
          </div>
          <div class="col gy-3">
            <div class="form-check pt-4 mb-5">
              <input
                  id="download-links"
                  name="download-links"
                  class="form-check-input"
                  type="checkbox"
                  v-model="withDownloadLinks"
              />
              <label for="download-links" class="form-check-label">Avec lien de téléchargement</label>
            </div>

            <button class="bouton" type="submit">Rechercher</button>
          </div>
        </div>

      </form>

      <p class="mb-4">Nombre de jeux : <strong>{{ gamesCount }}</strong>.</p>
    </div>

    <table class="table">
      <tbody>
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
            v-for="game in games"
            :game="game"
            :key="game.id"
        />
      </tbody>
    </table>

    <nav aria-label="Pagination de la liste des jeux" v-if="totalPagesCount > 1">
      <ul class="pagination justify-content-center mt-4 mb-4">
        <li class="page-item previous" :class="{disabled: page === 1}">
          <a class="page-link" href="#" @click.prevent="previousPage" tabindex="-1">Précédente</a>
        </li>
        <li v-for="index in totalPagesCount" :key="index" class="page-item" :class="{active: index === page}">
          <template v-if="index === page">
            <a class="page-link">
              {{ index }}
              <span class="visually-hidden">(current)</span>
            </a>
          </template>
          <template v-else>
            <a class="page-link" href="#" @click.prevent="goToPage(index)">
              {{ index }}
            </a>
          </template>
        </li>
        <li class="page-item next" :class="{disabled: lastPage}">
          <a class="page-link" href="#" @click.prevent="nextPage">Suivante</a>
        </li>
      </ul>
    </nav>
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
      type: Number,
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
      selectedSort: 'awards_count',
      withDownloadLinks: false,
      sortDirection: 'desc',
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
        20,
        21,
        22,
        23,
        25
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
        'RPG m\'écoeure 2003',
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
    lastPage() {
      return this.page === this.totalPagesCount
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
        page: this.page
      }
      if (this.query) {
        params.q = this.query
      }
      if (this.selectedSoftware) {
        params.software = this.selectedSoftware
      }
      if (this.selectedSession) {
        params.session_id = this.selectedSession
      }
      if (this.withDownloadLinks) {
        params.download_links = 'any'
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

      this.page = request.data.meta.current_page
      this.totalPagesCount = request.data.meta.last_page
      this.totalResultsCount = request.data.meta.total
      this.resultsCountOnThisPage = request.data.data.length

      this.games = request.data.data.map(this.formatGameForList)
    },
    async search() {
      this.page = 1
      await this.fetchGames()
    },
    async goToPage(page) {
      window.scrollTo(0, 0)
      this.page = page
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
      if (this.selectedSort === sortParam) {
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

  .pagination {
    .page-item {
      .page-link {
        color: #d39501;
      }

      &.active {
        .page-link {
          color: white;
          border-color: #d39501;
          background-color: #d39501;
        }
      }

      //noinspection CssUnusedSymbol
      &.disabled {
        .page-link {
          color: #6c757d;
        }
      }
    }
  }
}
</style>
