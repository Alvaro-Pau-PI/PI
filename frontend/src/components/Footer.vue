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
          <li><router-link to="/sostenibilidad">🌱 Sostenibilidad</router-link></li>
          <li><router-link to="/guia-montaje">Guía de montaje de PCs</router-link></li>
          <li><router-link to="/faq">FAQ</router-link></li>
        </ul>
      </div>
      <div class="footerEspacio">
        <strong>Legal</strong>
        <ul>
          <li><router-link to="/politica-privacidad">Política de Privacidad</router-link></li>
          <li><router-link to="/terminos-condiciones">Términos y Condiciones</router-link></li>
          <li><router-link to="/politica-cookies">Política de Cookies</router-link></li>
        </ul>
      </div>
    </div>
    <span class="copyright">&copy; {{ new Date().getFullYear() }} AlberoPerez Tech. Todos los derechos reservados.</span>
  </footer>
</template>

<style scoped>
/* Estilos footer específicos si no están en global */
.newsletter-form {
  display: flex;
  margin-top: 15px;
  gap: 10px;
}

.newsletter-form input {
  flex-grow: 1;
  padding: 12px 16px;
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: var(--radius-md, 8px);
  color: var(--text-primary, #eaeaea);
  font-family: inherit;
  transition: all 0.3s ease;
  outline: none;
}

.newsletter-form input:focus {
  border-color: var(--color-primary, #00A1FF);
  box-shadow: 0 0 0 4px rgba(0, 161, 255, 0.15);
  background: rgba(0, 0, 0, 0.4);
}

.newsletter-form button {
  padding: 12px 20px;
  background: linear-gradient(135deg, var(--color-primary, #00A1FF) 0%, var(--color-primary-dark, #007ecc) 100%);
  color: white;
  border: none;
  border-radius: var(--radius-md, 8px);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 161, 255, 0.3);
}

.newsletter-form button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 161, 255, 0.5);
}

.footerEspacio ul {
  list-style: none;
  padding: 0;
}

.footerEspacio ul li {
  margin-bottom: 8px;
}

.footerEspacio ul li a {
  color: #9BA3B0;
  text-decoration: none;
  transition: color 0.2s;
}

.footerEspacio ul li a:hover {
  color: #00A1FF;
}

.copyright {
  display: block;
  text-align: center;
  padding: 20px 0;
  color: #777;
  font-size: 0.9em;
  border-top: 1px solid rgba(255,255,255,0.05);
  margin-top: 30px;
}

@media (max-width: 768px) {
  .footer {
    display: flex;
    flex-direction: column;
    gap: 30px;
    padding: 20px;
  }
}
</style>
