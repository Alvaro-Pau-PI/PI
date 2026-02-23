<template>
  <div class="static-view">
    <section class="hero-section">
      <div class="container">
        <h1 class="hero__title">🍪 Política de Cookies</h1>
        <p class="hero__subtitle">Navegación transparente, control en tus manos</p>
      </div>
    </section>

    <section class="content-section">
      <div class="container document-style">
        <h2>1. ¿Qué son las Cookies?</h2>
        <p>Una cookie es un pequeño archivo de texto que un sitio web almacena en el ordenador o dispositivo móvil del usuario de navegación. Gracias a las cookies, el sitio web recuerda las acciones y preferencias del usuario (tales como inicio de sesión, el idioma, el tamaño de letra u otras preferencias) durante un periodo de tiempo, de manera que no debe volver a configurarlos.</p>

        <h2>2. Cookies Propias que Utilizamos</h2>
        <p>A nivel de nuestra aplicación, podemos hacer uso de las siguientes cookies intrínsecas:</p>
        <ul>
          <li><strong>Cookies técnicas:</strong> Imprescindibles para que puedas navegar con fluidez, gestionar tu carrito de compra y mantener el inicio de sesión como usuario de nuestra aplicación. Son siempre necesarias.</li>
          <li><strong>Cookies de personalización:</strong> Mantienen tus preferencias de diseño o idioma para tu deleite personal y facilitar tu comodidad.</li>
        </ul>

        <h2>3. Cookies de Terceros</h2>
        <p>En caso de integrar analíticas de comportamiento (como Google Analytics) u otros servicios de monitorización, esta página web recabará información estándar de registro y sobre tendencias en general. Para gestionar o deshabilitar estas cookies, puedes proceder siempre desde el control propio de su desarrollador correspondiente.</p>

        <h2>4. Gestión y Eliminación de Cookies</h2>
        <p>La mayoría de los navegadores aceptan las cookies automáticamente, no obstante, puedes modificar la parametrización de tu navegador para declinarlas en caso de que así te plazca. Ten en cuenta que, si deshabilitas algunas cookies u optas por una protección rigurosa de rastreo, secciones completas de la funcionalidad de inicio de sesión o área transaccional dejarán previsiblemente de funcionar.</p>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'CookiesPolicyView'
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
