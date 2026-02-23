<template>
  <div class="static-view">
    <section class="hero-section">
      <div class="container">
        <h1 class="hero__title">🛠️ Guía de Montaje de PCs</h1>
        <p class="hero__subtitle">Tu guía paso a paso para construir el equipo de tus sueños</p>
      </div>
    </section>

    <section class="content-section">
      <div class="container">
        <div class="content-block">
          <h2>1. Preparación y Herramientas</h2>
          <p>
            Antes de empezar a ensamblar tu nuevo PC, asegúrate de contar con un espacio amplio, limpio y bien iluminado. Necesitarás:
          </p>
          <ul>
            <li>Destornillador de estrella (Phillips) de tamaño mediano (idealmente magnético).</li>
            <li>Pulsera antiestática (opcional pero recomendada).</li>
            <li>Bridas o cintas de velcro para la gestión de cables.</li>
            <li>Pasta térmica extra (aunque muchos sistemas de refrigeración ya la tienen pre-aplicada).</li>
          </ul>
        </div>

        <div class="content-block">
          <h2>2. Instalación de la CPU y Memoria RAM</h2>
          <p>
            Es más fácil instalar estos componentes en la placa base <strong>antes</strong> de colocarla dentro de la caja.
          </p>
          <ul>
            <li><strong>Procesador (CPU):</strong> Levanta la palanca del socket, alinea el triángulo dorado de la CPU con la marca del socket y déjala caer suavemente sin hacer presión. Baja la palanca para fijarla.</li>
            <li><strong>Memoria RAM:</strong> Abre las pestañas de las ranuras DIMM (suele recomendarse ocupar los slots 2 y 4 primero para Dual Channel). Presiona el módulo hacia abajo por ambos extremos hasta escuchar un "clic".</li>
          </ul>
        </div>

        <div class="content-block">
          <h2>3. Almacenamiento M.2 y Disipador</h2>
          <p>
            Si tienes un SSD NVMe M.2, localiza la ranura en la placa base, insértalo en un ángulo de unos 30 grados y bájalo hasta atornillarlo suavemente. 
            A continuación, instala el sistema de refrigeración sobre la CPU (quitando el plástico protector si tiene pasta térmica pre-aplicada).
          </p>
        </div>

        <div class="content-block">
          <h2>4. Montaje de la Placa Base en la Caja</h2>
          <p>
            Instala el protector I/O en la parte trasera de la caja (si tu placa no lo lleva integrado). Asegúrate de que los separadores metálicos de la caja coincidan con los agujeros de tu placa base. 
            Colora la placa y atorníllala en forma de cruz sin forzar demasiado.
          </p>
        </div>

        <div class="content-block">
          <h2>5. Fuente de Alimentación y Cableado Frontal</h2>
          <p>
            Instala la fuente de alimentación (PSU) en la parte inferior de la caja con el ventilador apuntando hacia abajo o hacia el filtro antipolvo. Pasa los cables EPS de la CPU, el ATX de 24 pines y los PCI-e requeridos para la tarjeta gráfica hacia la parte delantera de la placa.
          </p>
          <p>
            Conecta los diminutos cables del panel frontal (Power SW, Reset SW, HD LED) de la caja en los pines indicados en el manual de tu placa base.
          </p>
        </div>

        <div class="content-block">
          <h2>6. Tarjeta Gráfica y Detalles Finales</h2>
          <p>
            Retira las tapas metálicas traseras necesarias de la caja. Inserta la GPU en la ranura PCIe principal (la más cercana al procesador) hasta hacer "clic" y atorníllala a la estructura. Conecta los cables de alimentación PCIe procedentes de la fuente.
          </p>
          <p>
            ¡Revisa todas las conexiones, organiza los cables (cable management), cierra la caja y prepárate para encenderla e instalar tu Sistema Operativo!
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
