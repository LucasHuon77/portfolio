<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message envoyé — Lucas.dev</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Mono', monospace; background: #080c10; color: #e8f4f0; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 20px; overflow: hidden; }
        .particles { position: fixed; inset: 0; z-index: 0; pointer-events: none; }
        .card { position: relative; z-index: 1; background: #111820; border: 1px solid rgba(0,255,204,0.2); border-radius: 20px; padding: 48px 40px; max-width: 500px; width: 100%; box-shadow: 0 0 60px rgba(0,255,204,0.08); }
        .card::before { content: ''; position: absolute; top: 0; left: 10%; right: 10%; height: 2px; background: linear-gradient(90deg, transparent, #00ffcc, #ff00ff, transparent); border-radius: 2px; }
        .icon { font-size: 3rem; margin-bottom: 20px; animation: bounce 1s ease infinite alternate; }
        @keyframes bounce { from { transform: translateY(0); } to { transform: translateY(-8px); } }
        h1 { font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 800; color: #00ffcc; margin-bottom: 12px; }
        p { color: #7a9090; font-size: 0.85rem; line-height: 1.7; margin-bottom: 8px; }
        .ref { display: inline-block; background: rgba(0,255,204,0.08); border: 1px solid rgba(0,255,204,0.2); color: #00ffcc; font-size: 0.7rem; letter-spacing: 0.15em; padding: 6px 16px; border-radius: 20px; margin: 16px 0 28px; }
        .btn { display: inline-block; background: linear-gradient(135deg, #00ffcc, #ff00ff); color: #080c10; font-family: 'DM Mono', monospace; font-weight: 700; font-size: 0.82rem; letter-spacing: 0.1em; text-decoration: none; padding: 13px 32px; border-radius: 8px; text-transform: uppercase; transition: transform 0.2s, box-shadow 0.2s; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,255,204,0.3); }
        .terminal-line { font-size: 0.72rem; color: rgba(0,255,204,0.4); margin-top: 24px; }
        .terminal-line span { color: #00ffcc; }
    </style>
</head>
<body>
<canvas class="particles" id="p"></canvas>
<div class="card">
    <div class="icon">✅</div>
    <h1>Message envoyé !</h1>
    <p>Merci pour ton message. Je l'ai bien reçu et je te réponds dans les plus brefs délais.</p>
    <p>En attendant, n'hésite pas à consulter mes projets ou à me suivre sur LinkedIn.</p>
    <div class="ref" id="refCode">REF: MSG-2026-????????</div>
    <br>
    <a href="accueil.php#contact" class="btn">← Retour au portfolio</a>
    <p class="terminal-line">lucas@portfolio:~$ <span>echo "À bientôt 👋"</span></p>
</div>
<script>
    // Génère une ref côté client
    const ref = 'MSG-2026-' + Math.random().toString(36).substring(2,10).toUpperCase();
    document.getElementById('refCode').textContent = 'REF: ' + ref;

    // Particules
    const canvas = document.getElementById('p');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    window.addEventListener('resize', () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; });
    const particles = Array.from({length: 60}, () => ({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        vx: (Math.random()-0.5)*0.3,
        vy: (Math.random()-0.5)*0.3,
        r: Math.random()*1.5+0.5,
        a: Math.random()*0.3+0.1
    }));
    function draw() {
        ctx.clearRect(0,0,canvas.width,canvas.height);
        particles.forEach(p => {
            p.x+=p.vx; p.y+=p.vy;
            if(p.x<0||p.x>canvas.width) p.vx*=-1;
            if(p.y<0||p.y>canvas.height) p.vy*=-1;
            ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
            ctx.fillStyle=`rgba(0,255,204,${p.a})`; ctx.fill();
        });
        requestAnimationFrame(draw);
    }
    draw();
</script>
</body>
</html>