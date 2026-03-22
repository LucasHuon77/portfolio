// THEME TOGGLE
const toggle = document.getElementById('themeToggle');
const html = document.documentElement;
const savedTheme = localStorage.getItem('theme') || 'dark';
html.setAttribute('data-theme', savedTheme);
toggle.addEventListener('click', () => {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
});

// SCANLINES
const scanlineBtn = document.getElementById('scanlineToggle');
if (localStorage.getItem('scanlines') === 'on') document.body.classList.add('scanlines-on');
scanlineBtn.addEventListener('click', () => {
    document.body.classList.toggle('scanlines-on');
    const isOn = document.body.classList.contains('scanlines-on');
    localStorage.setItem('scanlines', isOn ? 'on' : 'off');
    showToast(isOn ? 'Mode CRT activé 📺' : 'Mode CRT désactivé', 'success');
});

// SON AMBIANCE
const ambianceBtn = document.getElementById('ambianceToggle');
let ambianceNodes = null;
if (localStorage.getItem('ambiance') === 'on') document.body.classList.add('ambiance-on');

function createAmbianceSound() {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const nodes = [];
    function makeNoise(freq, gain, type = 'bandpass') {
        const bufferSize = ctx.sampleRate * 2;
        const buffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
        const data = buffer.getChannelData(0);
        for (let i = 0; i < bufferSize; i++) data[i] = Math.random() * 2 - 1;
        const source = ctx.createBufferSource();
        source.buffer = buffer; source.loop = true;
        const filter = ctx.createBiquadFilter();
        filter.type = type; filter.frequency.value = freq; filter.Q.value = 0.5;
        const gainNode = ctx.createGain();
        gainNode.gain.value = gain;
        source.connect(filter); filter.connect(gainNode); gainNode.connect(ctx.destination);
        source.start();
        return { source, gainNode };
    }
    nodes.push(makeNoise(80, 0.04, 'lowpass'));
    nodes.push(makeNoise(200, 0.02, 'bandpass'));
    nodes.push(makeNoise(440, 0.01, 'bandpass'));
    return { ctx, nodes };
}
function stopAmbiance() {
    if (ambianceNodes) {
        ambianceNodes.nodes.forEach(n => {
            try { n.gainNode.gain.setTargetAtTime(0, ambianceNodes.ctx.currentTime, 0.5); } catch(e) {}
        });
        setTimeout(() => { try { ambianceNodes.ctx.close(); } catch(e) {} ambianceNodes = null; }, 1000);
    }
}
ambianceBtn.addEventListener('click', () => {
    const isOn = document.body.classList.toggle('ambiance-on');
    localStorage.setItem('ambiance', isOn ? 'on' : 'off');
    if (isOn) { ambianceNodes = createAmbianceSound(); showToast('Ambiance cyber activée 🎵', 'success'); }
    else { stopAmbiance(); showToast('Ambiance désactivée', 'success'); }
});

// PROGRESS BAR
const progressBar = document.getElementById('progressBar');
window.addEventListener('scroll', () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    progressBar.style.width = (scrollTop / docHeight * 100) + '%';
});

// NAVBAR
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => navbar.classList.toggle('scrolled', window.scrollY > 50));

// HAMBURGER
const hamburger = document.getElementById('hamburger');
const navLinks  = document.getElementById('navLinks');
hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('open');
    hamburger.setAttribute('aria-expanded', hamburger.classList.contains('open'));
    navLinks.classList.toggle('open');
});
navLinks.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
        navLinks.classList.remove('open');
    });
});

// CURSEUR
const cursor = document.getElementById('cursor');
const cursorRing = document.getElementById('cursor-ring');
let mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
document.addEventListener('mousemove', e => {
    mouseX = e.clientX; mouseY = e.clientY;
    cursor.style.left = mouseX + 'px';
    cursor.style.top  = mouseY + 'px';
});
function animateCursor() {
    ringX += (mouseX - ringX) * 0.12;
    ringY += (mouseY - ringY) * 0.12;
    cursorRing.style.left = ringX + 'px';
    cursorRing.style.top  = ringY + 'px';
    requestAnimationFrame(animateCursor);
}
animateCursor();

// TOAST
function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = type === 'success' ? '✓ ' + msg : '✕ ' + msg;
    toast.className = 'toast ' + type + ' show';
    setTimeout(() => { toast.className = 'toast'; }, 3500);
}

// COPIER EMAIL
document.querySelectorAll('.copy-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const text = btn.dataset.copy;
        navigator.clipboard.writeText(text).then(() => {
            btn.textContent = '✓';
            btn.classList.add('copied');
            showToast('Email copié ! 📋', 'success');
            setTimeout(() => { btn.textContent = '⎘'; btn.classList.remove('copied'); }, 2000);
        });
    });
});

// TYPING
const typingLines = [
    "Développeur Web & étudiant en Cybersécurité.",
    "Je code, je teste, je sécurise.",
    "Futur pentester — Top 5% TryHackMe 🏆",
    "Disponible en alternance dès Septembre 2026."
];
function typeWriter() {
    const el = document.getElementById('typingText');
    if (!el) return;
    let lineIndex = 0, charIndex = 0, isDeleting = false, pause = 0;
    function type() {
        const line = typingLines[lineIndex];
        if (!isDeleting) {
            charIndex++;
            el.textContent = line.slice(0, charIndex);
            if (charIndex === line.length) {
                if (pause < 40) { pause++; setTimeout(type, 50); return; }
                pause = 0; isDeleting = true;
            }
            setTimeout(type, 45);
        } else {
            charIndex--;
            el.textContent = line.slice(0, charIndex);
            if (charIndex === 0) { isDeleting = false; lineIndex = (lineIndex + 1) % typingLines.length; }
            setTimeout(type, 22);
        }
    }
    setTimeout(type, 1200);
}
typeWriter();

// SCROLL REVEAL
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) { entry.target.classList.add('visible'); revealObserver.unobserve(entry.target); }
    });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal-fade, .reveal-left, .reveal-right, .reveal-zoom').forEach(el => revealObserver.observe(el));

// SKILL BARS
const skillObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            setTimeout(() => { entry.target.style.width = entry.target.dataset.width + '%'; }, 200);
            skillObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.3 });
document.querySelectorAll('.skill-level').forEach(bar => skillObserver.observe(bar));

// COMPTEURS
function animateCounter(el, target) {
    let current = 0;
    const step = Math.ceil(target / 50);
    const interval = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current;
        if (current >= target) clearInterval(interval);
    }, 30);
}
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.querySelectorAll('.stat-number[data-target]').forEach(el => animateCounter(el, parseInt(el.dataset.target)));
            counterObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.3 });
document.querySelectorAll('.about-stats').forEach(el => counterObserver.observe(el));

// TILT 3D + EFFET BRILLANCE
document.querySelectorAll('.tilt-card, .cert-card, .why-card').forEach(card => {
    const shine = card.querySelector('.card-shine');
    card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        if (card.classList.contains('tilt-card')) {
            const rotateX = ((y - centerY) / centerY) * -12;
            const rotateY = ((x - centerX) / centerX) * 12;
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        }
        if (shine) {
            const percentX = (x / rect.width) * 100;
            const percentY = (y / rect.height) * 100;
            shine.style.background = `radial-gradient(circle at ${percentX}% ${percentY}%, rgba(255,255,255,0.14) 0%, transparent 60%)`;
            shine.style.opacity = '1';
        }
    });
    card.addEventListener('mouseleave', () => {
        if (card.classList.contains('tilt-card')) {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale(1)';
        }
        if (shine) { shine.style.opacity = '0'; shine.style.background = ''; }
    });
});

// MODAL PHOTO
const photoClick = document.getElementById('photoClick');
const photoModal = document.getElementById('photoModal');
const photoModalClose = document.getElementById('photoModalClose');
const photoModalOverlay = document.getElementById('photoModalOverlay');
if (photoClick) {
    photoClick.addEventListener('click', () => {
        photoModal.classList.add('open');
        document.body.style.overflow = 'hidden';
    });
}
function closeModal() {
    photoModal.classList.remove('open');
    document.body.style.overflow = '';
}
if (photoModalClose) photoModalClose.addEventListener('click', closeModal);
if (photoModalOverlay) photoModalOverlay.addEventListener('click', closeModal);
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

// PARTICULES
const canvas = document.getElementById('particlesCanvas');
const ctx = canvas.getContext('2d');
let particles = [];
let mouse = { x: null, y: null };
function resizeCanvas() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
resizeCanvas();
window.addEventListener('resize', resizeCanvas);
window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });
window.addEventListener('mouseleave', () => { mouse.x = null; mouse.y = null; });
class Particle {
    constructor() { this.reset(); }
    reset() {
        this.x = Math.random() * canvas.width; this.y = Math.random() * canvas.height;
        this.vx = (Math.random()-0.5)*0.4; this.vy = (Math.random()-0.5)*0.4;
        this.r = Math.random()*1.5+0.5; this.alpha = Math.random()*0.4+0.1;
    }
    update() {
        if (mouse.x !== null) {
            const dx = this.x-mouse.x, dy = this.y-mouse.y;
            const dist = Math.sqrt(dx*dx+dy*dy);
            if (dist < 120) { const f=(120-dist)/120; this.vx+=(dx/dist)*f*0.6; this.vy+=(dy/dist)*f*0.6; }
        }
        this.vx*=0.97; this.vy*=0.97;
        this.x+=this.vx; this.y+=this.vy;
        if (this.x<0||this.x>canvas.width||this.y<0||this.y>canvas.height) this.reset();
    }
    draw() {
        const isDark = html.getAttribute('data-theme') !== 'light';
        ctx.beginPath(); ctx.arc(this.x, this.y, this.r, 0, Math.PI*2);
        ctx.fillStyle = isDark ? `rgba(0,255,204,${this.alpha})` : `rgba(0,136,102,${this.alpha})`;
        ctx.fill();
    }
}
for (let i = 0; i < 80; i++) particles.push(new Particle());
function connectParticles() {
    for (let i = 0; i < particles.length; i++) {
        for (let j = i+1; j < particles.length; j++) {
            const dx = particles[i].x-particles[j].x, dy = particles[i].y-particles[j].y;
            const dist = Math.sqrt(dx*dx+dy*dy);
            if (dist < 100) {
                const isDark = html.getAttribute('data-theme') !== 'light';
                ctx.beginPath(); ctx.moveTo(particles[i].x, particles[i].y); ctx.lineTo(particles[j].x, particles[j].y);
                ctx.strokeStyle = isDark ? `rgba(0,255,204,${0.06*(1-dist/100)})` : `rgba(0,136,102,${0.08*(1-dist/100)})`;
                ctx.lineWidth = 0.5; ctx.stroke();
            }
        }
    }
}
function animateParticles() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particles.forEach(p => { p.update(); p.draw(); });
    connectParticles();
    requestAnimationFrame(animateParticles);
}
animateParticles();

// SON CLAVIER
function playKeyClick() {
    try {
        const ac = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ac.createOscillator();
        const gain = ac.createGain();
        osc.connect(gain); gain.connect(ac.destination);
        osc.frequency.setValueAtTime(800, ac.currentTime);
        osc.frequency.exponentialRampToValueAtTime(200, ac.currentTime + 0.04);
        gain.gain.setValueAtTime(0.06, ac.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ac.currentTime + 0.05);
        osc.start(ac.currentTime); osc.stop(ac.currentTime + 0.05);
    } catch(e) {}
}

// TERMINAL
const termInput = document.getElementById('termInput');
const termBody  = document.getElementById('terminalBody');
const commands = {
    whoami:   () => `<span class="highlight">Lucas Huon</span> — Étudiant en Cybersécurité & Développeur Web\nVilleparisis, Île-de-France 🇫🇷`,
    age:      () => `<span class="highlight">22 ans</span>`,
    ville:    () => `<span class="highlight">Villeparisis</span>, Île-de-France`,
    objectif: () => `Devenir <span class="highlight">Pentester</span> 🎯\nPassionné par la sécurité offensive et le red team.`,
    skills:   () => `Nmap/Burp/Metasploit  ███████████░░░  75%\nPHP/JS/SQL            ████████████████ 100%\nTCP/IP/Wireshark      █████████████░░  85%\nWin/Linux/Hardware    ██████████████░  90%\nPentest Web/OSINT     ████████████░░░  80%`,
    contact:  () => `Email   : <span class="highlight">kkeyzoo2004@gmail.com</span>\nLinkedIn: <span class="highlight">linkedin.com/in/lucas-huon-4003b025a</span>\nGitHub  : <span class="highlight">github.com/LucasHuon77</span>\nTHM     : <span class="highlight">tryhackme.com/p/KKeyZoo</span>`,
    thm:      () => `TryHackMe — <span class="highlight">Top 5% mondial</span>\n101 000 points · 98 rooms complétées 🏆`,
    certifs:  () => `✅ Cyber Security 101\n✅ Web Fundamentals\n✅ Jr Penetration Tester\n🎯 CompTIA Security+ (en cours)\n🎓 CEH (visé)`,
    sudo:     () => `[sudo] mot de passe pour lucas :\n<span class="error">Accès refusé 🚫 — Je ne suis pas dans le fichier sudoers.</span>`,
    hack:     () => `Initialisation séquence de hack...\n<span class="highlight">[ ████████████████████ ] 100%</span>\nAccès refusé. Ce portfolio est protégé 😄`,
    matrix:   () => {
        const chars = '日ﾊﾐﾋｰｳｼﾅﾓﾆｻﾜﾂｵﾘｱﾎﾃﾏｹﾒｴｶｷﾑﾕﾗｾﾈｽﾀﾇﾍ012345789ABCDEF';
        let result = '';
        for (let i = 0; i < 5; i++) {
            let line = '';
            for (let j = 0; j < 30; j++) line += chars[Math.floor(Math.random()*chars.length)];
            result += `<span class="highlight">${line}</span>\n`;
        }
        return result + `\n<span class="highlight">Wake up, Neo... 🐰</span>`;
    },
    help: () => `Commandes disponibles :\n<span class="highlight">whoami</span>   — qui suis-je ?\n<span class="highlight">age</span>      — mon âge\n<span class="highlight">ville</span>    — où je suis\n<span class="highlight">objectif</span> — mon but pro\n<span class="highlight">skills</span>   — mes compétences\n<span class="highlight">contact</span>  — me contacter\n<span class="highlight">thm</span>      — mon profil TryHackMe\n<span class="highlight">certifs</span>  — mes certifications\n<span class="highlight">sudo</span>     — essaie toujours 😏\n<span class="highlight">hack</span>     — tente ta chance\n<span class="highlight">matrix</span>   — ...\n<span class="highlight">clear</span>    — vider le terminal`,
    clear: () => '__clear__',
};

function addLine(cmd, output) {
    if (output === '__clear__') { termBody.innerHTML = ''; return; }
    const line = document.createElement('div');
    line.className = 't-line';
    line.innerHTML = `<span class="t-prompt">lucas@portfolio:~$</span> <span class="t-cmd">${cmd}</span>`;
    termBody.appendChild(line);
    const out = document.createElement('div');
    out.className = 't-output';
    out.innerHTML = output;
    termBody.appendChild(out);
    termBody.scrollTop = termBody.scrollHeight;
}

setTimeout(() => {
    const outEl = document.getElementById('out-whoami');
    if (outEl) outEl.innerHTML = commands.whoami();
}, 600);

termInput.addEventListener('keydown', (e) => {
    if (!['Shift','Control','Alt','Meta','CapsLock','Tab'].includes(e.key)) playKeyClick();
    if (e.key === 'Enter') {
        const cmd = termInput.value.trim().toLowerCase();
        termInput.value = '';
        if (!cmd) return;
        addLine(cmd, commands[cmd] ? commands[cmd]() : `<span class="error">commande introuvable : ${cmd}</span>\nTape <span class="highlight">help</span> pour voir les commandes.`);
    }
});
document.getElementById('terminal').addEventListener('click', () => termInput.focus());

// SCROLL TOP
const scrollBtn = document.getElementById('scrollTop');
window.addEventListener('scroll', () => { scrollBtn.style.display = window.scrollY > 300 ? 'flex' : 'none'; });
scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

// FORM
document.getElementById('contactForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    const nom     = document.getElementById('nom').value.trim();
    const email   = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();

    if (!nom || !email || !message) {
        showToast('Remplis tous les champs !', 'error');
        return;
    }

    btn.textContent = 'Envoi en cours...';
    btn.style.opacity = '0.7';
    btn.disabled = true;

    try {
        const formData = new FormData(e.target);
        const response = await fetch('mailer.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(formData).toString()
        });
        const result = await response.json();
        if (result.success) {
            window.location.href = 'merci.php';
        } else {
            showToast('Erreur : ' + result.message, 'error');
        }
    } catch (err) {
        showToast('Erreur de connexion', 'error');
        console.error(err);
    } finally {
        btn.textContent = 'Envoyer le message';
        btn.style.opacity = '1';
        btn.disabled = false;
    }
});

// SIGNATURE
function startSignatureLoop() {
    const container = document.getElementById('signatureText');
    if (!container) return;
    const text = 'Lucas';
    let charIndex = 0, isDeleting = false, pause = 0;
    container.innerHTML = '';
    const spans = text.split('').map(char => {
        const span = document.createElement('span');
        span.className = 'sig-char';
        span.textContent = char;
        container.appendChild(span);
        return span;
    });
    const cur = document.createElement('span');
    cur.className = 'sig-cursor';
    container.appendChild(cur);
    function animate() {
        if (!isDeleting) {
            if (charIndex < spans.length) { spans[charIndex].classList.add('visible'); charIndex++; setTimeout(animate, 150); }
            else {
                if (pause < 20) { pause++; setTimeout(animate, 100); return; }
                pause = 0; isDeleting = true; setTimeout(animate, 400);
            }
        } else {
            if (charIndex > 0) { charIndex--; spans[charIndex].classList.remove('visible'); setTimeout(animate, 80); }
            else { isDeleting = false; setTimeout(animate, 600); }
        }
    }
    animate();
}
startSignatureLoop();

// ACTIVE NAV
const allSections = document.querySelectorAll('section[id]');
const allNavLinks = document.querySelectorAll('nav ul a');
const sectionObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            allNavLinks.forEach(link => {
                link.style.color = '';
                if (link.getAttribute('href') === '#' + entry.target.id) link.style.color = 'var(--accent)';
            });
        }
    });
}, { threshold: 0.4 });
allSections.forEach(s => sectionObserver.observe(s));