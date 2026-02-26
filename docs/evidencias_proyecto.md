# Evidencias y Capturas del Proyecto: AlberoPerezTech

Este documento recopila las evidencias visuales del desarrollo y despliegue del proyecto web **AlberoPerezTech** (E-commerce de componentes de ordenador). Se divide en diferentes áreas del proyecto para ilustrar la arquitectura, la base de datos, los flujos de la interfaz de usuario y la automatización con IA.

---

## 1. Arquitectura Cloud e Infraestructura en AWS

El proyecto se despliega en Amazon Web Services utilizando diversas capas de red y bases de datos completamente configuradas.

* **Instancia EC2 (Servidor Web):** El corazón de la aplicación donde se despliegan los contenedores Docker mediante `docker-compose`.
  ![AWS EC2 Running](img/evidencias/aws_01_ec2_running.png)

* **Grupos de Seguridad (Firewall):** Reglas estrictas configuradas para permitir tráfico HTTP/HTTPS y SSH restringido.
  ![AWS Security Groups](img/evidencias/aws_02_security_groups.png)

* **Dirección IP Elástica (Elastic IP):** IP fija asignada a la instancia para garantizar que el DNS y los registros no se rompan tras los reinicios.
  ![AWS Elastic IP](img/evidencias/aws_03_ec2_elastic_ip.png)

* **Balanceador de Carga (Application Load Balancer):** Distribución del tráfico entrante y manejo de las terminaciones SSL.
  ![AWS Load Balancer](img/evidencias/aws_04_load_balancer.png)

* **Gestión de DNS (Route 53):** Zona hospedada pública para el enrutamiento del nombre de dominio hacia la infraestructura.
  ![AWS Route 53 Zone](img/evidencias/aws_05_route53_zone.png)

* **Certificados SSL (ACM):** Certificado público emitido por Amazon Certificate Manager para garantizar conexiones seguras HTTPS.
  ![AWS ACM SSL](img/evidencias/aws_06_acm_ssl.png)

* **Base de datos Gestionada (Amazon RDS):** Uso de bases de datos relacionales en la nube (MySQL/Aurora) para alta disponibilidad.
  ![AWS RDS Aurora](img/evidencias/aws_07_rds_aurora.png)

* **VPC y Subredes:** Red virtual privada donde reside la plataforma, garantizando aislamiento de componentes internos.
  ![AWS VPC General](img/evidencias/aws_08_vpc_general.png)

---

## 2. Desarrollo Backend y APIs (Laravel)

El motor principal de los datos es robusto y se estructura acorde al patrón MVC.

* **Estructura de la Base de Datos:** Tablas relacionales para manejar usuarios, roles, productos, pedidos y valoraciones.
  ![Backend DB Tablas](img/evidencias/back_01_db_tablas.png)

* **Endpoints de la API (Rutas):** Capa de servicios RESTful protegida con Sanctum para surtir al frontend.
  ![Backend API](img/evidencias/back_02_api.png)

---

## 3. DevOps, CI/CD y Contenedores

La infraestructura como código y la automatización del despliegue agilizan las subidas a producción.

* **Github Actions (CI/CD):** Flujo de trabajo automatizado que orquesta el testing y despliegue cada vez que se hace push a la rama principal.
  ![Devops Github Actions](img/evidencias/devops_03_github_actions.png)

* **Docker Compose (Producción):** Orquestación de múltiples contenedores aislados (Nginx, PHP-FPM, MySQL, n8n, Frontend Vue).
  ![Devops Docker Compose Code](img/evidencias/devops_02_docker_compose_code.png)

* **Contenedores en Ejecución (AWS):** Verificación de los servicios levantados y expuestos en los puertos correctos de la instancia.
  ![Devops Docker PS Prod](img/evidencias/devops_01_docker_ps_prod.png)

---

## 4. Frontend: Módulo de Administración (Vue.js)

Espacio privado para la gestión de la tienda por parte de los administradores y trabajadores.

* **Dashboard Principal:** Panel de resumen y atajos de las operaciones comerciales.
  ![Admin Dashboard](img/evidencias/front_admin_01_dashboard.png)

* **Gestión de Usuarios:** CRUD para editar administradores, trabajadores y revocar accesos.
  ![Admin Usuarios Lista](img/evidencias/front_admin_02_usuarios_lista.png)

* **Inventario e Importación CSV:** Herramienta para gestión masiva de stock mediante importación/exportación de archivos.
  ![Admin Inventario CSV](img/evidencias/front_admin_03_inventario_csv.png)

* **Creación de Productos:** Formulario detallado que acepta múltiples imágenes (Dropzone) y opciones avanzadas.
  ![Admin Form Producto](img/evidencias/front_admin_04_form_producto.png)

* **Gestión de Pedidos:** Listado de ventas y cambio de estados logísticos de cada paquete.
  ![Admin Gestión Pedidos](img/evidencias/front_admin_05_gestion_pedidos.png)

* **Moderación de Reseñas:** Panel para revisar y autorizar comentarios de los clientes sobre los productos.
  ![Admin Reseñas](img/evidencias/front_admin_06_gestion_resenas.png)

* **Mensajes de Contacto:** Buzón integrado que recibe los formularios de soporte enviados por la tienda pública.
  ![Admin Mensajes](img/evidencias/front_admin_07_gestion_mensajes.png)

---

## 5. Frontend: Experiencia Pública y Tienda (Vue.js)

La web orientada al consumidor, con un diseño oscuro, dinámico y moderno adaptado a componentes de ordenador.

* **Buscador y Construcción (Home):** Vista principal con acceso directo a productos y animaciones de bienvenida.
  ![Public Home](img/evidencias/front_pub_01_home.png)

* **Catálogo y Filtros:** Búsqueda avanzada por categorías, rangos de precio y sostenibilidad.
  ![Public Catálogo](img/evidencias/front_pub_03_catalogo_filtros.png)

* **Detalle del Producto:** Vista ampliada de un componente, galería de imágenes en carrusel y botón de añadir al carro.
  ![Public Detalle Producto](img/evidencias/front_pub_04_producto_detalle.png)

* **Identidad Eco y Sostenibilidad:** Foco en componentes de eficiencia energética y opciones respetuosas medioambientalmente.
  ![Public Sostenibilidad](img/evidencias/front_pub_02_sostenibilidad.png)

* **Carrito de la Compra:** Resumen flotante y detallado con cálculos de subtotales y modificación de unidades.
  ![Public Carrito](img/evidencias/front_pub_05_carrito.png)

* **Proceso de Pago (Checkout):** Formulario final para recolectar datos de facturación y envío.
  ![Public Checkout](img/evidencias/front_pub_06_checkout.png)

* **Autenticación (Login / Registro):** Accesos de seguridad para cuentas de clientes.
  ![Public Login](img/evidencias/front_pub_07_auth_login.png)

* **Perfil y Mis Pedidos:** Los clientes pueden visualizar su historial.
  ![Public Perfil Pedidos](img/evidencias/front_pub_08_perfil_pedidos.png)

* **Términos y Privacidad (Legales):** Generación de contenido legal pertinente al comercio europeo.
  ![Public Legales](img/evidencias/front_pub_09_legales.png)

* **Modo Distribución (Build):** Empaquetado optimizado del código Vue.js listo para los servidores estáticos.
  ![Public Build](img/evidencias/front_pub_11_build.png)

---

## 6. Automatización e IA (n8n)

Implementación de automatismos mediante flujos nodales, integrando IA Generativa.

* **Flujo de Chatbot con IA:** Workflow completo donde un modelo de lenguaje responde dudas instantáneas en la web en base a las reglas de la tienda.
  ![N8N Flujo IA](img/evidencias/n8n_01_flujo_chatbot_ia.png)

* **Chatbot UI Integrado:** El cliente chateando en tiempo real con el asistente virtual en el E-commerce.
  ![N8N Chat UI](img/evidencias/n8n_02_chatbot_ui_charlando.png)

* **Flujo de Correos Automático:** Workflow que escucha la recepción de correos a direcciones concretas y emite respuestas automáticas.
  ![N8N Email Flow](img/evidencias/n8n_03_flujo_contacto_email.png)

* **Correo Recibido (Webhook trigger):** Captura en n8n de la solicitud entrante por parte de un cliente.
  ![N8N Correo Recibido](img/evidencias/n8n_04_correo_entrada_recibido.png)

* **Correo de Respuesta Enviado (Action action):** Mail automatizado en la bandeja de salida informando que el soporte procesará la gestión.
  ![N8N Correo Enviado](img/evidencias/n8n_05_correo_entrada_enviado.png)
