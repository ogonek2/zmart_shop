<template>
  <div class="zh-find">
    <form class="zh-find__form" @submit.prevent="performSearch">
      <i class="fas fa-search zh-find__icon" aria-hidden="true"></i>
      <input
        type="search"
        v-model="searchQuery"
        class="zh-find__input"
        placeholder="Пошук товарів"
        autocomplete="off"
        @focus="showSuggestions = true"
        @blur="hideSuggestions"
        @input="onInput"
      >
      <button
        v-if="searchQuery"
        type="button"
        class="zh-find__clear"
        aria-label="Очистити"
        @mousedown.prevent="clearQuery"
      >
        <i class="fas fa-times"></i>
      </button>
      <button type="submit" class="zh-find__go" aria-label="Шукати">
        <i class="fas fa-arrow-right"></i>
      </button>
    </form>

    <div v-if="showSuggestions && suggestions.length" class="zh-find__drop">
      <button
        v-for="item in suggestions"
        :key="item.id"
        type="button"
        class="zh-find__item"
        @mousedown.prevent="goToProduct(item.url)"
      >
        <span class="zh-find__thumb">
          <img v-if="item.image_path" :src="item.image_path" :alt="item.name">
          <i v-else class="fas fa-image"></i>
        </span>
        <span class="zh-find__meta">
          <span class="zh-find__name">{{ item.name }}</span>
          <strong>{{ formatPrice(item.price) }} ₴</strong>
        </span>
      </button>
      <button type="button" class="zh-find__all" @mousedown.prevent="performSearch">
        Усі результати
      </button>
    </div>

    <div
      v-else-if="showSuggestions && searchQuery.length >= 2 && !loading && !suggestions.length"
      class="zh-find__drop zh-find__drop--empty"
    >
      Нічого не знайдено
    </div>
  </div>
</template>

<script>
export default {
  name: 'Search',
  data() {
    return {
      searchQuery: '',
      suggestions: [],
      showSuggestions: false,
      searchTimeout: null,
      loading: false,
    };
  },
  methods: {
    clearQuery() {
      this.searchQuery = '';
      this.suggestions = [];
    },
    onInput() {
      if (this.searchTimeout) clearTimeout(this.searchTimeout);
      if (this.searchQuery.length >= 2) {
        this.loading = true;
        this.searchTimeout = setTimeout(() => this.fetchSuggestions(), 280);
      } else {
        this.suggestions = [];
        this.loading = false;
      }
    },
    async fetchSuggestions() {
      try {
        const response = await fetch(`/catalog/search?q=${encodeURIComponent(this.searchQuery)}`);
        this.suggestions = response.ok ? (await response.json()) || [] : [];
      } catch (e) {
        this.suggestions = [];
      } finally {
        this.loading = false;
      }
    },
    performSearch() {
      if (!this.searchQuery.trim()) return;
      window.location.href = `/search?q=${encodeURIComponent(this.searchQuery.trim())}`;
    },
    goToProduct(url) {
      window.location.href = `/catalog/${url}`;
    },
    hideSuggestions() {
      setTimeout(() => {
        this.showSuggestions = false;
      }, 180);
    },
    formatPrice(price) {
      return Math.round(parseFloat(price) || 0).toLocaleString('uk-UA');
    },
  },
};
</script>
