<template>
  <div v-if="isOpen" class="cookies">
    <p>
      Door de website van careto te gebruiken accepteer je onze niet-intrusieve cookies.
    </p>

    <div class="button-wrap">
      <button @click="accept()" class="button dark right">Bedankt!</button>
    </div>

  </div>
</template>

<script>
  import * as Cookie from 'tiny-cookie';

  export default {
    data: () => ({
      supportsLocalStorage: true,
      isOpen: false,
    }),

    created() {
      // Check for availability of localStorage
      try {
        const test = '__cookielaw-check-localStorage';
        window.localStorage.setItem(test, test);
        window.localStorage.removeItem(test);
      } catch (e) {
        // eslint-disable-next-line no-console
        console.error('Local storage is not supported, falling back to cookie use');
        this.supportsLocalStorage = false;
      }
      if (!this.getVisited() === true) {
        this.isOpen = true;
      }
    },

    methods: {
      setVisited() {
        if (this.supportsLocalStorage) {
          localStorage.setItem('cookie:accepted', true);
        } else {
          Cookie.set('cookie:accepted', true);
        }
      },

      getVisited() {
        if (this.supportsLocalStorage) {
          return localStorage.getItem('cookie:accepted');
        } else {
          return Cookie.get('cookie:accepted');
        }
      },

      accept() {
        this.setVisited();
        this.isOpen = false;
        this.$emit('accept');
      },
    },
  };
</script>