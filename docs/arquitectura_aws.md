# ☁️ Arquitectura Escalable en AWS## 🎯 Visión GeneralEste documento detalla el diseño de la infraestructura en Amazon Web Services (AWS) para soportar el despliegue en producción de la aplicación e-commerce (Vue+Laravel). La arquitectura prioriza la alta disponibilidad, la seguridad mediante aislamiento de red y la capacidad de escalada horizontal.---
## 📐 Diagrama de Arquitectura``mermaidgraph TD subgraph "VPC (10.0.0.0/16)" subgraph "Public Subnets (DMZ)"            ALB[Application Load Balancer]
            NAT[NAT Gateway]
            Bastion[Bastion Host]
        end

        subgraph "Private Application Subnets"
            subgraph "Auto Scaling Group / ECS"
                Vue1[Vue Frontend]
                Laravel1[Laravel Backend]
                Vue2[Vue Frontend]
                Laravel2[Laravel Backend]
            end
        end

        subgraph "Private Data Subnets"
            RDS_Primary[(RDS MySQL Primary)]
            RDS_Replica[(RDS MySQL Standby)]
        end
    end

    User((Usuari)) -->|HTTPS/443| ALB
    ALB -->|HTTP/80| Vue1
    ALB -->|HTTP/80| Laravel1
    
    Vue1 -->|API Call| ALB
    Laravel1 -->|SQL/3306| RDS_Primary
    
    RDS_Primary -.->|Replicació Síncrona| RDS_Replica
    
    Vue1 -.->|Outbound Traffic| NAT
    Laravel1 -.->|Outbound Traffic| NAT
    NAT -->|Internet| IGW[Internet Gateway]
```

---

## 1️⃣ Red (VPC)Hemos diseñado una **Virtual Private Cloud (VPC)** propia con el rango CIDR `10.0.0.0/16` para tener control total sobre la segmentación de red.### Segmentación de Subredes| Tipo | CIDR | Zona de disponibilidad (AZ) | Propósito | Acceso Internet ||-------|------|-----------------------------|----------|----------------|
| **Pública** | `10.0.1.0/24` | eu-west-1a | Load Balancer, NAT Gateway, Bastion | Sí (IGW) |
| **Pública** | `10.0.2.0/24` | eu-west-1b | Alta Disponibilidad LB | Sí (IGW) |
| **Privada App** | `10.0.3.0/24` | eu-west-1a | Contenedores Vue/Laravel | Sólo salida (vía NAT) |
| **Privada App** | `10.0.4.0/24` | eu-west-1b | Réplicas Contenedores | Sólo salida (vía NAT) |
| **Privada Datos** | `10.0.5.0/24` | eu-west-1a | RDS MySQL (Primary) | No |
| **Privada Datos** | `10.0.6.0/24` | eu-west-1b | RDS MySQL (Standby) | No |

### Justificación del Diseño- **VPC Propia**: Aislamiento total de otros proyectos o servicios por defecto.- **NAT Gateway**: Permite que los servidores de aplicación privados descarguen actualizaciones o paquetes (Composer/NPM) sin ser accesibles desde Internet.- **Distribución por AZ**: El uso de `eu-west---`.
## 2️⃣ Capa de Entrada (Edge)El único punto de entrada de tráfico es un **Application Load Balancer (ALB)**.- **Función**: Distribuye el tráfico entrante entre las instancias/contenedoras.- **Terminación HTTPS**: El ALB gestiona el certificado SSL (AWS Certificate Manager), **Enrutamiento**:- `api.AlberoPerezTech.ddaw.es` →2 Target Group `AlberoPerezTech.ddaw.es` → Target Group Frontend (Port 8001)---
## 3️⃣ Capa de Aplicación (Compute)Utilizamos Amazon ECS (Elastic Container Service) o Auto Scaling Group (EC2) como Docker.- **Alta Disponibilidad**: Mínimo 2 instancias, una en cada AZ.- **Escalabilidad**:- Si la CPU > 70%, se lanza a una nueva instancia automática. costas.- **Contenedorización**: Los mismos contenedores de Desarrollo se utilizan en Producción, garantiza---
## 4️⃣ Capa de datosUtilizamos Amazon RDS (Relational Database Service) para MySQL.- **Multi-AZ**: Activada. AWS mantiene una réplica en espera ("Standby") en otra AZ. Si la primaria falla, el DNS de RDS apunta automáticamente a la réplica.- **Aislamiento**: Ubicada en las "Private Data Subnets", sin ninguna ruta hacia Internet.- **Backup**: Snapshots automáticos diarios con retención de 7 días.
## 5️⃣ Seguridad (Security Groups)Implementamos una estrategia de "Defensa en Profundidad" mediante Security Groups (firewalls virtuales):### 🛡️ SG-ALB (Load Balancer)- `SG-App`.### 🛡️ SG-App (Aplicación)- **Inbound**: Puerto 8001/8002 **SÓLO** desde ʻSG-ALB`.- **Inbound**: Puerto 22 (SSH) **SÓLO** desde la IP de la VPN corporativa o## : SG-Datos (Base de Datos)- **Inbound**: Puerto 3306 (MySQL) **SÓLO** desde `SG-App`.- **Outbound**: Nadie.---
## ✅ Resumen de Beneficios1. **Seguridad**: La Base de Datos es inaccesible desde Internet. La aplicación sólo se puede acceder a través de Load Balancer.2. **Resiliencia**: La pérdida de un datacenter completo (AZ) no detiene el servicio (gracias a Multi-AZ y Auto Scaling).3. **Mantenibilidad**: El uso de servicios gestionados (RDS, ALB, NAT) reduce la carga de administración de sistemas.