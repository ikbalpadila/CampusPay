import os

output_dir = r"C:\Users\lenovo\.gemini\antigravity-ide\brain\d2e3f729-b45e-4cb9-817d-e64b624dc553"
os.makedirs(output_dir, exist_ok=True)

# -------------------------------------------------------------
# Diagram 6: Use Case Diagram
# -------------------------------------------------------------
svg6 = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 950 650" width="950" height="650" style="background-color: #0f172a; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">
  <defs>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="3" stdDeviation="5" flood-color="#000000" flood-opacity="0.4"/>
    </filter>
  </defs>

  <!-- Title -->
  <text x="475" y="38" text-anchor="middle" fill="#ffffff" font-size="20" font-weight="bold">DIAGRAM 6: USE CASE DIAGRAM SISTEM TERDISTRIBUSI CAMPUSPAY</text>
  <text x="475" y="60" text-anchor="middle" fill="#94a3b8" font-size="12">Interaksi Aktor Pengguna (Mahasiswa, Admin Keuangan, Superadmin, Payment Gateway) dengan Fitur Sistem</text>

  <!-- System Boundary Box -->
  <rect x="250" y="85" width="450" height="540" rx="16" fill="#1e293b" stroke="#475569" stroke-width="2" filter="url(#shadow)"/>
  <text x="475" y="112" text-anchor="middle" fill="#38bdf8" font-size="14" font-weight="bold">BATAS SISTEM CAMPUSPAY</text>

  <!-- Actors Left -->
  <!-- Mahasiswa -->
  <circle cx="100" cy="180" r="22" fill="#3b82f6" stroke="#ffffff" stroke-width="2"/>
  <text x="100" y="220" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Mahasiswa</text>

  <!-- Admin Keuangan -->
  <circle cx="100" cy="420" r="22" fill="#10b981" stroke="#ffffff" stroke-width="2"/>
  <text x="100" y="460" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Admin Keuangan</text>

  <!-- Actors Right -->
  <!-- Superadmin -->
  <circle cx="850" cy="200" r="22" fill="#a855f7" stroke="#ffffff" stroke-width="2"/>
  <text x="850" y="240" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Superadmin</text>

  <!-- Bank / Payment Gateway -->
  <circle cx="850" cy="440" r="22" fill="#f59e0b" stroke="#ffffff" stroke-width="2"/>
  <text x="850" y="480" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Payment Gateway</text>

  <!-- Use Cases Inside System -->
  <!-- UC 1: Login & Auth -->
  <ellipse cx="475" cy="155" rx="130" ry="22" fill="#334155" stroke="#60a5fa" stroke-width="2"/>
  <text x="475" y="159" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Login &amp; Authentikasi User</text>

  <!-- UC 2: Dashboard & Tagihan -->
  <ellipse cx="475" cy="215" rx="145" ry="22" fill="#334155" stroke="#60a5fa" stroke-width="2"/>
  <text x="475" y="219" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Lihat Dashboard &amp; Tagihan Aktif</text>

  <!-- UC 3: Generate VA -->
  <ellipse cx="475" cy="275" rx="140" ry="22" fill="#334155" stroke="#06b6d4" stroke-width="2"/>
  <text x="475" y="279" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Generate Virtual Account (VA)</text>

  <!-- UC 4: Upload Bukti -->
  <ellipse cx="475" cy="335" rx="145" ry="22" fill="#334155" stroke="#34d399" stroke-width="2"/>
  <text x="475" y="339" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Upload Resi / Bukti Transfer</text>

  <!-- UC 5: Mass Generate -->
  <ellipse cx="475" cy="395" rx="150" ry="22" fill="#334155" stroke="#a7f3d0" stroke-width="2"/>
  <text x="475" y="399" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Generate Tagihan Massal per Semester</text>

  <!-- UC 6: Kelola Mahasiswa & Import -->
  <ellipse cx="475" cy="455" rx="155" ry="22" fill="#334155" stroke="#a7f3d0" stroke-width="2"/>
  <text x="475" y="459" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Kelola Data Mahasiswa &amp; Import Excel</text>

  <!-- UC 7: Ekspor Laporan -->
  <ellipse cx="475" cy="515" rx="145" ry="22" fill="#334155" stroke="#c084fc" stroke-width="2"/>
  <text x="475" y="519" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Ekspor Laporan Keuangan (PDF/Excel)</text>

  <!-- UC 8: Kelola Semester & User -->
  <ellipse cx="475" cy="575" rx="145" ry="22" fill="#334155" stroke="#c084fc" stroke-width="2"/>
  <text x="475" y="579" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Kelola Semester &amp; User Admin</text>

  <!-- Links Mahasiswa -->
  <line x1="122" y1="180" x2="345" y2="155" stroke="#60a5fa" stroke-width="1.5"/>
  <line x1="122" y1="180" x2="330" y2="215" stroke="#60a5fa" stroke-width="1.5"/>
  <line x1="122" y1="180" x2="335" y2="275" stroke="#60a5fa" stroke-width="1.5"/>
  <line x1="122" y1="180" x2="330" y2="335" stroke="#60a5fa" stroke-width="1.5"/>

  <!-- Links Admin Keuangan -->
  <line x1="122" y1="420" x2="345" y2="155" stroke="#34d399" stroke-width="1.5"/>
  <line x1="122" y1="420" x2="325" y2="395" stroke="#34d399" stroke-width="1.5"/>
  <line x1="122" y1="420" x2="320" y2="455" stroke="#34d399" stroke-width="1.5"/>
  <line x1="122" y1="420" x2="330" y2="515" stroke="#34d399" stroke-width="1.5"/>

  <!-- Links Superadmin -->
  <line x1="828" y1="200" x2="605" y2="155" stroke="#c084fc" stroke-width="1.5"/>
  <line x1="828" y1="200" x2="620" y2="575" stroke="#c084fc" stroke-width="1.5"/>

  <!-- Links Payment Gateway -->
  <line x1="828" y1="440" x2="615" y2="275" stroke="#fbbf24" stroke-width="1.5"/>
</svg>
"""

with open(os.path.join(output_dir, "diagram_6_use_case.svg"), "w", encoding="utf-8") as f:
    f.write(svg6)


# -------------------------------------------------------------
# Diagram 7: ERD (Entity Relationship Diagram)
# -------------------------------------------------------------
svg7 = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 950 620" width="950" height="620" style="background-color: #0f172a; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">
  <defs>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="3" stdDeviation="5" flood-color="#000000" flood-opacity="0.4"/>
    </filter>
  </defs>

  <!-- Title -->
  <text x="475" y="38" text-anchor="middle" fill="#ffffff" font-size="20" font-weight="bold">DIAGRAM 7: ENTITY RELATIONSHIP DIAGRAM (ERD) CAMPUSPAY</text>
  <text x="475" y="60" text-anchor="middle" fill="#94a3b8" font-size="12">Skema Basis Data Terdistribusi (Database per Service Pattern)</text>

  <!-- Table 1: Semesters (Top Left) -->
  <rect x="40" y="90" width="220" height="140" rx="10" fill="#1e293b" stroke="#6366f1" stroke-width="2" filter="url(#shadow)"/>
  <rect x="40" y="90" width="220" height="30" rx="10" fill="#4338ca"/>
  <text x="150" y="110" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">SEMESTERS</text>
  <text x="50" y="140" fill="#38bdf8" font-size="11" font-weight="bold">PK  id : bigint</text>
  <text x="50" y="160" fill="#cbd5e1" font-size="10">    nama : varchar(50)</text>
  <text x="50" y="178" fill="#cbd5e1" font-size="10">    tahun_ajaran : varchar(20)</text>
  <text x="50" y="196" fill="#cbd5e1" font-size="10">    is_aktif : boolean</text>

  <!-- Table 2: Mahasiswas (Middle Left) -->
  <rect x="40" y="270" width="220" height="170" rx="10" fill="#1e293b" stroke="#3b82f6" stroke-width="2" filter="url(#shadow)"/>
  <rect x="40" y="270" width="220" height="30" rx="10" fill="#1d4ed8"/>
  <text x="150" y="290" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">MAHASISWAS</text>
  <text x="50" y="320" fill="#38bdf8" font-size="11" font-weight="bold">PK  id : bigint</text>
  <text x="50" y="340" fill="#cbd5e1" font-size="10">    nim : varchar(20) [UQ]</text>
  <text x="50" y="358" fill="#cbd5e1" font-size="10">    nama : varchar(100)</text>
  <text x="50" y="376" fill="#cbd5e1" font-size="10">    prodi : varchar(100)</text>
  <text x="50" y="394" fill="#cbd5e1" font-size="10">    kelas : varchar(20)</text>
  <text x="50" y="412" fill="#fbbf24" font-size="10" font-weight="bold">FK  semester_id : bigint</text>

  <!-- Table 3: Payment Types (Top Right) -->
  <rect x="360" y="90" width="220" height="120" rx="10" fill="#1e293b" stroke="#6366f1" stroke-width="2" filter="url(#shadow)"/>
  <rect x="360" y="90" width="220" height="30" rx="10" fill="#4338ca"/>
  <text x="470" y="110" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">PAYMENT_TYPES</text>
  <text x="370" y="140" fill="#38bdf8" font-size="11" font-weight="bold">PK  id : bigint</text>
  <text x="370" y="160" fill="#cbd5e1" font-size="10">    nama : varchar(100)</text>
  <text x="370" y="178" fill="#cbd5e1" font-size="10">    is_aktif : boolean</text>

  <!-- Table 4: Tagihans (Center Master) -->
  <rect x="360" y="270" width="230" height="190" rx="10" fill="#1e293b" stroke="#059669" stroke-width="2.5" filter="url(#shadow)"/>
  <rect x="360" y="270" width="230" height="30" rx="10" fill="#047857"/>
  <text x="475" y="290" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">TAGIHANS</text>
  <text x="370" y="320" fill="#38bdf8" font-size="11" font-weight="bold">PK  id : bigint</text>
  <text x="370" y="340" fill="#fbbf24" font-size="10" font-weight="bold">FK  mahasiswa_id : bigint</text>
  <text x="370" y="358" fill="#fbbf24" font-size="10" font-weight="bold">FK  payment_type_id : bigint</text>
  <text x="370" y="376" fill="#fbbf24" font-size="10" font-weight="bold">FK  semester_id : bigint</text>
  <text x="370" y="394" fill="#cbd5e1" font-size="10">    nominal : decimal(12,2)</text>
  <text x="370" y="412" fill="#cbd5e1" font-size="10">    jatuh_tempo : date</text>
  <text x="370" y="430" fill="#cbd5e1" font-size="10">    status : enum('belum','pending','lunas')</text>

  <!-- Table 5: Virtual Accounts (Middle Right) -->
  <rect x="680" y="90" width="230" height="150" rx="10" fill="#1e293b" stroke="#06b6d4" stroke-width="2" filter="url(#shadow)"/>
  <rect x="680" y="90" width="230" height="30" rx="10" fill="#0891b2"/>
  <text x="795" y="110" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">VIRTUAL_ACCOUNTS</text>
  <text x="690" y="140" fill="#38bdf8" font-size="11" font-weight="bold">PK  id : bigint</text>
  <text x="690" y="160" fill="#fbbf24" font-size="10" font-weight="bold">FK  tagihan_id : bigint</text>
  <text x="690" y="178" fill="#cbd5e1" font-size="10">    nomor_va : varchar(30) [UQ]</text>
  <text x="690" y="196" fill="#cbd5e1" font-size="10">    nominal : decimal(12,2)</text>
  <text x="690" y="214" fill="#cbd5e1" font-size="10">    expired_at : timestamp</text>

  <!-- Table 6: Transactions (Bottom Right) -->
  <rect x="680" y="270" width="230" height="170" rx="10" fill="#1e293b" stroke="#a855f7" stroke-width="2" filter="url(#shadow)"/>
  <rect x="680" y="270" width="230" height="30" rx="10" fill="#7e22ce"/>
  <text x="795" y="290" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">TRANSACTIONS</text>
  <text x="690" y="320" fill="#38bdf8" font-size="11" font-weight="bold">PK  id : bigint</text>
  <text x="690" y="340" fill="#fbbf24" font-size="10" font-weight="bold">FK  tagihan_id : bigint</text>
  <text x="690" y="358" fill="#cbd5e1" font-size="10">    nominal : decimal(12,2)</text>
  <text x="690" y="376" fill="#cbd5e1" font-size="10">    metode : varchar(30)</text>
  <text x="690" y="394" fill="#c084fc" font-size="10" font-weight="bold">    lamport_clock : bigint</text>
  <text x="690" y="412" fill="#cbd5e1" font-size="10">    status : varchar(20)</text>

  <!-- Table 7: Notifications (Bottom Left) -->
  <rect x="40" y="480" width="220" height="120" rx="10" fill="#1e293b" stroke="#34d399" stroke-width="2" filter="url(#shadow)"/>
  <rect x="40" y="480" width="220" height="30" rx="10" fill="#059669"/>
  <text x="150" y="500" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">NOTIFICATIONS</text>
  <text x="50" y="530" fill="#38bdf8" font-size="11" font-weight="bold">PK  id : bigint</text>
  <text x="50" y="550" fill="#fbbf24" font-size="10" font-weight="bold">FK  mahasiswa_id : bigint</text>
  <text x="50" y="568" fill="#cbd5e1" font-size="10">    title : varchar(150)</text>
  <text x="50" y="586" fill="#cbd5e1" font-size="10">    is_read : boolean</text>

  <!-- Relationships -->
  <!-- Semester -> Mahasiswa (1 to N) -->
  <line x1="150" y1="230" x2="150" y2="270" stroke="#94a3b8" stroke-width="2"/>
  <!-- Semester -> Tagihan (1 to N) -->
  <path d="M 260 160 L 360 290" stroke="#94a3b8" stroke-width="2"/>
  <!-- Mahasiswa -> Tagihan (1 to N) -->
  <line x1="260" y1="355" x2="360" y2="355" stroke="#94a3b8" stroke-width="2"/>
  <!-- PaymentType -> Tagihan (1 to N) -->
  <path d="M 470 210 L 470 270" stroke="#94a3b8" stroke-width="2"/>
  <!-- Tagihan -> VirtualAccount (1 to 1) -->
  <path d="M 590 320 L 680 160" stroke="#94a3b8" stroke-width="2"/>
  <!-- Tagihan -> Transaction (1 to N) -->
  <line x1="590" y1="355" x2="680" y2="355" stroke="#94a3b8" stroke-width="2"/>
  <!-- Mahasiswa -> Notification (1 to N) -->
  <line x1="150" y1="440" x2="150" y2="480" stroke="#94a3b8" stroke-width="2"/>
</svg>
"""

with open(os.path.join(output_dir, "diagram_7_erd.svg"), "w", encoding="utf-8") as f:
    f.write(svg7)


# -------------------------------------------------------------
# Diagram 8: Deployment Diagram
# -------------------------------------------------------------
svg8 = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 950 580" width="950" height="580" style="background-color: #0f172a; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">
  <defs>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#000000" flood-opacity="0.4"/>
    </filter>
  </defs>

  <!-- Title -->
  <text x="475" y="38" text-anchor="middle" fill="#ffffff" font-size="20" font-weight="bold">DIAGRAM 8: DEPLOYMENT DIAGRAM INFRASTRUKTUR TERDISTRIBUSI</text>
  <text x="475" y="60" text-anchor="middle" fill="#94a3b8" font-size="12">Topologi Kluster Container Docker &amp; Server Database Terdistribusi</text>

  <!-- Node 1: Client Layer -->
  <rect x="40" y="100" width="160" height="120" rx="14" fill="#1e293b" stroke="#3b82f6" stroke-width="2" filter="url(#shadow)"/>
  <text x="120" y="130" text-anchor="middle" fill="#3b82f6" font-size="12" font-weight="bold">CLIENT LAYER</text>
  <rect x="55" y="145" width="130" height="55" rx="8" fill="#334155"/>
  <text x="120" y="170" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Browser Web / Portal</text>
  <text x="120" y="187" text-anchor="middle" fill="#94a3b8" font-size="9">Mahasiswa &amp; Admin</text>

  <!-- Node 2: Load Balancer -->
  <rect x="250" y="100" width="160" height="120" rx="14" fill="#1e293b" stroke="#6366f1" stroke-width="2" filter="url(#shadow)"/>
  <text x="330" y="130" text-anchor="middle" fill="#6366f1" font-size="12" font-weight="bold">LOAD BALANCER</text>
  <rect x="265" y="145" width="130" height="55" rx="8" fill="#4338ca"/>
  <text x="330" y="170" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Nginx Reverse Proxy</text>
  <text x="330" y="187" text-anchor="middle" fill="#e0e7ff" font-size="9">SSL &amp; Round Robin</text>

  <!-- Node 3: Microservice Container Cluster -->
  <rect x="460" y="90" width="450" height="230" rx="16" fill="#1e293b" stroke="#059669" stroke-width="2" filter="url(#shadow)"/>
  <text x="685" y="120" text-anchor="middle" fill="#10b981" font-size="14" font-weight="bold">APPLICATION SERVER CLUSTER (DOCKER NODES)</text>

  <!-- Pod 1 -->
  <rect x="480" y="140" width="190" height="75" rx="10" fill="#064e3b" stroke="#34d399" stroke-width="1.5"/>
  <text x="575" y="165" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Node 1: Web &amp; API Container</text>
  <text x="575" y="185" text-anchor="middle" fill="#a7f3d0" font-size="9">Auth, Student &amp; Billing Svc</text>

  <!-- Pod 2 -->
  <rect x="700" y="140" width="190" height="75" rx="10" fill="#064e3b" stroke="#34d399" stroke-width="1.5"/>
  <text x="795" y="165" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Node 2: Gateway &amp; VA Container</text>
  <text x="795" y="185" text-anchor="middle" fill="#a7f3d0" font-size="9">VA Service &amp; Payment Gateway</text>

  <!-- Pod 3 -->
  <rect x="480" y="230" width="410" height="70" rx="10" fill="#064e3b" stroke="#34d399" stroke-width="1.5"/>
  <text x="685" y="255" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Node 3: Worker Queue Containers</text>
  <text x="685" y="275" text-anchor="middle" fill="#a7f3d0" font-size="9">Payment, Transaction (Lamport Clock), &amp; Notification Worker</text>

  <!-- Message Broker -->
  <rect x="180" y="380" width="220" height="130" rx="14" fill="#1e293b" stroke="#f59e0b" stroke-width="2" filter="url(#shadow)"/>
  <text x="290" y="410" text-anchor="middle" fill="#fbbf24" font-size="13" font-weight="bold">MESSAGE BROKER CLUSTER</text>
  <rect x="195" y="425" width="190" height="65" rx="8" fill="#78350f"/>
  <text x="290" y="450" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">RabbitMQ Cluster</text>
  <text x="290" y="470" text-anchor="middle" fill="#fde047" font-size="10">Durable Queues &amp; Exchanges</text>

  <!-- Database Cluster -->
  <rect x="460" y="380" width="450" height="130" rx="14" fill="#1e293b" stroke="#a855f7" stroke-width="2" filter="url(#shadow)"/>
  <text x="685" y="410" text-anchor="middle" fill="#c084fc" font-size="13" font-weight="bold">DATABASE SERVER CLUSTER (POSTGRESQL)</text>
  
  <rect x="480" y="425" width="190" height="65" rx="8" fill="#581c87"/>
  <text x="575" y="450" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Primary Master DB</text>
  <text x="575" y="470" text-anchor="middle" fill="#f3e8ff" font-size="9">Write Operations</text>

  <rect x="700" y="425" width="190" height="65" rx="8" fill="#581c87"/>
  <text x="795" y="450" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">Replica Slave DB</text>
  <text x="795" y="470" text-anchor="middle" fill="#f3e8ff" font-size="9">Read Operations (Streaming)</text>

  <!-- Connections -->
  <line x1="200" y1="160" x2="250" y2="160" stroke="#60a5fa" stroke-width="2"/>
  <line x1="410" y1="160" x2="460" y2="160" stroke="#818cf8" stroke-width="2"/>
  <line x1="580" y1="320" x2="290" y2="380" stroke="#f59e0b" stroke-width="2" stroke-dasharray="3"/>
  <line x1="685" y1="320" x2="685" y2="380" stroke="#34d399" stroke-width="2"/>
  <line x1="670" y1="457" x2="700" y2="457" stroke="#c084fc" stroke-width="2" stroke-dasharray="4"/>
</svg>
"""

with open(os.path.join(output_dir, "diagram_8_deployment.svg"), "w", encoding="utf-8") as f:
    f.write(svg8)

print("All 8 diagrams generated successfully!")
