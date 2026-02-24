<template>
  <div class="static-view">
    <section class="hero-section">
      <div class="container">
        <h1 class="hero__title">{{ $t('guide.title') }}</h1>
        <p class="hero__subtitle">{{ $t('guide.subtitle') }}</p>
      </div>
    </section>

    <section class="content-section">
      <div class="container">
        <div class="content-block">
          <h2>{{ $t('legalb.guide1_t') }}</h2>
          <p>
            {{ $t('legalb.guide1_b') }}
          </p>
          <ul>
            <li>{{ $t('legalb.guide1_l1') }}</li>
            <li>{{ $t('legalb.guide1_l2') }}</li>
            <li>{{ $t('legalb.guide1_l3') }}</li>
            <li>{{ $t('legalb.guide1_l4') }}</li>
          </ul>
        </div>

        <div class="content-block">
          <h2>{{ $t('legalb.guide2_t') }}</h2>
          <p>
            {{ $t('legalb.guide2_b') }}
          </p>
          <ul>
            <li><strong>Procesador (CPU):</strong> Levanta la palanca del socket, alinea el triángulo dorado de la CPU con la marca del socket y déjala caer suavemente sin hacer presión. Baja la palanca para fijarla.</li>
            <li><strong>Memoria RAM:</strong> Abre las pestañas de las ranuras DIMM (suele recomendarse ocupar los slots 2 y 4 primero para Dual Channel). Presiona el módulo hacia abajo por ambos extremos hasta escuchar un "clic".</li>
          </ul>
        </div>

        <div class="content-block">
          <h2>{{ $t('legalb.guide3_t') }}</h2>
          <p>
            {{ $t('legalb.guide3_b') }}
          </p>
        </div>

        <div class="content-block">
          <h2>{{ $t('legalb.guide4_t') }}</h2>
          <p>
            {{ $t('legalb.guide4_b') }}
          </p>
        </div>

        <div class="content-block">
          <h2>{{ $t('legalb.guide5_t') }}</h2>
          <p>
            {{ $t('legalb.guide5_b1') }}
          </p>
          <p>
            {{ $t('legalb.guide5_b2') }}
          </p>
        </div>

        <div class="content-block">
          <h2>{{ $t('legalb.guide6_t') }}</h2>
          <p>
            {{ $t('legalb.guide6_b1') }}
          </p>
          <p>
            {{ $t('legalb.guide6_b2') }}
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'GuiaMontajeView'
}
</script>

<style scoped>
.static-view {
  min-height: 100vh;
  background: #121418;
  color: #EAEAEA;
}

.hero-section {
  background: linear-gradient(135deg, #10b981 0%, #064e3b 100%);
  color: white;
  padding: 80px 20px;
  text-align: center;
  border-bottom: 2px solid #00A1FF;
}

.hero__title {
  font-size: 3rem;
  font-weight: 800;
  margin: 0 0 15px 0;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.hero__subtitle {
  font-size: 1.3rem;
  color: #EAEAEA;
  max-width: 600px;
  margin: 0 auto;
  font-weight: 500;
}

.content-section {
  padding: 60px 20px;
}

.container {
  max-width: 900px;
  margin: 0 auto;
}

.content-block {
  background: #1A1D24;
  padding: 40px;
  border-radius: 16px;
  border: 1px solid #3A4150;
  margin-bottom: 30px;
  transition: transform 0.3s ease;
}

.content-block:hover {
  transform: translateY(-5px);
  border-color: #10b981;
}

.content-block h2 {
  color: #00A1FF;
  font-size: 1.8rem;
  margin-bottom: 20px;
  border-bottom: 1px solid #3A4150;
  padding-bottom: 10px;
}

.content-block p {
  color: #9BA3B0;
  font-size: 1.1rem;
  line-height: 1.7;
  margin-bottom: 15px;
}

.content-block ul {
  padding-left: 25px;
  color: #9BA3B0;
}

.content-block li {
  margin-bottom: 10px;
  font-size: 1.1rem;
  line-height: 1.6;
}

.content-block strong {
  color: #FFFFFF;
}

/* Responsive */
@media (max-width: 768px) {
  .hero__title { font-size: 2.2rem; }
  .content-block { padding: 25px; }
  .content-block h2 { font-size: 1.5rem; }
}
</style>
