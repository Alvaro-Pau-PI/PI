<template>
  <div class="static-view">
    <section class="hero-section">
      <div class="container">
        <h1 class="hero__title">❓ Preguntas Frecuentes (FAQ)</h1>
        <p class="hero__subtitle">Resolvemos todas tus inquietudes al instante.</p>
      </div>
    </section>

    <section class="content-section">
      <div class="container">
        <!-- FAQ 1 -->
        <div class="faq-card">
          <h2 class="faq-card__question">¿Ofrecéis envíos a domicilio?</h2>
          <div class="faq-card__answer">
            <p>Sí, realizamos envíos a domicilio tanto a nivel nacional como internacional. Los tiempos de tránsito habituales son de 24 a 48 horas tras el procesamiento de tu pedido.</p>
          </div>
        </div>

        <!-- FAQ 2 -->
        <div class="faq-card">
          <h2 class="faq-card__question">¿Qué garantías tienen los productos reacondicionados?</h2>
          <div class="faq-card__answer">
            <p>Todos nuestros productos reacondicionados han pasado un riguroso testeo de estrés y de calidad y cuentan con <strong>1 año entero de garantía</strong>, al igual que nuestro compromiso de sostenibilidad.</p>
          </div>
        </div>

        <!-- FAQ 3 -->
        <div class="faq-card">
          <h2 class="faq-card__question">¿Puedo devolver un componente si no es compatible?</h2>
          <div class="faq-card__answer">
            <p>Dispones de 14 días naturales desde la recepción de tu envío para efectuar cualquier devolución sin penalización, siempre que el embalaje original se encuentre en perfectas condiciones y no presente indicios de maltrato.</p>
          </div>
        </div>

        <!-- FAQ 4 -->
        <div class="faq-card">
          <h2 class="faq-card__question">¿Cuáles son los métodos de pago aceptados?</h2>
          <div class="faq-card__answer">
            <p>Aceptamos tarjetas de crédito o débito (Visa, MasterCard, American Express) de forma totalmente segura a través de nuestra pasarela cifrada.</p>
          </div>
        </div>

        <!-- FAQ 5 -->
        <div class="faq-card">
          <h2 class="faq-card__question">¿Cómo puedo aplicar un descuento de Eco-Score?</h2>
          <div class="faq-card__answer">
            <p>Añadiendo productos con alto Eco-Score a tu cesta y acudiendo a tu área de cliente, en alguna de las promociones te indicarán como se aplica mecánicamente en tu pedido.</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'PreguntasFrecuentesView'
}
</script>

<style scoped>
.static-view {
  min-height: 100vh;
  background: #121418;
  color: #EAEAEA;
}

.hero-section {
  background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%);
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
  max-width: 800px;
  margin: 0 auto;
}

.faq-card {
  background: #1A1D24;
  border-radius: 12px;
  border: 1px solid #3A4150;
  margin-bottom: 20px;
  padding: 30px;
  transition: all 0.3s ease;
}

.faq-card:hover {
  border-color: #10b981;
  box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);
  transform: translateX(10px);
}

.faq-card__question {
  color: #00A1FF;
  font-size: 1.4rem;
  margin-bottom: 15px;
  font-weight: 700;
}

.faq-card__answer p {
  color: #9BA3B0;
  font-size: 1.1rem;
  line-height: 1.6;
  margin: 0;
}

.faq-card__answer strong {
  color: #FFFFFF;
}

/* Responsive */
@media (max-width: 768px) {
  .hero__title { font-size: 2.2rem; }
  .faq-card { padding: 20px; }
  .faq-card__question { font-size: 1.2rem; }
}
</style>
