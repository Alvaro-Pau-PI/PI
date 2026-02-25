<template>
  <div class="admin-layout">
    <!-- Barra Lateral (Sidebar) -->
    <aside class="admin-sidebar">
      <div class="sidebar-header">
        <span class="material-icons header-icon">admin_panel_settings</span>
        <h2>Panel Admin</h2>
      </div>

      <nav class="sidebar-nav">
        <!-- Enlace a Productos -->
        <router-link to="/admin/products" class="sidebar-link" active-class="active">
          <span class="material-icons">inventory_2</span>
          <span class="link-text">Productos</span>
        </router-link>
        <!-- Enlace a Usuarios -->
        <router-link to="/admin/users" class="sidebar-link" active-class="active">
          <span class="material-icons">people</span>
          <span class="link-text">Usuarios</span>
        </router-link>
        <!-- Enlace a Ressenyes -->
        <router-link to="/admin/reviews" class="sidebar-link" active-class="active">
          <span class="material-icons">rate_review</span>
          <span class="link-text">Reseñas</span>
        </router-link>
        <!-- Enlace a Mensajes de Contacto (NUEVO) -->
        <router-link to="/admin/contacts" class="sidebar-link" active-class="active">
          <span class="material-icons">email</span>
          <span class="link-text">Mensajes</span>
        </router-link>
        <!-- Enlace a Pedidos con Badge de Notificación -->
        <router-link to="/admin/orders" class="sidebar-link" active-class="active">
          <span class="material-icons">receipt_long</span>
          <span class="link-text">Pedidos</span>
          <span v-if="pendingCount > 0" class="notif-badge">{{ pendingCount }}</span>
        </router-link>
      </nav>

      <div class="sidebar-footer">
        <router-link to="/profile" class="sidebar-link back-link">
          <span class="material-icons">manage_accounts</span>
          <span class="link-text">Mi perfil</span>
        </router-link>
        <router-link to="/" class="sidebar-link back-link">
          <span class="material-icons">store</span>
          <span class="link-text">Volver a la tienda</span>
        </router-link>
      </div>
    </aside>

    <!-- Contenido principal (Vista de la ruta secundaria) -->
    <main class="admin-main">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import http from '@/services/http';

/**
 * Maqueta base para el panel de administración.
 * Gestiona la navegación lateral y el conteo de pedidos pendientes.
 */

const pendingCount = ref(0);

// Obtener el número de pedidos pendientes al cargar el componente
onMounted(async () => {
  try {
    const response = await http.get('/api/admin/orders', { params: { status: 'pending' } });
    pendingCount.value = response.data?.total || response.data?.data?.length || 0;
  } catch {
    // Si falla la petición, simplemente no mostramos el badge
  }
});
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: calc(100vh - 70px);
}

/* Estilos de la Sidebar */
.admin-sidebar {
  width: 260px;
  background: rgba(15, 18, 25, 0.95);
  border-right: 1px solid rgba(255,255,255,0.06);
  display: flex;
  flex-direction: column;
  padding: 24px 0;
  position: sticky;
  top: 70px;
  height: calc(100vh - 70px);
  flex-shrink: 0;
}

.sidebar-header {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 0 24px 24px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  margin-bottom: 16px;
}

.header-icon {
  font-size: 1.8rem;
  background: linear-gradient(135deg, #fbbf24, #f59e0b);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.sidebar-header h2 {
  font-size: 1.15rem;
  font-weight: 700;
  background: linear-gradient(to right, #fff, #94a3b8);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  margin: 0;
}

/* Estilos de los enlaces de navegación */
.sidebar-nav {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding: 0 12px;
}

.sidebar-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 13px 16px;
  border-radius: 12px;
  color: #8896AB;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.95rem;
  transition: all 0.2s;
  position: relative;
}

.sidebar-link:hover {
  background: rgba(255,255,255,0.05);
  color: #CBD5E1;
}

.sidebar-link.active {
  background: linear-gradient(135deg, rgba(0, 161, 255, 0.12), rgba(0, 212, 170, 0.08));
  color: #00A1FF;
  font-weight: 600;
}

.sidebar-link.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 8px;
  bottom: 8px;
  width: 3px;
  background: linear-gradient(to bottom, #00A1FF, #00D4AA);
  border-radius: 0 3px 3px 0;
}

.sidebar-link .material-icons {
  font-size: 1.3rem;
  width: 24px;
  text-align: center;
}

/* Badge de notificaciones en rojo */
.notif-badge {
  margin-left: auto;
  background: #ef4444;
  color: #fff;
  font-size: 0.7rem;
  font-weight: 700;
  min-width: 22px;
  height: 22px;
  border-radius: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 6px;
}

.sidebar-footer {
  padding: 16px 12px 0;
  border-top: 1px solid rgba(255,255,255,0.06);
  margin-top: 8px;
}

.back-link {
  color: #64748b !important;
  font-size: 0.85rem;
}
.back-link:hover {
  color: #00A1FF !important;
}

.admin-main {
  flex: 1;
  min-width: 0;
  padding: 0;
  overflow-x: auto;
}

/* Estilos responsivos para tablets y móviles */
@media (max-width: 900px) {
  .admin-layout {
    flex-direction: column;
  }
  .admin-sidebar {
    width: 100%;
    height: auto;
    position: static;
    flex-direction: row;
    padding: 12px;
    gap: 0;
    overflow-x: auto;
  }
  .sidebar-header {
    display: none;
  }
  .sidebar-nav {
    flex-direction: row;
    gap: 8px;
    padding: 0;
    flex: 1;
  }
  .sidebar-link {
    padding: 10px 14px;
    white-space: nowrap;
    font-size: 0.85rem;
  }
  .sidebar-link.active::before {
    display: none;
  }
  .sidebar-footer {
    border-top: none;
    padding: 0;
    margin: 0;
  }
  .back-link .link-text {
    display: none;
  }
}
</style>
