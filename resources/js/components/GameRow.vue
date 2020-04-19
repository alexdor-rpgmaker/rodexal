<template>
  <tr class="tr">
    <td>
      <template v-if="game.screenshots[0]">
        <a :href="formerAppUrl + '/?p=jeu&id=' + game.id" class="screenshot-link">
          <img :src="game.screenshots[0].url" width="100" class="screenshot" style="border: none;" alt="" />
        </a>
      </template>
    </td>
    <td>
      <p class="title mb-1">
        <a :href="formerAppUrl + '/?p=jeu&id=' + game.id" class="title-link">
          {{ game.title }}
        </a>
      </p>
      <p class="awarded-categories mb-1" v-if="wasAwarded">
        Victoire : {{ awardedCategoriesList }}
      </p>
      <p class="nominated-categories mb-1" v-if="wasNominated">
        Nominations : {{ nominatedCategoriesList }}
      </p>
    </td>
    <td class="session">{{ game.session.name }}</td>
    <td class="makers">
      <span v-if="game.creationGroup">{{ game.creationGroup }} :</span>
      <span v-for="(author, index) in game.authors" :key="author.id + author.username">
        <a :href="formerAppUrl + '/?p=profil&membre=' + author.id">{{
          author.username
        }}</a><template v-if="index < game.authors.length - 1">, </template>
      </span>
    </td>
    <td class="software">{{ game.software }}</td>
    <td class="genre">{{ game.genre }}</td>
    <td class="download-links">
      <a :href="link.url" v-for="link in game.downloadLinks" :key="link.platform">
        <img :src="formerAppUrl + '/design/divers/disquette-verte.gif'" alt="Disquette" style="border: none;" />
        <span v-if="link.platform === 'windows'">(Win)</span>
        <span v-else>(Mac)</span>
      </a>
    </td>
  </tr>
</template>

<script>
export default {
  props: {
    game: {
      type: Object,
      required: true
    }
  },
  computed: {
    awardedCategories() {
      if(!this.game.awards) {
        console.log(this.game)
      }
      return this.game
        .awards
        .filter(award => award.status === "awarded")
    },
    awardedCategoriesList() {
      return this.awardedCategories
        .map(this.awardName)
        .join(", ")
    },
    wasAwarded() {
      return this.awardedCategoriesList.length > 0
    },
    nominatedCategories() {
      return this.game
        .awards
        .filter(award => award.status === "nominated")
    },
    nominatedCategoriesList() {
      return this.nominatedCategories
        .map(award => award.category_name)
        .join(", ")
    },
    wasNominated() {
      return this.nominatedCategories.length > 0
    }
  },
  methods: {
    awardName(award) {
      let awardName = award.category_name
      switch(award.award_level) {
        case 'gold': awardName += ' (or)';  break;
        case 'silver': awardName += ' (argent)';  break;
        case 'bronze': awardName += ' (bronze)';  break;
        default: break;
      }
      return awardName
    }
  }
}
</script>

<style lang="scss" scoped>
.tr td {
  text-align: left;
}

.awarded-categories, .nominated-categories {
  font-style: italic;
}
</style>
