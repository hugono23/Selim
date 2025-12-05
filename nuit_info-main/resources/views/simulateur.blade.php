<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIRD - Calculateur d'Impact</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;500;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    
    <style>
        :root {
            --primary: #00ff88; /* Vert néon NIRD */
            --primary-dark: #00cc6a;
            --bg-dark: #0a0a0f;
            --card-bg: rgba(20, 25, 30, 0.7);
            --text-white: #ffffff;
            --text-grey: #8b9bb4;
            --stroke-width: 12;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

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

        .body {
            font-family: 'Share Tech Mono', sans-serif;
            background-color: var(--bg-dark);
            /* Petit gradient de fond pour donner de la profondeur à l'effet Glass */
            background: radial-gradient(circle at 50% 10%, #1a2a3a 0%, #050505 100%);
            background-color: rgba(0, 0, 0, 0.5);
            filter: blur(4)
            color: var(--text-white);
            height: 100vh;
            display: flex;
            justify-content: center;
            background-image: linear-gradient(
        rgba(0, 0, 0, 0.7), /* Le dernier chiffre (0.4) est l'opacité */
        rgba(0, 0, 0, 0.7) 
    ), url('simulator.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            align-items: center;
            overflow: hidden;
        }

        /* --- LA CARTE PRINCIPALE (GLASSMORPHISM) --- */
        .calculator-card {
            position: relative;
            width: 900px;
            max-width: 90%;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 60px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            display: flex;
            gap: 60px;
            z-index: 2;
        }

        /* Background Glow effect derrière la carte */
        .calculator-card::before {
            content: '';
            position: absolute;
            top: -20%; left: -10%;
            width: 50%; height: 50%;
            background: radial-gradient(circle, rgba(0, 255, 136, 0.15) 0%, transparent 70%);
            z-index: -1;
            pointer-events: none;
        }

        /* --- COLONNE GAUCHE : INPUTS --- */
        .inputs-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 10px;
            background: linear-gradient(to right, #fff, #b3b3b3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            color: var(--text-grey);
            font-size: 1rem;
            margin-bottom: 40px;
        }

        /* SLIDER CUSTOM */
        .slider-container { margin-bottom: 40px; }
        
        .label-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-weight: 600;
            color: var(--text-white);
        }
        
        .range-value { color: var(--primary); font-weight: 800; }

        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 5px;
            background: rgba(255,255,255,0.1);
            outline: none;
            cursor: pointer;
            /* Le background sera mis à jour en JS pour la progression */
            background-image: linear-gradient(var(--primary), var(--primary));
            background-size: 0% 100%;
            background-repeat: no-repeat;
        }

        /* Thumb du slider (le rond) */
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 25px;
            width: 25px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 0 15px var(--primary);
            cursor: pointer;
            transition: transform 0.1s;
        }
        input[type=range]::-webkit-slider-thumb:hover { transform: scale(1.2); }

        /* BOUTONS DUREE */
        .duration-selector {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .duration-btn {
            flex: 1;
            padding: 15px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: var(--text-grey);
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Manrope', sans-serif;
            font-weight: 600;
            text-align: center;
        }

        .duration-btn.active {
            background: rgba(0, 255, 136, 0.1);
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.1);
        }

        /* --- COLONNE DROITE : RESULTATS --- */
        .results-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.2);
            border-radius: 20px;
            padding: 40px;
            position: relative;
        }

        /* CERCLE SVG */
        .progress-circle {
            width: 250px;
            height: 250px;
            position: relative;
        }

        svg {
            transform: rotate(-90deg); /* Début en haut */
            width: 100%;
            height: 100%;
        }

        circle {
            fill: none;
            stroke-width: var(--stroke-width);
            stroke-linecap: round;
        }

        .bg-ring { stroke: rgba(255,255,255,0.05); }
        
        .progress-ring {
            stroke: var(--primary);
            stroke-dasharray: 660; /* Circumference approx pour r=105 */
            stroke-dashoffset: 660; /* Plein au départ (vide) */
            transition: stroke-dashoffset 0.8s cubic-bezier(0.25, 1, 0.5, 1);
            filter: drop-shadow(0 0 8px rgba(0, 255, 136, 0.5));
        }

        /* TEXTE AU CENTRE DU CERCLE */
        .circle-content {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .impact-number {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            display: block;
            line-height: 1;
        }

        .impact-unit {
            font-size: 0.9rem;
            color: var(--text-grey);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        /* BOITE EQUIVALENCE */
        .equivalence-box {
            margin-top: 30px;
            width: 100%;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 4px solid var(--primary);
            opacity: 0;
            transform: translateY(10px);
            animation: slideUp 0.5s forwards 0.5s;
        }

        .icon-eq { font-size: 1.5rem; }
        
        .text-eq {
            font-size: 0.9rem;
            line-height: 1.4;
            color: #ddd;
        }
        
        .highlight { color: var(--primary); font-weight: 700; }

        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .calculator-card { flex-direction: column; height: auto; padding: 30px; }
            .results-section { width: 100%; margin-top: 30px; }
        }
    </style>
</head>
<body>
    <div class="absolute right-10 m-5 top-10">
        <nav>
            <div class="nav-right flex flex-col gap-3">
                <a href="/index-2" class="cta-header">Acceuil</a>
                <a href="/demarches" class="cta-header">Démarches</a>
                <a href="/pilote" class="cta-header">Pilote</a>
                <a href="/faire-un-don" class="cta-header">Faire un don</a>
            </div>
        </nav>
    </div>
    <div class="body">
        <div class="calculator-card">
            <div class="inputs-section">
                <h2>Calculateur <br>d'Impact Carbone</h2>
                <p class="subtitle">Estimez vos économies de CO2 en choisissant le reconditionné avec NIRD.</p>
    
                <div class="slider-container">
                    <div class="label-group">
                        <span>Nombre d'ordinateurs</span>
                        <span class="range-value" id="pc-display">25 PC</span>
                    </div>
                    <input type="range" min="1" max="500" value="25" id="pc-slider">
                </div>
    
                <div class="duration-selector">
                    <button class="duration-btn" data-years="3" onclick="setDuration(3)">Extension 3 ans</button>
                    <button class="duration-btn active" data-years="5" onclick="setDuration(5)">Extension 5 ans</button>
                </div>
            </div>
    
            <div class="results-section">
                <div class="progress-circle">
                    <svg>
                        <circle class="bg-ring" cx="125" cy="125" r="105"></circle>
                        <circle class="progress-ring" cx="125" cy="125" r="105" id="circle-ring"></circle>
                    </svg>
                    <div class="circle-content">
                        <span class="impact-number" id="co2-number">0</span>
                        <span class="impact-unit">Tonnes CO2e<br>Évitées</span>
                    </div>
                </div>
    
                <div class="equivalence-box">
                    <span class="icon-eq">✈️</span>
                    <p class="text-eq" id="eq-text">
                        Cela équivaut à <span class="highlight">2</span> allers-retours Paris-New York évités.
                    </p>
                </div>
            </div>
        </div>
    </div>


    <script>
        // --- CONFIGURATION & LOGIQUE ---
        // Hypothèse : Fabriquer 1 PC portable neuf ~ 250kg CO2 (Moyenne ADEME)
        // Prolonger sa vie évite la fabrication d'un neuf.
        // Facteur années : + on garde longtemps, + le "lissage" est efficace.
        // Ici on simplifie : Impact évité = (Nb PC * 0.25 Tonnes). 
        // Bonus pour 5 ans vs 3 ans (coef 1.2 pour encourager la durabilité).

        const CO2_PER_PC = 0.25; // Tonnes
        
        const slider = document.getElementById('pc-slider');
        const pcDisplay = document.getElementById('pc-display');
        const co2Number = document.getElementById('co2-number');
        const circleRing = document.getElementById('circle-ring');
        const eqText = document.getElementById('eq-text');
        
        let currentYears = 5; // Défaut

        // Circonférence du cercle (2 * PI * r) où r=105
        const circumference = 2 * Math.PI * 105;
        circleRing.style.strokeDasharray = `${circumference} ${circumference}`;
        circleRing.style.strokeDashoffset = circumference;

        function setDuration(years) {
            currentYears = years;
            // Update UI buttons
            document.querySelectorAll('.duration-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`button[data-years="${years}"]`).classList.add('active');
            updateCalculator();
        }

        function updateCalculator() {
            const pcCount = parseInt(slider.value);
            
            // 1. Mise à jour visuelle du slider (effet remplissage)
            const percentage = ((pcCount - slider.min) / (slider.max - slider.min)) * 100;
            slider.style.backgroundSize = `${percentage}% 100%`;
            pcDisplay.textContent = `${pcCount} PC`;

            // 2. Calcul CO2
            let co2Saved = pcCount * CO2_PER_PC;
            if(currentYears === 5) co2Saved *= 1.2; // Bonus durabilité
            
            // Arrondir
            const finalValue = co2Saved.toFixed(1);

            // 3. Animation du chiffre (Rolling Numbers)
            animateValue(co2Number, parseFloat(co2Number.textContent), finalValue, 500);

            // 4. Animation du cercle
            // On définit un max arbitraire pour la jauge (ex: 150 Tonnes = 100%)
            const maxScale = 150; 
            const offset = circumference - ((co2Saved / maxScale) * circumference);
            // Empêcher l'offset d'être négatif
            circleRing.style.strokeDashoffset = offset > 0 ? offset : 0;

            // 5. Mise à jour de l'équivalence
            updateEquivalence(finalValue);
        }

        function updateEquivalence(co2Val) {
            // Logique simple pour changer le texte selon le score
            const flight = 1.0; // 1 Tonne = ~1 vol Paris NY
            const car = 0.2; // 1 Tonne = ~5000km, 0.2T = 1000km
            
            let html = "";
            let icon = "";

            if (co2Val < 2) {
                const km = Math.round((co2Val * 5000));
                icon = "🚗";
                html = `Équivalent à <span class="highlight">${km} km</span> en voiture thermique évités.`;
            } else if (co2Val < 20) {
                const flights = (co2Val / flight).toFixed(1);
                icon = "✈️";
                html = `Équivalent à <span class="highlight">${flights}</span> allers-retours Paris-New York.`;
            } else {
                const trees = Math.round(co2Val * 50); // 1T = 50 arbres plantés/an (approx)
                icon = "🌳";
                html = `C'est comme si vous aviez planté <span class="highlight">${trees}</span> arbres cette année.`;
            }

            document.querySelector('.icon-eq').textContent = icon;
            eqText.innerHTML = html;
        }

        // Fonction utilitaire pour animer les chiffres
        function animateValue(obj, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                // Easing simple
                obj.innerHTML = (progress * (end - start) + start).toFixed(1);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Event Listeners
        slider.addEventListener('input', updateCalculator);

        // Init
        updateCalculator();

    </script>
</body>
</html>