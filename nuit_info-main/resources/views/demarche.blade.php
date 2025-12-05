<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIRD - Processus Interactif</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=Manrope:wght@300;400;600&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --nird-green: #00C853;
            --nird-violet: #8e44ad;
            --bg-dark: #050505;
            --card-bg: #111;
            --text-white: #ffffff;
            --gradient-active: linear-gradient(135deg, rgba(52, 152, 219, 0.2), rgba(155, 89, 182, 0.2));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .body {
            background-color: var(--bg-dark);
            font-family: 'Manrope', sans-serif;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            /* Pas de débordement */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* FOND */
        .bg-layer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            opacity: 0.15;
            z-index: -2;
            filter: grayscale(100%);
        }

        /* TITRE */
        h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.5rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2vh;
            text-shadow: 0 0 15px rgba(0, 200, 83, 0.4);
            z-index: 10;
        }

        /* CONTAINER ACCORDÉON */
        .accordion-container {
            display: flex;
            align-items: center;
            width: 95vw;
            height: 75vh;
            /* Occupe max page sans déborder */
            gap: 40px;
            /* Espace pour les liens */
            position: relative;
            z-index: 1;
        }

        /* SVG LINK LAYER (Derrière les cartes) */
        .connections-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: visible;
        }

        .connection-line {
            fill: none;
            stroke: rgba(255, 255, 255, 0.3);
            stroke-width: 2px;
            stroke-dasharray: 8;
            /* Style schéma DB */
            transition: stroke 0.3s;
        }

        /* Les points de connexion (les ronds aux bouts des lignes) */
        .connection-dot {
            fill: #000;
            stroke: var(--nird-green);
            stroke-width: 2px;
            r: 4;
        }

        /* LA CARTE */
        .card {
            position: relative;
            flex: 1;
            /* Taille égale au départ */
            background: rgba(10, 10, 10, 0.9);
            border-radius: 20px;
            overflow: hidden;
            height: fit-content;
            cursor: default;
            transition: flex 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            display: flex;
            flex-direction: column;
            border: 2px solid var(--nird-green);
            /* Bordure verte repos */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        /* INTERACTION SURVOL */
        .card:hover {
            flex: 3;
            /* Devient 3x plus large que les autres */
            background: rgba(15, 15, 15, 0.95);
            border-color: transparent;
            /* On cache la bordure verte pour l'anim */
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
        }

        /* Quand on survole une carte, les lignes connectées s'allument */
        .card:hover~.connections-layer path {
            opacity: 0.5;
        }

        /* ANIMATION POINT VIOLET (SVG OVERLAY) */
        .border-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 10;
        }

        .card:hover .border-animation {
            opacity: 1;
        }

        .border-rect {
            fill: none;
            stroke: var(--nird-violet);
            stroke-width: 4px;
            stroke-dasharray: 20 1000;
            /* Un tiret court (le point), un espace immense */
            stroke-linecap: round;
            animation: dashMove 3s linear infinite;
            filter: drop-shadow(0 0 8px var(--nird-violet));
        }

        @keyframes dashMove {
            to {
                stroke-dashoffset: -1020;
            }

            /* Doit matcher le dasharray total */
        }

        /* CONTENU INTERNE */
        .card-inner {
            padding: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            opacity: 0.8;
            transition: opacity 0.4s;
        }

        .card:hover .card-inner {
            opacity: 1;
        }

        .card-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 20px;
            filter: grayscale(100%);
            transition: filter 0.5s;
        }

        .card:hover .card-img {
            filter: grayscale(0%);
            height: 200px;
        }

        .badge {
            background: rgba(0, 200, 83, 0.15);
            color: var(--nird-green);
            padding: 6px 12px;
            border-radius: 4px;
            font-family: 'Space Grotesk';
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            align-self: flex-start;
            margin-bottom: 15px;
        }

        h2 {
            font-family: 'Space Grotesk';
            font-size: 1.5rem;
            color: white;
            margin-bottom: 10px;
            white-space: nowrap;
            /* Empêche le saut de ligne quand petit */
        }

        .card:hover h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .short-desc {
            color: #aaa;
            font-size: 0.9rem;
            display: block;
        }

        /* CONTENU CACHÉ (VISIBLE SEULEMENT AU SURVOL) */
        .full-content {
            margin-top: 20px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease 0.1s;
            display: none;
            /* Retiré du flux quand fermé */
        }

        .card:hover .full-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        ul {
            list-style: none;
            margin-bottom: 20px;
        }

        li {
            color: #ddd;
            margin-bottom: 12px;
            padding-left: 20px;
            position: relative;
            line-height: 1.5;
            font-size: 1rem;
        }

        li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            width: 6px;
            height: 6px;
            background: var(--nird-violet);
            border-radius: 50%;
        }

        .result-box {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            color: white;
        }

        .result-box span {
            color: var(--nird-green);
            font-weight: 700;
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
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

        .cta-header:hover {
            transform: scale(1.3);
            background-color: #00C853;
            color: #121212;
        }
    </style>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="absolute right-10 m-5 top-10 z-20 flex items-center gap-6">
        <nav>
            <div class="nav-right flex flex-col gap-3">
                <a href="/index-2" class="cta-header">Acceuil</a>
                <a href="/simulateur" class="cta-header">Simulateur</a>
                <a href="/pilote" class="cta-header">Pilote</a>
                <a href="/faire-un-don" class="cta-header">Faire un don</a>
            </div>
        </nav>
    </div>
    <div class="body">
        <div class="bg-layer"></div>
        <h1>La Démarche NIRD</h1>
    
        <div class="accordion-container">
            <svg class="connections-layer" id="svg-layer">
                <path id="line1" class="connection-line" d="" />
                <path id="line2" class="connection-line" d="" />
                <circle id="dot1-start" class="connection-dot" />
                <circle id="dot1-end" class="connection-dot" />
                <circle id="dot2-start" class="connection-dot" />
                <circle id="dot2-end" class="connection-dot" />
            </svg>
    
            <div class="card" id="card1">
                <svg class="border-animation" width="100%" height="100%">
                    <rect class="border-rect" x="2" y="2" width="99%" height="99%" rx="20" />
                </svg>
    
                <div class="card-inner">
                    <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&w=800&q=80"
                        class="card-img">
                    <div class="badge">Jalon 01</div>
                    <h2>Mobilisation</h2>
                    <span class="short-desc">Initier la dynamique collective.</span>
    
                    <div class="full-content">
                        <ul>
                            <li>Identification d'un enseignant référent.</li>
                            <li>Réunion de sensibilisation équipe & direction.</li>
                            <li>Mise en réseau via Tchap.</li>
                        </ul>
                        <div class="result-box">
                            <span>Résultat</span>
                            Feu vert de la direction.
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="card" id="card2">
                <svg class="border-animation" width="100%" height="100%">
                    <rect class="border-rect" x="2" y="2" width="99%" height="99%" rx="20" />
                </svg>
    
                <div class="card-inner">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80"
                        class="card-img">
                    <div class="badge">Jalon 02</div>
                    <h2>Expérimentation</h2>
                    <span class="short-desc">Tester le matériel et l'usage.</span>
    
                    <div class="full-content">
                        <ul>
                            <li>Installation de postes sous Linux.</li>
                            <li>Club informatique géré par les élèves.</li>
                            <li>Analyse des retours techniques.</li>
                        </ul>
                        <div class="result-box">
                            <span>Résultat</span>
                            Première expérience réussie.
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="card" id="card3">
                <svg class="border-animation" width="100%" height="100%">
                    <rect class="border-rect" x="2" y="2" width="99%" height="99%" rx="20" />
                </svg>
    
                <div class="card-inner">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80"
                        class="card-img">
                    <div class="badge">Jalon 03</div>
                    <h2>Intégration</h2>
                    <div class="short-desc">Pérenniser la structure.</div>
    
                    <div class="full-content">
                        <ul>
                            <li>Inscription au projet d'établissement.</li>
                            <li>Pilotage officiel par la direction.</li>
                            <li>Partenariat durable avec la collectivité.</li>
                        </ul>
                        <div class="result-box">
                            <span>Résultat</span>
                            Démarche ancrée durablement.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // SCRIPT DE DESSIN DES LIENS "FIGMA"
        // On doit redessiner les lignes en permanence car les cartes bougent (flex transition)

        const svg = document.getElementById('svg-layer');
        const line1 = document.getElementById('line1');
        const line2 = document.getElementById('line2');

        // Les points SVG
        const d1s = document.getElementById('dot1-start');
        const d1e = document.getElementById('dot1-end');
        const d2s = document.getElementById('dot2-start');
        const d2e = document.getElementById('dot2-end');

        const cards = [
            document.getElementById('card1'),
            document.getElementById('card2'),
            document.getElementById('card3')
        ];

        function drawLines() {
            const containerRect = document.querySelector('.accordion-container').getBoundingClientRect();

            function connect(cardA, cardB, lineEl, dotStart, dotEnd) {
                const rA = cardA.getBoundingClientRect();
                const rB = cardB.getBoundingClientRect();

                // Point de départ : Milieu droit de Carte A
                const x1 = rA.right - containerRect.left;
                const y1 = (rA.top + rA.height / 2) - containerRect.top;

                // Point d'arrivée : Milieu gauche de Carte B
                const x2 = rB.left - containerRect.left;
                const y2 = (rB.top + rB.height / 2) - containerRect.top;

                // Dessin de la ligne (Style Figma : courbe)
                // On ajoute un petit offset de 20px pour que le lien ne colle pas aux bords
                const path = `M ${x1 - 2} ${y1} C ${x1 + 50} ${y1}, ${x2 - 50} ${y2}, ${x2 + 2} ${y2}`;
                lineEl.setAttribute('d', path);

                // Positionnement des points
                dotStart.setAttribute('cx', x1);
                dotStart.setAttribute('cy', y1);
                dotEnd.setAttribute('cx', x2);
                dotEnd.setAttribute('cy', y2);
            }

            connect(cards[0], cards[1], line1, d1s, d1e);
            connect(cards[1], cards[2], line2, d2s, d2e);
        }

        // Boucle d'animation pour fluidité extrême pendant la transition CSS
        function animate() {
            drawLines();
            requestAnimationFrame(animate);
        }

        // Lancer l'animation
        animate();

        // Ajuster aussi les rect SVG au resize
        window.addEventListener('resize', drawLines);

    </script>
</body>

</html>