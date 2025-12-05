<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIRD - Parcours de Don Écologique</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=Manrope:wght@300;400;600&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')

    <style>
        :root {
            --nird-green: #00C853;
            --nird-violet: #8e44ad;
            --bg-deep: #050505;
            --card-bg: #111;
            --text-white: #ffffff;
            --step-inactive: #2a2a2a;
            --step-active: var(--nird-green);
            --step-progress: 0%;
            --text-muted: #a0a0a0;
            /* Ajout pour le footer */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-deep);
            font-family: 'Manrope', sans-serif;
            color: var(--text-white);
            min-height: 100vh;
            /* Permet de coller le footer */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            padding-bottom: 80px;
            /* Espace pour le footer si le contenu est court */
        }

        /* --- FOND : IMAGE DE DON (avec superposition) --- */
        .bg-layer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.0.3&q=80&w=1920');
            background-size: cover;
            background-position: center;
            z-index: -2;
            filter: brightness(0.3) contrast(1.1);
        }

        .nird-footer {
            width: 100%;
            background: var(--card-bg);
            border-top: 2px solid var(--nird-violet);
            padding: 20px 40px;
            margin-top: 40px;
            color: var(--text-muted);
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-links-container {
            display: flex;
            gap: 25px;
        }

        .footer-links-container a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s;
            font-family: 'Space Grotesk', sans-serif;
        }

        .footer-links-container a:hover {
            color: var(--nird-green);
        }

        .forge-logo {
            text-align: right;
        }

        .forge-logo span {
            font-family: 'Space Grotesk', sans-serif;
            color: var(--nird-green);
            font-size: 1.1rem;
            display: block;
        }

        /* --- CONTENEUR PRINCIPAL --- */
        .donation-flow {
            width: 90%;
            max-width: 1000px;
            background: rgba(17, 17, 17, 0.95);
            border-radius: 15px;
            padding: 40px 50px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.6);
            min-height: 450px;
            position: relative;
            backdrop-filter: blur(5px);
        }

        h1 {
            font-family: 'Space Grotesk', sans-serif;
            color: var(--nird-green);
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .subtitle {
            color: var(--text-muted);
            margin-bottom: 40px;
        }

        /* --- PROGRESSION STEPPER --- */
        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 50px;
            position: relative;
        }

        .stepper::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--step-inactive);
            transform: translateY(-50%);
            z-index: 1;
        }

        .stepper::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: var(--step-progress);
            height: 3px;
            background: var(--step-active);
            box-shadow: 0 0 10px var(--step-active);
            transform: translateY(-50%);
            transition: width 0.5s ease-in-out;
            z-index: 2;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 150px;
            z-index: 3;
        }

        .step-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--card-bg);
            border: 3px solid var(--step-inactive);
            color: var(--step-inactive);
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            transition: all 0.5s;
        }

        .step.active .step-icon {
            border-color: var(--step-active);
            background: var(--step-active);
            color: var(--bg-deep);
            transform: scale(1.1);
        }

        .step-label {
            margin-top: 10px;
            font-size: 0.85rem;
            color: var(--step-inactive);
            transition: color 0.5s;
        }

        .step.active .step-label {
            color: var(--text-white);
        }

        /* --- CONTENU D'ÉTAPE --- */
        .step-content {
            padding: 20px;
        }

        .content-card {
            background: var(--bg-deep);
            border-radius: 10px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ÉTAPE 1 : CHOIX */
        .choice-buttons {
            display: flex;
            gap: 20px;
        }

        .choice-btn {
            flex: 1;
            padding: 30px 20px;
            background: var(--step-inactive);
            border: 2px solid var(--step-inactive);
            border-radius: 8px;
            color: white;
            text-align: center;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .choice-btn:hover {
            border-color: var(--nird-violet);
        }

        .choice-btn.selected {
            background: rgba(0, 200, 83, 0.1);
            border-color: var(--nird-green);
            color: var(--nird-green);
        }

        .choice-btn svg {
            width: 60px;
            height: 60px;
            fill: white;
            transition: fill 0.3s;
        }

        .choice-btn.selected svg {
            fill: var(--nird-green);
        }

        /* ÉTAPE 2 : IMPACT VISUEL */
        .impact-display {
            text-align: center;
        }

        .impact-number {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 4rem;
            color: var(--nird-green);
            text-shadow: 0 0 15px var(--nird-green);
            line-height: 1;
        }

        .impact-number-unit {
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .impact-equivalence {
            background: rgba(142, 68, 173, 0.15);
            padding: 15px;
            border-radius: 8px;
            color: #ddd;
            font-style: italic;
        }

        /* ÉTAPE 3 : FORMULAIRE */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 5px;
            color: var(--text-white);
            font-family: 'Manrope', sans-serif;
            margin-bottom: 15px;
        }

        .form-full {
            grid-column: 1 / span 2;
        }

        /* Contrôles de navigation */
        .navigation-controls {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .nav-btn {
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .nav-prev {
            background: var(--step-inactive);
            color: var(--text-white);
        }

        .nav-next,
        .nav-submit {
            background: var(--nird-green);
            color: var(--bg-deep);
            font-family: 'Space Grotesk', sans-serif;
        }

        .nav-next:hover,
        .nav-submit:hover {
            background: white;
        }

        /* Confirmation finale */
        .confirmation-message {
            text-align: center;
            padding: 50px;
            color: var(--nird-green);
        }

        .confirmation-message h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 3rem;
        }

        /* --- FOOTER STYLES (NOUVELLE SECTION) --- */
        .nird-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(17, 17, 17, 0.9);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 50px;
            color: var(--text-muted);
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }

        .footer-links-container {
            display: flex;
            gap: 20px;
        }

        .footer-links-container a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.85rem;
        }

        .footer-links-container a:hover {
            color: var(--nird-green);
        }

        .forge-info {
            text-align: right;
            line-height: 1.2;
        }

        .forge-info span {
            font-family: 'Space Grotesk', sans-serif;
            color: var(--nird-green);
            font-size: 0.9rem;
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .donation-flow {
                padding: 30px;
            }

            .stepper {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .choice-buttons,
            .form-grid {
                flex-direction: column;
            }

            .nird-footer {
                flex-direction: column;
                padding: 10px;
                gap: 10px;
            }

            .footer-links-container {
                gap: 10px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .forge-info {
                text-align: center;
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
        }
    </style>
</head>

<body>
    <div class="bg-layer"></div>
    <div class="absolute right-10 m-5 top-10 z-20 flex items-center gap-6">
        <nav>
            <div class="nav-right flex flex-col gap-3">
                <a href="/demarches" class="cta-header">Démarches</a>
                <a href="/simulateur" class="cta-header">Simulateur</a>
                <a href="/pilote" class="cta-header">Pilote</a>
            </div>
        </nav>
    </div>


    <div class="donation-flow">
        <h1>Initiative NIRD : Faites un Don Écologique</h1>
        <p class="subtitle">Transformez votre ancien matériel en ressource éducative.</p>

        <div class="stepper">
            <div class="step active" id="step1">
                <div class="step-icon">1</div>
                <div class="step-label">Le Don</div>
            </div>
            <div class="step" id="step2">
                <div class="step-icon">2</div>
                <div class="step-label">L'Impact</div>
            </div>
            <div class="step" id="step3">
                <div class="step-icon">3</div>
                <div class="step-label">Le Contact</div>
            </div>
            <div class="step" id="step4">
                <div class="step-icon">✓</div>
                <div class="step-label">Validation</div>
            </div>
        </div>

        <div class="step-content">

            <div id="content1" class="content-card">
                <h2>Que souhaitez-vous donner ?</h2>
                <div class="choice-buttons">
                    <button class="choice-btn" data-type="pc" onclick="selectDonation('pc', 0.25)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M20 18H4V6H20V18ZM20 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H20C21.1046 20 22 19.1046 22 18V6C22 4.89543 21.1046 4 20 4ZM6 15H18V16H6V15ZM6 8H18V13H6V8Z">
                            </path>
                        </svg>
                        PC Complet
                    </button>
                    <button class="choice-btn" data-type="components" onclick="selectDonation('components', 0.05)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM11 10V6H13V10H17V12H13V16H11V12H7V10H11Z">
                            </path>
                        </svg>
                        Composants & Périphériques
                    </button>
                </div>
            </div>

            <div id="content2" style="display: none;" class="content-card impact-display">
                <h2>Votre don, c'est de l'impact !</h2>
                <span class="impact-number" id="impact-val">0.0</span>
                <p class="impact-number-unit">Tonnes CO2e Évitées (par machine reconditionnée)</p>
                <div class="impact-equivalence" id="impact-eq">
                    Ceci est l'équivalent d'un voyage Paris-Londres en voiture.
                </div>
            </div>

            <div id="content3" style="display: none;" class="content-card">
                <h2>Dernière étape : Vos coordonnées</h2>
                <p style="color: var(--nird-green); margin-bottom: 20px;">Afin d'organiser la logistique (envoi ou
                    collecte), veuillez laisser vos informations.</p>
                <div class="form-grid">
                    <input type="text" placeholder="Nom et Prénom" id="donor-name">
                    <input type="email" placeholder="Email (obligatoire)" id="donor-email">
                    <input type="tel" placeholder="Téléphone" id="donor-phone">
                    <input type="text" placeholder="Ville et Code Postal (pour la collecte)" id="donor-location">
                    <textarea class="form-full"
                        placeholder="Détails du matériel et disponibilités (Ex: 2 PC portables DELL, disponibles les mardis après-midi)"></textarea>
                </div>
            </div>

            <div id="content4" style="display: none;" class="content-card confirmation-message">
                <h2>Merci de votre engagement !</h2>
                <p>Votre don a été enregistré. Un agent NIRD vous contactera par email sous 48h pour valider la
                    logistique.</p>
                <p style="margin-top: 20px;">ENSEMBLE, BÂTISSONS UN NUMÉRIQUE DURABLE.</p>
            </div>


        </div>

        <div class="navigation-controls">
            <button class="nav-btn nav-prev" onclick="navigate(-1)" style="display: none;">Précédent</button>
            <button class="nav-btn nav-next" onclick="navigate(1)">Suivant</button>
            <button class="nav-btn nav-submit" onclick="submitDonation()" style="display: none;">Confirmer le
                Don</button>
        </div>
    </div>

    <footer class="nird-footer">
        <div class="footer-links-container">
            <a href="#">Salon Tchap</a>
            <a href="#">Compte Mastodon</a>
            <a href="#">GitLab</a>
            <a href="#">Distributions</a>
        </div>
        <div class="forge-logo">
            <small>Squelette MIT / Contenu CC BY</small>
            <span>LA FORGE</span>
            <small>des communs numériques éducatifs</small>
        </div>
    </footer>

    <script>
        let currentStep = 1;
        let donationType = null;
        let co2PerUnit = 0;
        const totalSteps = 4;

        // Fonction pour animer les chiffres
        function animateValue(obj, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.textContent = (progress * (end - start) + start).toFixed(2);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Fonction de sélection (Étape 1)
        function selectDonation(type, co2) {
            donationType = type;
            co2PerUnit = co2;

            // Mise à jour visuelle des boutons
            document.querySelectorAll('.choice-btn').forEach(btn => btn.classList.remove('selected'));
            document.querySelector(`.choice-btn[data-type="${type}"]`).classList.add('selected');

            // Passer automatiquement à l'étape suivante après un court délai
            setTimeout(() => navigate(1), 400);
        }

        // Fonction de navigation
        function navigate(direction) {
            const nextStep = currentStep + direction;

            // Validation de l'étape 1 avant de passer à l'étape 2
            if (nextStep === 2 && currentStep === 1) {
                if (donationType === null) {
                    alert("Veuillez choisir le type de matériel donné.");
                    return;
                }
            }

            // Si on tente d'aller trop loin ou trop tôt
            if (nextStep < 1 || nextStep > totalSteps) return;

            // Masquer le contenu actuel
            document.getElementById(`content${currentStep}`).style.display = 'none';

            // Mettre à jour l'étape
            currentStep = nextStep;

            // Afficher le nouveau contenu
            document.getElementById(`content${currentStep}`).style.display = 'block';

            // Mise à jour de l'affichage (Stepper et Contrôles)
            updateUI();

            // Logique spécifique à l'Étape 2
            if (currentStep === 2) {
                calculateImpact();
            }
        }

        // Fonction de soumission finale (Étape 3 -> 4)
        function submitDonation() {
            const name = document.getElementById('donor-name').value;
            const email = document.getElementById('donor-email').value;

            if (!name || !email) {
                alert("Veuillez renseigner au moins votre Nom et Email pour la validation.");
                return;
            }

            // Passer à la confirmation
            document.getElementById('content3').style.display = 'none';
            currentStep = 4;
            document.getElementById('content4').style.display = 'block';
            updateUI();
        }


        // Mise à jour de l'interface utilisateur
        function updateUI() {
            // A. Mise à jour du Stepper
            document.querySelectorAll('.step').forEach((step, index) => {
                step.classList.toggle('active', index + 1 <= currentStep);
            });

            // B. Mise à jour de la barre de progression (width)
            const progressPct = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.documentElement.style.setProperty('--step-progress', `${progressPct}%`);

            // C. Mise à jour des boutons de navigation
            document.querySelector('.nav-prev').style.display = currentStep > 1 && currentStep < 4 ? 'block' : 'none';
            document.querySelector('.nav-next').style.display = currentStep < 3 ? 'block' : 'none';
            document.querySelector('.nav-submit').style.display = currentStep === 3 ? 'block' : 'none';
        }


        // Logique de l'impact (Étape 2)
        function calculateImpact() {
            const impactValEl = document.getElementById('impact-val');
            const impactEqEl = document.getElementById('impact-eq');

            const totalUnits = 5;
            const totalCo2 = totalUnits * co2PerUnit;

            // Affichage des unités
            const unit = donationType === 'pc' ? ' Tonnes CO2e Évitées' : ' Kg CO2e Évitées';
            document.querySelector('.impact-number-unit').textContent = unit;

            // Animation du chiffre
            animateValue(impactValEl, 0.0, totalCo2, 800);

            // Mise à jour de l'équivalence
            let equivalenceText = '';
            if (donationType === 'pc') {
                equivalenceText = `Ce don est l'équivalent de l'empreinte carbone de la fabrication de ${totalUnits} PC neufs, ou de ${totalCo2 / 0.15} voyages Paris-New York.`;
            } else {
                equivalenceText = `Ce don permet de récupérer des matériaux précieux (cuivre, or, plomb) qui serviront à reconditionner au moins ${Math.floor(totalUnits / 2)} machine(s) !`;
            }
            impactEqEl.innerHTML = equivalenceText;
        }

        // Initialisation
        updateUI(); 
    </script>
</body>

</html>