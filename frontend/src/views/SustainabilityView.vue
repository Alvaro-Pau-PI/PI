<template>
  <div class="sustainability-view">
    <!-- Hero Section -->
    <section class="sustainability-hero">
      <div class="container">
        <h1 class="sustainability-hero__title">
          🌱 Compromiso con el Planeta
        </h1>
        <p class="sustainability-hero__subtitle">
          Construyendo un futuro sostenible, un componente a la vez
        </p>
      </div>
    </section>

    <!-- Estadísticas Generales -->
    <section class="sustainability-stats">
      <div class="container">
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-card__icon">♻️</div>
            <div class="stat-card__value">{{ stats.sustainable_products }}</div>
            <div class="stat-card__label">Productos Sostenibles</div>
          </div>
          
          <div class="stat-card">
            <div class="stat-card__icon">📊</div>
            <div class="stat-card__value">{{ stats.sustainability_percentage }}%</div>
            <div class="stat-card__label">Catálogo Eco</div>
          </div>
          
          <div class="stat-card">
            <div class="stat-card__icon">🔄</div>
            <div class="stat-card__value">{{ stats.refurbished_count }}</div>
            <div class="stat-card__label">Reacondicionados</div>
          </div>
          
          <div class="stat-card">
            <div class="stat-card__icon">🏠</div>
            <div class="stat-card__value">{{ stats.local_suppliers_count }}</div>
            <div class="stat-card__label">Proveedores Locales</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Explicación de Etiquetas Eco -->
    <section class="eco-labels-explanation">
      <div class="container">
        <h2 class="section-title">🏷️ Nuestras Etiquetas de Sostenibilidad</h2>
        <p class="section-description">
          Cada producto sostenible cuenta con etiquetas claras que te ayudan a tomar decisiones informadas
        </p>
        
        <div class="labels-grid">
          <div class="label-card">
            <div class="label-card__badge eco-badge--score eco-badge--excellent">🌿 80+</div>
            <h3 class="label-card__title">Eco Score Excelente</h3>
            <p class="label-card__description">
              Productos con puntuación ecológica de 80 o superior. Máxima eficiencia y mínimo impacto ambiental.
            </p>
          </div>
          
          <div class="label-card">
            <div class="label-card__badge eco-badge--refurbished">♻️ Reacondicionado</div>
            <h3 class="label-card__title">Producto Reacondicionado</h3>
            <p class="label-card__description">
              Segunda vida para componentes de calidad. Ahorro de recursos y reducción de residuos electrónicos.
            </p>
          </div>
          
          <div class="label-card">
            <div class="label-card__badge eco-badge--packaging">📦 Embalaje Eco</div>
            <h3 class="label-card__title">Embalaje Reciclado</h3>
            <p class="label-card__description">
              Embalaje 100% reciclado y reciclable. Menos plástico, más sostenibilidad.
            </p>
          </div>
          
          <div class="label-card">
            <div class="label-card__badge eco-badge--local">🏠 Local</div>
            <h3 class="label-card__title">Proveedor Local</h3>
            <p class="label-card__description">
              Distribuido localmente. Menor huella de carbono en transporte y apoyo a la economía local.
            </p>
          </div>
          
          <div class="label-card">
            <div class="label-card__badge eco-badge--recyclable">🌱 Reciclable</div>
            <h3 class="label-card__title">Materiales Reciclables</h3>
            <p class="label-card__description">
              Fabricado con materiales reciclables. Diseñado para facilitar su reciclaje al final de su vida útil.
            </p>
          </div>
          
          <div class="label-card">
            <div class="label-card__badge eco-badge--carbon">🌍 Baja Huella</div>
            <h3 class="label-card__title">Huella de Carbono Reducida</h3>
            <p class="label-card__description">
              Menos de 5 kg de CO₂ en su ciclo de vida. Comprometidos con la reducción de emisiones.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Economía Circular -->
    <section class="circular-economy">
      <div class="container">
        <h2 class="section-title">🔄 Economía Circular</h2>
        <p class="section-description">
          Productos reacondicionados que demuestran que calidad y sostenibilidad van de la mano
        </p>
        
        <div v-if="isLoadingProducts" class="loading">
          <p>Cargando productos sostenibles...</p>
        </div>
        
        <div v-else-if="sustainableProducts.length > 0" class="products-grid">
          <ProductCard 
            v-for="product in sustainableProducts" 
            :key="product.id"
            :product="product"
          />
        </div>
        
        <div v-else class="no-products">
          <p>No hay productos sostenibles disponibles actualmente.</p>
        </div>
        
        <div class="cta-button-wrapper">
          <router-link to="/products?sustainable_only=true" class="cta-button">
            Ver todos los productos sostenibles →
          </router-link>
        </div>
      </div>
    </section>

    <!-- Políticas ASG -->
    <section class="asg-policies">
      <div class="container">
        <h2 class="section-title">⚖️ Nuestros Pilares ASG</h2>
        
        <div class="policies-grid">
          <!-- Pilar Ambiental -->
          <div class="policy-card">
            <div class="policy-card__icon">🌍</div>
            <h3 class="policy-card__title">Ambiental</h3>
            <ul class="policy-card__list">
              <li>✅ Optimización de recursos web (WebP, lazy loading)</li>
              <li>✅ Promoción de productos reacondicionados</li>
              <li>✅ Medición de huella de carbono</li>
              <li>✅ Embalajes reciclados y reciclables</li>
              <li>✅ Reducción de peso de página en 40%+</li>
            </ul>
          </div>
          
          <!-- Pilar Social -->
          <div class="policy-card">
            <div class="policy-card__icon">👥</div>
            <h3 class="policy-card__title">Social</h3>
            <ul class="policy-card__list">
              <li>✅ Accesibilidad WCAG AA completa</li>
              <li>✅ Información clara y transparente</li>
              <li>✅ UX inclusiva sin barreras</li>
              <li>✅ Navegación por teclado optimizada</li>
              <li>✅ Contraste de colores validado</li>
            </ul>
          </div>
          
          <!-- Pilar Gobernanza -->
          <div class="policy-card">
            <div class="policy-card__icon">⚖️</div>
            <h3 class="policy-card__title">Gobernanza</h3>
            <ul class="policy-card__list">
              <li>✅ Código documentado y trazable</li>
              <li>✅ Criterios eco verificables</li>
              <li>✅ Políticas publicadas y transparentes</li>
              <li>✅ Mejora continua documentada</li>
              <li>✅ Métricas de sostenibilidad públicas</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Compromisos Futuros -->
    <section class="commitments">
      <div class="container">
        <h2 class="section-title">🎯 Nuestros Objetivos 2026</h2>
        
        <div class="commitments-list">
          <div class="commitment-item">
            <div class="commitment-item__number">50%</div>
            <div class="commitment-item__text">del catálogo con etiqueta eco</div>
          </div>
          
          <div class="commitment-item">
            <div class="commitment-item__number">100%</div>
            <div class="commitment-item__text">proveedores con certificación ambiental</div>
          </div>
          
          <div class="commitment-item">
            <div class="commitment-item__number">-60%</div>
            <div class="commitment-item__text">reducción de huella de carbono web</div>
          </div>
          
          <div class="commitment-item">
            <div class="commitment-item__number">95+</div>
            <div class="commitment-item__text">score Lighthouse Accessibility</div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import ProductCard from '@/components/ProductCard.vue';
import axios from 'axios';

export default {
  name: 'SustainabilityView',
  
  components: {
    ProductCard
  },
  
  data() {
    return {
      stats: {
        total_products: 0,
        sustainable_products: 0,
        sustainability_percentage: 0,
        avg_eco_score: 0,
        refurbished_count: 0,
        local_suppliers_count: 0,
        avg_carbon_footprint: '0 kg CO2'
      },
      sustainableProducts: [],
      isLoadingStats: true,
      isLoadingProducts: true
    }
  },
  
  mounted() {
    this.loadSustainabilityStats();
    this.loadSustainableProducts();
  },
  
  methods: {
    async loadSustainabilityStats() {
      try {
        const response = await axios.get('/api/products/sustainability-stats');
        this.stats = response.data;
      } catch (error) {
        console.error('Error cargando estadísticas de sostenibilidad:', error);
        // Datos simulados para demostración
        this.stats = {
          total_products: 48,
          sustainable_products: 12,
          sustainability_percentage: 25,
          avg_eco_score: 72,
          refurbished_count: 8,
          local_suppliers_count: 5,
          avg_carbon_footprint: '4.2 kg CO2'
        };
      } finally {
        this.isLoadingStats = false;
      }
    },
    
    async loadSustainableProducts() {
      try {
        const response = await axios.get('/api/products/sustainable', {
          params: {
            min_eco_score: 70,
            per_page: 6
          }
        });
        this.sustainableProducts = response.data.data || [];
        
        // Si no hay datos, usamos simulados
        if (this.sustainableProducts.length === 0) {
          this.sustainableProducts = this.getMockProducts();
        }
      } catch (error) {
        console.error('Error cargando productos sostenibles:', error);
        this.sustainableProducts = this.getMockProducts();
      } finally {
        this.isLoadingProducts = false;
      }
    },

    getMockProducts() {
      return [
        {
          id: 1, // IDs que suelen venir por defecto en el seeder
          name: 'NVIDIA RTX 3080 Reconditionat',
          category: 'Targetes Gràfiques',
          price: 499.99,
          image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&q=80&w=800',
          eco_score: 85,
          is_refurbished: true,
          is_recyclable: true,
          has_eco_packaging: true,
          is_local_supplier: true,
          carbon_footprint: 2.5,
          stock: 5,
          description: 'Targeta gràfica d\'alta gamma reacondicionada professionalment.'
        },
        {
          id: 2,
          name: 'AMD Ryzen 9 5900X Eco',
          category: 'Processadors',
          price: 329.50,
          image: 'https://images.unsplash.com/photo-1591405351990-4726e331f141?auto=format&fit=crop&q=80&w=800',
          eco_score: 78,
          is_refurbished: false,
          is_recyclable: true,
          has_eco_packaging: true,
          is_local_supplier: false,
          carbon_footprint: 1.8,
          stock: 12,
          description: 'Processador potent amb embalatge certificat 100% lliure de plàstics.'
        },
        {
          id: 3,
          name: 'RAM DDR4 16GB Reciclada',
          category: 'Memòria RAM',
          price: 45.00,
          image: 'https://images.unsplash.com/photo-1547082299-de196ea013d6?auto=format&fit=crop&q=80&w=800', // Foto real de RAM
          eco_score: 92,
          is_refurbished: true,
          is_recyclable: true,
          has_eco_packaging: true,
          is_local_supplier: true,
          carbon_footprint: 0.5,
          stock: 25,
          description: 'Mòduls de memòria testejats per a una segona vida útil amb garantia total.'
        }
      ];
    }
  }
}
</script>

<style scoped>
/* Container */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Hero */
.sustainability-hero {
  background: linear-gradient(135deg, #065f46 0%, #064e3b 100%); /* Verde más oscuro para mejor contraste */
  color: white;
  padding: 100px 20px;
  text-align: center;
  border-bottom: 2px solid #10b981;
}

.sustainability-hero__title {
  font-size: 3.5rem;
  font-weight: 800;
  margin: 0 0 20px 0;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.sustainability-hero__subtitle {
  font-size: 1.5rem;
  color: #EAEAEA; /* Gris muy claro para lectura perfecta */
  max-width: 800px;
  margin: 0 auto;
  font-weight: 500;
  line-height: 1.6;
}

/* Stats Section */
.sustainability-stats {
  padding: 80px 20px;
  background: #121418; /* Fondo general de la app */
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 30px;
}

.stat-card {
  background: #1A1D24; /* Fondo oscuro premium */
  padding: 40px 20px;
  border-radius: 20px;
  text-align: center;
  border: 1px solid #3A4150;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-8px);
  border-color: #10b981;
  box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
}

.stat-card__icon {
  font-size: 3.5rem;
  margin-bottom: 20px;
}

.stat-card__value {
  font-size: 3rem;
  font-weight: 800;
  color: #10b981;
  margin-bottom: 10px;
}

.stat-card__label {
  font-size: 1rem;
  color: #9BA3B0; /* Gris azulado legible */
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: 600;
}

/* Global Section Titles */
.section-title {
  font-size: 2.8rem;
  font-weight: 800;
  text-align: center;
  margin-bottom: 20px;
  color: #00A1FF; /* Azul corporativo */
}

.section-description {
  text-align: center;
  font-size: 1.2rem;
  color: #EAEAEA;
  max-width: 800px;
  margin: 0 auto 60px auto;
  line-height: 1.7;
}

/* Eco Labels Section */
.eco-labels-explanation {
  padding: 80px 20px;
  background: #1A1D24;
}

.labels-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 30px;
}

.label-card {
  background: #242833; /* Fondo ligeramente más claro para contraste */
  padding: 30px;
  border-radius: 16px;
  border: 1px solid #3A4150;
  transition: border-color 0.3s ease;
}

.label-card:hover {
  border-color: #00A1FF;
}

.label-card__badge {
  display: inline-flex;
  padding: 10px 20px;
  font-size: 0.9rem;
  font-weight: 700;
  border-radius: 10px;
  margin-bottom: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.label-card__title {
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 15px;
  color: #FFFFFF; /* Texto blanco puro para títulos */
}

.label-card__description {
  color: #9BA3B0;
  line-height: 1.8;
  margin: 0;
  font-size: 1rem;
}

/* Circular Economy */
.circular-economy {
  padding: 80px 20px;
  background: #121418;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 30px;
  margin-bottom: 60px;
}

.cta-button {
  display: inline-block;
  padding: 18px 40px;
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  border-radius: 12px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
}

.cta-button:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

.cta-button-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  padding: 40px 0;
}

/* ASG Policies - Social, Ambiental, Governanza */
.asg-policies {
  padding: 80px 20px;
  background: #1A1D24;
}

.policies-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 40px;
}

.policy-card {
  background: #242833;
  padding: 40px;
  border-radius: 24px;
  border: 1px solid #3A4150;
  position: relative;
  overflow: hidden;
}

.policy-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 6px;
  background: #10b981;
}

.policy-card:nth-child(2)::before { background: #00A1FF; } /* Azul para Social */
.policy-card:nth-child(3)::before { background: #f59e0b; } /* Naranja para Gobernanza */

.policy-card__icon {
  font-size: 4rem;
  margin-bottom: 20px;
}

.policy-card__title {
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 25px;
  color: #FFFFFF;
}

.policy-card__list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.policy-card__list li {
  padding: 12px 0;
  color: #EAEAEA;
  line-height: 1.5;
  display: flex;
  align-items: flex-start;
  font-size: 1.05rem;
}

/* Commitments */
.commitments {
  background: linear-gradient(135deg, #0f172a 0%, #020617 100%);
  padding: 100px 20px;
}

.commitments-list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 30px;
}

.commitment-item {
  text-align: center;
  padding: 40px;
  background: rgba(255, 255, 255, 0.03);
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
}

.commitment-item__number {
  font-size: 4rem;
  font-weight: 900;
  color: #10b981;
  margin-bottom: 15px;
}

.commitment-item__text {
  font-size: 1.2rem;
  color: #EAEAEA;
  font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
  .sustainability-hero__title { font-size: 2.5rem; }
  .sustainability-hero__subtitle { font-size: 1.2rem; }
  .section-title { font-size: 2rem; }
  .stat-card__value { font-size: 2.5rem; }
}
</style>
