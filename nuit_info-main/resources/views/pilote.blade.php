<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIRD - Grille d'Analyse des Pilotes</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=Manrope:wght@300;400;600&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --nird-green: #00C853;
            --nird-violet: #8e44ad;
            --bg-deep: #050505;
            --card-bg: #111;
            --text-white: #ffffff;
            --grid-line: rgba(0, 200, 83, 0.1);
            --card-radius: 6px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .body {
            /* Manrope pour le corps général */
            background-color: var(--bg-deep);
            font-family: 'Share Tech Mono', sans-serif;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            background-image: linear-gradient(rgba(0, 0, 0, 0.7),
                    /* Le dernier chiffre (0.4) est l'opacité */
                    rgba(0, 0, 0, 0.7)), url('pilote.png');
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* --- FOND ET TITRE --- */
        .bg-layer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1543286386-77889ff0183b?auto=format&fit=crop&q=80&w=2070&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            filter: brightness(0.2) contrast(1.5) saturate(1.2);
            z-index: -2;
        }

        h1,
        .instruction {
            /* Space Grotesk pour les titres et accents Tech */
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.5rem;
            color: white;
            margin-bottom: 2vh;
            text-shadow: 0 0 10px rgba(0, 200, 83, 0.3);
            z-index: 10;
        }

        .subtitle {
            color: var(--nird-green);
            font-size: 1.5rem;
            margin-bottom: 2vh;
            text-align: center;
            z-index: 10;
        }

        .instruction {
            color: var(--nird-violet);
            font-size: 1.2rem;
            margin-bottom: 3vh;
            padding: 5px 15px;
            border: 1px dashed var(--nird-violet);
            border-radius: 4px;
        }


        /* --- CONTENEUR PRINCIPAL (Grille) --- */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: auto 1fr;
            width: 90vw;
            max-width: 100%;
            gap: 20px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--grid-line);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        /* La zone de détail prend les 3 colonnes */
        .detail-console {
            grid-column: 1 / span 3;
            background: rgba(10, 10, 10, 0.9);
            min-height: 250px;
            border-top: 2px solid var(--nird-violet);
            padding: 20px 30px;
            overflow-y: auto;
            opacity: 0;
            transition: all 0.5s ease-in-out;
            transform: translateY(20px);
            pointer-events: none;
        }

        /* --- CARTE PRINCIPALE --- */
        .card-pilot {
            background: var(--card-bg);
            border: 1px solid var(--grid-line);
            border-radius: var(--card-radius);
            padding: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        /* Effet de survol : Bordure neon + Ombre */
        .card-pilot:hover {
            border-color: var(--nird-green);
            box-shadow: 0 0 15px var(--nird-green);
            transform: translateY(-5px);
        }

        /* Contenu de base */
        .card-pilot h3 {
            font-family: 'Space Grotesk';
            font-size: 1.2rem;
            color: var(--nird-violet);
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .card-pilot p {
            color: var(--text-white);
            font-size: 2rem;
            font-weight: 700;
        }

        .card-pilot span {
            display: block;
            color: var(--nird-green);
            font-size: 1rem;
            margin-top: 5px;
        }

        /* Contenu spécifique à la console de détails */
        .console-header {
            color: var(--nird-green);
            font-family: 'Space Grotesk';
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .console-list {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .console-list li {
            background: rgba(0, 200, 83, 0.08);
            border-left: 3px solid var(--nird-green);
            padding: 8px 12px;
            color: #ddd;
            font-size: 0.9rem;
            border-radius: 4px;
        }

        .console-cta-box {
            border-top: 1px dashed rgba(255, 255, 255, 0.2);
            padding-top: 15px;
        }

        .console-cta-box a {
            color: var(--nird-violet);
            text-decoration: none;
            font-weight: 600;
            margin-right: 20px;
            transition: color 0.3s;
        }

        .console-cta-box a:hover {
            color: var(--nird-green);
        }

        /* Classes spécifiques pour l'activation JS */
        .detail-console.active {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Cacher les détails initialement */
        .detail-content {
            display: none;
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
    <div class="absolute right-10 m-5 top-10">
        <nav>
            <div class="nav-right flex flex-col gap-3">
                <a href="/index-2" class="cta-header">Acceuil</a>
                <a href="/demarches" class="cta-header">Démarches</a>
                <a href="/simulateur" class="cta-header">Simulateur</a>
                <a href="/faire-un-don" class="cta-header">Faire un don</a>
            </div>
        </nav>
    </div>
    <div class="body">
        <div class="bg-layer"></div>
        <h1>Tableau de Bord des Projets Pilotes</h1>
        <p class="subtitle">Visualisez l'engagement actuel et les projets en cours (Expérimentation 2025/2026).</p>

        <div class="grid-container">

            <div class="card-pilot" data-target="lycees" onmouseenter="showDetails('lycees')"
                onmouseleave="hideDetails()">
                <h3>Lycées</h3>
                <p>15+</p>
                <span class="">Établissements Moteurs</span>
            </div>

            <div class="card-pilot" data-target="colleges" onmouseenter="showDetails('colleges')"
                onmouseleave="hideDetails()">
                <h3>Collèges</h3>
                <p>10</p>
                <span>Clubs de Reconditionnement</span>
            </div>

            <div class="card-pilot" data-target="primaire" onmouseenter="showDetails('primaire')"
                onmouseleave="hideDetails()">
                <h3>Primaire</h3>
                <p>3+</p>
                <span>Focus PrimTux & Pédagogie</span>
            </div>

            <div class="detail-console" id="detail-console">
                <h2 class="console-header" id="console-title">Sélectionnez une catégorie pour afficher les pilotes...
                </h2>

                <div class="detail-content" id="lycees-details">
                    <p><strong>Domaine d'Action :</strong> Reconditionnement, Clubs Info, Linux.</p>
                    <ul class="console-list">
                        <li>Lycée Alain Borne</li>
                        <li>Lycée Carnot</li>
                        <li>Lycée de la Plaine de l’Ain</li>
                        <li>Lycée des métiers Heinrich-Nessel</li>
                        <li>Lycée Jacques Prevert</li>
                        <li>Lycée Jean Monnet</li>
                        <li>Lycée La Martinière Diderot</li>
                        <li>Lycée Marie Curie</li>
                        <li>Lycée professionnel Jean Lurçat</li>
                        <li>Lycée Simone de Beauvoir</li>
                        <li>Lycée Vincent d’Indy</li>
                        <li style="border-left-color: var(--nird-violet);">* 4 Lycées en phase de pré-inscription</li>
                    </ul>
                    <div class="console-cta-box">
                        <a href="#formulaire-inscription">**Inscrire mon Lycée**</a>
                        <a href="#">Rejoindre le forum Tchap</a>
                    </div>
                </div>

                <div class="detail-content" id="colleges-details">
                    <p><strong>Domaine d'Action :</strong> Création de clubs informatiques et expérimentation Linux.</p>
                    <ul class="console-list">
                        <li>Cité scolaire Bellevue</li>
                        <li>Collège Coat Mez</li>
                        <li>Collège des 7 vallées</li>
                        <li>Collège Les Cuvelles</li>
                        <li>Collège Uporu</li>
                        <li>Collège Victor Vasarely</li>
                        <li style="border-left-color: var(--nird-violet);">* 4 Collèges en cours d'intégration</li>
                    </ul>
                    <div class="console-cta-box">
                        <a href="#formulaire-inscription">**Inscrire mon Collège**</a>
                        <a href="#">Consulter le Compte Mastodon</a>
                    </div>
                </div>

                <div class="detail-content" id="primaire-details">
                    <p><strong>Domaine d'Action :</strong> Pilotage PrimTux et actions pédagogiques en reconditionnement
                        (Cycles 2/3).</p>
                    <ul class="console-list">
                        <li>École élémentaire Louis Barrié</li>
                        <li style="border-left-color: var(--nird-violet);">* 2 Écoles élémentaires en préparation</li>
                    </ul>
                    <div class="console-cta-box">
                        <a href="#formulaire-inscription">**Inscrire mon École**</a>
                        <a href="#">Accéder au code source (GitLab)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const consoleEl = document.getElementById('detail-console');
        const consoleTitle = document.getElementById('console-title');
        let activeTimeout = null;

        function showDetails(category) {
            // Effacer le timeout précédent pour éviter la désactivation prématurée
            clearTimeout(activeTimeout);

            // Masquer tous les contenus détaillés
            document.querySelectorAll('.detail-content').forEach(el => {
                el.style.display = 'none';
            });

            // Afficher la console
            consoleEl.classList.add('active');

            // Afficher le contenu spécifique
            const targetDetails = document.getElementById(category + '-details');
            if (targetDetails) {
                const titleText = targetDetails.closest('.grid-container').querySelector(`.card-pilot[data-target="${category}"] h3`).textContent;
                consoleTitle.textContent = `Projets en cours : ${titleText}`;
                targetDetails.style.display = 'block';
            }
        }

        function hideDetails() {
            // Lancer un timeout avant de cacher la console
            activeTimeout = setTimeout(() => {
                consoleEl.classList.remove('active');
                consoleTitle.textContent = 'Sélectionnez une catégorie pour afficher les pilotes...';
                document.querySelectorAll('.detail-content').forEach(el => {
                    el.style.display = 'none';
                });
            }, 300);
        }

        // Pour maintenir la console active même quand on passe la souris dessus
        consoleEl.onmouseenter = () => clearTimeout(activeTimeout);
        consoleEl.onmouseleave = hideDetails;

        // Ajout d'une logique pour les mobiles (touch) pour simuler le hover
        document.querySelectorAll('.card-pilot').forEach(card => {
            card.addEventListener('click', function (e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');
                // Si la console est déjà active et affiche déjà ce contenu, la masquer
                if (consoleEl.classList.contains('active') && document.getElementById(target + '-details').style.display === 'block') {
                    hideDetails();
                } else {
                    showDetails(target);
                }
            });
        });

    </script>
</body>

</html>