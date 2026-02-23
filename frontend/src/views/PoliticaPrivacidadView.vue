<template>
  <div class="static-view">
    <section class="hero-section">
      <div class="container">
        <h1 class="hero__title">🛡️ Política de Privacidad</h1>
        <p class="hero__subtitle">Protegiendo tus datos al máximo nivel.</p>
      </div>
    </section>

    <section class="content-section">
      <div class="container document-style">
        <h2>1. Información Básica sobre Protección de Datos</h2>
        <p>De acuerdo con la legislación vigente en materia de protección de datos, te informamos que el responsable del tratamiento centralizado de tus datos personales es nuestra entidad organizativa. Estos datos serán objeto de tratamiento para gestionar las relaciones generadas con la base de clientes.</p>

        <h2>2. Finalidad del Tratamiento de los Datos Personales</h2>
        <p>Los datos que nos proporciones a lo largo de tu relación comercial y/o de uso como usuario de nuestros servicios se utilizan con las siguientes finalidades básicas:</p>
        <ul>
          <li>Mantenimiento, desarrollo y control de la relación contractual, a través de compras.</li>
          <li>Gestión de contacto e incidencias.</li>
          <li>Acciones de márketing si te inscribes previamente.</li>
        </ul>

        <h2>3. Plazo de Conservación de sus Datos</h2>
        <p>Los datos proporcionados se conservarán mientras se mantenga la relación comercial, o durante los años necesarios para cumplir con las obligaciones legales aplicables.</p>

        <h2>4. Legitimación para el Tratamiento</h2>
        <p>La base legal para el tratamiento de tus datos es la ejecución de un contrato o nuestro interés legítimo para la funcionalidad administrativa de nuestra página.</p>

        <h2>5. Derechos del Usuario</h2>
        <p>Tienes derecho a obtener confirmación sobre si estamos tratando tus datos personales por tanto, tienes la potestad de ejercer los siguientes derechos: <strong>Acceso, Rectificación, Supresión u Olvido, Oposición, Limitación y Portabilidad</strong>.</p>
        <p>Podrás ejercer tus derechos mediante correo electrónico en nuestra página de <router-link to="/contact">Contacto</router-link>.</p>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'PoliticaPrivacidadView'
}
</script>

<style scoped>
.static-view {
  min-height: 100vh;
  background: #121418;
  color: #EAEAEA;
}

.hero-section {
  background: linear-gradient(135deg, #111827 0%, #030712 100%);
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

.document-style a {
  color: #10b981;
  text-decoration: underline;
  transition: color 0.3s;
}

.document-style a:hover {
  color: #059669;
}

@media (max-width: 768px) {
  .hero__title { font-size: 2.2rem; }
  .document-style { padding: 30px 20px; }
}
</style>
