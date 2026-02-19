# ☁️ Arquitectura Escalable en AWS

## 🎯 Visió General

Aquest document detalla el disseny de la infraestructura en Amazon Web Services (AWS) per a suportar el desplegament en producció de l'aplicació e-commerce (Vue + Laravel). L'arquitectura prioritza l'alta disponibilitat, la seguretat mitjançant aïllament de xarxa i la capacitat d'escalada horitzontal.

---

## 📐 Diagrama d'Arquitectura

```mermaid
graph TD
    subgraph "VPC (10.0.0.0/16)"
        subgraph "Public Subnets (DMZ)"
            ALB[Application Load Balancer]
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

## 1️⃣ Xarxa (VPC)

Hem dissenyat una **Virtual Private Cloud (VPC)** pròpia amb el rang CIDR `10.0.0.0/16` per a tenir control total sobre la segmentació de xarxa.

### Segmentació de Subxarxes

| Tipus | CIDR | Zona de Disponibilitat (AZ) | Propòsit | Accés Internet |
|-------|------|-----------------------------|----------|----------------|
| **Pública** | `10.0.1.0/24` | eu-west-1a | Load Balancer, NAT Gateway, Bastion | Sí (IGW) |
| **Pública** | `10.0.2.0/24` | eu-west-1b | Alta Disponibilitat LB | Sí (IGW) |
| **Privada App** | `10.0.3.0/24` | eu-west-1a | Contenidors Vue/Laravel | Només sortida (via NAT) |
| **Privada App** | `10.0.4.0/24` | eu-west-1b | Rèpliques Contenidors | Només sortida (via NAT) |
| **Privada Dades** | `10.0.5.0/24` | eu-west-1a | RDS MySQL (Primary) | No |
| **Privada Dades** | `10.0.6.0/24` | eu-west-1b | RDS MySQL (Standby) | No |

### Justificació del Disseny

- **VPC Pròpia**: Aïllament total d'altres projectes o serveis per defecte.
- **NAT Gateway**: Permet que els servidors d'aplicació privats descarreguen actualitzacions o paquets (Composer/NPM) sense ser accessibles des d'Internet.
- **Distribució per AZ**: L'ús de `eu-west-1a` i `eu-west-1b` garanteix que si un Centre de Dades (AZ) cau físicament, l'aplicació continua funcionant en l'altre.

---

## 2️⃣ Capa d'Entrada (Edge)

L'únic punt d'entrada de tràfic és un **Application Load Balancer (ALB)**.

- **Funció**: Distribueix el tràfic entrant entre les instàncies/contenidors.
- **Terminació HTTPS**: L'ALB gestiona el certificat SSL (AWS Certificate Manager), descarregant aquesta feina dels servidors d'aplicació.
- **Enrutament**:
  - `api.AlberoPerezTech.ddaw.es` → Target Group Backend (Port 8002)
  - `AlberoPerezTech.ddaw.es` → Target Group Frontend (Port 8001)

---

## 3️⃣ Capa d'Aplicació (Compute)

Utilitzem **Amazon ECS (Elastic Container Service)** o un **Auto Scaling Group (EC2)** amb Docker.

- **Alta Disponibilitat**: Mínim 2 instàncies, una en cada AZ.
- **Escalabilitat**:
  - Si la CPU > 70%, es llança una nova instància automàticament.
  - Si el tràfic baixa, s'eliminen instàncies per estalviar costos.
- **Contenidorització**: Els mateixos contenidors de Desenvolupament s'utilitzen en Producció, garantint consistència.

---

## 4️⃣ Capa de Dades

Utilitzem **Amazon RDS (Relational Database Service)** per a MySQL.

- **Multi-AZ**: Activada. AWS manté una rèplica en espera ("Standby") en una altra AZ. Si la primària falla, el DNS d'RDS apunta automàticament a la rèplica.
- **Aïllament**: Situada a les "Private Data Subnets", sense cap ruta cap a Internet.
- **Backup**: Snapshots automàtics diaris amb retenció de 7 dies.

---

## 5️⃣ Seguretat (Security Groups)

Implementem una estratègia de "Defensa en Profunditat" mitjançant Security Groups (firewalls virtuals):

### 🛡️ SG-ALB (Load Balancer)
- **Inbound**: 443 (HTTPS) des de `0.0.0.0/0` (Qualsevol lloc).
- **Outbound**: Tot el tràfic cap a `SG-App`.

### 🛡️ SG-App (Aplicació)
- **Inbound**: Port 8001/8002 **NOMÉS** des de `SG-ALB`.
- **Inbound**: Port 22 (SSH) **NOMÉS** des de la IP de la VPN corporativa o Bastion Host.
- **Outbound**: Tot (per connectar a BD i Internet via NAT).

### 🛡️ SG-Dades (Base de Dades)
- **Inbound**: Port 3306 (MySQL) **NOMÉS** des de `SG-App`.
- **Outbound**: Cap.

---

## ✅ Resum de Beneficis

1. **Seguretat**: La Base de Dades és inaccessible des d'Internet. L'aplicació només es pot accedir a través del Load Balancer.
2. **Resiliència**: La pèrdua d'un datacenter complet (AZ) no atura el servei (gràcies a Multi-AZ i Auto Scaling).
3. **Mantenibilitat**: L'ús de serveis gestionats (RDS, ALB, NAT) redueix la càrrega d'administració de sistemes.
