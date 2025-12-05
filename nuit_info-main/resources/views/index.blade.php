<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission NIRD : Eco-Scanner</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --nird-green: #2ecc71;
            --alert-red: #e74c3c;
            --ui-bg: rgba(10, 10, 10, 0.9);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; cursor: none; user-select: none; }

        body {
            background: #000;
            overflow: hidden;
            font-family: 'Share Tech Mono', monospace;
            color: white;
            height: 100vh;
            width: 100vw;
        }

        /* --- COUCHE 1 : L'IMAGE DE FOND (Sombre/NB) --- */
        #bg-layer {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            /* REMPLACE PAR TON IMAGE */
            background-image: url({{asset('img.png')}}); 
            background-size: cover;
            background-position: center;
            filter: grayscale(100%) brightness(0.2); /* Aspect "éteint" */
            z-index: 1;
        }

        /* --- COUCHE 2 : FLASHLIGHT (Le trou de serrure) --- */
        #flashlight {
            position: absolute;
            top: 0; left: 0;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            /* L'astuce : un box-shadow géant pour faire l'obscurité autour */
            box-shadow: 0 0 0 200vw rgba(43, 40, 40, 0.5);
            background: url('4170f5e9-a798-48ed-93da-1834af18e7db.jpg') no-repeat center fixed;
            background-size: cover;
            border: 2px solid var(--nird-green);
            pointer-events: none; /* Laissez les clics passer au travers */
            transition: width 0.2s, height 0.2s;
        }
        
        /* Scan lines dans la lumière */
        #flashlight::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            background-size: 100% 2px, 3px 100%;
            border-radius: 50%;
        }

        /* --- UI DU JEU --- */
        .hud {
            position: absolute;
            z-index: 10;
            padding: 20px;
            width: 100%;
            pointer-events: none;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        h1 {
            font-family: 'Oswald', sans-serif;
            font-size: 2rem;
            text-transform: uppercase;
        }
        h1 span { color: var(--nird-green); }

        .mission-box {
            background: var(--ui-bg);
            border: 1px solid var(--nird-green);
            padding: 15px;
            max-width: 300px;
            box-shadow: 0 0 15px rgba(46, 204, 113, 0.2);
        }

        .progress-container {
            margin-top: 10px;
            width: 100%;
            height: 10px;
            background: #333;
            border: 1px solid white;
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background: var(--nird-green);
            transition: width 0.5s ease;
        }

        /* --- ITEMS À TROUVER (Zones invisibles) --- */
        .hidden-item {
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            z-index: 5;
            cursor: none;
            /* Border transparent pour débug, mettre 'red' pour voir les zones */
            border: 1px solid transparent;
            background: rgba(0, 0, 0, 0.4);
        }

        /* Indicateur visuel quand on survole un item */
        .scanner-reticle {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) scale(0.5);
            width: 60px; height: 60px;
            border: 2px dashed var(--alert-red);
            border-radius: 50%;
            opacity: 0;
            transition: all 0.3s;
            pointer-events: none;
            z-index: 6;
            animation: spin 4s linear infinite;
        }

        .hidden-item:hover .scanner-reticle {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.2);
            border-color: var(--nird-green);
        }
        
        /* Positions arbitraires basées sur l'image (à ajuster selon ton image) */
        #item-1 { top: 60%; left: 45%; } /* Un écran au centre */
        #item-2 { top: 40%; left: 65%; } /* Une tour à droite */
        #item-3 { top: 75%; left: 25%; } /* Clavier/câbles à gauche */

        /* --- POPUP INFO --- */
        .modal {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: rgba(0,0,0,0.95);
            border: 2px solid var(--nird-green);
            padding: 30px;
            width: 400px;
            z-index: 100;
            text-align: center;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: auto;
            cursor: default;
        }

        .modal.active { transform: translate(-50%, -50%) scale(1); }
        
        .modal h2 { color: var(--nird-green); font-family: 'Oswald'; font-size: 2rem; margin-bottom: 10px; }
        .modal p { font-size: 1rem; line-height: 1.5; color: #ccc; margin-bottom: 20px; }
        .modal button {
            background: var(--nird-green);
            color: black;
            border: none;
            padding: 10px 20px;
            font-family: 'Oswald';
            font-weight: bold;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .modal button:hover { background: white; }

        /* --- MESSAGE VICTOIRE --- */
        #victory-screen {
            display: none;
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 200;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .btn-final {
            margin-top: 20px;
            padding: 15px 40px;
            font-size: 1.5rem;
            background: transparent;
            color: var(--nird-green);
            border: 2px solid var(--nird-green);
            text-decoration: none;
            transition: 0.3s;
            cursor: pointer;
        }
        .btn-final:hover { background: var(--nird-green); color: black; box-shadow: 0 0 30px var(--nird-green); }

        @keyframes spin { 100% { transform: translate(-50%, -50%) scale(1.2) rotate(360deg); } }

        /* Curseur custom "Scanner" */
        #cursor-text {
            position: absolute;
            color: var(--nird-green);
            font-size: 12px;
            pointer-events: none;
            z-index: 20;
            transform: translate(20px, 20px);
        }

    </style>
</head>
<body>

    <div id="bg-layer"></div>

    <div id="flashlight"></div>

    <div id="cursor-text">SCANNING...</div>

    <div class="hud">
        <div class="top-bar">
            <div class="logo"><h1><span>NIRD</span> /// AGENT</h1></div>
            <div class="mission-box">
                <h3>MISSION EN COURS</h3>
                <p>Localisez 3 composants recyclables.</p>
                <div class="progress-container">
                    <div class="progress-bar" id="progress"></div>
                </div>
                <p style="text-align: right; margin-top: 5px; font-size: 0.8rem;">TROUVÉS: <span id="count">0</span>/3</p>
            </div>
        </div>
    </div>

    <div class="hidden-item" id="item-1" onclick="foundItem(1)">
        <div class="scanner-reticle"></div>
    </div>
    
    <div class="hidden-item" id="item-2" onclick="foundItem(2)">
        <div class="scanner-reticle"></div>
    </div>

    <div class="hidden-item" id="item-3" onclick="foundItem(3)">
        <div class="scanner-reticle"></div>
    </div>

    <div class="modal" id="modal-1">
        <h2>ÉCRAN CRT DÉTECTÉ</h2>
        <p>Les vieux moniteurs contiennent du plomb et du phosphore. S'ils sont jetés dans la nature, ils polluent les sols. Recyclés, le verre peut être réutilisé !</p>
        <button onclick="closeModal(1)">COLLECTER</button>
    </div>

    <div class="modal" id="modal-2">
        <h2>UNITÉ CENTRALE</h2>
        <p>Cette tour contient de l'or, de l'argent et du cuivre. Une mine urbaine ! NIRD récupère ces composants pour reconditionner des ordinateurs pour les écoles.</p>
        <button onclick="closeModal(2)">COLLECTER</button>
    </div>

    <div class="modal" id="modal-3">
        <h2>CÂBLAGE CUIVRE</h2>
        <p>Des kilomètres de câbles sont jetés chaque année. Le plastique est toxique s'il est brûlé, mais le cuivre à l'intérieur est recyclable à l'infini.</p>
        <button onclick="closeModal(3)">COLLECTER</button>
    </div>

    <div id="victory-screen">
        <h1 style="font-size: 4rem; margin-bottom: 20px;">MISSION ACCOMPLIE</h1>
        <p style="font-size: 1.5rem; max-width: 600px; margin-bottom: 30px;">
            Vous avez sauvé ces composants de la décharge. <br>
            Imaginez ce que nous pouvons faire ensemble dans la réalité.
        </p>
        <a href="/index-2" class="btn-final">REJOINDRE LE MOUVEMENT NIRD</a>
    </div>

    <script>
        const flashlight = document.getElementById('flashlight');
        const cursorText = document.getElementById('cursor-text');
        let itemsFound = 0;
        const totalItems = 3;

        // 1. Gestion de la lampe torche (Souris)
        document.addEventListener('mousemove', (e) => {
            const x = e.clientX;
            const y = e.clientY;

            // Déplace la "lumière"
            flashlight.style.left = `${x}px`;
            flashlight.style.top = `${y}px`;

            // Déplace le texte du curseur
            cursorText.style.left = `${x}px`;
            cursorText.style.top = `${y}px`;
        });

        // 2. Logique de jeu
        function foundItem(id) {
            const item = document.getElementById(`item-${id}`);
            // Empêcher le double clic
            if(item.style.pointerEvents === 'none') return;
            
            // Ouvrir la modale correspondante
            document.getElementById(`modal-${id}`).classList.add('active');
            
            // Désactiver l'item
            item.style.pointerEvents = 'none';
        }

        function closeModal(id) {
            // Fermer la modale
            document.getElementById(`modal-${id}`).classList.remove('active');
            
            // Mettre à jour le score
            itemsFound++;
            updateHUD();

            // Vérifier la victoire
            if (itemsFound === totalItems) {
                setTimeout(() => {
                    document.getElementById('victory-screen').style.display = 'flex';
                }, 500);
            }
        }

        function updateHUD() {
            document.getElementById('count').innerText = itemsFound;
            const percentage = (itemsFound / totalItems) * 100;
            document.getElementById('progress').style.width = `${percentage}%`;
        }

        // Ajout d'effet sonore "Bip" au survol (Optionnel mais cool)
        // Note: Les navigateurs bloquent souvent l'audio sans interaction préalable, 
        // donc on reste sur du visuel (le réticule qui tourne).

        // Changement dynamique du texte du curseur
        const items = document.querySelectorAll('.hidden-item');
        items.forEach(item => {
            item.addEventListener('mouseenter', () => {
                cursorText.innerText = "SIGNAL DÉTECTÉ !";
                cursorText.style.color = "var(--alert-red)";
                flashlight.style.borderColor = "var(--alert-red)";
            });
            item.addEventListener('mouseleave', () => {
                cursorText.innerText = "SCANNING...";
                cursorText.style.color = "var(--nird-green)";
                flashlight.style.borderColor = "var(--nird-green)";
            });
        });

    </script>
</body>
</html>