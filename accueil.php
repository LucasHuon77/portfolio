<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
session_start();

// CSRF TOKEN
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$competences = [
    'NMAP - BURP SUITE - METASPLOIT'        => 75,
    'PHP - Javascript - SQL'                => 100,
    'TCP/IP - Wireshark'                    => 85,
    'Window - Linux - Maintenance Hardware' => 90,
    'Pentest web - OSINT'                   => 80,
];
$year = date('Y');
?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lucas Huon | Développeur Web & Cybersécurité</title>
    <meta name="description" content="Lucas Huon, 22 ans — Étudiant en cybersécurité & développeur web à Villeparisis. Top 5% TryHackMe, passionné par le pentest. Disponible en alternance dès Septembre 2026.">
    <meta name="author" content="Lucas Huon">
    <meta name="keywords" content="développeur web, cybersécurité, pentest, TryHackMe, alternance, PHP, JavaScript, Kali Linux">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Lucas Huon | Développeur Web & Cybersécurité">
    <meta property="og:description" content="Étudiant en cybersécurité & développeur web. Top 5% TryHackMe. Disponible en alternance dès Septembre 2026.">
    <meta property="og:image" content="https://tonsite.com/og-preview.png">
    <meta property="og:url" content="https://tonsite.com">
    <meta property="og:locale" content="fr_FR">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Lucas Huon | Développeur Web & Cybersécurité">
    <meta name="twitter:description" content="Étudiant en cybersécurité & développeur web. Top 5% TryHackMe.">
    <meta name="twitter:image" content="https://tonsite.com/og-preview.png">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23080c10'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Syne,sans-serif' font-weight='800' font-size='20' fill='%2300ffcc'>L</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:ital,wght@0,300;0,400;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="accueil.css">
</head>
<body>

<div id="cursor"></div>
<div id="cursor-ring"></div>
<div id="progressBar"></div>
<div id="toast" class="toast"></div>
<div id="scanlines"></div>

<!-- MODAL PHOTO -->
<div id="photoModal" class="photo-modal">
    <div class="photo-modal-overlay" id="photoModalOverlay"></div>
    <img src="photo.jpg" alt="Lucas Huon" class="photo-modal-img">
    <button class="photo-modal-close" id="photoModalClose">✕</button>
</div>

<nav id="navbar">
    <span class="logo">Lucas<span class="logo-dot">.dev</span></span>
    <ul id="navLinks">
        <li><a href="#hero">Accueil</a></li>
        <li><a href="#apropos">À propos</a></li>
        <li><a href="#competences">Skills</a></li>
        <li><a href="#outils">Outils</a></li>
        <li><a href="#certifications">Certifications</a></li>
        <li><a href="#timeline">Parcours</a></li>
        <li><a href="#projets">Projets</a></li>
        <li><a href="#temoignages">Reco</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-right">
        <span class="badge-dispo">● Dispo Sept. 2026</span>
        <button id="ambianceToggle" aria-label="Son ambiance" title="Son ambiance cyber">🎵</button>
        <button id="scanlineToggle" aria-label="Mode CRT" title="Mode scanlines rétro">📺</button>
        <button id="themeToggle" aria-label="Changer le thème">
            <span class="icon-moon">🌙</span>
            <span class="icon-sun">☀️</span>
        </button>
        <button id="hamburger" aria-label="Menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<section class="hero" id="hero">
    <canvas id="particlesCanvas"></canvas>
    <div class="hero-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="grid-overlay"></div>
    </div>
    <div class="hero-content">
        <p class="hero-tag fade-in">Disponible en alternance — Septembre 2026</p>
        <h1 class="fade-in delay">
            <span class="line">Cybersécurité &</span>
            <span class="line accent">Développeur Web</span>
        </h1>
        <p class="hero-sub fade-in delay2"><span id="typingText"></span><span class="typing-cursor">|</span></p>
        <div class="hero-ctas fade-in delay3">
            <a href="#projets" class="btn btn-primary">Voir mes projets</a>
            <a href="#contact" class="btn btn-outline">Me contacter</a>
            <a href="Lucas_Huon_CV_Alternance_Cybersecurite.pdf" download class="btn btn-cv">⬇ Télécharger mon CV</a>
        </div>
    </div>
    <div class="hero-scroll">
        <span>Scroll</span>
        <div class="scroll-line"></div>
    </div>
</section>

<div class="section-sep"><span>01</span></div>

<section id="apropos" class="section reveal-fade">
    <div class="section-bg-num">01</div>
    <div class="section-inner">
        <div class="section-label">01 — À propos</div>
        <div class="about-grid">
            <div class="about-text">
                <h2>Je construis ce que<br><span class="accent">les autres tentent de casser.</span></h2>
                <p>Je suis Lucas, 22 ans, étudiant en cybersécurité & développeur web basé à Villeparisis. Passionné par la sécurité offensive, le pentest et la construction d'outils robustes.</p>
                <p>Mon objectif : devenir <strong>pentester</strong>. Je progresse chaque jour sur TryHackMe, en CTF et sur mes projets personnels.</p>
            </div>
            <div class="about-right">
                <div class="about-photo-wrapper">
                    <img src="photo.jpg" alt="Lucas Huon" class="about-photo" id="photoClick" loading="lazy">
                    <div class="about-photo-border"></div>
                    <div class="about-photo-tag">
                        <span>Lucas Huon</span>
                        <span class="about-photo-permis">🚗 Permis B — Villeparisis</span>
                    </div>
                </div>
                <div class="about-stats">
                    <div class="stat">
                        <span class="stat-number" data-target="22">0</span>
                        <span class="stat-label">Ans</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">Top 5%</span>
                        <span class="stat-label">TryHackMe</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number" data-target="98">0</span>
                        <span class="stat-label">Rooms complétées</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number" data-target="101">0</span>
                        <span class="stat-label">Points TryHackMe (k)</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="terminal-wrapper">
            <h3 class="terminal-title">Terminal interactif <span class="accent">— tape une commande</span></h3>
            <div class="terminal" id="terminal">
                <div class="terminal-bar">
                    <span class="t-dot red"></span>
                    <span class="t-dot yellow"></span>
                    <span class="t-dot green"></span>
                    <span class="t-title">lucas@portfolio:~</span>
                </div>
                <div class="terminal-body" id="terminalBody">
                    <div class="t-line">
                        <span class="t-prompt">lucas@portfolio:~$</span>
                        <span class="t-cmd">whoami</span>
                    </div>
                    <div class="t-output" id="out-whoami"></div>
                </div>
                <div class="terminal-input-line">
                    <span class="t-prompt">lucas@portfolio:~$</span>
                    <input type="text" id="termInput" placeholder="tape une commande..." autocomplete="off" spellcheck="false">
                </div>
                <div class="t-hint">help · whoami · age · ville · objectif · skills · contact · thm · certifs · clear</div>
            </div>
        </div>
    </div>
</section>

<div class="section-sep"><span>02</span></div>

<section id="competences" class="section reveal-left">
    <div class="section-bg-num">02</div>
    <div class="section-inner">
        <div class="section-label">02 — Compétences</div>
        <h2>Mon <span class="accent">Arsenal</span></h2>
        <div class="skills-list">
            <?php foreach($competences as $tech => $level): ?>
            <div class="skill">
                <div class="skill-header">
                    <span class="skill-name"><?= htmlspecialchars($tech) ?></span>
                    <span class="skill-pct"><?= (int)$level ?>%</span>
                </div>
                <div class="skill-bar">
                    <div class="skill-level" data-width="<?= (int)$level ?>"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="section-sep"><span>03</span></div>

<section id="outils" class="section reveal-right">
    <div class="section-bg-num">03</div>
    <div class="section-inner">
        <div class="section-label">03 — Outils & Environnement</div>
        <h2>Ma <span class="accent">Toolbox</span></h2>
        <div class="outils-grid">
            <div class="outil-category">
                <div class="outil-category-label">Système</div>
                <div class="outil-cards">
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/kalilinux/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">Kali Linux</span>
                        <span class="outil-level expert">Expert</span>
                    </div>
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/windows11/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">Windows 11</span>
                        <span class="outil-level expert">Expert</span>
                    </div>
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/virtualbox/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">VirtualBox</span>
                        <span class="outil-level inter">Intermédiaire</span>
                    </div>
                </div>
            </div>
            <div class="outil-category">
                <div class="outil-category-label">Cybersécurité</div>
                <div class="outil-cards">
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/burpsuite/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">Burp Suite</span>
                        <span class="outil-level inter">Intermédiaire</span>
                    </div>
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/nmap/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">Nmap</span>
                        <span class="outil-level expert">Expert</span>
                    </div>
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/wireshark/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">Wireshark</span>
                        <span class="outil-level inter">Intermédiaire</span>
                    </div>
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/metasploit/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">Metasploit</span>
                        <span class="outil-level inter">Intermédiaire</span>
                    </div>
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/owasp/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">OWASP ZAP</span>
                        <span class="outil-level debutant">Débutant</span>
                    </div>
                </div>
            </div>
            <div class="outil-category">
                <div class="outil-category-label">Développement</div>
                <div class="outil-cards">
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/visualstudiocode/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">VS Code</span>
                        <span class="outil-level expert">Expert</span>
                    </div>
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/php/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">PHP / XAMPP</span>
                        <span class="outil-level expert">Expert</span>
                    </div>
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/mysql/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">MySQL</span>
                        <span class="outil-level inter">Intermédiaire</span>
                    </div>
                    <div class="outil-card">
                        <img class="outil-svg" src="https://cdn.simpleicons.org/github/00ffcc" alt="" loading="lazy">
                        <span class="outil-name">Git / GitHub</span>
                        <span class="outil-level inter">Intermédiaire</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-sep"><span>04</span></div>

<section id="certifications" class="section reveal-fade">
    <div class="section-bg-num">04</div>
    <div class="section-inner">
        <div class="section-label">04 — Certifications</div>
        <h2>Mes <span class="accent">certifications</span></h2>
        <div class="certs-grid">
            <div class="cert-card">
                <div class="cert-icon">🏆</div>
                <div class="cert-info">
                    <h3>Cyber Security 101</h3>
                    <p>Certification TryHackMe — Fondamentaux de la cybersécurité</p>
                    <div class="cert-tags"><span>Obtenue</span><span>TryHackMe</span></div>
                    <a href="https://tryhackme.com/p/KKeyZoo" target="_blank" rel="noopener" class="cert-link">Voir le profil →</a>
                </div>
            </div>
            <div class="cert-card">
                <div class="cert-icon">🌐</div>
                <div class="cert-info">
                    <h3>Web Fundamentals</h3>
                    <p>Certification TryHackMe — Sécurité des applications web</p>
                    <div class="cert-tags"><span>Obtenue</span><span>TryHackMe</span></div>
                    <a href="https://tryhackme.com/p/KKeyZoo" target="_blank" rel="noopener" class="cert-link">Voir le profil →</a>
                </div>
            </div>
            <div class="cert-card">
                <div class="cert-icon">🔓</div>
                <div class="cert-info">
                    <h3>Jr Penetration Tester</h3>
                    <p>Certification TryHackMe — Pentest offensif junior</p>
                    <div class="cert-tags"><span>Obtenue</span><span>TryHackMe</span></div>
                    <a href="https://tryhackme.com/p/KKeyZoo" target="_blank" rel="noopener" class="cert-link">Voir le profil →</a>
                </div>
            </div>
            <div class="cert-card cert-coming">
                <div class="cert-icon">🎯</div>
                <div class="cert-info">
                    <h3>CompTIA Security+</h3>
                    <p>En cours de préparation — objectif 2026</p>
                    <div class="cert-tags"><span>En cours</span></div>
                </div>
            </div>
            <div class="cert-card cert-coming">
                <div class="cert-icon">🎓</div>
                <div class="cert-info">
                    <h3>CEH — Certified Ethical Hacker</h3>
                    <p>Objectif après alternance</p>
                    <div class="cert-tags"><span>Visé</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-sep"><span>05</span></div>

<section id="timeline" class="section reveal-left">
    <div class="section-bg-num">05</div>
    <div class="section-inner">
        <div class="section-label">05 — Parcours</div>
        <h2>Mon <span class="accent">parcours</span></h2>
        <div class="timeline">
            <div class="tl-item">
                <div class="tl-dot"></div>
                <div class="tl-date">Sept. 2026</div>
                <div class="tl-content">
                    <h3>Paris Of School Technology & Business</h3>
                    <p>Intégration en alternance — Spécialisation Cybersécurité</p>
                    <span class="tl-tag upcoming">À venir</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot"></div>
                <div class="tl-date">2023 — 2025</div>
                <div class="tl-content">
                    <h3>BTS Cybersécurité</h3>
                    <p>Lycée Gaston Bachelard, Chelles — BTS Systèmes Numériques option Informatique & Réseaux</p>
                    <span class="tl-tag">Diplômé</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot"></div>
                <div class="tl-date">Mai — Juil. 2023</div>
                <div class="tl-content">
                    <h3>Stage — OuiCrèches</h3>
                    <p>Stage de fin de 1ère année — Mission technique en informatique & réseaux. Fort investissement et grande autonomie reconnus par le fondateur.</p>
                    <span class="tl-tag">Stage</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot"></div>
                <div class="tl-date">2023 →</div>
                <div class="tl-content">
                    <h3>TryHackMe — Pratique continue</h3>
                    <p>Pentest et cybersécurité offensive en autodidacte. Top 5% mondial, 98 rooms, 101 000 pts.</p>
                    <span class="tl-tag">En cours</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-sep"><span>06</span></div>

<section id="projets" class="section reveal-right">
    <div class="section-bg-num">06</div>
    <div class="section-inner">
        <div class="section-label">06 — Projets</div>
        <h2>Mes <span class="accent">réalisations</span></h2>
        <div class="projects-grid">
            <div class="card tilt-card">
                <div class="card-shine"></div>
                <div class="card-num">01</div>
                <div class="card-tag">Flight Radar</div>
                <h3>AJL Flight</h3>
                <p>Visualisation de vols en temps réel sur carte interactive style Flight Radar. Suivi des trajectoires et données de vol via API externe.</p>
                <div class="card-stack"><span>PHP</span><span>MySQL</span><span>JS</span><span>API</span></div>
                <a href="#" class="card-link">Voir le projet →</a>
            </div>
            <div class="card card-featured tilt-card">
                <div class="card-shine"></div>
                <div class="card-num">02</div>
                <div class="card-tag">Portfolio</div>
                <h3>Portfolio — Lucas.dev</h3>
                <p>Ce portfolio, conçu pour ma recherche d'alternance. Design cybersécurité, terminal interactif, animations avancées et mode sombre/clair.</p>
                <div class="card-stack"><span>PHP</span><span>MySQL</span><span>JS</span><span>CSS</span></div>
                <a href="#" class="card-link">Voir le projet →</a>
            </div>
            <div class="card tilt-card">
                <div class="card-shine"></div>
                <div class="card-num">03</div>
                <div class="card-tag">Bot Discord</div>
                <h3>Bot Communautaire</h3>
                <p>Bot Discord développé pour la gestion d'une communauté — modération automatique, commandes personnalisées et interactions avec les membres.</p>
                <div class="card-stack"><span>Python</span><span>Discord.py</span><span>API</span></div>
                <a href="#" class="card-link">Voir le projet →</a>
            </div>
        </div>
    </div>
</section>

<div class="section-sep"><span>07</span></div>

<section id="temoignages" class="section reveal-zoom">
    <div class="section-bg-num">07</div>
    <div class="section-inner">
        <div class="section-label">07 — Recommandations</div>
        <h2>Mes <span class="accent">recommandations</span></h2>
        <div class="temoignages-grid">
            <div class="temoignage-card">
                <div class="temo-quote">"</div>
                <p>HUON LUCAS a particulièrement fait preuve d'un fort investissement et d'une grande autonomie. Sa réflexion, sa curiosité et son intérêt pour le numérique lui ont permis de relever les défis techniques. Par ses initiatives, LUCAS s'est particulièrement distingué au sein du binôme.</p>
                <div class="temo-author">
                    <div class="temo-avatar">R</div>
                    <div class="temo-info">
                        <strong>RAKOTOVAHINY Ny Hary</strong>
                        <span>Fondateur — OuiCrèches</span>
                        <span class="temo-date">Avril 2024</span>
                    </div>
                </div>
                <a href="Lettre_de_recommandation_LH.pdf" target="_blank" rel="noopener" class="temo-pdf-link">📄 Voir la lettre originale</a>
            </div>
        </div>
    </div>
</section>

<div class="section-sep"><span>08</span></div>

<section id="contact" class="section reveal-fade">
    <div class="section-bg-num">08</div>
    <div class="section-inner">
        <div class="section-label">08 — Contact</div>
        <div class="contact-grid">
            <div class="contact-intro">
                <h2>Travaillons<br><span class="accent">ensemble</span></h2>
                <p>Un projet en tête ? Je suis disponible pour des missions freelance et des collaborations. Alternance disponible dès Septembre 2026.</p>
                <div class="contact-links">
                    <a href="mailto:kkeyzoo2004@gmail.com" class="contact-link-item">
                        <span class="contact-icon-wrap">
                            <svg class="contact-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <span>kkeyzoo2004@gmail.com</span>
                        <button class="copy-btn" data-copy="kkeyzoo2004@gmail.com" title="Copier l'email">⎘</button>
                    </a>
                    <a href="https://www.linkedin.com/in/lucas-huon-4003b025a/" target="_blank" rel="noopener" class="contact-link-item">
                        <span class="contact-icon-wrap">
                            <svg class="contact-svg-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                        </span>
                        <span>LinkedIn</span>
                    </a>
                    <a href="https://github.com/LucasHuon77" target="_blank" rel="noopener" class="contact-link-item">
                        <span class="contact-icon-wrap">
                            <svg class="contact-svg-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                        </span>
                        <span>GitHub</span>
                    </a>
                    <a href="https://tryhackme.com/p/KKeyZoo" target="_blank" rel="noopener" class="contact-link-item">
                        <span class="contact-icon-wrap">
                            <svg class="contact-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </span>
                        <span>TryHackMe</span>
                    </a>
                    <a href="Lucas_Huon_CV_Alternance_Cybersecurite.pdf" download class="contact-link-item">
                        <span class="contact-icon-wrap">
                            <svg class="contact-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 004.561 21h14.878a2 2 0 001.94-1.515L22 17"/></svg>
                        </span>
                        <span>Télécharger mon CV</span>
                    </a>
                </div>
            </div>
            <form id="contactForm" class="contact-form" novalidate>
                <!-- CSRF TOKEN -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <!-- HONEYPOT — champ invisible pour les bots -->
                <div style="display:none" aria-hidden="true">
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>
                <div class="input-group">
                    <input type="text" id="nom" name="nom" placeholder=" " required maxlength="100">
                    <label for="nom">Votre nom</label>
                </div>
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder=" " required maxlength="150">
                    <label for="email">Votre email</label>
                </div>
                <div class="input-group">
                    <textarea id="message" name="message" placeholder=" " rows="5" required maxlength="2000"></textarea>
                    <label for="message">Votre message</label>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer le message</button>
            </form>
        </div>

        <div class="why-hire">
            <h3 class="why-title">Pourquoi me <span class="accent">recruter ?</span></h3>
            <div class="why-grid">
                <div class="why-card">
                    <div class="why-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    </div>
                    <div>
                        <strong>Autonome</strong>
                        <p>Capable de prendre en charge un projet de A à Z sans supervision constante. Stage OuiCrèches en est la preuve.</p>
                    </div>
                </div>
                <div class="why-card">
                    <div class="why-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9.663 17h4.673M12 3v1m6.364 1.636-.707.707M21 12h-1M4 12H3m3.343-5.657-.707-.707m2.828 9.9a5 5 0 1 1 7.072 0l-.548.547A3.374 3.374 0 0 0 14 18.469V19a2 2 0 1 1-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div>
                        <strong>Curieux</strong>
                        <p>Top 5% mondial sur TryHackMe, 98 rooms complétées en autodidacte. J'apprends en permanence par passion.</p>
                    </div>
                </div>
                <div class="why-card">
                    <div class="why-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <strong>Passionné sécu</strong>
                        <p>3 certifications TryHackMe obtenues, objectif pentester. La cybersécurité est ma vocation, pas juste un métier.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="signature-wrapper">
            <div class="signature-text" id="signatureText"></div>
            <p class="signature-tagline">— Étudiant en Cybersécurité & Développeur Web</p>
        </div>

    </div>
</section>

<button id="scrollTop" aria-label="Retour en haut">↑</button>

<footer>
    <div class="footer-inner">
        <div class="footer-left">
            <span class="logo">Lucas<span class="logo-dot">.dev</span></span>
            <p class="footer-tagline">Étudiant en Cybersécurité & Développeur Web</p>
            <p class="footer-location">📍 Villeparisis, Île-de-France</p>
        </div>
        <div class="footer-center">
            <div class="footer-nav">
                <a href="#apropos">À propos</a>
                <a href="#competences">Skills</a>
                <a href="#projets">Projets</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="footer-badges">
                <span class="footer-badge">🏆 Top 5% TryHackMe</span>
                <span class="footer-badge">🔐 3 Certifications</span>
                <span class="footer-badge">🚀 Dispo Sept. 2026</span>
            </div>
        </div>
        <div class="footer-right">
            <p class="footer-copy">&copy; <?= $year ?> Lucas Huon</p>
            <p class="footer-made">Fait avec ❤️ & ☕</p>
            <p class="footer-tech">PHP · CSS · JS vanilla</p>
        </div>
    </div>
    <div class="footer-bottom">
        <span>lucas@portfolio:~$ <span class="footer-cmd">echo "Merci pour ta visite 👋"</span></span>
    </div>
</footer>
<script src="accueil.js"></script>
</body>
</html>