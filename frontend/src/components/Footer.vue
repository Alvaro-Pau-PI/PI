<template>
  <footer>
    <div class="footer">
      <div class="footerEspacio">
        <img src="/img/LOGO AlberoPerezTech.png" alt="Logo AlberoPerez Tech pie">
        <p>Tu tienda de informática y componentes de confianza.</p>
      </div>
      <div class="footerEspacio">
        <strong>¡Suscríbete!</strong>
        <p>Recibe las mejores ofertas y novedades.</p>
        <div class="newsletter-form">
          <label for="newsletter-email" class="sr-only">Correo electrónico para newsletter</label>
          <input 
            id="newsletter-email" 
            type="email" 
            placeholder="Escribe tu email aquí"
            aria-label="Correo electrónico para newsletter"
            required
          >
          <button 
            type="submit" 
            aria-label="Suscribirse al newsletter"
          >
            Suscribirse
          </button>
        </div>
      </div>
      <div class="footerEspacio">
        <strong>Enlaces Útiles</strong>
        <ul>
          <li><router-link to="/contact">Contacto</router-link></li>
          <li><a href="#">Guía de montaje de PCs</a></li>
          <li><a href="#">FAQ</a></li>
        </ul>
      </div>
      <div class="footerEspacio">
        <strong>Legal</strong>
        <ul>
          <li><a href="#">Política de Privacidad</a></li>
          <li><a href="#">Términos y Condiciones</a></li>
          <li><a href="#">Política de Cookies</a></li>
        </ul>
      </div>
    </div>
    <span class="copyright">&copy; {{ new Date().getFullYear() }} AlberoPerez Tech. Todos los derechos reservados.</span>
  </footer>
</template>

<style scoped>
/* Estilos footer si no están en global */
</style>
