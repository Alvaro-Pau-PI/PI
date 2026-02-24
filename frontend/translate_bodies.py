import json
import os

locales = {'es': 'src/locales/es.json', 'en': 'src/locales/en.json', 'ca': 'src/locales/ca.json'}

# Extract all text blocks
texts = {
    'es': {
        'legalb': {
            'cookies1_t': '1. ¿Qué son las Cookies?',
            'cookies1_b': 'Una cookie es un pequeño archivo de texto que un sitio web almacena en el ordenador o dispositivo móvil del usuario de navegación. Gracias a las cookies, el sitio web recuerda las acciones y preferencias del usuario (tales como inicio de sesión, el idioma, el tamaño de letra u otras preferencias) durante un periodo de tiempo, de manera que no debe volver a configurarlos.',
            'cookies2_t': '2. Cookies Propias que Utilizamos',
            'cookies2_b': 'A nivel de nuestra aplicación, podemos hacer uso de las siguientes cookies intrínsecas:',
            'cookies2_l1': 'Cookies técnicas: Imprescindibles para que puedas navegar con fluidez, gestionar tu carrito de compra y mantener el inicio de sesión como usuario de nuestra aplicación. Son siempre necesarias.',
            'cookies2_l2': 'Cookies de personalización: Mantienen tus preferencias de diseño o idioma para tu deleite personal y facilitar tu comodidad.',
            'cookies3_t': '3. Cookies de Terceros',
            'cookies3_b': 'En caso de integrar analíticas de comportamiento (como Google Analytics) u otros servicios de monitorización, esta página web recabará información estándar de registro y sobre tendencias en general. Para gestionar o deshabilitar estas cookies, puedes proceder siempre desde el control propio de su desarrollador correspondiente.',
            'cookies4_t': '4. Gestión y Eliminación de Cookies',
            'cookies4_b': 'La mayoría de los navegadores aceptan las cookies automáticamente, no obstante, puedes modificar la parametrización de tu navegador para declinarlas en caso de que así te plazca. Ten en cuenta que, si deshabilitas algunas cookies u optas por una protección rigurosa de rastreo, secciones completas de la funcionalidad de inicio de sesión o área transaccional dejarán previsiblemente de funcionar.',
            
            'priv1_t': '1. Información Básica sobre Protección de Datos',
            'priv1_b': 'De acuerdo con la legislación vigente en materia de protección de datos, te informamos que el responsable del tratamiento centralizado de tus datos personales es nuestra entidad organizativa. Estos datos serán objeto de tratamiento para gestionar las relaciones generadas con la base de clientes.',
            'priv2_t': '2. Finalidad del Tratamiento de los Datos Personales',
            'priv2_b': 'Los datos que nos proporciones a lo largo de tu relación comercial y/o de uso como usuario de nuestros servicios se utilizan con las siguientes finalidades básicas:',
            'priv2_l1': 'Mantenimiento, desarrollo y control de la relación contractual, a través de compras.',
            'priv2_l2': 'Gestión de contacto e incidencias.',
            'priv2_l3': 'Acciones de márketing si te inscribes previamente.',
            'priv3_t': '3. Plazo de Conservación de sus Datos',
            'priv3_b': 'Los datos proporcionados se conservarán mientras se mantenga la relación comercial, o durante los años necesarios para cumplir con las obligaciones legales aplicables.',
            'priv4_t': '4. Legitimación para el Tratamiento',
            'priv4_b': 'La base legal para el tratamiento de tus datos es la ejecución de un contrato o nuestro interés legítimo para la funcionalidad administrativa de nuestra página.',
            'priv5_t': '5. Derechos del Usuario',
            'priv5_b1': 'Tienes derecho a obtener confirmación sobre si estamos tratando tus datos personales por tanto, tienes la potestad de ejercer los siguientes derechos: Acceso, Rectificación, Supresión u Olvido, Oposición, Limitación y Portabilidad.',
            'priv5_b2': 'Podrás ejercer tus derechos mediante correo electrónico en nuestra página de Contacto.',
            
            'terms1_t': '1. Identidad del Titular Web',
            'terms1_b': 'Las condiciones aquí recogidas rigen el uso de este sitio web y el correspondiente acuerdo para las adquisiciones entre tú y nosotros. El mero acceso a esta página web nos exime de responsabilidades que puedan surgir fuera del uso intencionado descrito.',
            'terms2_t': '2. Uso del Sitio Web',
            'terms2_b1': 'El Usuario se compromete a realizar un uso adecuado y lícito del Sitio Web así como de los contenidos y servicios, de conformidad con la legislación aplicable en cada momento, las presentes Condiciones Generales de Uso, la moral, así como las buenas costumbres.',
            'terms2_b2': 'Está prohibido usar software que comprometa la integridad estructural o extraiga datos sin autorización.',
            'terms3_t': '3. Proceso de Presupuesto o Compra',
            'terms3_b': 'Para poder hacer compras en esta plataforma necesitas estar debidamente registrado, o en su defecto aceptar el tratamiento en la fase de pago de "invitado". Te expondremos siempre el subtotal y los impuestos (IVA aplicable) antes de la finalización de un pago con tu entidad bancaria.',
            'terms4_t': '4. Precios y Forma de pago',
            'terms4_b': 'Nuestros precios se muestran con carácter ordinario con los impuestos correspondientes si procede. Utilizamos pasarelas de pago seguro para salvaguardar tu capacidad de pago. Nos reservamos el derecho de revisión de precios en cualquier momento en el catálogo de productos.',
            'terms5_t': '5. Envíos y Reclamaciones',
            'terms5_b': 'Trataremos que los envíos se tramiten en un límite razonable nunca superior a tres semanas desde la formalización a menos que el producto estuviese explícitamente sin stock de reserva.',
            
            'faq1_q': '¿Ofrecéis envíos a domicilio?',
            'faq1_a': 'Sí, realizamos envíos a domicilio tanto a nivel nacional como internacional. Los tiempos de tránsito habituales son de 24 a 48 horas tras el procesamiento de tu pedido.',
            'faq2_q': '¿Qué garantías tienen los productos reacondicionados?',
            'faq2_a': 'Todos nuestros productos reacondicionados han pasado un riguroso testeo de estrés y de calidad y cuentan con 1 año entero de garantía, al igual que nuestro compromiso de sostenibilidad.',
            'faq3_q': '¿Puedo devolver un componente si no es compatible?',
            'faq3_a': 'Dispones de 14 días naturales desde la recepción de tu envío para efectuar cualquier devolución sin penalización, siempre que el embalaje original se encuentre en perfectas condiciones y no presente indicios de maltrato.',
            'faq4_q': '¿Cuáles son los métodos de pago aceptados?',
            'faq4_a': 'Aceptamos tarjetas de crédito o débito (Visa, MasterCard, American Express) de forma totalmente segura a través de nuestra pasarela cifrada.',
            'faq5_q': '¿Cómo puedo aplicar un descuento de Eco-Score?',
            'faq5_a': 'Añadiendo productos con alto Eco-Score a tu cesta y acudiendo a tu área de cliente, en alguna de las promociones te indicarán como se aplica mecánicamente en tu pedido.',

            'guide1_t': '1. Preparación y Herramientas',
            'guide1_b': 'Antes de empezar a ensamblar tu nuevo PC, asegúrate de contar con un espacio amplio, limpio y bien iluminado. Necesitarás:',
            'guide1_l1': 'Destornillador de estrella (Phillips) de tamaño mediano (idealmente magnético).',
            'guide1_l2': 'Pulsera antiestática (opcional pero recomendada).',
            'guide1_l3': 'Bridas o cintas de velcro para la gestión de cables.',
            'guide1_l4': 'Pasta térmica extra (aunque muchos sistemas de refrigeración ya la tienen pre-aplicada).',
            'guide2_t': '2. Instalación de la CPU y Memoria RAM',
            'guide2_b': 'Es más fácil instalar estos componentes en la placa base antes de colocarla dentro de la caja.',
            'guide2_l1': 'Procesador (CPU): Levanta la palanca del socket, alinea el triángulo dorado de la CPU con la marca del socket y déjala caer suavemente sin hacer presión. Baja la palanca para fijarla.',
            'guide2_l2': 'Memoria RAM: Abre las pestañas de las ranuras DIMM (suele recomendarse ocupar los slots 2 y 4 primero para Dual Channel). Presiona el módulo hacia abajo por ambos extremos hasta escuchar un "clic".',
            'guide3_t': '3. Almacenamiento M.2 y Disipador',
            'guide3_b': 'Si tienes un SSD NVMe M.2, localiza la ranura en la placa base, insértalo en un ángulo de unos 30 grados y bájalo hasta atornillarlo suavemente. A continuación, instala el sistema de refrigeración sobre la CPU (quitando el plástico protector si tiene pasta térmica pre-aplicada).',
            'guide4_t': '4. Montaje de la Placa Base en la Caja',
            'guide4_b': 'Instala el protector I/O en la parte trasera de la caja (si tu placa no lo lleva integrado). Asegúrate de que los separadores metálicos de la caja coincidan con los agujeros de tu placa base. Colora la placa y atorníllala en forma de cruz sin forzar demasiado.',
            'guide5_t': '5. Fuente de Alimentación y Cableado Frontal',
            'guide5_b1': 'Instala la fuente de alimentación (PSU) en la parte inferior de la caja con el ventilador apuntando hacia abajo o hacia el filtro antipolvo. Pasa los cables EPS de la CPU, el ATX de 24 pines y los PCI-e requeridos para la tarjeta gráfica hacia la parte delantera de la placa.',
            'guide5_b2': 'Conecta los diminutos cables del panel frontal (Power SW, Reset SW, HD LED) de la caja en los pines indicados en el manual de tu placa base.',
            'guide6_t': '6. Tarjeta Gráfica y Detalles Finales',
            'guide6_b1': 'Retira las tapas metálicas traseras necesarias de la caja. Inserta la GPU en la ranura PCIe principal (la más cercana al procesador) hasta hacer "clic" y atorníllala a la estructura. Conecta los cables de alimentación PCIe procedentes de la fuente.',
            'guide6_b2': '¡Revisa todas las conexiones, organiza los cables (cable management), cierra la caja y prepárate para encenderla e instalar tu Sistema Operativo!'
        }
    },
    'en': {
        'legalb': {
            'cookies1_t': '1. What are Cookies?',
            'cookies1_b': 'A cookie is a small text file that a website stores on the computer or mobile device of the browsing user. Thanks to cookies, the website remembers the user\'s actions and preferences (such as login, language, font size, and other display preferences) over a period of time, so you don\'t have to keep re-entering them.',
            'cookies2_t': '2. Own Cookies we use',
            'cookies2_b': 'At the application level, we may use the following intrinsic cookies:',
            'cookies2_l1': 'Technical cookies: Essential for you to navigate smoothly, manage your shopping cart, and maintain your session as a user of our application. They are always necessary.',
            'cookies2_l2': 'Personalization cookies: They maintain your layout or language preferences for your personal delight and comfort.',
            'cookies3_t': '3. Third Party Cookies',
            'cookies3_b': 'In case of integrating behavioral analytics (such as Google Analytics) or other monitoring services, this webpage will collect standard registration information and general trends. To manage or disable these cookies, you can always proceed from their respective developer\'s control panel.',
            'cookies4_t': '4. Cookie Management and Deletion',
            'cookies4_b': 'Most browsers accept cookies automatically; however, you can modify your browser settings to decline them if you prefer. Keep in mind that if you disable some cookies or opt for strict tracking protection, entire sections of the login functionality or transaction areas are likely to stop working.',
            
            'priv1_t': '1. Basic Information on Data Protection',
            'priv1_b': 'In accordance with current legislation on data protection, we inform you that the party responsible for the centralized processing of your personal data is our organizational entity. These data will be processed to manage the relationships generated with our customer base.',
            'priv2_t': '2. Purpose of Personal Data Processing',
            'priv2_b': 'The data you provide to us throughout your business relationship and/or use as a user of our services are used for the following basic purposes:',
            'priv2_l1': 'Maintenance, development, and control of the contractual relationship through purchases.',
            'priv2_l2': 'Contact and incident management.',
            'priv2_l3': 'Marketing actions if you subscribe proactively.',
            'priv3_t': '3. Data Retention Period',
            'priv3_b': 'The provided data will be retained as long as the commercial relationship is maintained, or for the necessary years to comply with applicable legal obligations.',
            'priv4_t': '4. Legitimation for Processing',
            'priv4_b': 'The legal basis for processing your data is the execution of a contract or our legitimate interest in the administrative functionality of our website.',
            'priv5_t': '5. User Rights',
            'priv5_b1': 'You have the right to obtain confirmation as to whether we are processing your personal data, and therefore, you possess the authority to exercise the following rights: Access, Rectification, Deletion or oblivion, Opposition, Limitation, and Portability.',
            'priv5_b2': 'You can exercise your rights by sending an email via our Contact page.',
            
            'terms1_t': '1. Identity of the Website Owner',
            'terms1_b': 'The conditions collected here govern the use of this website and the corresponding agreement for acquisitions between you and us. Mere access to this website exempts us from responsibilities that may arise outside the intended described use.',
            'terms2_t': '2. Website Usage',
            'terms2_b1': 'The User agrees to make appropriate and legal use of the Website as well as the content and services, according to the applicable legislation at all times, the present General Conditions of Use, morality, and good customs.',
            'terms2_b2': 'Using software that compromises structural integrity or extracts data without authorization is prohibited.',
            'terms3_t': '3. Quoting or Purchasing Process',
            'terms3_b': 'To make purchases on this platform, you must be properly registered, or otherwise accept processing in the payment phase as a "guest". We will always present the subtotal and taxes (applicable VAT) before finalizing a payment with your bank entity.',
            'terms4_t': '4. Prices and Payment Methods',
            'terms4_b': 'Our prices are ordinarily displayed with related taxes if applicable. We use secure payment gateways to safeguard your payment capability. We reserve the right to review prices at any time in the product catalog.',
            'terms5_t': '5. Shipping and Claims',
            'terms5_b': 'We will try to process shipments within a reasonable timeframe extending never beyond three weeks from formalization unless the product was explicitly out of a reserved stock.',
            
            'faq1_q': 'Do you offer home delivery?',
            'faq1_a': 'Yes, we perform home deliveries both nationally and internationally. The regular transit times are 24 to 48 hours after processing your order.',
            'faq2_q': 'What guarantees do refurbished products have?',
            'faq2_a': 'All our refurbished products have successfully undergone rigorous stress and quality testing and offer a full 1-year warranty, in line with our sustainability commitment.',
            'faq3_q': 'Can I return a component if it is incompatible?',
            'faq3_a': 'You have 14 calendar days strictly from the reception of your shipment to execute any return without penalty, provided the item\'s original packaging remains in perfect conditions entirely lacking signs of misuse.',
            'faq4_q': 'What payment methods are accepted?',
            'faq4_a': 'We accept credit or debit cards (Visa, MasterCard, American Express) entirely securely via our encrypted terminal.',
            'faq5_q': 'How can I apply an Eco-Score discount?',
            'faq5_a': 'Adding products with a high Eco-Score rating directly limits the price. Visiting your internal customer area, distinct promotions will specifically indicate their mechanical order applicability.',

            'guide1_t': '1. Preparation and Tools',
            'guide1_b': 'Before commencing assembling your new PC, ensure you reside in an ample, spotlessly clean, properly illuminated area. You will require:',
            'guide1_l1': 'Medium-sized Phillips screwdriver (ideally magnetized).',
            'guide1_l2': 'Anti-static wristband (optional but highly recommended).',
            'guide1_l3': 'Cable ties or velcro straps for adequate cable management.',
            'guide1_l4': 'Extra thermal paste (although many advanced cooling systems retain it pre-applied).',
            'guide2_t': '2. CPU and RAM Installation',
            'guide2_b': 'It natively proves easier installing these components straight into the motherboard specifically before dropping it into your case.',
            'guide2_l1': 'Processor (CPU): Lift the socket arm, align the CPU\'s golden triangle directly alongside the socket\'s marking confidently, and lower it gently without applying pressure. Depress the arm.',
            'guide2_l2': 'RAM Memory: Flip open the locking tabs of the DIMM slots correspondingly (commonly advising populating slot elements 2 and 4 initially for accurate Dual Channel). Exert downwards pressure at both flanks synchronously till generating a "click".',
            'guide3_t': '3. M.2 Storage and Cooler',
            'guide3_b': 'If wielding an NVMe M.2 SSD, effortlessly locate your motherboard slotting base, securely insert it exactly at a sharp 30-degree incline, maintaining low altitude until fully engaging the singular screw steadily. Proceed installing the CPU cooling mechanism continuously over the CPU (stripping away prior protective plastics if utilizing paste).',
            'guide4_t': '4. Motherboard to Case Mounting',
            'guide4_b': 'Set the I/O shield steadily atop the inner rear element directly forming the structure cases (unnecessary if pre-installed upon motherboards). Validate all case standoff posts align distinctly matching the internal mainboard layout. Deliberately position identically applying light cross-pattern screwing torque lacking overexertion.',
            'guide5_t': '5. Power Supply and Front Panel Cabling',
            'guide5_b1': 'Position appropriately the Power Supply Unit (PSU) exclusively sitting along bottom compartment zones facing airflow mechanisms outwardly addressing downwards filters correctly. Systematically run the top EPS cables, expansive main 24-pin ATX connectors, including adequate PCI-e lines addressing frontal routing holes proactively maintaining aesthetics.',
            'guide5_b2': 'Identify minor front-panel lines identically matching casing specifics (Power SW, Reset SW, HD LED) matching precisely identical configurations referenced in user manuals correctly locating pins.',
            'guide6_t': '6. Graphics Card and Final Details',
            'guide6_b1': 'Extrude necessary chassis rear metal shielding plates sequentially. Firmly seat the distinct heavy GPU explicitly accessing primarily leading proximal PCIe channels producing a definitive locking "click" instantly applying structural fastening screws immediately. Terminally plug requisite PSU PCI-e cords into adjacent GPU terminators.',
            'guide6_b2': 'Systematically scrutinize entire connective arrays critically, decisively deploy competent cable management bundling properly utilizing restraints precisely, securing panels completely predicting successfully initializing core component operating sequence subsequent operational systems installing!'
        }
    },
    'ca': {
        'legalb': {
            'cookies1_t': '1. Què són les Cookies?',
            'cookies1_b': 'Una cookie és un petit fitxer de text que un lloc web emmagatzema a l\'ordinador o dispositiu mòbil de l\'usuari de navegació. Gràcies a les cookies, el lloc web recorda les accions i preferències de l\'usuari (com ara l\'inici de sessió, l\'idioma, la grandària de lletra o altres preferències) durant un període de temps, de manera que no cal configurar-les de nou.',
            'cookies2_t': '2. Cookies Pròpies que Utilitzem',
            'cookies2_b': 'A nivell de la nostra aplicació, podem fer ús de les següents cookies intrínseques:',
            'cookies2_l1': 'Cookies tècniques: Imprescindibles perquè puguis navegar amb fluïdesa, gestionar el teu carret de compra i mantenir l\'inici de sessió com a usuari de la nostra aplicació. Són sempre necessàries.',
            'cookies2_l2': 'Cookies de personalització: Mantenen les teves preferències de disseny o idioma pel teu gaudi personal i per facilitar la teva comoditat.',
            'cookies3_t': '3. Cookies de Tercers',
            'cookies3_b': 'En cas d\'integrar analítiques de comportament (com Google Analytics) o altres serveis de monitorització, aquesta pàgina web recollirà informació estàndard de registre i sobre tendències en general. Per gestionar o inhabilitar aquestes cookies, pots procedir sempre des del control propi del seu desenvolupador corresponent.',
            'cookies4_t': '4. Gestió i Eliminació de Cookies',
            'cookies4_b': 'La majoria dels navegadors accepten les cookies automàticament, no obstant això, pots modificar la parametrització del teu navegador per declinar-les en cas que així ho desitgis. Tingues en compte que, si inhabilites algunes cookies o optes per una protecció rigorosa de rastreig, seccions completes de la funcionalitat d\'inici de sessió o l\'àrea transaccional deixaran previsiblement de funcionar.',
            
            'priv1_t': '1. Informació Bàsica sobre Protecció de Dades',
            'priv1_b': 'D\'acord amb la legislació vigent en matèria de protecció de dades, t\'informem que el responsable del tractament centralitzat de les teves dades personals és la nostra entitat organitzativa. Aquestes dades seran objecte de tractament per gestionar les relacions generades amb la base de clients.',
            'priv2_t': '2. Finalitat del Tractament de les Dades Personals',
            'priv2_b': 'Les dades que ens proporcionis al llarg de la teva relació comercial i/o d\'ús com a usuari dels nostres serveis s\'utilitzen amb les següents finalitats bàsiques:',
            'priv2_l1': 'Manteniment, desenvolupament i control de la relació contractual, a través de compres.',
            'priv2_l2': 'Gestió de contacte i incidències.',
            'priv2_l3': 'Accions de màrqueting si t\'inscrius prèviament.',
            'priv3_t': '3. Termini de Conservació de les teves Dades',
            'priv3_b': 'Les dades proporcionades es conservaran mentre es mantingui la relació comercial, o durant els anys necessaris per complir amb les obligacions legals aplicables.',
            'priv4_t': '4. Legitimació per al Tractament',
            'priv4_b': 'La base legal per al tractament de les teves dades és l\'execució d\'un contracte o el nostre interès legítim per a la funcionalitat administrativa de la nostra pàgina.',
            'priv5_t': '5. Drets de l\'Usuari',
            'priv5_b1': 'Tens dret a obtenir confirmació sobre si estem tractant les teves dades personals i, per tant, tens la potestat d\'exercir els següents drets: Accés, Rectificació, Supressió o Oblit, Oposició, Limitació i Portabilitat.',
            'priv5_b2': 'Podràs exercir els teus drets mitjançant correu electrònic a la nostra pàgina de Contacte.',
            
            'terms1_t': '1. Identitat del Titular Web',
            'terms1_b': 'Les condicions aquí recollides regeixen l\'ús d\'aquest lloc web i el corresponent acord d\'adquisicions entre tu i nosaltres. El simple accés a aquesta pàgina web ens eximeix de responsabilitats que puguin sorgir fora de l\'ús intencionat descrit.',
            'terms2_t': '2. Ús del Lloc Web',
            'terms2_b1': 'L\'Usuari es compromet a fer un ús adequat i lícit del Lloc Web així com dels continguts i serveis, de conformitat amb la legislació aplicable en cada moment, les presents Condicions Generals d\'Ús, la moral, i els bons costums.',
            'terms2_b2': 'Està prohibit utilitzar programari que comprometi la integritat estructural o extregui dades sense autorització.',
            'terms3_t': '3. Procés de Pressupost o Compra',
            'terms3_b': 'Per poder fer compres a aquesta plataforma necessites estar degudament registrat, o en el seu defecte acceptar el tractament en la fase de pagament de "convidat". Sempre t\'exposarem el subtotal i els impostos (IVA aplicable) abans de finalitzar un pagament amb la teva entitat bancària.',
            'terms4_t': '4. Preus i Forma de pagament',
            'terms4_b': 'Els nostres preus es mostren amb caràcter ordinari amb els impostos corresponents si escau. Utilitzem passarel·les de pagament segur per salvaguardar la teva capacitat de pagament. Ens reservem el dret de revisió de preus en qualsevol moment al catàleg de productes.',
            'terms5_t': '5. Enviaments i Reclamacions',
            'terms5_b': 'Mirarem que els enviaments es tramitin en un límit raonable mai superior a tress setmanes des de la formalització a menys que el producte estigués explícitament sense estoc de reserva.',
            
            'faq1_q': 'Ofereixen enviaments a domicili?',
            'faq1_a': 'Sí, fem enviaments a domicili tant a nivell nacional com internacional. Els temps de trànsit habituals són de 24 a 48 hores després de processar la teva comanda.',
            'faq2_q': 'Quines garanties tenen els productes recondicionats?',
            'faq2_a': 'Tots els nostres productes recondicionats han superat un rigorós test d\'estrès i de qualitat i disposen d\'1 any sencer de garantia, igual que el nostre compromís de sostenibilitat.',
            'faq3_q': 'Puc retornar un component si no és compatible?',
            'faq3_a': 'Disposes de 14 dies naturals des de la recepció del teu enviament per a efectuar qualsevol devolució sense penalització, sempre que l\'embalatge original es trobi en perfectes condicions i no presenti indicis de mal ús.',
            'faq4_q': 'Quins són els mètodes de pagament acceptats?',
            'faq4_a': 'Acceptem targetes de crèdit o dèbit (Visa, MasterCard, American Express) de forma totalment segura mitjançant la nostra passarel·la xifrada.',
            'faq5_q': 'Com puc aplicar un descompte d\'Eco-Score?',
            'faq5_a': 'Afegint productes amb un alt Eco-Score a la teva cistella i accedint a la teva àrea de client, en alguna de les promocions t\'indicaran com s\'aplica mecànicament a la teva comanda.',

            'guide1_t': '1. Preparació i Eines',
            'guide1_b': 'Abans de començar a acoblar el teu nou PC, assegura\'t de disposar d\'un espai ampli, net i ben il·luminat. Necessitaràs:',
            'guide1_l1': 'Tornavís d\'estrella (Phillips) de mida mitjana (idealment magnètic).',
            'guide1_l2': 'Polsera antiestàtica (opcional però recomanada).',
            'guide1_l3': 'Brides o cintes de velcro per a la gestió de cables.',
            'guide1_l4': 'Pasta tèrmica extra (tot i que molts sistemes de refrigeració ja la tenen pre-aplicada).',
            'guide2_t': '2. Instal·lació de la CPU i Memòria RAM',
            'guide2_b': 'És més fàcil instal·lar aquests components a la placa base abans de col·locar-la dins de la caixa.',
            'guide2_l1': 'Processador (CPU): Aixeca la palanca del sòcol, alinea el triangle daurat de la CPU amb la marca del sòcol i deixa-la caure suaument sense fer pressió. Baixa la palanca per fixar-la.',
            'guide2_l2': 'Memòria RAM: Obre les pestanyes de les ranures DIMM (sol recomanar-se ocupar els slots 2 i 4 primer per a Dual Channel). Pressiona el mòdul cap avall per ambdós extrems fins a escoltar un "clic".',
            'guide3_t': '3. Emmagatzematge M.2 i Dissipador',
            'guide3_b': 'Si tens un SSD NVMe M.2, localitza la ranura a la placa base, insereix-lo en un angle d\'uns 30 graus i baixa\'l fins a cargolar-lo suaument. A continuació, instal·la el sistema de refrigeració sobre la CPU (llevant el plàstic protector si té pasta tèrmica pre-aplicada).',
            'guide4_t': '4. Muntatge de la Placa Base a la Caixa',
            'guide4_b': 'Instal·la el protector I/O a la part posterior de la caixa (si la teva placa no el duu integrat). Assegura\'t que els separadors metàl·lics de la caixa coincideixin amb els forats de la teva placa base. Col·loca la placa i cargola-la en forma de creu sense forçar massa.',
            'guide5_t': '5. Font d\'Alimentació i Cablejat Frontal',
            'guide5_b1': 'Instal·la la font d\'alimentació (PSU) a la part inferior de la caixa amb el ventilador apuntant cap avall o cap al filtre antipols. Passa els cables EPS de la CPU, l\'ATX de 24 pins i els PCI-e requerits per a la targeta gràfica cap a la part davantera de la placa.',
            'guide5_b2': 'Connecta els petits cables del panell frontal (Power SW, Reset SW, HD LED) de la caixa als pins indicats en el manual de la teva placa base.',
            'guide6_t': '6. Targeta Gràfica i Detalls Finals',
            'guide6_b1': 'Treu les tapes metàl·liques posteriors necessàries de la caixa. Insereix la GPU a la ranura PCIe principal (la més pròxima al processador) fins a fer "clic" i cargola-la a l\'estructura. Connecta els cables d\'alimentació PCIe procedents de la font.',
            'guide6_b2': 'Revisa totes les connexions, organitza els cables (cable management), tanca la caixa i prepara\'t per engegar-la i instal·lar el teu Sistema Operatiu!'
        }
    }
}

for lang, path in locales.items():
    if not os.path.exists(path):
        continue
    with open(path, 'r', encoding='utf-8') as f:
        data = json.load(f)
    if 'legalb' not in data:
        data['legalb'] = {}
    data['legalb'].update(texts[lang]['legalb'])
    with open(path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

file_replacements = {
    'src/views/PoliticaCookiesView.vue': [
        ('1. ¿Qué son las Cookies?', "{{ $t('legalb.cookies1_t') }}"),
        ('Una cookie es un pequeño archivo de texto que un sitio web almacena en el ordenador o dispositivo móvil del usuario de navegación. Gracias a las cookies, el sitio web recuerda las acciones y preferencias del usuario (tales como inicio de sesión, el idioma, el tamaño de letra u otras preferencias) durante un periodo de tiempo, de manera que no debe volver a configurarlos.', "{{ $t('legalb.cookies1_b') }}"),
        ('2. Cookies Propias que Utilizamos', "{{ $t('legalb.cookies2_t') }}"),
        ('A nivel de nuestra aplicación, podemos hacer uso de las siguientes cookies intrínsecas:', "{{ $t('legalb.cookies2_b') }}"),
        ('Cookies técnicas: Imprescindibles para que puedas navegar con fluidez, gestionar tu carrito de compra y mantener el inicio de sesión como usuario de nuestra aplicación. Son siempre necesarias.', "{{ $t('legalb.cookies2_l1') }}"),
        ('Cookies de personalización: Mantienen tus preferencias de diseño o idioma para tu deleite personal y facilitar tu comodidad.', "{{ $t('legalb.cookies2_l2') }}"),
        ('3. Cookies de Terceros', "{{ $t('legalb.cookies3_t') }}"),
        ('En caso de integrar analíticas de comportamiento (como Google Analytics) u otros servicios de monitorización, esta página web recabará información estándar de registro y sobre tendencias en general. Para gestionar o deshabilitar estas cookies, puedes proceder siempre desde el control propio de su desarrollador correspondiente.', "{{ $t('legalb.cookies3_b') }}"),
        ('4. Gestión y Eliminación de Cookies', "{{ $t('legalb.cookies4_t') }}"),
        ('La mayoría de los navegadores aceptan las cookies automáticamente, no obstante, puedes modificar la parametrización de tu navegador para declinarlas en caso de que así te plazca. Ten en cuenta que, si deshabilitas algunas cookies u optas por una protección rigurosa de rastreo, secciones completas de la funcionalidad de inicio de sesión o área transaccional dejarán previsiblemente de funcionar.', "{{ $t('legalb.cookies4_b') }}")
    ],
    'src/views/PoliticaPrivacidadView.vue': [
        ('1. Información Básica sobre Protección de Datos', "{{ $t('legalb.priv1_t') }}"),
        ('De acuerdo con la legislación vigente en materia de protección de datos, te informamos que el responsable del tratamiento centralizado de tus datos personales es nuestra entidad organizativa. Estos datos serán objeto de tratamiento para gestionar las relaciones generadas con la base de clientes.', "{{ $t('legalb.priv1_b') }}"),
        ('2. Finalidad del Tratamiento de los Datos Personales', "{{ $t('legalb.priv2_t') }}"),
        ('Los datos que nos proporciones a lo largo de tu relación comercial y/o de uso como usuario de nuestros servicios se utilizan con las siguientes finalidades básicas:', "{{ $t('legalb.priv2_b') }}"),
        ('Mantenimiento, desarrollo y control de la relación contractual, a través de compras.', "{{ $t('legalb.priv2_l1') }}"),
        ('Gestión de contacto e incidencias.', "{{ $t('legalb.priv2_l2') }}"),
        ('Acciones de márketing si te inscribes previamente.', "{{ $t('legalb.priv2_l3') }}"),
        ('3. Plazo de Conservación de sus Datos', "{{ $t('legalb.priv3_t') }}"),
        ('Los datos proporcionados se conservarán mientras se mantenga la relación comercial, o durante los años necesarios para cumplir con las obligaciones legales aplicables.', "{{ $t('legalb.priv3_b') }}"),
        ('4. Legitimación para el Tratamiento', "{{ $t('legalb.priv4_t') }}"),
        ('La base legal para el tratamiento de tus datos es la ejecución de un contrato o nuestro interés legítimo para la funcionalidad administrativa de nuestra página.', "{{ $t('legalb.priv4_b') }}"),
        ('5. Derechos del Usuario', "{{ $t('legalb.priv5_t') }}"),
        ('Tienes derecho a obtener confirmación sobre si estamos tratando tus datos personales por tanto, tienes la potestad de ejercer los siguientes derechos: <strong>Acceso, Rectificación, Supresión u Olvido, Oposición, Limitación y Portabilidad</strong>.', "Tienes derecho a obtener confirmación sobre si estamos tratando tus datos personales por tanto, tienes la potestad de ejercer los siguientes derechos: <strong>{{ $t('legalb.priv5_b1').split(':')[1] || $t('legalb.priv5_b1') }}</strong>."),
        ('Podrás ejercer tus derechos mediante correo electrónico en nuestra página de <router-link to="/contact">Contacto</router-link>.', "{{ $t('legalb.priv5_b2') }}")
    ],
    'src/views/TerminosCondicionesView.vue': [
        ('1. Identidad del Titular Web', "{{ $t('legalb.terms1_t') }}"),
        ('Las condiciones aquí recogidas rigen el uso de este sitio web y el correspondiente acuerdo para las adquisiciones entre tú y nosotros. El mero acceso a esta página web nos exime de responsabilidades que puedan surgir fuera del uso intencionado descrito.', "{{ $t('legalb.terms1_b') }}"),
        ('2. Uso del Sitio Web', "{{ $t('legalb.terms2_t') }}"),
        ('El Usuario se compromete a realizar un uso adecuado y lícito del Sitio Web así como de los contenidos y servicios, de conformidad con la legislación aplicable en cada momento, las presentes Condiciones Generales de Uso, la moral, así como las buenas costumbres.', "{{ $t('legalb.terms2_b1') }}"),
        ('Está prohibido usar software que comprometa la integridad estructural o extraiga datos sin autorización.', "{{ $t('legalb.terms2_b2') }}"),
        ('3. Proceso de Presupuesto o Compra', "{{ $t('legalb.terms3_t') }}"),
        ('Para poder hacer compras en esta plataforma necesitas estar debidamente registrado, o en su defecto aceptar el tratamiento en la fase de pago de "invitado". Te expondremos siempre el subtotal y los impuestos (IVA aplicable) antes de la finalización de un pago con tu entidad bancaria.', "{{ $t('legalb.terms3_b') }}"),
        ('4. Precios y Forma de pago', "{{ $t('legalb.terms4_t') }}"),
        ('Nuestros precios se muestran con carácter ordinario con los impuestos correspondientes si procede. Utilizamos pasarelas de pago seguro para salvaguardar tu capacidad de pago. Nos reservamos el derecho de revisión de precios en cualquier momento en el catálogo de productos.', "{{ $t('legalb.terms4_b') }}"),
        ('5. Envíos y Reclamaciones', "{{ $t('legalb.terms5_t') }}"),
        ('Trataremos que los envíos se tramiten en un límite razonable nunca superior a tres semanas desde la formalización a menos que el producto estuviese explícitamente sin stock de reserva.', "{{ $t('legalb.terms5_b') }}")
    ],
    'src/views/PreguntasFrecuentesView.vue': [
        ('¿Ofrecéis envíos a domicilio?', "{{ $t('legalb.faq1_q') }}"),
        ('Sí, realizamos envíos a domicilio tanto a nivel nacional como internacional. Los tiempos de tránsito habituales son de 24 a 48 horas tras el procesamiento de tu pedido.', "{{ $t('legalb.faq1_a') }}"),
        ('¿Qué garantías tienen los productos reacondicionados?', "{{ $t('legalb.faq2_q') }}"),
        ('Todos nuestros productos reacondicionados han pasado un riguroso testeo de estrés y de calidad y cuentan con <strong>1 año entero de garantía</strong>, al igual que nuestro compromiso de sostenibilidad.', "{{ $t('legalb.faq2_a') }}"),
        ('¿Puedo devolver un componente si no es compatible?', "{{ $t('legalb.faq3_q') }}"),
        ('Dispones de 14 días naturales desde la recepción de tu envío para efectuar cualquier devolución sin penalización, siempre que el embalaje original se encuentre en perfectas condiciones y no presente indicios de maltrato.', "{{ $t('legalb.faq3_a') }}"),
        ('¿Cuáles son los métodos de pago aceptados?', "{{ $t('legalb.faq4_q') }}"),
        ('Aceptamos tarjetas de crédito o débito (Visa, MasterCard, American Express) de forma totalmente segura a través de nuestra pasarela cifrada.', "{{ $t('legalb.faq4_a') }}"),
        ('¿Cómo puedo aplicar un descuento de Eco-Score?', "{{ $t('legalb.faq5_q') }}"),
        ('Añadiendo productos con alto Eco-Score a tu cesta y acudiendo a tu área de cliente, en alguna de las promociones te indicarán como se aplica mecánicamente en tu pedido.', "{{ $t('legalb.faq5_a') }}")
    ],
    'src/views/GuiaMontajeView.vue': [
        ('1. Preparación y Herramientas', "{{ $t('legalb.guide1_t') }}"),
        ('Antes de empezar a ensamblar tu nuevo PC, asegúrate de contar con un espacio amplio, limpio y bien iluminado. Necesitarás:', "{{ $t('legalb.guide1_b') }}"),
        ('Destornillador de estrella (Phillips) de tamaño mediano (idealmente magnético).', "{{ $t('legalb.guide1_l1') }}"),
        ('Pulsera antiestática (opcional pero recomendada).', "{{ $t('legalb.guide1_l2') }}"),
        ('Bridas o cintas de velcro para la gestión de cables.', "{{ $t('legalb.guide1_l3') }}"),
        ('Pasta térmica extra (aunque muchos sistemas de refrigeración ya la tienen pre-aplicada).', "{{ $t('legalb.guide1_l4') }}"),
        ('2. Instalación de la CPU y Memoria RAM', "{{ $t('legalb.guide2_t') }}"),
        ('Es más fácil instalar estos componentes en la placa base <strong>antes</strong> de colocarla dentro de la caja.', "{{ $t('legalb.guide2_b') }}"),
        ('Procesador (CPU): Levanta la palanca del socket, alinea el triángulo dorado de la CPU con la marca del socket y déjala caer suavemente sin hacer presión. Baja la palanca para fijarla.', "{{ $t('legalb.guide2_l1') }}"),
        ('Memoria RAM: Abre las pestañas de las ranuras DIMM (suele recomendarse ocupar los slots 2 y 4 primero para Dual Channel). Presiona el módulo hacia abajo por ambos extremos hasta escuchar un "clic".', "{{ $t('legalb.guide2_l2') }}"),
        ('3. Almacenamiento M.2 y Disipador', "{{ $t('legalb.guide3_t') }}"),
        ('Si tienes un SSD NVMe M.2, localiza la ranura en la placa base, insértalo en un ángulo de unos 30 grados y bájalo hasta atornillarlo suavemente. \n            A continuación, instala el sistema de refrigeración sobre la CPU (quitando el plástico protector si tiene pasta térmica pre-aplicada).', "{{ $t('legalb.guide3_b') }}"),
        ('4. Montaje de la Placa Base en la Caja', "{{ $t('legalb.guide4_t') }}"),
        ('Instala el protector I/O en la parte trasera de la caja (si tu placa no lo lleva integrado). Asegúrate de que los separadores metálicos de la caja coincidan con los agujeros de tu placa base. \n            Colora la placa y atorníllala en forma de cruz sin forzar demasiado.', "{{ $t('legalb.guide4_b') }}"),
        ('5. Fuente de Alimentación y Cableado Frontal', "{{ $t('legalb.guide5_t') }}"),
        ('Instala la fuente de alimentación (PSU) en la parte inferior de la caja con el ventilador apuntando hacia abajo o hacia el filtro antipolvo. Pasa los cables EPS de la CPU, el ATX de 24 pines y los PCI-e requeridos para la tarjeta gráfica hacia la parte delantera de la placa.', "{{ $t('legalb.guide5_b1') }}"),
        ('Conecta los diminutos cables del panel frontal (Power SW, Reset SW, HD LED) de la caja en los pines indicados en el manual de tu placa base.', "{{ $t('legalb.guide5_b2') }}"),
        ('6. Tarjeta Gráfica y Detalles Finales', "{{ $t('legalb.guide6_t') }}"),
        ('Retira las tapas metálicas traseras necesarias de la caja. Inserta la GPU en la ranura PCIe principal (la más cercana al procesador) hasta hacer "clic" y atorníllala a la estructura. Conecta los cables de alimentación PCIe procedentes de la fuente.', "{{ $t('legalb.guide6_b1') }}"),
        ('¡Revisa todas las conexiones, organiza los cables (cable management), cierra la caja y prepárate para encenderla e instalar tu Sistema Operativo!', "{{ $t('legalb.guide6_b2') }}")
    ]
}

def clean_spacing(s):
    return " ".join(s.split())

for path, reps in file_replacements.items():
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
        for old, new in reps:
            # We use basic string replacement. Sometimes newlines and spaces mess with exact matches.
            content = content.replace(old, new)
            
            # Sub-method for whitespaces
            content = content.replace(clean_spacing(old), new)
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)

print("Body translations completed!")
