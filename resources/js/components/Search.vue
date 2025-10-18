<template>
    <div class="relative w-full">
        <form @submit.prevent="performSearch" class="relative w-full">
            <input type="text" 
                   v-model="searchQuery" 
                   placeholder="Пошук товарів..." 
                   class="w-full pl-10 pr-16 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                   @focus="showSuggestions = true"
                   @blur="hideSuggestions"
                   @input="onInput">
            
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                <i class="fas fa-search"></i>
            </div>
            
            <button type="submit" 
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-4 py-2 rounded-lg hover:from-emerald-600 hover:to-teal-600 transition-all">
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <!-- Search Suggestions - Outside form -->
        <div v-if="showSuggestions && suggestions.length > 0" 
             class="search-suggestions absolute left-0 right-0 bg-white border border-gray-200 rounded-xl shadow-xl max-h-96 overflow-y-auto"
             :style="getSuggestionsStyle()">
            <div v-for="suggestion in suggestions" 
                 :key="suggestion.id" 
                 class="p-3 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-100 last:border-b-0"
                 @mousedown.prevent="goToProduct(suggestion.url)">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                        <img v-if="suggestion.image_path" 
                             :src="suggestion.image_path" 
                             :alt="suggestion.name"
                             class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h5 class="font-medium text-gray-900 text-sm truncate">{{ suggestion.name }}</h5>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-sm text-emerald-600 font-bold">{{ formatPrice(suggestion.price) }} ₴</p>
                            <span v-if="suggestion.discount > 0" class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">
                                -{{ suggestion.discount }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Show All Results -->
            <div class="p-3 bg-gray-50 border-t border-gray-200">
                <button @mousedown.prevent="performSearch" 
                        class="w-full text-center text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                    Показати всі результати ({{ suggestions.length }}+)
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- No Results - Outside form -->
        <div v-if="showSuggestions && searchQuery.length >= 2 && suggestions.length === 0 && !loading" 
             class="absolute left-0 right-0 bg-white border border-gray-200 rounded-xl shadow-xl p-6 text-center"
             :style="getSuggestionsStyle()">
            <i class="fas fa-search text-gray-300 text-3xl mb-3"></i>
            <p class="text-gray-600">Нічого не знайдено</p>
            <p class="text-sm text-gray-400 mt-1">Спробуйте інший запит</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "Search",
    data() {
        return {
            searchQuery: '',
            suggestions: [],
            showSuggestions: false,
            searchTimeout: null,
            loading: false,
            resizeHandler: null
        };
    },
    mounted() {
        // Добавляем обработчик изменения размера окна для навбара
        this.resizeHandler = () => {
            if (this.showSuggestions && this.$el && this.$el.closest('.navbar-search')) {
                this.$forceUpdate();
            }
        };
        window.addEventListener('scroll', this.resizeHandler);
        window.addEventListener('resize', this.resizeHandler);
    },
    beforeUnmount() {
        if (this.resizeHandler) {
            window.removeEventListener('scroll', this.resizeHandler);
            window.removeEventListener('resize', this.resizeHandler);
        }
    },
    methods: {
        onInput() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            if (this.searchQuery.length >= 2) {
                this.loading = true;
                this.searchTimeout = setTimeout(() => {
                    this.fetchSuggestions();
                }, 300);
            } else {
                this.suggestions = [];
                this.loading = false;
            }
        },
        async fetchSuggestions() {
            try {
                const response = await fetch(`/catalog/search?q=${encodeURIComponent(this.searchQuery)}`);
                if (response.ok) {
                    const data = await response.json();
                    this.suggestions = Array.isArray(data) ? data : [];
                    console.log('Search suggestions:', this.suggestions);
                } else {
                    console.error('Search API error:', response.status);
                    this.suggestions = [];
                }
            } catch (e) {
                console.error('Error fetching suggestions:', e);
                this.suggestions = [];
            } finally {
                this.loading = false;
            }
        },
        performSearch() {
            if (this.searchQuery.trim()) {
                window.location.href = `/search?q=${encodeURIComponent(this.searchQuery)}`;
            }
        },
        goToProduct(url) {
            window.location.href = `/catalog/${url}`;
        },
        hideSuggestions() {
            setTimeout(() => {
                this.showSuggestions = false;
            }, 200);
        },
        formatPrice(price) {
            return Math.round(price).toLocaleString('uk-UA');
        },
        getSuggestionsStyle() {
            // Проверяем, находится ли компонент в навбаре
            const isInNavbar = this.$el && this.$el.closest('.navbar-search');
            const isInMobileSearch = this.$el && this.$el.closest('.mobile-search');
            
            if (isInMobileSearch) {
                // Для мобильного поиска используем абсолютное позиционирование относительно контейнера
                return {
                    position: 'absolute',
                    top: 'calc(100% + 8px)',
                    left: '0',
                    right: '0',
                    zIndex: '99999'
                };
            } else if (isInNavbar) {
                // Для навбара используем фиксированное позиционирование
                const inputRect = this.$el.querySelector('input').getBoundingClientRect();
                return {
                    position: 'fixed',
                    top: `${inputRect.bottom + 10}px`,
                    left: `${inputRect.left}px`,
                    width: `${inputRect.width}px`,
                    zIndex: '99999'
                };
            } else {
                return {
                    top: 'calc(100% + 8px)',
                    zIndex: '9999'
                };
            }
        }
    }
};
</script>

<style scoped>
/* Ensure proper z-index layering */
.z-\[9999\] {
    z-index: 9999 !important;
}

/* Additional styles for search suggestions */
.search-suggestions {
    position: relative;
    z-index: 9999 !important;
}

/* Ensure the form container has proper positioning context */
form {
    position: relative;
    z-index: 1;
}

/* Special positioning for navbar search - handled by JavaScript */

/* Mobile search positioning */
.mobile-search {
    position: relative !important;
    overflow: visible !important;
}

.mobile-search .search-suggestions {
    position: absolute !important;
    top: calc(100% + 8px) !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 99999 !important;
}
</style>