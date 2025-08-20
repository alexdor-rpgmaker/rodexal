<template>
  <div
    v-if="scriptLoaded"
    id="jukebox"
  >
    <select
      ref="musicSelector"
      class="music-selector"
      @change="changeMusicFromSelector"
    >
      <option
        selected="selected"
        :value="selectorHeading"
      >
        --- Choix de la musique ---
      </option>
      <option
        v-for="(music, index) in musics"
        :key="index"
        :value="index"
      >
        {{ music.title }}
      </option>
    </select>
    <div class="current-music-player">
      <div class="song-slider">
        <span class="amplitude-current-time" />

        <input
          type="range"
          value="0"
          class="slider amplitude-song-slider"
          @input="changeSongPlayedPercentage"
        >

        <span
          v-if="playOrPause"
          class="current-music-time"
        >{{ currentMusicDuration }}</span>
        <span v-else>00:00</span>
      </div>

      <div class="commands">
        <div class="repeat-shuffle">
          <a
            class="command repeat"
            title="Répéter"
            :class="{ active: repeat.value }"
            @click="toggleRepeat"
          >
            <i class="fas fa-redo-alt" />
          </a>
          <a
            class="command shuffle"
            title="Aléatoire"
            :class="{ active: shuffle.value }"
            @click="toggleShuffle"
          >
            <i class="fas fa-random" />
          </a>
        </div>

        <div class="main-commands">
          <a
            class="command prev"
            title="Précédent"
            @click="previous"
          >
            <i class="fas fa-step-backward" />
          </a>
          <a
            class="command play"
            :title="[playing.value ? 'Mettre en pause' : 'Lecture']"
            :class="{ active: playing.value }"
            @click="playPause"
          >
            <i
              class="fas"
              :class="[playing.value ? 'fa-pause' : 'fa-play']"
            />
          </a>
          <a
            class="command next"
            title="Suivant"
            @click="next"
          >
            <i class="fas fa-step-forward" />
          </a>
        </div>

        <div class="volume">
          <i class="fas fa-volume-down" />
          <input
            type="range"
            class="slider amplitude-volume-slider"
            @input="changeVolume"
          >
        </div>
      </div>
    </div>

    <template v-if="playOrPause">
      <div class="card">
        <div class="card-header">
          {{ currentMusic.value.title }}
        </div>
        <div class="card-body">
          <p style="margin-bottom: 4px;">
            <strong>Commentaire</strong>
          </p>
          <div style="margin-bottom: 15px;">
            {{ currentMusic.value.description }}
          </div>
          <p>
            <strong>Jeu :</strong>
            <a
              :href="currentMusicGameLink.value"
              target="_blank"
            >{{ currentMusic.value.game.title }}</a>
            ({{ currentMusic.value.game.session }})
          </p>
          <p>
            <strong>Lien :</strong>
            <a
              :href="currentMusic.value.link"
              target="_blank"
            >Cliquer ici</a>
          </p>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Amplitude from 'amplitudejs'
import axios from 'axios'

const musics = ref([])
const currentMusic = ref({})
const scriptLoaded = ref(false)
const repeat = ref(false)
const shuffle = ref(true)
const status = ref('stop')
const currentMusicDuration = ref(0)
const selectorHeading = ref('-1')
const musicChangedFromSelector = ref(false)
const musicSelector = ref(null)

const playing = computed(() => status.value === 'playing')
const playOrPause = computed(() => status.value !== 'stop')
const currentMusicGameLink = computed(() => {
  if (!currentMusic.value.game) return ''

  return `${import.meta.env.VITE_FORMER_APP_URL}?p=jeu&id=${currentMusic.value.game.id}`
})

onMounted(async () => {
  await fetchMusics()
  initializeAmplitude()
  scriptLoaded.value = true
})

async function fetchMusics() {
  const request = await axios({
    url: '/api/v0/musics'
  })
  musics.value = request.data.data.map(formatMusicForJukebox)
}

function initializeAmplitude() {
  Amplitude.init({
    debug: import.meta.env.VITE_DEBUG,
    shuffle_on: shuffle.value,
    callbacks: {
      play: playCallback,
      song_change: songChangeCallback,
      durationchange: durationChangeCallback
    },
    songs: musics.value.map(formatMusicForAmplitude)
  })
}

function playCallback() {
  status.value = 'playing'
  const metadata = Amplitude.getActiveSongMetadata()
  currentMusic.value = musics.value.find(
    music => music.title === metadata.name
  )
}

function durationChangeCallback() {
  const duration = Amplitude.getSongDuration()
  let minutes = Math.floor(duration / 60)
  if (minutes < 10) minutes = `0${minutes}`
  let seconds = Math.floor(duration % 60)
  if (seconds < 10) seconds = `0${seconds}`
  currentMusicDuration.value = `${minutes}:${seconds}`
}

function songChangeCallback() {
  if (!musicChangedFromSelector.value && musicSelector.value) {
    musicSelector.value.value = selectorHeading.value
  }
}

function toggleRepeat() {
  repeat.value = !repeat.value
  Amplitude.setRepeatSong(repeat.value)
}

function toggleShuffle() {
  shuffle.value = !shuffle.value
  Amplitude.setShuffle(shuffle.value)
}

function changeMusicFromSelector(event) {
  musicChangedFromSelector.value = true
  Amplitude.playSongAtIndex(event.target.value)
  musicChangedFromSelector.value = false
}

function changeSongPlayedPercentage(event) {
  let newPercentage = parseFloat(event.target.value)
  if (newPercentage <= 0) newPercentage = 0.1
  Amplitude.setSongPlayedPercentage(newPercentage)
}

function changeVolume(event) {
  const newPercentage = parseFloat(event.target.value)
  Amplitude.setVolume(newPercentage)
}

function previous() {
  Amplitude.prev()
}

function playPause() {
  if (!playing.value) {
    Amplitude.play()
  } else {
    Amplitude.pause()
    status.value = 'pause'
  }
}

function next() {
  Amplitude.next()
}

function formatMusicForJukebox(musicDto) {
  return {
    title: musicDto.title,
    description: musicDto.description,
    game: {
      id: musicDto.game.id,
      title: musicDto.game.title,
      session: musicDto.game.session
    },
    link: musicDto.music_url
  }
}

function formatMusicForAmplitude(jukeboxMusic) {
  return {
    name: jukeboxMusic.title,
    url: jukeboxMusic.link
  }
}
</script>

<style lang="scss" scoped>
$link-color: #d39501;

#jukebox {
  text-align: center;

  .music-selector {
    max-width: 100%;
  }

  .current-music-player {
    margin: 40px 0;

    .slider {
      -webkit-appearance: none;
      appearance: none;
      height: 10px;
      border-radius: 5px;
      background: #fffcea;
      outline: none;
      opacity: 0.7;
      -webkit-transition: 0.2s;
      transition: opacity 0.2s;

      &:hover {
        opacity: 1;
      }

      &::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #f3be43;
        cursor: pointer;
      }

      &::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #f3be43;
        cursor: pointer;
      }

      &.amplitude-song-slider {
        width: 250px;
      }

      &.amplitude-volume-slider {
        height: 5px;
        width: 80px;

        &::-webkit-slider-thumb {
          width: 15px;
          height: 15px;
        }

        &::-moz-range-thumb {
          width: 15px;
          height: 15px;
        }
      }
    }

    .song-slider {
      margin-bottom: 18px;
    }

    .commands {
      display: flex;
      justify-content: center;
      align-items: baseline;

      a.command {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: #fffcea;
        background: #f3be43;
        display: inline-block;
        cursor: pointer;

        &.play {
          font-size: 20px;
          width: 50px;
          height: 50px;
          line-height: 50px;
          margin-left: -10px;
          margin-right: -10px;
        }

        &.prev,
        &.next {
          width: 40px;
          height: 40px;
          line-height: 40px;
        }

        &.prev {
          margin-left: 22px;
        }

        &.next {
          margin-right: 26px;
        }

        &.repeat,
        &.shuffle {
          color: #3f3f3f;
          background: none;
        }

        &.active {
          color: $link-color;
          background-position: bottom;
        }

        &:active {
          background-position: bottom;
        }

        &:hover,
        &:focus {
          color: $link-color;
        }
      }

      .repeat-shuffle {
        display: inline-block;
      }

      .main-commands {
        display: inline-block;
      }

      .volume {
        display: inline-flex;
        align-items: center;

        input {
          margin-left: 5px;
        }
      }
    }
  }

  .card {
    margin-bottom: 0;

    .card-body {
      text-align: left;

      p:last-child {
        margin-bottom: 0;
      }
    }
  }

  @media (max-width: 460px) {
    .current-music-player {
      .slider {
        &.amplitude-song-slider {
          width: 150px;
        }

        &.amplitude-volume-slider {
          height: 5px;
          width: 80px;
        }
      }

      .commands {
        flex-direction: column;
        align-items: center;

        a.command {
          &.repeat,
          &.shuffle {
            width: 20px;
            height: 20px;
            line-height: 20px;
          }
        }

        .main-commands {
          margin-top: 20px;
          margin-bottom: 30px;
        }
      }
    }
  }
}
</style>
