<template>
  <div class="admin-layout">
    <!-- Botón Toggle Mejorado con Icono Menu -->
    <button class="mobile-toggle enhanced" @click="toggleSidebar" :class="{ 'active': sidebarOpen }">
      <div class="toggle-icon">
        <span class="material-icons">{{ sidebarOpen ? 'close' : 'menu' }}</span>
      </div>
      <div class="toggle-ripple"></div>
    </button>

    <!-- Overlay para móviles -->
    <div class="sidebar-overlay" v-if="sidebarOpen" @click="closeSidebar"></div>

    <!-- Barra Lateral Mejorada -->
    <aside class="admin-sidebar" :class="{ 'collapsed': sidebarCollapsed, 'mobile-open': sidebarOpen }">
      <!-- Header con Toggle Profesional -->
      <div class="sidebar-header">
        <!-- Botón de cerrar para móviles -->
        <button class="close-mobile-btn" @click="closeSidebar" v-if="windowWidth <= 900">
          <span class="material-icons">close</span>
        </button>
        
        <!-- Toggle para desktop -->
        <button class="toggle-btn professional" @click="toggleCollapse" v-if="windowWidth > 900">
          <div class="toggle-icon-wrapper">
            <span class="material-icons">{{ sidebarCollapsed ? 'menu_open' : 'menu' }}</span>
          </div>
          <div class="btn-glow"></div>
        </button>
        
        <div class="header-content" v-show="!sidebarCollapsed || windowWidth <= 900">
          <span class="material-icons header-icon">admin_panel_settings</span>
          <h2>Panel Admin</h2>
        </div>
      </div>

      <!-- Navegación Principal -->
      <nav class="sidebar-nav">
        <div class="nav-section" v-show="!sidebarCollapsed || windowWidth <= 900">
          <h3>Gestión Principal</h3>
        </div>
        
        <router-link to="/admin/products" class="sidebar-link" active-class="active">
          <span class="material-icons link-icon">inventory_2</span>
          <span class="link-text" v-show="!sidebarCollapsed || windowWidth <= 900">Productos</span>
          <span class="link-badge" v-if="(!sidebarCollapsed || windowWidth <= 900) && productsCount > 0">{{ productsCount }}</span>
        </router-link>
        
        <router-link to="/admin/users" class="sidebar-link" active-class="active">
          <span class="material-icons link-icon">people</span>
          <span class="link-text" v-show="!sidebarCollapsed || windowWidth <= 900">Usuarios</span>
        </router-link>
        
        <router-link to="/admin/reviews" class="sidebar-link" active-class="active">
          <span class="material-icons link-icon">rate_review</span>
          <span class="link-text" v-show="!sidebarCollapsed || windowWidth <= 900">Reseñas</span>
          <span class="link-badge" v-if="(!sidebarCollapsed || windowWidth <= 900) && reviewsCount > 0">{{ reviewsCount }}</span>
        </router-link>
        
        <router-link to="/admin/contacts" class="sidebar-link" active-class="active">
          <span class="material-icons link-icon">email</span>
          <span class="link-text" v-show="!sidebarCollapsed || windowWidth <= 900">Mensajes</span>
          <span class="link-badge danger" v-if="(!sidebarCollapsed || windowWidth <= 900) && messagesCount > 0">{{ messagesCount }}</span>
        </router-link>
        
        <router-link to="/admin/orders" class="sidebar-link" active-class="active">
          <span class="material-icons link-icon">receipt_long</span>
          <span class="link-text" v-show="!sidebarCollapsed || windowWidth <= 900">Pedidos</span>
          <span class="link-badge warning" v-if="(!sidebarCollapsed || windowWidth <= 900) && pendingCount > 0">{{ pendingCount }}</span>
        </router-link>
      </nav>

      <!-- Footer -->
      <div class="sidebar-footer" v-show="!sidebarCollapsed || windowWidth <= 900">
        <div class="nav-section" v-show="!sidebarCollapsed || windowWidth <= 900">
          <h3>Cuenta</h3>
        </div>
        
        <router-link to="/profile" class="sidebar-link back-link">
          <span class="material-icons link-icon">manage_accounts</span>
          <span class="link-text" v-show="!sidebarCollapsed || windowWidth <= 900">Mi perfil</span>
        </router-link>
        
        <router-link to="/" class="sidebar-link back-link">
          <span class="material-icons link-icon">store</span>
          <span class="link-text" v-show="!sidebarCollapsed || windowWidth <= 900">Volver a la tienda</span>
        </router-link>
      </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="admin-main" :class="{ 'sidebar-collapsed': sidebarCollapsed }">
      <div class="main-content">
        <router-view />
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import http from '@/services/http';

/**
 * Maqueta base para el panel de administración.
 * Gestiona la navegación lateral responsive y conteos de notificaciones.
 */

// Estado del sidebar
const sidebarCollapsed = ref(false);
const sidebarOpen = ref(false);
const windowWidth = ref(window.innerWidth);

// Contadores
const pendingCount = ref(0);
const productsCount = ref(0);
const reviewsCount = ref(0);
const messagesCount = ref(0);

// Toggle functions
const toggleCollapse = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value;
  // Guardar preferencia en localStorage
  localStorage.setItem('sidebar-collapsed', sidebarCollapsed.value);
};

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
  // Prevenir scroll del body cuando el sidebar está abierto en móviles
  if (windowWidth.value <= 900) {
    // No bloquear el scroll del body, permitir scroll dentro del sidebar
    document.body.style.overflow = '';
  }
};

// Cerrar sidebar al hacer clic en el overlay
const closeSidebar = () => {
  sidebarOpen.value = false;
  if (windowWidth.value <= 900) {
    document.body.style.overflow = '';
  }
};

// Cargar datos al montar y configurar resize listener
onMounted(async () => {
  // Configurar listener para resize
  const handleResize = () => {
    windowWidth.value = window.innerWidth;
  };
  window.addEventListener('resize', handleResize);
  
  // Cleanup al desmontar
  onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
  });
  
  // Cargar preferencia del sidebar
  const savedCollapsed = localStorage.getItem('sidebar-collapsed');
  if (savedCollapsed !== null) {
    sidebarCollapsed.value = savedCollapsed === 'true';
  } else {
    // Por defecto abierto como solicitaste
    sidebarCollapsed.value = false;
  }

  try {
    // Obtener conteos de forma paralela
    const [ordersRes, productsRes, reviewsRes, messagesRes] = await Promise.allSettled([
      http.get('/api/admin/orders', { params: { status: 'pending' } }),
      http.get('/api/products', { params: { per_page: 1 } }),
      http.get('/api/reviews', { params: { per_page: 1 } }),
      http.get('/api/contacts', { params: { per_page: 1 } })
    ]);

    // Procesar respuestas
    if (ordersRes.status === 'fulfilled') {
      pendingCount.value = ordersRes.value.data?.total || ordersRes.value.data?.data?.length || 0;
    }

    if (productsRes.status === 'fulfilled') {
      productsCount.value = productsRes.value.data?.total || 0;
    }

    if (reviewsRes.status === 'fulfilled') {
      reviewsCount.value = reviewsRes.value.data?.total || 0;
    }

    if (messagesRes.status === 'fulfilled') {
      messagesCount.value = messagesRes.value.data?.total || 0;
    }
  } catch (error) {
    console.warn('Error cargando conteos del admin:', error);
  }
});

// Cerrar sidebar al cambiar de ruta en móviles
import { useRouter } from 'vue-router';
const router = useRouter();
router.afterEach(() => {
  if (window.innerWidth <= 900) {
    sidebarOpen.value = false;
  }
});
</script>

<style scoped>
/* Layout Principal */
.admin-layout {
  display: flex;
  min-height: calc(100vh - 70px);
  position: relative;
}

/* Botón Toggle Mejorado para Móviles - Corregido Posición */
.mobile-toggle.enhanced {
  display: none;
  position: fixed;
  top: 100px;
  left: 20px;
  z-index: 1001;
  background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
  border: 2px solid rgba(0, 161, 255, 0.3);
  border-radius: 16px;
  width: 56px;
  height: 56px;
  color: white;
  cursor: pointer;
  box-shadow: 
    0 8px 32px rgba(0, 161, 255, 0.2),
    0 4px 16px rgba(0, 0, 0, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  backdrop-filter: blur(10px);
}

.mobile-toggle.enhanced:hover {
  transform: translateY(-3px) scale(1.05);
  box-shadow: 
    0 12px 40px rgba(0, 161, 255, 0.4),
    0 6px 20px rgba(0, 0, 0, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
  border-color: rgba(0, 161, 255, 0.6);
}

.mobile-toggle.enhanced:active {
  transform: translateY(-1px) scale(1.02);
}

.mobile-toggle.enhanced.active {
  background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
  border-color: rgba(239, 68, 68, 0.5);
  box-shadow: 
    0 8px 32px rgba(239, 68, 68, 0.3),
    0 4px 16px rgba(0, 0, 0, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.mobile-toggle.enhanced.active:hover {
  box-shadow: 
    0 12px 40px rgba(239, 68, 68, 0.5),
    0 6px 20px rgba(0, 0, 0, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
  border-color: rgba(239, 68, 68, 0.7);
}

.toggle-icon {
  position: relative;
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.toggle-icon .material-icons {
  font-size: 24px;
  transition: all 0.3s ease;
}

.mobile-toggle.enhanced:hover .toggle-icon .material-icons {
  transform: scale(1.1);
}

.toggle-ripple {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.mobile-toggle.enhanced:active .toggle-ripple {
  width: 100px;
  height: 100px;
}

/* Overlay para móviles */
.sidebar-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1000;
  backdrop-filter: blur(4px);
}

/* Sidebar Profesional */
.admin-sidebar {
  width: 280px;
  background: linear-gradient(180deg, rgba(15, 18, 25, 0.98) 0%, rgba(10, 12, 18, 0.98) 100%);
  border-right: 1px solid rgba(255, 255, 255, 0.08);
  display: flex;
  flex-direction: column;
  position: sticky;
  top: 70px;
  height: calc(100vh - 70px);
  flex-shrink: 0;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
  overflow-y: auto;
  overflow-x: hidden;
}

.admin-sidebar.collapsed {
  width: 80px;
}

/* Header del Sidebar */
.sidebar-header {
  display: flex;
  align-items: center;
  padding: 20px 16px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  min-height: 80px;
}

/* Botón de cerrar para móviles */
.close-mobile-btn {
  background: linear-gradient(135deg, rgba(239, 68, 68, 0.8), rgba(248, 113, 113, 0.8));
  border: none;
  border-radius: 10px;
  width: 40px;
  height: 40px;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  margin-right: 12px;
}

.close-mobile-btn:hover {
  background: linear-gradient(135deg, #ef4444, #f87171);
  transform: scale(1.05);
  box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
}

.close-mobile-btn .material-icons {
  font-size: 20px;
}

/* Toggle del Sidebar Profesional */
.toggle-btn.professional {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.04));
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 12px;
  width: 44px;
  height: 44px;
  color: #94a3b8;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  margin-right: 12px;
  position: relative;
  overflow: hidden;
  backdrop-filter: blur(8px);
}

.toggle-btn.professional:hover {
  background: linear-gradient(135deg, rgba(0, 161, 255, 0.15), rgba(0, 212, 170, 0.1));
  border-color: rgba(0, 161, 255, 0.3);
  color: var(--color-primary);
  transform: scale(1.05);
  box-shadow: 0 4px 16px rgba(0, 161, 255, 0.2);
}

.toggle-btn.professional:active {
  transform: scale(1.02);
}

.toggle-icon-wrapper {
  position: relative;
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.toggle-icon-wrapper .material-icons {
  font-size: 20px;
  transition: all 0.3s ease;
}

.toggle-btn.professional:hover .toggle-icon-wrapper .material-icons {
  transform: rotate(180deg);
}

.btn-glow {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(0, 161, 255, 0.3) 0%, transparent 70%);
  transform: translate(-50%, -50%);
  transition: width 0.4s, height 0.4s;
  pointer-events: none;
}

.toggle-btn.professional:hover .btn-glow {
  width: 60px;
  height: 60px;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 12px;
  flex: 1;
}

.header-icon {
  font-size: 1.5rem;
  background: linear-gradient(135deg, #fbbf24, #f59e0b);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.sidebar-header h2 {
  font-size: 1.1rem;
  font-weight: 700;
  background: linear-gradient(to right, #fff, #94a3b8);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  margin: 0;
  white-space: nowrap;
}

/* Secciones de Navegación */
.nav-section {
  padding: 16px 16px 8px;
}

.nav-section h3 {
  font-size: 0.75rem;
  font-weight: 600;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin: 0;
}

/* Navegación Principal */
.sidebar-nav {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 0 8px;
  overflow-y: auto;
}

.sidebar-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 12px;
  color: #94a3b8;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  position: relative;
  margin-bottom: 4px;
}

.sidebar-link:hover {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.04));
  color: #cbd5e1;
  transform: translateX(4px);
}

.sidebar-link.active {
  background: linear-gradient(135deg, rgba(0, 161, 255, 0.15), rgba(0, 212, 170, 0.1));
  color: #00a1ff;
  font-weight: 600;
  box-shadow: 0 4px 15px rgba(0, 161, 255, 0.2);
}

.sidebar-link.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 4px;
  height: 24px;
  background: linear-gradient(to bottom, #00a1ff, #00d4aa);
  border-radius: 0 4px 4px 0;
}

.link-icon {
  font-size: 1.2rem;
  width: 24px;
  text-align: center;
  flex-shrink: 0;
}

.link-text {
  flex: 1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Badges */
.link-badge {
  margin-left: auto;
  background: linear-gradient(135deg, #00a1ff, #00d4aa);
  color: white;
  font-size: 0.7rem;
  font-weight: 700;
  min-width: 24px;
  height: 24px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 8px;
  box-shadow: 0 2px 8px rgba(0, 161, 255, 0.3);
  animation: pulse 2s infinite;
}

.link-badge.danger {
  background: linear-gradient(135deg, #ef4444, #f87171);
  box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.link-badge.warning {
  background: linear-gradient(135deg, #f59e0b, #fbbf24);
  box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

/* Footer */
.sidebar-footer {
  padding: 16px 8px 20px;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  margin-top: auto;
}

.back-link {
  color: #64748b !important;
  font-size: 0.85rem;
}

.back-link:hover {
  color: #00a1ff !important;
}

/* Contenido Principal */
.admin-main {
  flex: 1;
  min-width: 0;
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  transition: all 0.3s ease;
}

.main-content {
  padding: 24px;
  max-width: 1400px;
  margin: 0 auto;
}

.admin-main.sidebar-collapsed .main-content {
  max-width: 1600px;
}

/* Responsive para Tablets */
@media (max-width: 1200px) {
  .admin-sidebar {
    width: 260px;
  }
  
  .admin-sidebar.collapsed {
    width: 70px;
  }
  
  .main-content {
    padding: 20px;
  }
}

/* Responsive para Móviles */
@media (max-width: 900px) {
  .mobile-toggle.enhanced {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .sidebar-overlay {
    display: block;
  }
  
  .admin-sidebar {
    position: fixed;
    top: 70px;
    left: 0;
    bottom: 0;
    width: 280px;
    z-index: 1001;
    transform: translateX(-100%);
    border-radius: 0 20px 20px 0;
    height: calc(100vh - 70px);
    overflow-y: auto;
    overflow-x: hidden;
  }
  
  .admin-sidebar.mobile-open {
    transform: translateX(0);
  }
  
  .admin-sidebar.collapsed {
    width: 280px;
    transform: translateX(-100%);
  }
  
  .admin-sidebar.collapsed.mobile-open {
    transform: translateX(0);
  }
  
  .sidebar-header {
    padding: 16px;
  }
  
  .toggle-btn.professional {
    position: absolute;
    top: 16px;
    right: 16px;
    margin: 0;
  }
  
  .header-content {
    justify-content: center;
  }
  
  .main-content {
    padding: 16px;
  }
}

/* Responsive para Móviles Pequeños - Corregido Posición */
@media (max-width: 600px) {
  .mobile-toggle.enhanced {
    top: 100px;
    left: 16px;
    width: 52px;
    height: 52px;
  }
  
  .mobile-toggle.enhanced .toggle-icon .material-icons {
    font-size: 22px;
  }
  
  .admin-sidebar {
    width: 100%;
    max-width: 320px;
    border-radius: 0 20px 20px 0;
  }
  
  .sidebar-link {
    padding: 14px 16px;
    font-size: 0.95rem;
  }
  
  .main-content {
    padding: 12px;
  }
}

/* Estados Collapsed (Desktop) */
.admin-sidebar.collapsed .nav-section {
  padding: 16px 4px 8px;
}

.admin-sidebar.collapsed .nav-section h3 {
  display: none;
}

.admin-sidebar.collapsed .sidebar-link {
  justify-content: center;
  padding: 12px 8px;
}

.admin-sidebar.collapsed .sidebar-link:hover {
  transform: translateX(0);
}

.admin-sidebar.collapsed .sidebar-link.active::before {
  top: 8px;
  bottom: 8px;
  left: 50%;
  transform: translateX(-50%);
  width: 24px;
  height: 4px;
  border-radius: 4px;
}

.admin-sidebar.collapsed .link-badge {
  position: absolute;
  top: 8px;
  right: 8px;
  margin: 0;
}

.admin-sidebar.collapsed .sidebar-footer {
  display: none;
}

/* Animaciones suaves */
* {
  transition-duration: 0.3s;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
