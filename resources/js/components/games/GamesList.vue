<template>
  <!--  TODO: Split this component into smaller components -->

  <div id="games-list">
    <div class="container">
      <form
        class="games-form"
        @submit.prevent="search"
      >
        <div class="row">
          <div class="col">
            <div class="row gy-2">
              <div class="col-md-6">
                <label
                  for="session"
                  class="form-label"
                >Session</label>
                <select
                  id="session"
                  v-model="selectedSession"
                  name="session"
                  class="form-select"
                >
                  <option :value="null">
                    (Toutes les sessions)
                  </option>
                  <option
                    v-for="session in sessions"
                    :key="session"
                    :value="session"
                  >
                    {{ sessionName(session) }}
                  </option>
                </select>
              </div>

              <div class="col-md-6">
                <label
                  for="software"
                  class="form-label"
                >Logiciels</label>
                <select
                  id="software"
                  v-model="selectedSoftware"
                  name="software"
                  class="form-select"
                >
                  <option :value="null">
                    (Tous les logiciels)
                  </option>
                  <option
                    v-for="software in softwares"
                    :key="software"
                    :value="software"
                  >
                    {{ software }}
                  </option>
                </select>
              </div>

              <div class="col-md-12">
                <label
                  for="query"
                  class="form-label"
                >Recherche</label>
                <input
                  id="query"
                  v-model="query"
                  name="query"
                  class="form-control"
                  type="text"
                  placeholder="Aventure, Humour, RuTiPa's Quest, ..."
                >
              </div>
            </div>
          </div>
          <div class="col gy-3">
            <div class="form-check pt-4 mb-5">
              <input
                id="download-links"
                v-model="withDownloadLinks"
                name="download-links"
                class="form-check-input"
                type="checkbox"
              >
              <label
                for="download-links"
                class="form-check-label"
              >Avec lien de téléchargement</label>
            </div>

            <button
              class="bouton"
              type="submit"
            >
              Rechercher
            </button>
          </div>
        </div>
      </form>

      <p class="mb-4">
        Nombre de jeux : <strong>{{ gamesCount }}</strong>.
      </p>
    </div>

    <table class="table">
      <tbody>
        <tr class="tableau_legend">
          <th />
          <th
            class="title"
            @click="sortBy('title')"
          >
            Titre du Jeu
          </th>
          <th
            class="session"
            @click="sortBy('session')"
          >
            Session
          </th>
          <th class="author">
            Auteur(s)
          </th>
          <th
            class="software"
            @click="sortBy('software')"
          >
            Support
          </th>
          <th
            class="genre"
            @click="sortBy('genre')"
          >
            Genre
          </th>
          <th class="download">
            Téléch.
          </th>
        </tr>
        <GameRow
          v-for="game in games"
          :key="game.id"
          :game="game"
        />
      </tbody>
    </table>

    <nav
      v-if="totalPagesCount > 1"
      aria-label="Pagination de la liste des jeux"
    >
      <ul class="pagination justify-content-center mt-4 mb-4">
        <li
          class="page-item previous"
          :class="{disabled: page === 1}"
        >
          <a
            class="page-link"
            href="#"
            tabindex="-1"
            @click.prevent="previousPage"
          >Précédente</a>
        </li>
        <li
          v-for="index in totalPagesCount"
          :key="index"
          class="page-item"
          :class="{active: index === page}"
        >
          <template v-if="index === page">
            <a class="page-link">
              {{ index }}
              <span class="visually-hidden">(current)</span>
            </a>
          </template>
          <template v-else>
            <a
              class="page-link"
              href="#"
              @click.prevent="goToPage(index)"
            >
              {{ index }}
            </a>
          </template>
        </li>
        <li
          class="page-item next"
          :class="{disabled: lastPage}"
        >
          <a
            class="page-link"
            href="#"
            @click.prevent="nextPage"
          >Suivante</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'
import GameRow from './GameRow.vue'
import sessionNamePart from '@/session-name'

const props = defineProps({
  initSession: {
    type: Number,
    required: false,
    default: null
  }
})

const games = ref([])
const query = ref(null)
const page = ref(1)
const totalPagesCount = ref(1)
const resultsCountOnThisPage = ref(null)
const totalResultsCount = ref(null)
const selectedSoftware = ref(null)
const selectedSession = ref(null)
const selectedSort = ref('awards_count')
const withDownloadLinks = ref(false)
const sortDirection = ref('desc')
const sessions = [
  1, 2, 3, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 19, 20, 21, 22, 23, 25, 26
]
const softwares = [
  'adventure game studio', 'AINSI', 'Autre', 'Clickteam Fusion 2.5', 'Game Maker Studio 2',
  'Geex / Rpg Maker Xp', 'Klik & Game', 'MMF2', 'MPEG-1/2 Audio Layer', 'Mugen',
  'Multimedia Fusion 2', 'RMVX', 'RPG m\'écoeure 2003', 'RPG maker', 'RPG Maker 2000',
  'RPG Maker 2003', 'Rpg maker 95', 'RPG Maker MV', 'RPG Maker MZ', 'RPG Maker VX',
  'RPG Maker VX Ace', 'RPG Maker XP', 'The Games Factory 1.06', 'Unity'
]

const lastPage = computed(() => page.value === totalPagesCount.value)
const gamesCount = computed(() => {
  if (resultsCountOnThisPage.value === totalResultsCount.value) {
    return String(resultsCountOnThisPage.value)
  }

  return `${resultsCountOnThisPage.value} sur ${totalResultsCount.value}`
})

onMounted(async () => {
  if (props.initSession) {
    selectedSession.value = props.initSession
  }
  await fetchGames()
})

async function fetchGames() {
  const params = { page: page.value }
  if (query.value) params.q = query.value
  if (selectedSoftware.value) params.software = selectedSoftware.value
  if (selectedSession.value) params.session_id = selectedSession.value
  if (withDownloadLinks.value) params.download_links = 'any'
  if (selectedSort.value) {
    params.sort = `${selectedSort.value}:${sortDirection.value}`
    if (selectedSort.value !== 'title') {
      params.sort += ',title:asc'
    }
  }
  const request = await axios({ url: '/api/v0/games', params })
  page.value = request.data.meta.current_page
  totalPagesCount.value = request.data.meta.last_page
  totalResultsCount.value = request.data.meta.total
  resultsCountOnThisPage.value = request.data.data.length
  games.value = request.data.data.map(formatGameForList)
}

async function search() {
  page.value = 1
  await fetchGames()
}

async function goToPage(newPage) {
  window.scrollTo(0, 0)
  page.value = newPage
  await fetchGames()
}

async function previousPage() {
  window.scrollTo(0, 0)
  page.value -= 1
  await fetchGames()
}

async function nextPage() {
  window.scrollTo(0, 0)
  page.value += 1
  await fetchGames()
}

async function sortBy(sortParam) {
  if (selectedSort.value === sortParam) {
    sortDirection.value = sortDirection.value !== 'asc' ? 'asc' : 'desc'
  }
  selectedSort.value = sortParam
  await search()
}

function sessionName(id) {
  return `Session ${sessionNamePart(id)}`
}

function formatGameForList(gameDto) {
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
    awards: gameDto.awards.map(
      (award) => ({
        status: award.status,
        awardLevel: award.award_level,
        categoryName: award.category_name
      })
    )
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

      &.disabled {
        .page-link {
          color: #6c757d;
        }
      }
    }
  }
}
</style>
