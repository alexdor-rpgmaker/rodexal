<template>
  <tr class="tr">
    <td>
      <template v-if="game.screenshots[0]">
        <a :href="formerAppUrl + '/?p=jeu&id=' + game.id" class="screenshot-link">
          <img :src="game.screenshots[0].url" width="100" class="screenshot" style="border: none;" />
        </a>
      </template>
    </td>
    <td>
      <a :href="formerAppUrl + '/?p=jeu&id=' + game.id" class="title-link">
        {{ game.title }}
      </a>
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
    game: Object,
    formerAppUrl: String
  }
}
</script>

<style lang="scss" scoped>
td {
  text-align: left;
}
</style>
