import os

output_dir = r"C:\Users\lenovo\.gemini\antigravity-ide\brain\d2e3f729-b45e-4cb9-817d-e64b624dc553"
os.makedirs(output_dir, exist_ok=True)

# -------------------------------------------------------------
# Diagram 1: Arsitektur Microservices
# -------------------------------------------------------------
svg1 = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 950 650" width="950" height="650" style="background-color: #0f172a; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">
  <defs>
    <linearGradient id="gradPrimary" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#4f46e5"/>
      <stop offset="100%" stop-color="#2563eb"/>
    </linearGradient>
    <linearGradient id="gradGreen" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#059669"/>
      <stop offset="100%" stop-color="#10b981"/>
    </linearGradient>
    <linearGradient id="gradAmber" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#d97706"/>
      <stop offset="100%" stop-color="#f59e0b"/>
    </linearGradient>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#000000" flood-opacity="0.4"/>
    </filter>
  </defs>

  <!-- Title -->
  <text x="475" y="40" text-anchor="middle" fill="#ffffff" font-size="22" font-weight="bold">DIAGRAM 1: ARSITEKTUR TERDISTRIBUSI MICROSERVICES CAMPUSPAY</text>
  <text x="475" y="65" text-anchor="middle" fill="#94a3b8" font-size="13">Model Microservices dengan Pattern Database-per-Service &amp; Event-Driven Architecture</text>

  <!-- Clients -->
  <rect x="50" y="110" width="180" height="70" rx="14" fill="#1e293b" stroke="#3b82f6" stroke-width="2" filter="url(#shadow)"/>
  <text x="140" y="142" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">Portal Mahasiswa</text>
  <text x="140" y="162" text-anchor="middle" fill="#94a3b8" font-size="11">Web / Blade Client</text>

  <rect x="50" y="210" width="180" height="70" rx="14" fill="#1e293b" stroke="#3b82f6" stroke-width="2" filter="url(#shadow)"/>
  <text x="140" y="242" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">Panel Admin Filament</text>
  <text x="140" y="262" text-anchor="middle" fill="#94a3b8" font-size="11">Keuangan &amp; Superadmin</text>

  <!-- API Gateway -->
  <rect x="290" y="110" width="140" height="170" rx="16" fill="url(#gradPrimary)" filter="url(#shadow)"/>
  <text x="360" y="185" text-anchor="middle" fill="#ffffff" font-size="16" font-weight="bold">API Gateway</text>
  <text x="360" y="210" text-anchor="middle" fill="#e0e7ff" font-size="11">Routing &amp; Auth Check</text>

  <!-- Arrows Client -> Gateway -->
  <path d="M 230 145 L 290 145" stroke="#60a5fa" stroke-width="2.5" marker-end="url(#arrow)" stroke-dasharray="4"/>
  <path d="M 230 245 L 290 245" stroke="#60a5fa" stroke-width="2.5" marker-end="url(#arrow)" stroke-dasharray="4"/>

  <!-- Core Services -->
  <!-- Auth Service -->
  <rect x="500" y="100" width="170" height="60" rx="12" fill="#1e293b" stroke="#6366f1" stroke-width="2" filter="url(#shadow)"/>
  <text x="585" y="130" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Auth Service</text>
  <text x="585" y="147" text-anchor="middle" fill="#818cf8" font-size="10">JWT / Sanctum Auth</text>

  <!-- Billing Service -->
  <rect x="500" y="180" width="170" height="65" rx="12" fill="#1e293b" stroke="#6366f1" stroke-width="2" filter="url(#shadow)"/>
  <text x="585" y="210" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Billing Service</text>
  <text x="585" y="228" text-anchor="middle" fill="#818cf8" font-size="10">Tagihan &amp; Portal Core</text>

  <!-- Student Service -->
  <rect x="500" y="265" width="170" height="60" rx="12" fill="#1e293b" stroke="#6366f1" stroke-width="2" filter="url(#shadow)"/>
  <text x="585" y="295" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Student Service</text>
  <text x="585" y="312" text-anchor="middle" fill="#818cf8" font-size="10">Data Induk Mahasiswa</text>

  <!-- VA Service -->
  <rect x="730" y="180" width="170" height="65" rx="12" fill="#1e293b" stroke="#06b6d4" stroke-width="2" filter="url(#shadow)"/>
  <text x="815" y="210" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">VA Service</text>
  <text x="815" y="228" text-anchor="middle" fill="#67e8f9" font-size="10">Virtual Account Gen</text>

  <!-- Gateway -> Services -->
  <path d="M 430 130 L 500 130" stroke="#818cf8" stroke-width="2"/>
  <path d="M 430 195 L 500 195" stroke="#818cf8" stroke-width="2"/>
  <path d="M 430 260 L 500 290" stroke="#818cf8" stroke-width="2"/>
  
  <!-- Billing -> VA Service (REST) -->
  <path d="M 670 212 L 730 212" stroke="#38bdf8" stroke-width="2.5" stroke-dasharray="3"/>
  <text x="700" y="204" text-anchor="middle" fill="#38bdf8" font-size="9" font-weight="bold">REST API</text>

  <!-- Message Broker (RabbitMQ) -->
  <rect x="350" y="370" width="480" height="70" rx="16" fill="url(#gradAmber)" filter="url(#shadow)"/>
  <text x="590" y="405" text-anchor="middle" fill="#ffffff" font-size="16" font-weight="bold">RabbitMQ Message Broker</text>
  <text x="590" y="425" text-anchor="middle" fill="#fef3c7" font-size="11">Events: payment.initiated | payment.success | tagihan.created</text>

  <!-- Billing -> RabbitMQ -->
  <path d="M 585 245 L 585 370" stroke="#f59e0b" stroke-width="2.5"/>
  <text x="600" y="310" text-anchor="start" fill="#fbbf24" font-size="10" font-weight="bold">Publish Event</text>

  <!-- Worker / Consumer Services -->
  <!-- Payment Service -->
  <rect x="180" y="510" width="170" height="65" rx="12" fill="#1e293b" stroke="#10b981" stroke-width="2" filter="url(#shadow)"/>
  <text x="265" y="540" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Payment Service</text>
  <text x="265" y="558" text-anchor="middle" fill="#34d399" font-size="10">Prosesor Transaksi</text>

  <!-- Transaction Service -->
  <rect x="390" y="510" width="170" height="65" rx="12" fill="#1e293b" stroke="#10b981" stroke-width="2" filter="url(#shadow)"/>
  <text x="475" y="540" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Transaction Service</text>
  <text x="475" y="558" text-anchor="middle" fill="#34d399" font-size="10">Lamport Clock Log</text>

  <!-- Notification Service -->
  <rect x="600" y="510" width="170" height="65" rx="12" fill="#1e293b" stroke="#10b981" stroke-width="2" filter="url(#shadow)"/>
  <text x="685" y="540" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Notification Service</text>
  <text x="685" y="558" text-anchor="middle" fill="#34d399" font-size="10">Push Notif &amp; Email</text>

  <!-- Report Service -->
  <rect x="790" y="510" width="140" height="65" rx="12" fill="#1e293b" stroke="#a855f7" stroke-width="2" filter="url(#shadow)"/>
  <text x="860" y="540" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Report Service</text>
  <text x="860" y="558" text-anchor="middle" fill="#c084fc" font-size="10">PDF &amp; Excel Generator</text>

  <!-- RabbitMQ -> Consumers -->
  <path d="M 430 440 L 265 510" stroke="#34d399" stroke-width="2"/>
  <path d="M 590 440 L 475 510" stroke="#34d399" stroke-width="2"/>
  <path d="M 680 440 L 685 510" stroke="#34d399" stroke-width="2"/>
  <path d="M 750 440 L 860 510" stroke="#34d399" stroke-width="2"/>

  <!-- Legend -->
  <rect x="50" y="605" width="850" height="35" rx="8" fill="#1e293b" stroke="#334155" stroke-width="1"/>
  <text x="70" y="627" fill="#94a3b8" font-size="11" font-weight="bold">Keterangan:</text>
  <line x1="160" y1="623" x2="190" y2="623" stroke="#818cf8" stroke-width="2"/>
  <text x="195" y="627" fill="#cbd5e1" font-size="10">HTTP/REST Client</text>
  
  <line x1="310" y1="623" x2="340" y2="623" stroke="#38bdf8" stroke-width="2" stroke-dasharray="3"/>
  <text x="345" y="627" fill="#cbd5e1" font-size="10">REST API Inter-service (Sinkron)</text>

  <line x1="530" y1="623" x2="560" y2="623" stroke="#f59e0b" stroke-width="2"/>
  <text x="565" y="627" fill="#cbd5e1" font-size="10">RabbitMQ Publish Event</text>

  <line x1="720" y1="623" x2="750" y2="623" stroke="#34d399" stroke-width="2"/>
  <text x="755" y="627" fill="#cbd5e1" font-size="10">RabbitMQ Consume Event (Asinkron)</text>
</svg>
"""

with open(os.path.join(output_dir, "diagram_1_arsitektur_microservices.svg"), "w", encoding="utf-8") as f:
    f.write(svg1)


# -------------------------------------------------------------
# Diagram 2: Sequence Diagram Komunikasi Inter-Process
# -------------------------------------------------------------
svg2 = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 950 680" width="950" height="680" style="background-color: #0f172a; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">
  <defs>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="3" stdDeviation="5" flood-color="#000000" flood-opacity="0.4"/>
    </filter>
  </defs>

  <!-- Title -->
  <text x="475" y="38" text-anchor="middle" fill="#ffffff" font-size="20" font-weight="bold">DIAGRAM 2: SEQUENCE DIAGRAM KOMUNIKASI SINKRON &amp; ASINKRON</text>
  <text x="475" y="60" text-anchor="middle" fill="#94a3b8" font-size="12">Skenario: Mahasiswa Mengajukan Pembayaran VA hingga Verifikasi Real-time</text>

  <!-- Lifeline Columns -->
  <!-- Nodes -->
  <!-- 1. Mahasiswa (100) -->
  <rect x="40" y="85" width="120" height="45" rx="10" fill="#3b82f6" filter="url(#shadow)"/>
  <text x="100" y="112" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">Mahasiswa</text>
  <line x1="100" y1="130" x2="100" y2="640" stroke="#334155" stroke-width="2" stroke-dasharray="4"/>

  <!-- 2. Billing Service (250) -->
  <rect x="190" y="85" width="120" height="45" rx="10" fill="#6366f1" filter="url(#shadow)"/>
  <text x="250" y="112" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">Billing Service</text>
  <line x1="250" y1="130" x2="250" y2="640" stroke="#334155" stroke-width="2" stroke-dasharray="4"/>

  <!-- 3. VA Service (400) -->
  <rect x="340" y="85" width="120" height="45" rx="10" fill="#06b6d4" filter="url(#shadow)"/>
  <text x="400" y="112" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">VA Service</text>
  <line x1="400" y1="130" x2="400" y2="640" stroke="#334155" stroke-width="2" stroke-dasharray="4"/>

  <!-- 4. RabbitMQ Broker (550) -->
  <rect x="490" y="85" width="120" height="45" rx="10" fill="#d97706" filter="url(#shadow)"/>
  <text x="550" y="112" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">RabbitMQ</text>
  <line x1="550" y1="130" x2="550" y2="640" stroke="#334155" stroke-width="2" stroke-dasharray="4"/>

  <!-- 5. Payment Service (700) -->
  <rect x="640" y="85" width="120" height="45" rx="10" fill="#10b981" filter="url(#shadow)"/>
  <text x="700" y="112" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">Payment Svc</text>
  <line x1="700" y1="130" x2="700" y2="640" stroke="#334155" stroke-width="2" stroke-dasharray="4"/>

  <!-- 6. Notification Service (850) -->
  <rect x="790" y="85" width="120" height="45" rx="10" fill="#10b981" filter="url(#shadow)"/>
  <text x="850" y="112" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">Notif Service</text>
  <line x1="850" y1="130" x2="850" y2="640" stroke="#334155" stroke-width="2" stroke-dasharray="4"/>

  <!-- Section 1: Synchronous REST API -->
  <rect x="30" y="150" width="890" height="175" rx="10" fill="#1e1b4b" fill-opacity="0.4" stroke="#4338ca" stroke-width="1.5" stroke-dasharray="3"/>
  <text x="45" y="170" fill="#a5b4fc" font-size="11" font-weight="bold">FASE 1: KOMUNIKASI SINKRON (REST API / HTTP INSTAN)</text>

  <!-- Msg 1: Mahasiswa -> Billing -->
  <line x1="100" y1="195" x2="250" y2="195" stroke="#60a5fa" stroke-width="2"/>
  <polygon points="250,195 240,190 240,200" fill="#60a5fa"/>
  <text x="175" y="187" text-anchor="middle" fill="#93c5fd" font-size="10 font-weight=bold">1. POST /tagihan/bayar-va</text>

  <!-- Msg 2: Billing -> VA Service -->
  <line x1="250" y1="225" x2="400" y2="225" stroke="#38bdf8" stroke-width="2"/>
  <polygon points="400,225 390,220 390,230" fill="#38bdf8"/>
  <text x="325" y="217" text-anchor="middle" fill="#7dd3fc" font-size="10" font-weight="bold">2. POST /api/va/generate</text>

  <!-- Msg 3: VA Service -> Billing (Response) -->
  <line x1="400" y1="260" x2="250" y2="260" stroke="#38bdf8" stroke-width="2" stroke-dasharray="4"/>
  <polygon points="250,260 260,255 260,265" fill="#38bdf8"/>
  <text x="325" y="252" text-anchor="middle" fill="#7dd3fc" font-size="10">3. 200 OK { nomor_va: "988..." }</text>

  <!-- Msg 4: Billing -> Mahasiswa (Response) -->
  <line x1="250" y1="295" x2="100" y2="295" stroke="#60a5fa" stroke-width="2" stroke-dasharray="4"/>
  <polygon points="100,295 110,290 110,300" fill="#60a5fa"/>
  <text x="175" y="287" text-anchor="middle" fill="#93c5fd" font-size="10">4. Render Halaman Nomor VA</text>

  <!-- Section 2: Asynchronous Event-Driven Queue -->
  <rect x="30" y="340" width="890" height="280" rx="10" fill="#064e3b" fill-opacity="0.3" stroke="#059669" stroke-width="1.5" stroke-dasharray="3"/>
  <text x="45" y="360" fill="#6ee7b7" font-size="11" font-weight="bold">FASE 2: KOMUNIKASI ASINKRON (MESSAGE QUEUE - RABBITMQ DECOUPLED)</text>

  <!-- Msg 5: Mahasiswa Transfer Bank -->
  <line x1="100" y1="390" x2="250" y2="390" stroke="#f43f5e" stroke-width="2"/>
  <polygon points="250,390 240,385 240,395" fill="#f43f5e"/>
  <text x="175" y="382" text-anchor="middle" fill="#fda4af" font-size="10" font-weight="bold">5. Transfer Bank / Webhook</text>

  <!-- Msg 6: Billing -> RabbitMQ (Publish) -->
  <line x1="250" y1="425" x2="550" y2="425" stroke="#f59e0b" stroke-width="2.5"/>
  <polygon points="550,425 540,420 540,430" fill="#f59e0b"/>
  <text x="400" y="417" text-anchor="middle" fill="#fde047" font-size="10" font-weight="bold">6. Publish Event: payment.initiated (Lamport L=1)</text>

  <!-- Msg 7: RabbitMQ -> Payment Service (Consume) -->
  <line x1="550" y1="465" x2="700" y2="465" stroke="#34d399" stroke-width="2.5"/>
  <polygon points="700,465 690,460 690,470" fill="#34d399"/>
  <text x="625" y="457" text-anchor="middle" fill="#6ee7b7" font-size="10" font-weight="bold">7. Consume &amp; Process Payment</text>

  <!-- Internal Processing Payment -->
  <path d="M 700 480 C 730 480, 730 500, 700 500" stroke="#34d399" stroke-width="2" fill="none"/>
  <text x="735" y="494" text-anchor="start" fill="#a7f3d0" font-size="9">Verify &amp; Update Status</text>

  <!-- Msg 8: Payment Service -> RabbitMQ (Publish Success) -->
  <line x1="700" y1="530" x2="550" y2="530" stroke="#f59e0b" stroke-width="2.5"/>
  <polygon points="550,530 560,525 560,535" fill="#f59e0b"/>
  <text x="625" y="522" text-anchor="middle" fill="#fde047" font-size="10" font-weight="bold">8. Publish Event: payment.success (Lamport L=2)</text>

  <!-- Msg 9: RabbitMQ -> Notification Service (Consume) -->
  <line x1="550" y1="570" x2="850" y2="570" stroke="#34d399" stroke-width="2.5"/>
  <polygon points="850,570 840,565 840,575" fill="#34d399"/>
  <text x="700" y="562" text-anchor="middle" fill="#6ee7b7" font-size="10" font-weight="bold">9. Consume &amp; Kirim Push Notification</text>

  <!-- Msg 10: Notification -> Mahasiswa -->
  <line x1="850" y1="605" x2="100" y2="605" stroke="#38bdf8" stroke-width="2" stroke-dasharray="4"/>
  <polygon points="100,605 110,600 110,610" fill="#38bdf8"/>
  <text x="475" y="597" text-anchor="middle" fill="#7dd3fc" font-size="10" font-weight="bold">10. Push Notifikasi Real-time "Pembayaran Lunas!"</text>
</svg>
"""

with open(os.path.join(output_dir, "diagram_2_komunikasi_sequence.svg"), "w", encoding="utf-8") as f:
    f.write(svg2)


# -------------------------------------------------------------
# Diagram 3: Lamport Logical Clock Timing Diagram
# -------------------------------------------------------------
svg3 = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 950 560" width="950" height="560" style="background-color: #0f172a; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">
  <defs>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="3" stdDeviation="5" flood-color="#000000" flood-opacity="0.4"/>
    </filter>
  </defs>

  <!-- Title -->
  <text x="475" y="38" text-anchor="middle" fill="#ffffff" font-size="20" font-weight="bold">DIAGRAM 3: SINKRONISASI WAKTU LOGIS (LAMPORT LOGICAL CLOCK)</text>
  <text x="475" y="60" text-anchor="middle" fill="#94a3b8" font-size="12">Pengurutan Kejadian Kausal Tanpa Jam Global (L_rec = max(L_local, L_msg) + 1)</text>

  <!-- Service Process Timelines -->
  <!-- Process 1: Billing Service -->
  <text x="120" y="105" text-anchor="end" fill="#818cf8" font-size="13" font-weight="bold">Billing Service</text>
  <line x1="140" y1="100" x2="900" y2="100" stroke="#475569" stroke-width="3"/>

  <!-- Process 2: RabbitMQ Broker -->
  <text x="120" y="235" text-anchor="end" fill="#fbbf24" font-size="13" font-weight="bold">RabbitMQ Broker</text>
  <line x1="140" y1="230" x2="900" y2="230" stroke="#475569" stroke-width="3"/>

  <!-- Process 3: Payment Service -->
  <text x="120" y="365" text-anchor="end" fill="#34d399" font-size="13" font-weight="bold">Payment Service</text>
  <line x1="140" y1="360" x2="900" y2="360" stroke="#475569" stroke-width="3"/>

  <!-- Process 4: Transaction Service -->
  <text x="120" y="495" text-anchor="end" fill="#c084fc" font-size="13" font-weight="bold">Transaction Svc</text>
  <line x1="140" y1="490" x2="900" y2="490" stroke="#475569" stroke-width="3"/>

  <!-- Event 1: Billing Internal (Tagihan Dibuat) -->
  <circle cx="200" cy="100" r="10" fill="#6366f1" stroke="#ffffff" stroke-width="2" filter="url(#shadow)"/>
  <text x="200" y="78" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">E1: Tagihan Dibuat</text>
  <rect x="175" y="120" width="50" height="22" rx="6" fill="#312e81"/>
  <text x="200" y="135" text-anchor="middle" fill="#a5b4fc" font-size="10" font-weight="bold">L = 1</text>

  <!-- Event 2: Billing Send Event to RabbitMQ -->
  <circle cx="360" cy="100" r="10" fill="#6366f1" stroke="#ffffff" stroke-width="2" filter="url(#shadow)"/>
  <text x="360" y="78" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">E2: Send payment.initiated</text>
  <rect x="335" y="120" width="50" height="22" rx="6" fill="#312e81"/>
  <text x="360" y="135" text-anchor="middle" fill="#a5b4fc" font-size="10" font-weight="bold">L = 2</text>

  <!-- Message Arrow E2 -> E3 (RabbitMQ Receive) -->
  <line x1="360" y1="100" x2="440" y2="230" stroke="#f59e0b" stroke-width="2.5" stroke-dasharray="4"/>
  <polygon points="440,230 432,220 442,217" fill="#f59e0b"/>
  <text x="415" y="160" text-anchor="middle" fill="#fde047" font-size="10" font-weight="bold">msg(L=2)</text>

  <!-- Event 3: RabbitMQ Buffer Event -->
  <circle cx="440" cy="230" r="10" fill="#d97706" stroke="#ffffff" stroke-width="2" filter="url(#shadow)"/>
  <text x="440" y="210" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">E3: Queue Event</text>
  <rect x="415" y="250" width="50" height="22" rx="6" fill="#78350f"/>
  <text x="440" y="265" text-anchor="middle" fill="#fde047" font-size="10" font-weight="bold">L = 3</text>

  <!-- Message Arrow E3 -> E4 (Payment Service Consume) -->
  <line x1="440" y1="230" x2="560" y2="360" stroke="#34d399" stroke-width="2.5" stroke-dasharray="4"/>
  <polygon points="560,360 552,350 562,347" fill="#34d399"/>
  <text x="520" y="290" text-anchor="middle" fill="#6ee7b7" font-size="10" font-weight="bold">msg(L=3)</text>

  <!-- Event 4: Payment Service Process Event -->
  <circle cx="560" cy="360" r="10" fill="#10b981" stroke="#ffffff" stroke-width="2" filter="url(#shadow)"/>
  <text x="560" y="340" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">E4: Receive &amp; Update Status</text>
  <rect x="535" y="380" width="50" height="22" rx="6" fill="#064e3b"/>
  <text x="560" y="395" text-anchor="middle" fill="#a7f3d0" font-size="10" font-weight="bold">L = 4</text>
  <text x="560" y="415" text-anchor="middle" fill="#94a3b8" font-size="9">max(0, 3) + 1 = 4</text>

  <!-- Event 5: Payment Send Event to Transaction Service -->
  <circle cx="720" cy="360" r="10" fill="#10b981" stroke="#ffffff" stroke-width="2" filter="url(#shadow)"/>
  <text x="720" y="340" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">E5: Publish payment.success</text>
  <rect x="695" y="380" width="50" height="22" rx="6" fill="#064e3b"/>
  <text x="720" y="395" text-anchor="middle" fill="#a7f3d0" font-size="10" font-weight="bold">L = 5</text>

  <!-- Message Arrow E5 -> E6 (Transaction Service Record) -->
  <line x1="720" y1="360" x2="820" y2="490" stroke="#c084fc" stroke-width="2.5" stroke-dasharray="4"/>
  <polygon points="820,490 812,480 822,477" fill="#c084fc"/>
  <text x="785" y="420" text-anchor="middle" fill="#e9d5ff" font-size="10" font-weight="bold">msg(L=5)</text>

  <!-- Event 6: Transaction Service Immutable Log -->
  <circle cx="820" cy="490" r="10" fill="#a855f7" stroke="#ffffff" stroke-width="2" filter="url(#shadow)"/>
  <text x="820" y="470" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="bold">E6: Write Immutable Log</text>
  <rect x="795" y="510" width="50" height="22" rx="6" fill="#581c87"/>
  <text x="820" y="525" text-anchor="middle" fill="#f3e8ff" font-size="10" font-weight="bold">L = 6</text>
  <text x="820" y="545" text-anchor="middle" fill="#94a3b8" font-size="9">max(2, 5) + 1 = 6</text>

  <!-- Happened-before Formula Summary Box -->
  <rect x="550" y="80" width="350" height="40" rx="8" fill="#1e293b" stroke="#334155" stroke-width="1"/>
  <text x="725" y="105" text-anchor="middle" fill="#38bdf8" font-size="11" font-weight="bold">Urutan Kejadian Logis: E1 → E2 → E3 → E4 → E5 → E6</text>
</svg>
"""

with open(os.path.join(output_dir, "diagram_3_lamport_clock_timing.svg"), "w", encoding="utf-8") as f:
    f.write(svg3)


# -------------------------------------------------------------
# Diagram 4: Diagram Replikasi Data & Teorema CAP
# -------------------------------------------------------------
svg4 = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 950 560" width="950" height="560" style="background-color: #0f172a; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">
  <defs>
    <linearGradient id="gradPrimaryDB" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#2563eb"/>
      <stop offset="100%" stop-color="#1d4ed8"/>
    </linearGradient>
    <linearGradient id="gradReplicaDB" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#059669"/>
      <stop offset="100%" stop-color="#047857"/>
    </linearGradient>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#000000" flood-opacity="0.4"/>
    </filter>
  </defs>

  <!-- Title -->
  <text x="475" y="38" text-anchor="middle" fill="#ffffff" font-size="20" font-weight="bold">DIAGRAM 4: STRATEGI REPLIKASI DATA &amp; TEOREMA CAP (AP MODEL)</text>
  <text x="475" y="60" text-anchor="middle" fill="#94a3b8" font-size="12">Master-Replica Asynchronous Streaming dengan Eventual Consistency</text>

  <!-- Writers Side (Billing & Payment) -->
  <rect x="50" y="120" width="180" height="90" rx="14" fill="#1e293b" stroke="#3b82f6" stroke-width="2" filter="url(#shadow)"/>
  <text x="140" y="155" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">Billing &amp; Payment</text>
  <text x="140" y="175" text-anchor="middle" fill="#60a5fa" font-size="11">Writer Services</text>
  <text x="140" y="195" text-anchor="middle" fill="#94a3b8" font-size="10">(Write Operations Only)</text>

  <!-- Arrow Writer -> Primary DB -->
  <path d="M 230 165 L 340 165" stroke="#60a5fa" stroke-width="3" marker-end="url(#arrow)"/>
  <text x="285" y="155" text-anchor="middle" fill="#93c5fd" font-size="10" font-weight="bold">INSERT / UPDATE</text>

  <!-- Primary DB -->
  <rect x="340" y="110" width="180" height="110" rx="16" fill="url(#gradPrimaryDB)" filter="url(#shadow)"/>
  <text x="430" y="150" text-anchor="middle" fill="#ffffff" font-size="16" font-weight="bold">PRIMARY DATABASE</text>
  <text x="430" y="170" text-anchor="middle" fill="#93c5fd" font-size="12" font-weight="bold">(Master Node)</text>
  <text x="430" y="195" text-anchor="middle" fill="#e0e7ff" font-size="10">Source of Truth (Write Master)</text>

  <!-- Asynchronous Replication Stream -->
  <path d="M 430 220 L 430 330" stroke="#f59e0b" stroke-width="3.5" stroke-dasharray="5"/>
  <text x="445" y="275" text-anchor="start" fill="#fde047" font-size="11" font-weight="bold">Async Replication Stream</text>
  <text x="445" y="292" text-anchor="start" fill="#94a3b8" font-size="9">(WAL / Binary Log Stream)</text>

  <!-- Replica DB 1 -->
  <rect x="230" y="330" width="170" height="100" rx="14" fill="url(#gradReplicaDB)" filter="url(#shadow)"/>
  <text x="315" y="370" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">REPLICA DB 1</text>
  <text x="315" y="390" text-anchor="middle" fill="#a7f3d0" font-size="11">(Read Only Node)</text>
  <text x="315" y="410" text-anchor="middle" fill="#d1fae5" font-size="9">Eventual Consistency</text>

  <!-- Replica DB 2 -->
  <rect x="460" y="330" width="170" height="100" rx="14" fill="url(#gradReplicaDB)" filter="url(#shadow)"/>
  <text x="545" y="370" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">REPLICA DB 2</text>
  <text x="545" y="390" text-anchor="middle" fill="#a7f3d0" font-size="11">(Read Only Node)</text>
  <text x="545" y="410" text-anchor="middle" fill="#d1fae5" font-size="9">Eventual Consistency</text>

  <!-- Stream Split Arrows -->
  <path d="M 430 300 L 315 330" stroke="#f59e0b" stroke-width="2.5" stroke-dasharray="4"/>
  <path d="M 430 300 L 545 330" stroke="#f59e0b" stroke-width="2.5" stroke-dasharray="4"/>

  <!-- Readers Side (Report Service & Portal) -->
  <rect x="720" y="330" width="180" height="100" rx="14" fill="#1e293b" stroke="#10b981" stroke-width="2" filter="url(#shadow)"/>
  <text x="810" y="365" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">Report Service &amp; Portal</text>
  <text x="810" y="385" text-anchor="middle" fill="#34d399" font-size="11">Reader Services</text>
  <text x="810" y="405" text-anchor="middle" fill="#94a3b8" font-size="10">(Read Queries / SELECT)</text>

  <!-- Arrows Replica -> Readers -->
  <path d="M 400 380 L 720 380" stroke="#34d399" stroke-width="2.5"/>
  <text x="660" y="372" text-anchor="middle" fill="#6ee7b7" font-size="10" font-weight="bold">SELECT Queries</text>

  <!-- CAP Theorem Trade-off Box -->
  <rect x="50" y="465" width="850" height="70" rx="12" fill="#1e293b" stroke="#6366f1" stroke-width="1.5"/>
  <text x="70" y="492" fill="#818cf8" font-size="13" font-weight="bold">ANALISIS TEOREMA CAP (AP MODEL):</text>
  <text x="70" y="515" fill="#cbd5e1" font-size="11">• <tspan font-weight="bold" fill="#38bdf8">Availability (A)</tspan>: Portal pembayaran tetap 100% dapat diakses mahasiswa meskipun salah satu node replica mati.</text>
  <text x="480" y="515" fill="#cbd5e1" font-size="11">• <tspan font-weight="bold" fill="#34d399">Partition Tolerance (P)</tspan>: Sistem tahan partisi jaringan dengan sinkronisasi ulang otomatis (*Eventual Consistency*).</text>
</svg>
"""

with open(os.path.join(output_dir, "diagram_4_replikasi_data_cap.svg"), "w", encoding="utf-8") as f:
    f.write(svg4)


# -------------------------------------------------------------
# Diagram 5: User Flow & Navigasi Antarmuka UI
# -------------------------------------------------------------
svg5 = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 950 500" width="950" height="500" style="background-color: #0f172a; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">
  <defs>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#000000" flood-opacity="0.4"/>
    </filter>
  </defs>

  <!-- Title -->
  <text x="475" y="38" text-anchor="middle" fill="#ffffff" font-size="20" font-weight="bold">DIAGRAM 5: ALUR USER FLOW &amp; NAVIGASI ANTARMUKA MAHASISWA</text>
  <text x="475" y="60" text-anchor="middle" fill="#94a3b8" font-size="12">Skenario Lengkap Penggunaan Portal Pembayaran CampusPay</text>

  <!-- Step 1: Login -->
  <rect x="40" y="120" width="150" height="100" rx="14" fill="#1e293b" stroke="#3b82f6" stroke-width="2" filter="url(#shadow)"/>
  <rect x="55" y="135" width="30" height="30" rx="8" fill="#3b82f6"/>
  <text x="70" y="155" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">1</text>
  <text x="115" y="180" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Login Mahasiswa</text>
  <text x="115" y="198" text-anchor="middle" fill="#94a3b8" font-size="10">NIM &amp; Password</text>

  <!-- Step 2: Dashboard -->
  <rect x="230" y="120" width="160" height="100" rx="14" fill="#1e293b" stroke="#6366f1" stroke-width="2" filter="url(#shadow)"/>
  <rect x="245" y="135" width="30" height="30" rx="8" fill="#6366f1"/>
  <text x="260" y="155" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">2</text>
  <text x="310" y="180" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Dashboard Utama</text>
  <text x="310" y="198" text-anchor="middle" fill="#94a3b8" font-size="10">Ringkasan KPI &amp; Alert</text>

  <!-- Step 3: Tagihan Saya -->
  <rect x="430" y="120" width="160" height="100" rx="14" fill="#1e293b" stroke="#6366f1" stroke-width="2" filter="url(#shadow)"/>
  <rect x="445" y="135" width="30" height="30" rx="8" fill="#6366f1"/>
  <text x="460" y="155" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">3</text>
  <text x="510" y="180" text-anchor="middle" fill="#ffffff" font-size="13" font-weight="bold">Daftar Tagihan</text>
  <text x="510" y="198" text-anchor="middle" fill="#94a3b8" font-size="10">Pilih Metode Bayar</text>

  <!-- Branching: Bayar VA vs Upload Bukti -->
  <!-- Step 4A: Bayar VA -->
  <rect x="670" y="100" width="160" height="90" rx="14" fill="#1e293b" stroke="#06b6d4" stroke-width="2" filter="url(#shadow)"/>
  <rect x="685" y="115" width="28" height="28" rx="8" fill="#06b6d4"/>
  <text x="699" y="134" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">4A</text>
  <text x="750" y="150" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">Generate Nomor VA</text>
  <text x="750" y="168" text-anchor="middle" fill="#67e8f9" font-size="10">Salin Rekening VA</text>

  <!-- Step 4B: Upload Bukti -->
  <rect x="670" y="210" width="160" height="90" rx="14" fill="#1e293b" stroke="#d97706" stroke-width="2" filter="url(#shadow)"/>
  <rect x="685" y="225" width="28" height="28" rx="8" fill="#d97706"/>
  <text x="699" y="244" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">4B</text>
  <text x="750" y="260" text-anchor="middle" fill="#ffffff" font-size="12" font-weight="bold">Upload Resi Manual</text>
  <text x="750" y="278" text-anchor="middle" fill="#fde047" font-size="10">Resi JPG / PNG / PDF</text>

  <!-- Step 5: Verification & Notification -->
  <rect x="430" y="340" width="400" height="110" rx="16" fill="#1e293b" stroke="#10b981" stroke-width="2" filter="url(#shadow)"/>
  <rect x="445" y="355" width="32" height="32" rx="10" fill="#10b981"/>
  <text x="461" y="376" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">5</text>
  <text x="630" y="375" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="bold">Verifikasi Otomatis &amp; Notifikasi Real-time</text>
  <text x="630" y="398" text-anchor="middle" fill="#a7f3d0" font-size="11">Status Berubah Menjadi LUNAS &amp; Riwayat Transaksi Tersimpan</text>
  <text x="630" y="420" text-anchor="middle" fill="#94a3b8" font-size="10">Disinkronkan dengan Lamport Logical Clock &amp; Push Notification</text>

  <!-- Connectors -->
  <path d="M 190 170 L 230 170" stroke="#60a5fa" stroke-width="2.5"/>
  <path d="M 390 170 L 430 170" stroke="#818cf8" stroke-width="2.5"/>
  <path d="M 590 150 L 670 145" stroke="#38bdf8" stroke-width="2"/>
  <path d="M 590 190 L 670 245" stroke="#f59e0b" stroke-width="2"/>
  
  <path d="M 750 190 L 750 340" stroke="#34d399" stroke-width="2" stroke-dasharray="3"/>
  <path d="M 750 300 L 750 340" stroke="#34d399" stroke-width="2" stroke-dasharray="3"/>
</svg>
"""

with open(os.path.join(output_dir, "diagram_5_user_flow_navigasi_ui.svg"), "w", encoding="utf-8") as f:
    f.write(svg5)

print("All 5 SVG diagrams created successfully!")
