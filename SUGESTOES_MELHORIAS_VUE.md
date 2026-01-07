# Sugestões de Melhorias - Frontend Vue.js

## 🚀 Melhorias de Performance

### 1. **Lazy Loading de Componentes**
```javascript
// Ao invés de importar todos os componentes diretamente
import ScheduleSpeechesForm from "@/Pages/Schedule/Partials/ScheduleSpeechesForm.vue";

// Use lazy loading para componentes pesados
const ScheduleSpeechesForm = defineAsyncComponent(() => 
  import("@/Pages/Schedule/Partials/ScheduleSpeechesForm.vue")
);
```

### 2. **Otimização do FontAwesome**
**Problema atual:** Todos os ícones são carregados no app.js
**Solução:** Carregar ícones dinamicamente ou tree shaking
```javascript
// Ao invés de importar todos os ícones no app.js, use:
import { defineAsyncComponent } from 'vue'

// Ou use um plugin de tree-shaking para FontAwesome
```

### 3. **Memoização de Computeds**
```javascript
// Em componentes como TablePaginator.vue
import { computed, toRef } from 'vue'

const activeClass = computed(() => ({
  active: 'z-10 border-gray-500 bg-gray-100 text-grey-600',
  inactive: 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
}))
```

### 4. **Virtual Scrolling para Listas Grandes**
Para tabelas com muitos dados, implementar virtual scrolling:
```javascript
// Instalar: npm install @tanstack/vue-virtual
import { useVirtualizer } from '@tanstack/vue-virtual'
```

## 🏗️ Melhorias de Estrutura

### 1. **Composables para Lógica Reutilizável** ✅ IMPLEMENTADO
**Status:** Implementado em `/resources/js/composables/useDebounceSearch.js` e aplicado em:
- ✅ `Schedule/List.vue`
- ✅ `Speech/List.vue`
- ✅ `Speaker/List.vue`
- ✅ `Passenger/List.vue`
- ✅ `Bus/List.vue`

Criar composables para lógica repetitiva:

```javascript
// composables/useDebounceSearch.js
import { ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import debounce from 'lodash/debounce'

export function useDebounceSearch(route, initialFilters = {}) {
  const filters = ref({ ...initialFilters })
  
  const debouncedSearch = debounce((newFilters) => {
    Inertia.get(route, newFilters, { 
      preserveState: true, 
      replace: true 
    })
  }, 300)
  
  watch(filters, debouncedSearch, { deep: true })
  
  return { filters }
}
```

### 2. **Padronização de Props**
```javascript
// Usar TypeScript ou pelo menos validações consistentes
defineProps({
  modelValue: {
    type: [String, Number],
    required: true
  },
  disabled: {
    type: Boolean,
    default: false
  }
})
```

### 3. **Store Global (Pinia)**
Para gerenciamento de estado global:
```bash
npm install pinia
```

```javascript
// stores/auth.js
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isDark: false
  }),
  actions: {
    toggleDarkMode() {
      this.isDark = !this.isDark
    }
  }
})
```

## 🎨 Melhorias de UX/UI

### 1. **Loading States Consistentes** ✅ IMPLEMENTADO
**Status:** Implementado composable `useLoading.js` + componente `LoadingButton.vue` + `GlobalLoading.vue`
```javascript
// composables/useLoading.js
export function useLoading() {
  const isLoading = ref(false)
  
  const withLoading = async (asyncFn) => {
    isLoading.value = true
    try {
      return await asyncFn()
    } finally {
      isLoading.value = false
    }
  }
  
  return { isLoading, withLoading }
}
```

### 2. **Error Handling Melhorado**
```javascript
// composables/useErrorHandler.js
export function useErrorHandler() {
  const errors = ref([])
  
  const handleError = (error) => {
    errors.value.push({
      id: Date.now(),
      message: error.message,
      type: 'error'
    })
  }
  
  const clearErrors = () => {
    errors.value = []
  }
  
  return { errors, handleError, clearErrors }
}
```

## 🔧 Melhorias Técnicas

### 1. **TypeScript**
```bash
npm install -D typescript @vue/tsconfig
```

### 2. **ESLint + Prettier Configurados**
```javascript
// .eslintrc.js
module.exports = {
  extends: [
    '@vue/eslint-config-typescript',
    '@vue/eslint-config-prettier'
  ],
  rules: {
    'vue/multi-word-component-names': 'off',
    'vue/no-unused-vars': 'error'
  }
}
```

### 3. **Vite Plugins para Otimização**
```javascript
// vite.config.js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

export default defineConfig({
  plugins: [vue()],
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          vendor: ['vue', '@inertiajs/inertia-vue3'],
          icons: ['@fortawesome/vue-fontawesome']
        }
      }
    }
  }
})
```

## 🐛 Correções de Bugs/Melhorias Específicas

### 1. **ScheduleSpeechesForm.vue**
**Problemas:**
- `onUpdated` executando desnecessariamente
- DOM queries diretas

**Soluções:**
```javascript
// Usar refs ao invés de getElementById
const datepickers = ref([])

onMounted(() => {
  nextTick(() => {
    datepickers.value.forEach((el, index) => {
      if (el) {
        new Datepicker(el, { /* config */ })
      }
    })
  })
})
```

### 2. **Schedule/List.vue**
**Problemas:**
- `let` desnecessário para props
- Lógica complexa no watch

**Soluções:**
```javascript
// Usar composable
const { filters } = useDebounceSearch('/schedules', props.filters)
```

### 3. **Navbar.vue**
**Problemas:**
- `setNavigation()` chamado em cada navigate
- Navigation array poderia ser computed

**Soluções:**
```javascript
const navigation = computed(() => [
  {
    name: "Dashboard",
    href: route("dashboard"),
    current: route().current("dashboard*"),
  },
  // ...
])
```

## 📱 Responsividade

### 1. **Mobile-First Approach**
```css
/* Usar mobile-first no Tailwind */
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
```

### 2. **Touch-Friendly Elements**
```css
/* Botões com área de toque adequada */
<button class="min-h-[44px] min-w-[44px] p-3">
```

## 🔍 Testing

### 1. **Vue Test Utils**
```bash
npm install -D @vue/test-utils vitest
```

### 2. **Component Testing**
```javascript
// tests/components/TextInput.test.js
import { mount } from '@vue/test-utils'
import TextInput from '@/Components/TextInput.vue'

describe('TextInput', () => {
  it('emits update:modelValue on input', async () => {
    const wrapper = mount(TextInput)
    await wrapper.find('input').setValue('test')
    expect(wrapper.emitted('update:modelValue')).toBeTruthy()
  })
})
```

## 📊 Monitoramento

### 1. **Bundle Analyzer**
```bash
npm install -D rollup-plugin-visualizer
```

### 2. **Performance Metrics**
```javascript
// Usar Web Vitals
import { getCLS, getFID, getFCP, getLCP, getTTFB } from 'web-vitals'

getCLS(console.log)
getFID(console.log)
getFCP(console.log)
getLCP(console.log)
getTTFB(console.log)
```

## 🚀 Próximos Passos Recomendados

1. **Implementar TypeScript** - Maior type safety
2. **Adicionar Pinia** - Gerenciamento de estado
3. **Criar Composables** - Reutilização de lógica
4. **Otimizar Bundle** - Code splitting e lazy loading
5. **Adicionar Testes** - Qualidade e confiabilidade
6. **PWA Features** - Cache e offline support

## 📈 Métricas de Sucesso

- **Redução do Bundle Size:** Objetivo de 20-30%
- **Melhoria do Core Web Vitals:** LCP < 2.5s
- **Redução de Re-renders:** Usar Vue DevTools
- **Cobertura de Testes:** Objetivo de 80%
- **TypeScript Coverage:** 100% dos novos componentes
