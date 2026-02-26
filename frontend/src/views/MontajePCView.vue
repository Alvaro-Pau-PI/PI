<template>
  <div class="pc-builder-container">
    <!-- Header -->
    <div class="builder-header">
      <div class="header-content">
        <div class="header-text">
          <h1 class="builder-title">{{ $t('builder.title') }}</h1>
          <p class="builder-subtitle">{{ $t('builder.subtitle') }}</p>
        </div>
      </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-section">
      <div class="progress-bar">
        <div class="progress-fill" :style="{ width: progressPercentage + '%' }"></div>
      </div>
      <div class="progress-text">{{ completedSteps }}/{{ totalSteps }} {{ $t('builder.progress') }}</div>
    </div>

    <!-- Main Content -->
    <div class="builder-main">
      <!-- PC Visualization -->
      <div class="pc-visualization">
        <div class="pc-workspace">
          <div class="pc-visual-layout">
            <!-- PC Case Isometric View -->
            <div class="pc-visual-stage">
              <div class="pc-blueprint" :class="{ complete: isBuildComplete }">
                <div class="bp-header">
                  <div class="bp-title">Blueprint</div>
                  <div class="bp-subtitle">Montaje PC</div>
                </div>

                <div class="bp-canvas">
                  <div class="bp-case" :class="{ installed: !!selectedComponents.case }">
                    <div class="bp-left">
                      <div class="bp-motherboard" :class="{ installed: !!selectedComponents.motherboard }">
                        <div class="bp-mb-grid">
                          <div class="bp-slot" :class="{ installed: !!selectedComponents.cpu }">
                            <div class="bp-slot-icon">🔧</div>
                            <div class="bp-slot-label">CPU</div>
                          </div>

                          <div class="bp-slot" :class="{ installed: !!selectedComponents.ram }">
                            <div class="bp-slot-icon">💾</div>
                            <div class="bp-slot-label">RAM</div>
                          </div>

                          <div class="bp-slot" :class="{ installed: !!selectedComponents.storage }">
                            <div class="bp-slot-icon">💿</div>
                            <div class="bp-slot-label">SSD</div>
                          </div>

                          <div class="bp-slot" :class="{ installed: !!selectedComponents.cooling }">
                            <div class="bp-slot-icon">❄️</div>
                            <div class="bp-slot-label">COOL</div>
                          </div>
                        </div>

                        <div class="bp-gpu-lane">
                          <div class="bp-slot bp-slot-wide" :class="{ installed: !!selectedComponents.gpu }">
                            <div class="bp-slot-icon">🎮</div>
                            <div class="bp-slot-label">GPU</div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="bp-right">
                      <div class="bp-slot bp-psu" :class="{ installed: !!selectedComponents.psu }">
                        <div class="bp-slot-icon">⚡</div>
                        <div class="bp-slot-label">PSU</div>
                      </div>

                      <div class="bp-power" :class="{ on: isBuildComplete }"></div>
                    </div>
                  </div>

                  <div class="bp-legend">
                    <div class="bp-legend-item">
                      <span class="bp-dot installed"></span>
                      {{ $t('builder.bp_installed') }}
                    </div>
                    <div class="bp-legend-item">
                      <span class="bp-dot pending"></span>
                      {{ $t('builder.bp_pending') }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Component Selection -->
      <div class="component-selection">
        <h2 class="selection-title">{{ $t('builder.select_title') }}</h2>
        
        <!-- Component Categories -->
        <div class="component-categories">
          <button 
            v-for="category in componentCategories" 
            :key="category.id"
            @click="selectCategory(category.id)"
            :class="['category-btn', { active: activeCategory === category.id, completed: selectedComponents[category.id] }]"
          >
            <span class="category-icon">{{ category.icon }}</span>
            <span class="category-name">{{ category.name }}</span>
            <span v-if="selectedComponents[category.id]" class="check-mark">✓</span>
          </button>
        </div>

        <!-- Component Options -->
        <div v-if="activeCategory" class="component-options">
          <h3 class="options-title">{{ componentCategories.find(c => c.id === activeCategory)?.name }}</h3>
          
          <div v-if="loading" class="loading-message">
            <div class="loading-spinner">⏳</div>
            {{ $t('builder.loading') }}
          </div>
          <div v-else-if="filteredComponents.length === 0" class="no-components">
            {{ $t('builder.no_components') }}
          </div>
          <div v-else class="options-grid">
            <div 
              v-for="component in filteredComponents" 
              :key="component.id"
              @click="selectComponent(component)"
              :class="['component-card', { selected: selectedComponents[activeCategory]?.id === component.id, incompatible: !isCompatible(component) }]"
            >
              <div class="component-image">
                <img :src="component.image || '/placeholder-component.jpg'" :alt="component.name" />
              </div>
              <div class="component-info">
                <h4 class="component-name">{{ component.name }}</h4>
                <p class="component-brand">{{ component.brand || component.sku }}</p>
                <div class="component-specs">
                  <span class="spec-tag">{{ component.category }}</span>
                  <span v-if="component.stock > 0" class="spec-tag stock">{{ $t('builder.stock') }}: {{ component.stock }}</span>
                  <span v-else class="spec-tag out-of-stock">{{ $t('builder.out_of_stock') }}</span>
                </div>
                <div class="component-price">€{{ parseFloat(component.price).toFixed(2) }}</div>
                <div 
                  v-if="Number(component.discount_price) > 0 && Number(component.discount_price) < Number(component.price)"
                  class="component-discount"
                >
                  <span class="original-price">€{{ parseFloat(component.price).toFixed(2) }}</span>
                  <span class="discount-price">€{{ parseFloat(component.discount_price).toFixed(2) }}</span>
                  <span class="discount-badge">-{{ Number(component.discount_percentage) || 0 }}%</span>
                </div>
              </div>
              <div v-if="!isCompatible(component)" class="incompatibility-warning">
                {{ $t('builder.incompatible') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Build Summary -->
    <div class="build-summary">
      <h2 class="summary-title">{{ $t('builder.summary_title') }}</h2>
      <div class="summary-content">
        <div class="summary-components">
          <div v-for="(component, key) in selectedComponents" :key="key" class="summary-item">
            <span class="item-name">{{ component.name }}</span>
            <span class="item-price">
              €{{ (Number(component.discount_price) > 0 && Number(component.discount_price) < Number(component.price)) ? parseFloat(component.discount_price).toFixed(2) : parseFloat(component.price).toFixed(2) }}
            </span>
          </div>
        </div>
        <div class="summary-total">
          <span class="total-label">{{ $t('builder.total') }}</span>
          <span class="total-price">€{{ totalPrice.toFixed(2) }}</span>
        </div>
        <div class="summary-actions">
          <button @click="addToCart" class="btn-cart" :disabled="!isBuildComplete">
            {{ $t('builder.add_cart') }}
          </button>
        </div>
      </div>
    </div>

    <!-- Compatibility Alerts -->
    <div v-if="compatibilityIssues.length > 0" class="compatibility-alerts">
      <h3>{{ $t('builder.compat_title') }}</h3>
      <ul>
        <li v-for="issue in compatibilityIssues" :key="issue">{{ issue }}</li>
      </ul>
    </div>

    <!-- Notification System -->
    <div class="notification-container">
      <div 
        v-for="notification in notifications" 
        :key="notification.id"
        :class="['notification', `notification-${notification.type}`]"
      >
        <span class="notification-message">{{ notification.message }}</span>
        <button @click="removeNotification(notification.id)" class="notification-close">×</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useProductStore } from '@/stores/products'
import { useCartStore } from '@/stores/cart'
import { storeToRefs } from 'pinia'

const router = useRouter()
const { t } = useI18n()
const productStore = useProductStore()
const cartStore = useCartStore()
const { products, loading } = storeToRefs(productStore)

// State
const activeCategory = ref('')
const selectedComponents = ref({})
const compatibilityIssues = ref([])

// Component Categories mapping to real categories
const componentCategories = computed(() => [
  { id: 'cpu', name: t('builder.cat_cpu'), icon: '🔧', dbCategory: 'Processadors' },
  { id: 'gpu', name: t('builder.cat_gpu'), icon: '🎮', dbCategory: 'Targetes Gr\u00e0fiques' },
  { id: 'motherboard', name: t('builder.cat_motherboard'), icon: '🔌', dbCategory: 'Plaques Base' },
  { id: 'ram', name: t('builder.cat_ram'), icon: '💾', dbCategory: 'Mem\u00f2ria RAM' },
  { id: 'storage', name: t('builder.cat_storage'), icon: '💿', dbCategory: 'Emmagatzematge' },
  { id: 'cooling', name: t('builder.cat_cooling'), icon: '\u2744\ufe0f', dbCategory: 'Refrigeraci\u00f3' },
  { id: 'psu', name: t('builder.cat_psu'), icon: '\u26a1', dbCategory: 'Fonts Alimentaci\u00f3' },
  { id: 'case', name: t('builder.cat_case'), icon: '🖥\ufe0f', dbCategory: 'Caixes' }
])

// Computed
const totalSteps = computed(() => componentCategories.value.length)
const completedSteps = computed(() => Object.keys(selectedComponents.value).length)
const progressPercentage = computed(() => (completedSteps.value / totalSteps.value) * 100)
const totalPrice = computed(() => {
  return Object.values(selectedComponents.value).reduce((total, component) => {
    const hasRealDiscount = Number(component.discount_price) > 0 && Number(component.discount_price) < Number(component.price)
    const price = hasRealDiscount ? parseFloat(component.discount_price) : parseFloat(component.price)
    return total + price
  }, 0)
})
const isBuildComplete = computed(() => completedSteps.value === totalSteps.value)

// Filtered components for active category
const filteredComponents = computed(() => {
  if (!activeCategory.value || !products.value.length) return []
  
  const category = componentCategories.value.find(c => c.id === activeCategory.value)
  if (!category) return []
  
  return products.value.filter(comp => comp.category === category.dbCategory)
})

// Load all products across all pages
const loadAllProducts = async () => {
  try {
    await productStore.fetchProducts(1)
    if (productStore.pagination.last_page > 1) {
      for (let page = 2; page <= productStore.pagination.last_page; page++) {
        await productStore.fetchProducts(page, true)
      }
    }
  } catch (error) {
    console.error('Error loading products:', error)
    showNotification(t('builder.notif_load_error'), 'error')
  }
}

// Methods
const selectCategory = (categoryId) => {
  activeCategory.value = categoryId
}

const selectComponent = (component) => {
  if (!isCompatible(component)) return
  
  selectedComponents.value[activeCategory.value] = component
  checkCompatibility()
}

const isCompatible = (component) => {
  // Enhanced compatibility checks based on real product data
  if (activeCategory.value === 'cpu' && selectedComponents.value.motherboard) {
    // Check socket compatibility based on product names and descriptions
    const cpuSocket = getCpuSocket(component)
    const mbSocket = getMotherboardSocket(selectedComponents.value.motherboard)
    return cpuSocket === mbSocket
  }
  if (activeCategory.value === 'motherboard' && selectedComponents.value.cpu) {
    const cpuSocket = getCpuSocket(selectedComponents.value.cpu)
    const mbSocket = getMotherboardSocket(component)
    return cpuSocket === mbSocket
  }
  if (activeCategory.value === 'ram' && selectedComponents.value.motherboard) {
    // Check DDR type compatibility
    const ramType = getRamType(component)
    const mbRamType = getMotherboardRamType(selectedComponents.value.motherboard)
    return ramType === mbRamType
  }
  return true
}

// Helper functions to extract compatibility info from product data
const getCpuSocket = (cpu) => {
  const name = cpu.name.toLowerCase()
  const desc = cpu.description.toLowerCase()
  
  if (name.includes('ryzen') || name.includes('amd')) {
    if (desc.includes('am5') || name.includes('7000') || name.includes('7600') || name.includes('7700') || name.includes('7800') || name.includes('7900') || name.includes('7950')) {
      return 'AM5'
    }
    return 'AM4' // Default for older Ryzen
  }
  
  if (name.includes('intel') || name.includes('core')) {
    if (name.includes('14900') || name.includes('14700') || name.includes('13900') || name.includes('13700') || name.includes('1700')) {
      return 'LGA1700'
    }
    if (name.includes('12900') || name.includes('12700') || name.includes('12600')) {
      return 'LGA1700'
    }
  }
  
  return 'Unknown'
}

const getMotherboardSocket = (motherboard) => {
  const name = motherboard.name.toLowerCase()
  const desc = motherboard.description.toLowerCase()
  
  if (name.includes('b650') || name.includes('x670') || name.includes('a620') || desc.includes('am5')) {
    return 'AM5'
  }
  if (name.includes('b550') || name.includes('x570') || name.includes('a520') || desc.includes('am4')) {
    return 'AM4'
  }
  if (name.includes('z790') || name.includes('b760') || name.includes('h770') || desc.includes('lga1700') || desc.includes('1700')) {
    return 'LGA1700'
  }
  if (name.includes('z690') || name.includes('b660') || name.includes('h670')) {
    return 'LGA1700'
  }
  
  return 'Unknown'
}

const getRamType = (ram) => {
  const name = ram.name.toLowerCase()
  const desc = ram.description.toLowerCase()
  
  if (name.includes('ddr5') || desc.includes('ddr5')) {
    return 'DDR5'
  }
  if (name.includes('ddr4') || desc.includes('ddr4')) {
    return 'DDR4'
  }
  
  return 'DDR4' // Default assumption
}

const getMotherboardRamType = (motherboard) => {
  const name = motherboard.name.toLowerCase()
  const desc = motherboard.description.toLowerCase()
  
  if (name.includes('ddr5') || desc.includes('ddr5')) {
    return 'DDR5'
  }
  if (name.includes('ddr4') || desc.includes('ddr4')) {
    return 'DDR4'
  }
  
  // Newer chipsets typically support DDR5
  if (name.includes('z790') || name.includes('b650') || name.includes('x670')) {
    return 'DDR5'
  }
  
  return 'DDR4' // Default assumption
}

const checkCompatibility = () => {
  compatibilityIssues.value = []
  
  // Check power consumption (estimated based on component types)
  const totalPower = Object.values(selectedComponents.value).reduce((total, comp) => {
    if (comp.category === 'Processadors') return total + 150 // Average CPU power
    if (comp.category === 'Tarjetes Gràfiques') return total + 300 // Average GPU power
    return total + 50 // Other components
  }, 0)
  
  if (selectedComponents.value.psu) {
    const psuWattage = extractPsuWattage(selectedComponents.value.psu)
    if (totalPower > psuWattage) {
      compatibilityIssues.value.push(`La fuente de alimentación (${psuWattage}W) no tiene suficiente potencia para los componentes seleccionados (${totalPower}W estimados)`)
    }
  }
  
  // Check RAM compatibility
  if (selectedComponents.value.ram && selectedComponents.value.motherboard) {
    const ramType = getRamType(selectedComponents.value.ram)
    const mbRamType = getMotherboardRamType(selectedComponents.value.motherboard)
    if (ramType !== mbRamType) {
      compatibilityIssues.value.push(`El tipo de memoria RAM (${ramType}) no es compatible con la placa base (soporta ${mbRamType})`)
    }
  }
}

const extractPsuWattage = (psu) => {
  const name = psu.name.toLowerCase()
  const desc = psu.description.toLowerCase()
  
  // Extract wattage from name or description
  const wattMatch = name.match(/(\d+)w/) || desc.match(/(\d+)w/)
  if (wattMatch) {
    return parseInt(wattMatch[1])
  }
  
  return 650 // Default assumption
}

// Notification system
const notifications = ref([])

const showNotification = (message, type = 'info') => {
  const id = Date.now()
  notifications.value.push({ id, message, type })
  
  // Auto remove after 3 seconds
  setTimeout(() => {
    removeNotification(id)
  }, 3000)
}

const removeNotification = (id) => {
  notifications.value = notifications.value.filter(n => n.id !== id)
}

const addToCart = () => {
  if (!isBuildComplete.value) {
    showNotification(t('builder.notif_select_all'), 'warning')
    return
  }
  
  const outOfStockComponents = Object.values(selectedComponents.value).filter(comp => comp.stock <= 0)
  if (outOfStockComponents.length > 0) {
    showNotification(`${t('builder.notif_no_stock')}: ${outOfStockComponents.map(c => c.name).join(', ')}`, 'error')
    return
  }
  
  Object.values(selectedComponents.value).forEach((component, index) => {
    const pcBuildComponent = {
      ...component,
      pcBuildId: `build-${Date.now()}`,
      pcBuildName: `PC Gaming ${new Date().toLocaleDateString()}`,
      pcBuildOrder: index,
      isPCBuildComponent: true,
      buildTotal: totalPrice.value,
      buildComponents: Object.values(selectedComponents.value).length
    }
    cartStore.addItem(pcBuildComponent)
  })
  
  showNotification(t('builder.notif_added'), 'success')
  
  setTimeout(() => {
    if (confirm(t('builder.notif_cart_confirm'))) {
      router.push('/cart')
    }
  }, 1500)
}

// Load products on mount
onMounted(() => {
  // Load all products across all pages
  loadAllProducts()
  
  // Load shared build if exists in URL
  const urlParams = new URLSearchParams(window.location.search)
  const buildData = urlParams.get('build')
  if (buildData) {
    try {
      selectedComponents.value = JSON.parse(atob(buildData))
      checkCompatibility()
    } catch (e) {
      console.error('Error loading shared build:', e)
    }
  }
})
</script>

<style scoped>
.pc-builder-container {
  max-width: var(--max-width-container);
  margin: 0 auto;
  padding: 40px 20px;
  min-height: 100vh;
  color: var(--text-primary);
}

.builder-header {
  margin-bottom: 2rem;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
}

.header-text {
  flex: 1;
}

.builder-title {
  font-family: var(--font-headings);
  font-size: 2.5rem;
  font-weight: var(--font-weight-bold);
  margin-bottom: 0.5rem;
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.builder-subtitle {
  font-size: 1.05rem;
  color: var(--text-secondary);
}

.progress-section {
  background: var(--bg-card);
  border: var(--border-width) solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: var(--spacing-lg);
  margin-bottom: 2rem;
  box-shadow: var(--shadow-sm);
}

.progress-bar {
  height: 8px;
  background: rgba(255,255,255,0.08);
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 0.5rem;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--color-primary), var(--color-primary-light));
  transition: width 0.3s ease;
}

.progress-text {
  text-align: center;
  font-weight: var(--font-weight-semibold);
  color: var(--text-secondary);
}

.builder-main {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

.pc-visualization {
  background: var(--bg-card);
  border: var(--border-width) solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: var(--spacing-xl);
  position: relative;
  min-height: 600px;
  overflow: hidden;
  box-shadow: var(--shadow-md);
}

.pc-workspace {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  height: 100%;
  position: relative;
  z-index: 1;
}

.pc-visual-layout {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  align-items: start;
  justify-items: center;
}

.pc-visual-stage {
  position: relative;
  width: 100%;
  min-height: 420px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  border-radius: 1rem;
}

.pc-blueprint {
  width: 100%;
  max-width: 520px;
  background: linear-gradient(180deg, rgba(0, 161, 255, 0.10), rgba(0,0,0,0)) , var(--bg-elevated);
  border: var(--border-width) solid var(--border-color);
  border-radius: var(--radius-xl);
  padding: 14px;
  box-shadow: var(--shadow-lg);
}

.bp-header {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 1rem;
  padding: 6px 8px 10px;
}

.bp-title {
  font-weight: 800;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.bp-subtitle {
  font-weight: 700;
  font-size: 0.85rem;
  color: var(--color-primary);
}

.bp-canvas {
  position: relative;
  border-radius: 14px;
  background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.01));
  border: var(--border-width) solid rgba(255,255,255,0.06);
  padding: 14px;
}

.bp-case {
  position: relative;
  height: 320px;
  border-radius: 14px;
  background:
    linear-gradient(135deg, rgba(255,255,255,0.06), rgba(255,255,255,0.015));
  border: 2px solid rgba(255,255,255,0.16);
  box-shadow: inset 0 0 0 1px rgba(0,0,0,0.25);
  overflow: hidden;
  display: grid;
  grid-template-columns: 1fr clamp(92px, 22%, 124px);
  gap: 12px;
  padding: 14px;
}

.pc-blueprint.complete .bp-case {
  border-color: var(--color-primary);
  box-shadow: 0 0 0 1px rgba(0, 161, 255, 0.25), 0 0 26px rgba(0, 161, 255, 0.18);
}

.bp-case::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px),
    linear-gradient(0deg, rgba(255,255,255,0.06) 1px, transparent 1px);
  background-size: 22px 22px;
  opacity: 0.35;
  pointer-events: none;
}

.bp-left {
  min-width: 0;
  display: flex;
}

.bp-right {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: space-between;
  gap: 12px;
}

.bp-motherboard {
  position: relative;
  width: 100%;
  height: 100%;
  border-radius: 12px;
  background: linear-gradient(135deg, rgba(45, 74, 43, 0.65), rgba(26, 47, 26, 0.55));
  border: 2px solid rgba(255,215,0,0.25);
  box-shadow: inset 0 0 0 1px rgba(0,0,0,0.25);
  display: grid;
  grid-template-rows: auto 1fr;
  gap: 12px;
  padding: 12px;
}

.bp-motherboard.installed {
  border-color: rgba(0, 161, 255, 0.55);
  box-shadow: 0 0 22px rgba(0, 161, 255, 0.14), inset 0 0 0 1px rgba(0,0,0,0.25);
}

.bp-slot {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  gap: 4px;
  border-radius: 12px;
  background: rgba(0,0,0,0.18);
  border: 1px dashed rgba(255,255,255,0.28);
  padding: 10px;
  user-select: none;
  min-height: 72px;
}

.bp-slot.installed {
  border-style: solid;
  border-color: rgba(0, 161, 255, 0.75);
  background: rgba(0, 161, 255, 0.12);
  box-shadow: 0 0 18px rgba(0, 161, 255, 0.18);
}

.bp-slot-icon {
  font-size: 1.25rem;
}

.bp-slot-label {
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.85);
}


.bp-mb-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.bp-gpu-lane {
  display: flex;
}

.bp-slot-wide {
  width: 100%;
  min-height: 92px;
}

.bp-psu {
  width: 100%;
  min-height: 92px;
  border-radius: 14px;
}

.bp-power {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: rgba(255,255,255,0.25);
  box-shadow: 0 0 0 rgba(0,255,136,0);
  transition: all 0.25s ease;
  align-self: flex-end;
  margin-right: 8px;
}

.bp-power.on {
  background: var(--color-primary);
  box-shadow: 0 0 18px rgba(0, 161, 255, 0.6);
}

.bp-legend {
  display: flex;
  gap: 14px;
  justify-content: flex-end;
  align-items: center;
  padding: 10px 6px 2px;
  color: var(--text-secondary);
  font-size: 0.78rem;
}

.bp-legend-item {
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.bp-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  display: inline-block;
}

.bp-dot.installed {
  background: var(--color-primary);
  box-shadow: 0 0 10px rgba(0, 161, 255, 0.35);
}

.bp-dot.pending {
  background: rgba(255,255,255,0.25);
}

.pc-case-3d {
  position: relative;
  width: 100%;
  height: 400px;
  transform: rotateX(12deg) rotateY(-18deg) scale(0.92);
  transform-origin: center top;
  transform-style: preserve-3d;
  perspective: 1000px;
  margin-bottom: 1.5rem;
  z-index: 2;
}

.case-frame {
  position: relative;
  width: 300px;
  height: 350px;
  margin: 0 auto;
  transform-style: preserve-3d;
}

.case-panel {
  position: absolute;
  background: linear-gradient(135deg, #2a2a2a, #1a1a1a);
  border: 2px solid rgba(255,255,255,0.1);
  box-shadow: 0 0 20px rgba(0,0,0,0.5);
}

.case-panel-left {
  width: 300px;
  height: 350px;
  transform: translateX(-150px) rotateY(90deg);
  background: linear-gradient(135deg, #1f1f1f, #0f0f0f);
}

.case-panel-right {
  width: 300px;
  height: 350px;
  transform: translateX(150px) rotateY(-90deg);
  background: linear-gradient(135deg, #1f1f1f, #0f0f0f);
}

.case-panel-top {
  width: 300px;
  height: 300px;
  transform: translateY(-150px) rotateX(90deg);
  background: linear-gradient(135deg, #252525, #151515);
}

.case-panel-front {
  width: 300px;
  height: 350px;
  transform: translateZ(150px);
  background: linear-gradient(135deg, #2a2a2a, #1a1a1a);
  display: flex;
  align-items: center;
  justify-content: center;
}

.front-component-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  align-items: center;
}

.power-button {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: radial-gradient(circle, #333, #111);
  border: 2px solid #555;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.power-button.active {
  background: radial-gradient(circle, #00ff88, #00cc66);
  border-color: #00ff88;
  box-shadow: 0 0 20px rgba(0,255,136,0.6);
}

.power-led {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #333;
  transition: all 0.3s ease;
}

.power-button.active .power-led {
  background: #ffffff;
  box-shadow: 0 0 10px rgba(255,255,255,0.8);
}

.usb-ports {
  display: flex;
  gap: 0.5rem;
}

.usb-port {
  width: 15px;
  height: 8px;
  background: linear-gradient(90deg, #444, #222);
  border: 1px solid #666;
  border-radius: 2px;
}

.internal-components {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 250px;
  height: 300px;
  transform-style: preserve-3d;
}

.motherboard-3d {
  position: absolute;
  width: 200px;
  height: 250px;
  background: linear-gradient(135deg, #2d4a2b, #1a2f1a);
  border: 2px solid rgba(255,215,0,0.3);
  border-radius: 5px;
  transform: translateZ(10px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  transition: all 0.4s ease;
}

.motherboard-3d.installed {
  box-shadow: 0 0 25px rgba(0,255,136,0.4);
  border-color: #00ff88;
}

.mb-layer {
  position: relative;
  width: 100%;
  height: 100%;
  padding: 1rem;
}

.mb-cpu-socket {
  position: absolute;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 60px;
  background: radial-gradient(circle, #444, #222);
  border: 3px solid #666;
  border-radius: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.mb-cpu-socket.filled {
  background: radial-gradient(circle, #00ff88, #00cc66);
  border-color: #00ff88;
  box-shadow: 0 0 15px rgba(0,255,136,0.6);
}

.cpu-3d {
  position: relative;
  width: 50px;
  height: 50px;
}

.cpu-fan {
  width: 40px;
  height: 40px;
  border: 2px solid #888;
  border-radius: 50%;
  background: radial-gradient(circle, #666, #333);
  position: absolute;
  top: 5px;
  left: 5px;
  animation: fanSpin 2s linear infinite;
}

.cpu-body {
  width: 50px;
  height: 20px;
  background: linear-gradient(135deg, #444, #222);
  border: 1px solid #666;
  position: absolute;
  top: 15px;
  left: 0;
  border-radius: 3px;
}

@keyframes fanSpin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.mb-ram-slots {
  position: absolute;
  top: 100px;
  right: 20px;
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.ram-slot-3d {
  width: 80px;
  height: 8px;
  background: linear-gradient(90deg, #333, #111);
  border: 1px solid #555;
  border-radius: 2px;
  transition: all 0.3s ease;
}

.ram-slot-3d.filled {
  background: linear-gradient(90deg, #00ff88, #00cc66);
  border-color: #00ff88;
  box-shadow: 0 0 10px rgba(0,255,136,0.4);
}

.ram-stick-3d {
  width: 75px;
  height: 6px;
  background: linear-gradient(90deg, #4a90e2, #357abd);
  border-radius: 1px;
  margin: 1px;
}

.mb-pcie-slots {
  position: absolute;
  bottom: 80px;
  left: 20px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.pcie-slot {
  width: 100px;
  height: 12px;
  background: linear-gradient(90deg, #333, #111);
  border: 1px solid #555;
  border-radius: 2px;
  transition: all 0.3s ease;
}

.pcie-slot.filled {
  background: linear-gradient(90deg, #00ff88, #00cc66);
  border-color: #00ff88;
  box-shadow: 0 0 10px rgba(0,255,136,0.4);
}

.pcie-x16 {
  width: 120px;
}

.gpu-3d {
  position: relative;
  width: 115px;
  height: 40px;
}

.gpu-fan {
  width: 30px;
  height: 30px;
  border: 2px solid #888;
  border-radius: 50%;
  background: radial-gradient(circle, #666, #333);
  position: absolute;
  top: 5px;
  left: 10px;
  animation: fanSpin 1.5s linear infinite;
}

.gpu-body {
  width: 115px;
  height: 35px;
  background: linear-gradient(135deg, #444, #222);
  border: 1px solid #666;
  position: absolute;
  top: 0;
  left: 0;
  border-radius: 3px;
}

.gpu-ports {
  position: absolute;
  right: 5px;
  top: 10px;
  display: flex;
  gap: 3px;
}

.gpu-ports::before {
  content: '';
  width: 8px;
  height: 8px;
  background: #333;
  border: 1px solid #555;
}

.mb-storage-connectors {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
}

.storage-m2 {
  width: 40px;
  height: 8px;
  background: linear-gradient(90deg, #333, #111);
  border: 1px solid #555;
  border-radius: 2px;
  transition: all 0.3s ease;
}

.storage-m2.filled {
  background: linear-gradient(90deg, #00ff88, #00cc66);
  border-color: #00ff88;
  box-shadow: 0 0 10px rgba(0,255,136,0.4);
}

.ssd-m2-3d {
  width: 35px;
  height: 6px;
  background: linear-gradient(90deg, #4a90e2, #357abd);
  border-radius: 1px;
  margin: 1px;
}

.storage-sata {
  width: 15px;
  height: 8px;
  background: linear-gradient(90deg, #333, #111);
  border: 1px solid #555;
  border-radius: 2px;
}

.psu-3d {
  position: absolute;
  bottom: 20px;
  right: 20px;
  width: 80px;
  height: 60px;
  background: linear-gradient(135deg, #444, #222);
  border: 2px solid #666;
  border-radius: 5px;
  transform: translateZ(5px);
  transition: all 0.4s ease;
}

.psu-3d.installed {
  box-shadow: 0 0 20px rgba(0,255,136,0.4);
  border-color: #00ff88;
}

.psu-fan {
  width: 40px;
  height: 40px;
  border: 2px solid #888;
  border-radius: 50%;
  background: radial-gradient(circle, #666, #333);
  position: absolute;
  top: 10px;
  left: 20px;
  animation: fanSpin 1.8s linear infinite;
}

.psu-body {
  width: 80px;
  height: 60px;
  background: linear-gradient(135deg, #444, #222);
  border-radius: 5px;
}

.psu-cables {
  position: absolute;
  left: -20px;
  top: 20px;
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.cable-set {
  width: 30px;
  height: 3px;
  background: linear-gradient(90deg, #666, #333);
  border-radius: 2px;
  transform-origin: left center;
}

.cpu-cable {
  transform: rotate(-30deg);
}

.gpu-cable {
  transform: rotate(0deg);
}

.motherboard-cable {
  transform: rotate(30deg);
}

.storage-bay {
  position: absolute;
  bottom: 100px;
  left: 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.drive-bay {
  width: 60px;
  height: 20px;
  background: linear-gradient(135deg, #333, #111);
  border: 1px solid #555;
  border-radius: 3px;
  transition: all 0.3s ease;
}

.drive-bay.filled {
  background: linear-gradient(135deg, #00ff88, #00cc66);
  border-color: #00ff88;
  box-shadow: 0 0 15px rgba(0,255,136,0.4);
}

.drive-bay.empty {
  opacity: 0.5;
}

.storage-drive-3d {
  position: relative;
  width: 55px;
  height: 18px;
  margin: 1px;
}

.drive-body {
  width: 55px;
  height: 18px;
  background: linear-gradient(135deg, #4a90e2, #357abd);
  border-radius: 2px;
}

.drive-activity {
  position: absolute;
  top: 2px;
  right: 2px;
  width: 4px;
  height: 4px;
  background: #00ff88;
  border-radius: 50%;
  animation: blink 1s infinite;
}

@keyframes blink {
  0%, 50%, 100% { opacity: 1; }
  25%, 75% { opacity: 0.3; }
}

.case-lighting {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.5s ease;
}

.case-lighting.active {
  opacity: 1;
}

.led-strip {
  position: absolute;
  background: linear-gradient(90deg, #ff0000, #00ff00, #0000ff, #ff0000);
  border-radius: 2px;
  animation: rgbCycle 3s linear infinite;
}

.led-strip-top {
  width: 280px;
  height: 4px;
  top: 10px;
  left: 10px;
}

.led-strip-side {
  width: 4px;
  height: 330px;
  top: 10px;
  right: 10px;
}

.led-strip-front {
  width: 280px;
  height: 4px;
  bottom: 10px;
  left: 10px;
}

@keyframes rgbCycle {
  0% { filter: hue-rotate(0deg); }
  100% { filter: hue-rotate(360deg); }
}

.fan-rgb {
  position: absolute;
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(255,0,0,0.8), rgba(0,255,0,0.8), rgba(0,0,255,0.8));
  animation: rgbSpin 4s linear infinite;
}

.fan-rgb-rear {
  top: 20px;
  right: 20px;
}

.fan-rgb-top {
  top: 10px;
  left: 50%;
  transform: translateX(-50%);
}

@keyframes rgbSpin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.component-status-panel {
  background: rgba(8, 12, 20, 0.45);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  padding: 1.25rem;
  border: 1px solid rgba(255,255,255,0.14);
  position: relative;
  z-index: 5;
  width: 100%;
  max-width: none;
  margin: 0 auto;
  min-height: 420px;
  max-height: 520px;
  overflow: auto;
  box-shadow: 0 16px 40px rgba(0,0,0,0.25);
}

@media (min-width: 1201px) {
  .component-status-panel {
    max-height: none;
    overflow: visible;
  }
}

.component-status-panel::-webkit-scrollbar {
  width: 10px;
}

.component-status-panel::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,0.18);
  border-radius: 999px;
}

.component-status-panel::-webkit-scrollbar-track {
  background: rgba(0,0,0,0.12);
  border-radius: 999px;
}

@media (max-width: 1200px) {
  .builder-main {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 1100px) {
  .pc-visual-layout {
    grid-template-columns: 1fr;
  }

  .component-status-panel {
    min-height: 0;
  }
}

@media (max-width: 420px) {
  .bp-mb-grid {
    grid-template-columns: 1fr;
  }

  .bp-case {
    height: 360px;
  }
}

.status-title {
  font-size: 1.2rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
  color: #00ff88;
}

.status-grid {
  display: grid;
  gap: 0.6rem;
}

.status-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.7rem 0.8rem;
  background: rgba(0,0,0,0.18);
  border-radius: 0.75rem;
  border: 1px solid rgba(255,255,255,0.12);
  transition: all 0.3s ease;
}

.status-item.installed {
  background: rgba(0,255,136,0.1);
  border-color: rgba(0,255,136,0.3);
  box-shadow: 0 0 10px rgba(0,255,136,0.2);
}

.status-item.pending {
  opacity: 0.7;
}

.status-icon {
  font-size: 1.35rem;
  width: 34px;
  text-align: center;
}

.status-info {
  flex: 1;
}

.status-name {
  font-weight: 800;
  font-size: 0.95rem;
  margin-bottom: 0.25rem;
}

.status-detail {
  font-size: 0.78rem;
  opacity: 0.82;
  line-height: 1.2;
}

.installed-text {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  color: #00ff88;
}

.pending-text {
  color: #ff6666;
}

.status-indicator {
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.indicator-led {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.indicator-on {
  background: var(--color-primary);
  box-shadow: 0 0 10px rgba(0, 161, 255, 0.45);
}

.indicator-off {
  background: rgba(255,255,255,0.22);
}

.component-selection {
  background: var(--bg-card);
  border: var(--border-width) solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: 2rem;
  box-shadow: var(--shadow-md);
}

.selection-title {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  text-align: center;
  font-family: var(--font-headings);
  font-weight: var(--font-weight-bold);
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.component-categories {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.category-btn {
  background: var(--bg-elevated);
  border: var(--border-width) solid var(--border-color);
  border-radius: var(--radius-md);
  padding: 1rem;
  color: var(--text-primary);
  cursor: pointer;
  transition: var(--transition-base);
  position: relative;
  box-shadow: var(--shadow-sm);
}

.category-btn:hover {
  background: rgba(0, 161, 255, 0.08);
  transform: translateY(-2px);
  border-color: var(--border-hover);
  box-shadow: var(--shadow-md);
}

.category-btn.active {
  background: rgba(0, 161, 255, 0.14);
  border-color: var(--color-primary);
  box-shadow: 0 8px 20px rgba(0, 161, 255, 0.16);
}

.category-btn.completed {
  background: rgba(46, 204, 113, 0.10);
  border-color: rgba(46, 204, 113, 0.45);
}

.category-icon {
  display: block;
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.category-name {
  font-size: 0.9rem;
  font-weight: bold;
}

.check-mark {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  color: var(--color-success);
  font-weight: bold;
}

.options-title {
  font-size: 1.3rem;
  margin-bottom: 1rem;
}

.loading-message, .no-components {
  text-align: center;
  padding: 2rem;
  opacity: 0.7;
  font-style: italic;
}

.loading-spinner {
  font-size: 2rem;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.options-grid {
  display: grid;
  gap: 1rem;
  max-height: 400px;
  overflow-y: auto;
}

.component-card {
  background: var(--bg-elevated);
  border: var(--border-width) solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: 1rem;
  cursor: pointer;
  transition: var(--transition-base);
  display: grid;
  grid-template-columns: 80px 1fr;
  gap: 1rem;
  box-shadow: var(--shadow-sm);
}

.component-card:hover {
  background: rgba(0, 161, 255, 0.06);
  transform: translateY(-2px);
  border-color: var(--border-hover);
  box-shadow: var(--shadow-md);
}

.component-card.selected {
  background: rgba(0, 161, 255, 0.14);
  border-color: var(--color-primary);
}

.component-card.incompatible {
  opacity: 0.5;
  border-color: var(--color-error);
  cursor: not-allowed;
}

.component-image img {
  width: 100%;
  height: 60px;
  object-fit: cover;
  border-radius: 0.25rem;
}

.component-name {
  font-size: 1rem;
  font-weight: bold;
  margin-bottom: 0.25rem;
  font-family: var(--font-headings);
}

.component-brand {
  font-size: 0.8rem;
  color: var(--text-secondary);
  margin-bottom: 0.5rem;
}

.component-specs {
  display: flex;
  flex-wrap: wrap;
  gap: 0.25rem;
  margin-bottom: 0.5rem;
}

.spec-tag {
  background: rgba(255,255,255,0.08);
  border: var(--border-width) solid rgba(255,255,255,0.10);
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.8rem;
}

.spec-tag.stock {
  background: var(--color-success-bg);
  color: var(--color-success);
  border-color: rgba(46, 204, 113, 0.35);
}

.spec-tag.out-of-stock {
  background: var(--color-error-bg);
  color: var(--color-error);
  border-color: rgba(255, 77, 77, 0.35);
}

.component-price {
  font-size: 1.2rem;
  font-weight: var(--font-weight-bold);
  color: var(--color-primary);
  margin-top: 0.5rem;
}

.component-discount {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.original-price {
  text-decoration: line-through;
  opacity: 0.7;
  font-size: 0.9rem;
}

.discount-price {
  font-weight: bold;
  color: var(--color-success);
}

.discount-badge {
  background: var(--color-error);
  color: var(--text-on-primary);
  padding: 0.2rem 0.4rem;
  border-radius: 0.25rem;
  font-size: 0.7rem;
  font-weight: bold;
}

.incompatibility-warning {
  grid-column: 1 / -1;
  text-align: center;
  color: var(--color-error);
  font-weight: bold;
}

.build-summary {
  background: var(--bg-card);
  border: var(--border-width) solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: var(--spacing-xl);
  margin-bottom: 2rem;
  box-shadow: var(--shadow-md);
}

.summary-title {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  text-align: center;
  font-family: var(--font-headings);
  font-weight: var(--font-weight-bold);
}

.summary-components {
  margin-bottom: 1.5rem;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: var(--border-width) solid rgba(255,255,255,0.08);
}

.summary-total {
  display: flex;
  justify-content: space-between;
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  padding-top: 1rem;
  border-top: 2px solid rgba(255,255,255,0.3);
}

.total-price {
  color: #00ff88;
}

.summary-actions {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
}

.btn-cart {
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
  border: var(--border-width) solid var(--color-primary);
  border-radius: var(--radius-md);
  padding: 1rem 2rem;
  color: var(--text-on-primary);
  font-size: 1.05rem;
  font-weight: var(--font-weight-semibold);
  cursor: pointer;
  transition: var(--transition-base);
  min-width: 250px;
  text-align: center;
  box-shadow: var(--shadow-primary);
}

.btn-cart:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: var(--border-color);
  border-color: var(--border-color);
  box-shadow: none;
}

.btn-cart:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary);
}

.compatibility-alerts {
  background: rgba(255,68,68,0.2);
  border: 2px solid #ff4444;
  border-radius: 1rem;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.compatibility-alerts h3 {
  margin-bottom: 1rem;
  color: #ff4444;
}

.compatibility-alerts ul {
  list-style: none;
  padding: 0;
}

.compatibility-alerts li {
  padding: 0.5rem 0;
  border-bottom: 1px solid rgba(255,68,68,0.3);
}

/* Notification System */
.notification-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.notification {
  background: rgba(30, 30, 30, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 8px;
  padding: 16px;
  min-width: 300px;
  max-width: 400px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  border-left: 4px solid;
  animation: slideIn 0.3s ease-out;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.notification-success {
  border-left-color: #00ff88;
}

.notification-error {
  border-left-color: #ff4444;
}

.notification-warning {
  border-left-color: #ffaa00;
}

.notification-info {
  border-left-color: #00ccff;
}

.notification-message {
  color: white;
  font-weight: 500;
  flex: 1;
}

.notification-close {
  background: none;
  border: none;
  color: rgba(255, 255, 255, 0.7);
  font-size: 1.2rem;
  cursor: pointer;
  padding: 0;
  margin-left: 10px;
  transition: color 0.2s;
}

.notification-close:hover {
  color: white;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@media (max-width: 768px) {
  .builder-main {
    grid-template-columns: 1fr;
  }
  
  .component-categories {
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
  }
  
  .summary-actions {
    justify-content: center;
  }
  
  .btn-cart {
    min-width: auto;
    width: 100%;
    max-width: 300px;
  }
  
  .header-content {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  
  .builder-title {
    font-size: 2rem;
  }
  
  .notification-container {
    top: 10px;
    right: 10px;
    left: 10px;
  }
  
  .notification {
    min-width: auto;
    max-width: none;
  }
}
</style>
