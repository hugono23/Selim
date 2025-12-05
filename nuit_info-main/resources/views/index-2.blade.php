<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIRD - Technologie & Avenir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;600&family=Space+Grotesk:wght@300;500;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    
    <style>
        :root {
            --eco-green: #00C853; /* Vert vibrant mais pro */
            --tech-dark: #121212;
            --tech-grey: #F5F5F7; /* Gris type Apple/Clean Tech */
            --line-color: rgba(255,255,255,0.2);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--tech-dark);
            color: white;
            font-family: 'Share Tech Mono', sans-serif;
            height: 100vh;
            overflow: hidden; /* Pas de scroll, effet "App" */
        }

        /* --- HEADER --- */
        nav {
            position: fixed;
            top: 0; width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 50px;
            z-index: 100;
            pointer-events: none; /* Laisse passer la souris sur le slider */
        }
        
        .logo {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo span { color: var(--eco-green); }

        .nav-right {
            pointer-events: auto;
        }

        .cta-header {
            text-decoration: none;
            color: var(--tech-dark);
            background: white;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }
        .cta-header:hover { transform: scale(1.3); background-color: #00C853; color: #121212; }

        /* --- GALERIE ACCORDÉON --- */
        .gallery-container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        .slide {
            position: relative;
            flex: 1; /* Tous égaux au départ */
            height: 100%;
            transition: flex 0.8s cubic-bezier(0.25, 1, 0.5, 1), filter 0.5s ease;
            overflow: hidden;
            cursor: pointer;
            border-right: 1px solid var(--line-color);
            filter: grayscale(100%) brightness(0.7); /* État inactif : gris */
        }

        .slide:last-child { border-right: none; }

        /* L'état ACTIF (Survol) */
        .slide:hover {
            flex: 5; /* Prend 5x plus de place */
            filter: grayscale(0%) brightness(1); /* Devient coloré */
        }

        /* IMAGES DE FOND */
        .slide-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 1.2s ease; /* Zoom lent */
        }
        
        .slide:hover .slide-bg {
            transform: scale(1.1);
        }

        /* --- CONTENU DU SLIDE --- */
        .content {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 40px;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease 0.2s; /* Délai pour l'apparition */
        }

        .slide:hover .content {
            opacity: 1;
            transform: translateY(0);
        }

        /* Numéro Tech en haut */
        .slide-number {
            position: absolute;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            opacity: 0.7;
            transition: opacity 0.3s;
        }
        .slide:hover .slide-number { opacity: 0; } /* Disparait quand ouvert */

        /* Titre Vertical (État fermé) */
        .vertical-title {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-90deg);
            white-space: nowrap;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: opacity 0.3s;
        }
        .slide:hover .vertical-title { opacity: 0; }

        /* Contenu complet (État ouvert) */
        h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 3rem;
            line-height: 1;
            margin-bottom: 10px;
            color: white;
        }
        h2 span { color: var(--eco-green); }

        p.desc {
            font-size: 1.1rem;
            max-width: 500px;
            color: #ddd;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        /* Data tags tech */
        .tags {
            display: flex;
            gap: 10px;
            font-family: 'Space Grotesk', monospace;
            font-size: 0.8rem;
            color: var(--eco-green);
            text-transform: uppercase;
        }
        .tag { border: 1px solid var(--eco-green); padding: 5px 10px; border-radius: 4px; }

        /* --- CURSEUR TECH --- */
        .cursor {
            position: fixed;
            width: 20px;
            height: 20px;
            border: 2px solid white;
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%, -50%);
            z-index: 9999;
            transition: width 0.3s, height 0.3s, background 0.3s;
            mix-blend-mode: difference;
        }
        
        /* Quand on survole un slide */
        body.hovering-slide .cursor {
            width: 80px;
            height: 80px;
            background: white;
            border-color: transparent;
            opacity: 0.1;
        }

    </style>
</head>
<body>

    <div class="cursor" id="cursor"></div>

    <nav>
        <div class="logo">
            <img src="logo.png" class="h-16" alt="">
        </div>
        <div class="nav-right flex flex-col gap-3">
            <a href="/demarches" class="cta-header">Démarches</a>
            <a href="/simulateur" class="cta-header">Simulateur</a>
            <a href="/pilote" class="cta-header">Pilote</a>
            <a href="/faire-un-don" class="cta-header">Faire un don</a>
        </div>
    </nav>

    <div class="gallery-container">
        
        <div class="slide" id="slide1">
            <div class="slide-bg" style="background-image: url('urgence.png');"></div>
            <!-- <div class="slide-number">01 /// WASTE</div> -->
            <div class="vertical-title">L'Urgence</div>
            
            <div class="content">
                <div class="tags"><span class="tag">Analyse</span><span class="tag">57M Tonnes</span></div>
                <h2>Le Chaos <br><span>Numérique</span></h2>
                <p class="desc">Chaque année, des millions de tonnes de potentiel sont gâchées. Nous voyons des ressources là où d'autres voient des déchets.</p>
            </div>
        </div>

        <div class="slide" id="slide2">
            <div class="slide-bg" style="background-image: url('expertise.png');"></div>
            <!-- <div class="slide-number">02 /// REPAIR</div> -->
            <div class="vertical-title">L'Expertise</div>
            
            <div class="content">
                <div class="tags"><span class="tag">Engineering</span><span class="tag">Reconditionnement</span></div>
                <h2>Précision <br><span>Technique</span></h2>
                <p class="desc">Nos laboratoires prolongent la durée de vie du matériel. Une approche chirurgicale pour une technologie durable.</p>
            </div>
        </div>

        <div class="slide" id="slide3">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2070&auto=format&fit=crop');"></div>
            <!-- <div class="slide-number">03 /// EDU</div> -->
            <div class="vertical-title">La Mission</div>
            
            <div class="content">
                <div class="tags"><span class="tag">Social</span><span class="tag">Transmission</span></div>
                <h2>Équiper <br><span>L'Avenir</span></h2>
                <p class="desc">L'école ne doit pas être une poubelle, mais un tremplin. Nous fournissons du matériel premium aux étudiants.</p>
            </div>
        </div>

        <div class="slide" id="slide4">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop');"></div>
            <!-- <div class="slide-number">04 /// FUTURE</div> -->
            <div class="vertical-title">L'Impact</div>
            
            <div class="content">
                <div class="tags"><span class="tag">Ecology</span><span class="tag">Net Zero</span></div>
                <h2>Symbiose <br><span>Totale</span></h2>
                <p class="desc">Technologie et écologie ne sont plus ennemies. Rejoignez le mouvement NIRD pour un cycle vertueux.</p>
            </div>
        </div>

    </div>

    <script>
        const cursor = document.getElementById('cursor');
        const slides = document.querySelectorAll('.slide');

        // Gestion du curseur fluide
        document.addEventListener('mousemove', (e) => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });

        // Gestion des interactions sur les slides
        slides.forEach(slide => {
            slide.addEventListener('mouseenter', () => {
                document.body.classList.add('hovering-slide');
                // Petit effet magnétique ou changement de couleur possible ici
            });
            
            slide.addEventListener('mouseleave', () => {
                document.body.classList.remove('hovering-slide');
            });
        });

        // Bonus : Effet Parallax sur la souris pour l'image active
        document.addEventListener('mousemove', (e) => {
            // Trouver le slide qui est en hover (flex > 1)
            const activeSlide = document.querySelector('.slide:hover .slide-bg');
            if(activeSlide) {
                const x = (window.innerWidth - e.pageX) / 50;
                const y = (window.innerHeight - e.pageY) / 50;
                activeSlide.style.transform = `scale(1.1) translate(${x}px, ${y}px)`;
            }
        });

    </script>
</body>
</html>