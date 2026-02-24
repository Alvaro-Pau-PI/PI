<template>
  <div class="static-view">
    <section class="hero-section">
      <div class="container">
        <h1 class="hero__title">{{ $t('legal.cookies_t') }}</h1>
        <p class="hero__subtitle">{{ $t('legal.cookies_s') }}</p>
      </div>
    </section>

    <section class="content-section">
      <div class="container document-style">
        <h2>{{ $t('legalb.cookies1_t') }}</h2>
        <p>{{ $t('legalb.cookies1_b') }}</p>

        <h2>{{ $t('legalb.cookies2_t') }}</h2>
        <p>{{ $t('legalb.cookies2_b') }}</p>
        <ul>
          <li><strong>Cookies técnicas:</strong> Imprescindibles para que puedas navegar con fluidez, gestionar tu carrito de compra y mantener el inicio de sesión como usuario de nuestra aplicación. Son siempre necesarias.</li>
          <li><strong>Cookies de personalización:</strong> Mantienen tus preferencias de diseño o idioma para tu deleite personal y facilitar tu comodidad.</li>
        </ul>

        <h2>{{ $t('legalb.cookies3_t') }}</h2>
        <p>{{ $t('legalb.cookies3_b') }}</p>

        <h2>{{ $t('legalb.cookies4_t') }}</h2>
        <p>{{ $t('legalb.cookies4_b') }}</p>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'PoliticaCookiesView'
}
</script>

<style scoped>
.static-view {
  min-height: 100vh;
  background: #121418;
  color: #EAEAEA;
}

.hero-section {
  background: linear-gradient(135deg, #7c2d12 0%, #451a03 100%);
  color: white;
  padding: 80px 20px;
  text-align: center;
  border-bottom: 2px solid #00A1FF;
}

.hero__title {
  font-size: 3rem;
  font-weight: 800;
  margin: 0 0 15px 0;
}

.hero__subtitle {
  font-size: 1.3rem;
  color: #9BA3B0;
}

.content-section {
  padding: 60px 20px;
}

.container {
  max-width: 900px;
  margin: 0 auto;
}

.document-style {
  background: #1A1D24;
  padding: 50px;
  border-radius: 12px;
  border: 1px solid #3A4150;
  box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.document-style h2 {
  color: #10b981;
  font-size: 1.5rem;
  margin-top: 30px;
  margin-bottom: 15px;
  border-bottom: 1px solid #3A4150;
  padding-bottom: 10px;
}

.document-style h2:first-of-type {
  margin-top: 0;
}

.document-style p {
  color: #EAEAEA;
  font-size: 1.1rem;
  line-height: 1.8;
  margin-bottom: 15px;
}

.document-style ul {
  padding-left: 25px;
  color: #EAEAEA;
  margin-bottom: 20px;
}

.document-style li {
  margin-bottom: 10px;
  font-size: 1.1rem;
  line-height: 1.6;
}

.document-style strong {
  color: #00A1FF;
}

@media (max-width: 768px) {
  .hero__title { font-size: 2.2rem; }
  .document-style { padding: 30px 20px; }
}
</style>
