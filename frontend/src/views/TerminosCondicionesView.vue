<template>
  <div class="static-view">
    <section class="hero-section">
      <div class="container">
        <h1 class="hero__title">⚖️ Términos y Condiciones</h1>
        <p class="hero__subtitle">Condiciones de venta y uso correcto de nuestra plataforma</p>
      </div>
    </section>

    <section class="content-section">
      <div class="container document-style">
        <h2>1. Identidad del Titular Web</h2>
        <p>Las condiciones aquí recogidas rigen el uso de este sitio web y el correspondiente acuerdo para las adquisiciones entre tú y nosotros. El mero acceso a esta página web nos exime de responsabilidades que puedan surgir fuera del uso intencionado descrito.</p>

        <h2>2. Uso del Sitio Web</h2>
        <p>El Usuario se compromete a realizar un uso adecuado y lícito del Sitio Web así como de los contenidos y servicios, de conformidad con la legislación aplicable en cada momento, las presentes Condiciones Generales de Uso, la moral, así como las buenas costumbres.</p>
        <p>Está prohibido usar software que comprometa la integridad estructural o extraiga datos sin autorización.</p>

        <h2>3. Proceso de Presupuesto o Compra</h2>
        <p>Para poder hacer compras en esta plataforma necesitas estar debidamente registrado, o en su defecto aceptar el tratamiento en la fase de pago de "invitado". Te expondremos siempre el subtotal y los impuestos (IVA aplicable) antes de la finalización de un pago con tu entidad bancaria.</p>

        <h2>4. Precios y Forma de pago</h2>
        <p>Nuestros precios se muestran con carácter ordinario con los impuestos correspondientes si procede. Utilizamos pasarelas de pago seguro para salvaguardar tu capacidad de pago. Nos reservamos el derecho de revisión de precios en cualquier momento en el catálogo de productos.</p>

        <h2>5. Envíos y Reclamaciones</h2>
        <p>Trataremos que los envíos se tramiten en un límite razonable nunca superior a tres semanas desde la formalización a menos que el producto estuviese explícitamente sin stock de reserva.</p>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'TerminosCondicionesView'
}
</script>

<style scoped>
.static-view {
  min-height: 100vh;
  background: #121418;
  color: #EAEAEA;
}

.hero-section {
  background: linear-gradient(135deg, #4b5563 0%, #1f2937 100%);
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
  color: #00A1FF;
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

.document-style strong {
  color: #10b981;
}

@media (max-width: 768px) {
  .hero__title { font-size: 2.2rem; }
  .document-style { padding: 30px 20px; }
}
</style>
