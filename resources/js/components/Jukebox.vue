<template>
  <div id="jukebox" v-if="scriptLoaded">
    <select @change="changeMusicFromSelector" ref="musicSelector">
      <option selected="selected" :value="selectorHeading">--- Choix de la musique ---</option>
      <option v-for="(music, index) in musics" :key="index" :value="index">{{ music.title }}</option>
    </select>
    <div class="current-music-player">
      <div>
        <span class="amplitude-current-time"></span>

        <input
          type="range"
          value="0"
          class="slider amplitude-song-slider"
          @input="changeSongPlayedPercentage"
        />

        <span class="current-music-time" v-if="playOrPause">{{ currentMusicDuration }}</span>
        <span v-else>00:00</span>
      </div>

      <div>
        <a
          class="commands repeat"
          title="Répéter"
          :class="{ active: repeat }"
          @click="toggleRepeat"
        >
          <i class="fas fa-redo-alt"></i>
        </a>
        <a
          class="commands shuffle"
          title="Aléatoire"
          :class="{ active: shuffle }"
          @click="toggleShuffle"
        >
          <i class="fas fa-random"></i>
        </a>

        <a class="commands prev" title="Précédent" @click="previous">
          <i class="fas fa-step-backward"></i>
        </a>
        <a
          class="commands play"
          :title="[playing ? 'Mettre en pause' : 'Lecture']"
          :class="{ active: playing }"
          @click="playPause"
        >
          <i class="fas" :class="[playing ? 'fa-pause' : 'fa-play']"></i>
        </a>
        <a class="commands next" title="Suivant" @click="next">
          <i class="fas fa-step-forward"></i>
        </a>
        <span class="volume">
          <i class="fas fa-volume-down"></i>
          <input type="range" class="slider amplitude-volume-slider" @input="changeVolume" />
        </span>
      </div>
    </div>

    <template v-if="playOrPause">
      <div class="card">
        <div class="card-header">{{ currentMusic.title }}</div>
        <div class="card-body">
          <p style="margin-bottom: 4px;">
            <strong>Commentaire</strong>
          </p>
          <div style="margin-bottom: 15px;">{{ currentMusic.description }}</div>
          <p>
            <strong>Jeu :</strong>
            <a :href="currentMusicGameLink" target="_blank">{{ currentMusic.game.title }}</a>
            ({{ currentMusic.game.session }})
          </p>
          <p>
            <strong>Lien :</strong>
            <a :href="currentMusic.link" target="_blank">Cliquer ici</a>
          </p>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import Amplitude from 'amplitudejs'

export default {
  data() {
    return {
      musics: [],
      currentMusic: {},
      scriptLoaded: false,
      repeat: false,
      shuffle: true,
      status: 'stop',
      currentMusicDuration: 0,
      selectorHeading: '-1',
      musicChangedFromSelector: false
    }
  },
  async mounted() {
    await this.fetchMusics()
    this.initializeAmplitude()
    this.scriptLoaded = true
  },
  computed: {
    playing() {
      return this.status === 'playing'
    },
    playOrPause() {
      return this.status !== 'stop'
    },
    currentMusicGameLink() {
      return formerAppUrl + '?p=jeu&id=' + this.currentMusic.game.id
    }
  },
  methods: {
    // Init and callbacks
    async fetchMusics() {
      const request = await axios({
        url: formerAppUrl + '/api/v0/musics.php'
      })
      this.musics = request.data.map(this.formatMusicForJukebox)
    },
    initializeAmplitude() {
      Amplitude.init({
        debug,
        shuffle_on: this.shuffle,
        callbacks: {
          play: this.playCallback,
          song_change: this.songChangeCallback,
          durationchange: this.durationChangeCallback
        },
        songs: this.musics.map(this.formatMusicForAmplitude)
      })
    },
    playCallback() {
      this.status = 'playing'
      const metadata = Amplitude.getActiveSongMetadata()
      this.currentMusic = this.musics.find(
        music => music.title === metadata.name
      )
    },
    durationChangeCallback() {
      const duration = Amplitude.getSongDuration()
      let minutes = Math.floor(duration / 60)
      if (minutes < 10) minutes = `0${minutes}`
      let seconds = Math.floor(duration % 60)
      if (seconds < 10) seconds = `0${seconds}`
      this.currentMusicDuration = `${minutes}:${seconds}`
    },
    songChangeCallback() {
      if (!this.musicChangedFromSelector) {
        this.$refs.musicSelector.value = this.selectorHeading
      }
    },
    // Advance commands
    toggleRepeat() {
      this.repeat = !this.repeat
      Amplitude.setRepeatSong(this.repeat)
    },
    toggleShuffle() {
      this.shuffle = !this.shuffle
      Amplitude.setShuffle(this.shuffle)
    },
    changeMusicFromSelector(event) {
      this.musicChangedFromSelector = true
      Amplitude.playSongAtIndex(event.target.value)
      this.musicChangedFromSelector = false
    },
    changeSongPlayedPercentage(event) {
      let newPercentage = parseFloat(event.target.value)
      if (newPercentage <= 0) newPercentage = 0.1
      Amplitude.setSongPlayedPercentage(newPercentage)
    },
    changeVolume(event) {
      let newPercentage = parseFloat(event.target.value)
      Amplitude.setVolume(newPercentage)
    },
    // Commands
    previous() {
      Amplitude.prev()
    },
    playPause() {
      if (!this.playing) {
        Amplitude.play()
      } else {
        Amplitude.pause()
        this.status = 'pause'
      }
    },
    next() {
      Amplitude.next()
    },
    // Formatting
    formatMusicForJukebox(musicDto) {
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
    },
    formatMusicForAmplitude(jukeboxMusic) {
      return {
        name: jukeboxMusic.title,
        url: jukeboxMusic.link
      }
    }
  }
}
</script>

<style lang="scss" scoped>
$link-color: #d39501;

#jukebox {
  text-align: center;

  .current-music-player {
    margin: 40px 0;

    div:first-child {
      margin-bottom: 18px;
    }

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

    a.commands {
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

    .volume {
      display: inline-flex;
      align-items: center;

      input {
        margin-left: 5px;
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
}
</style>
