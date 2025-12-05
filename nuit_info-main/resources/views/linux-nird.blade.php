<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIRD - Distributions Linux</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=Manrope:wght@300;400;600&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        :root {
            --nird-green: #00C853;
            --nird-violet: #8e44ad;
            --bg-deep: #050505;
            --card-bg: #121212;
            --text-white: #ffffff;
            --text-muted: #a0a0a0;
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
            padding: 40px;
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            /* Utilisation de flexbox pour coller le footer en bas */
            flex-direction: column;
        }

        .cta-header {
            text-decoration: none;
            color: var(--tech-dark);
            background: white;
            padding: 12px 25px;
            border-radius: 30px;
            color: #121212;
            font-weight: 600;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .cta-header:hover {
            transform: scale(1.3);
            background-color: #00C853;
            color: #121212;
        }

        /* --- CONTENU PRINCIPAL (Pour occuper l'espace) --- */
        .main-content {
            flex-grow: 1;
            /* Permet au contenu de pousser le footer vers le bas */
            max-width: 1400px;
            margin: 0 auto;
        }

        /* TYPOGRAPHIE ET STRUCTURE GLOBALE */
        h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.5rem;
            color: var(--nird-green);
            text-align: center;
            margin-bottom: 10px;
        }

        .section-intro {
            text-align: center;
            color: var(--text-muted);
            max-width: 800px;
            margin: 0 auto 50px;
        }

        /* --- SECTION TWIN PILLARS (NIRD & PRIMTUX) --- */
        .distro-pillars {
            display: flex;
            gap: 40px;
            margin-bottom: 70px;
        }

        .distro-card {
            flex: 1;
            background: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            border-top: 5px solid var(--nird-violet);
            transition: transform 0.3s ease;
        }

        .distro-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        .distro-card h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            color: var(--nird-violet);
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
        }

        /* Images (Screenshots) */
        .distro-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: filter 0.5s;
        }

        .distro-image:hover {
            filter: brightness(1.1);
        }

        /* Liste des avantages (Synthèse) */
        .advantages-list {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }

        .advantages-list li {
            position: relative;
            padding-left: 30px;
            margin-bottom: 15px;
            font-size: 0.95rem;
            line-height: 1.4;
            color: #ddd;
        }

        .advantages-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--nird-green);
            font-size: 1.2rem;
            font-weight: bold;
        }

        /* Boutons CTA */
        .distro-cta a {
            display: inline-block;
            padding: 10px 20px;
            background: var(--nird-green);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-family: 'Space Grotesk', sans-serif;
            transition: all 0.3s;
            margin-right: 10px;
        }

        .distro-cta a:hover {
            background: white;
            box-shadow: 0 0 15px var(--nird-green);
        }

        .distro-cta span {
            color: var(--nird-violet);
            font-size: 0.8rem;
            display: block;
            margin-top: 10px;
        }

        /* --- SECTION ARGUMENTAIRE (WHY LINUX) --- */
        .why-linux-section {
            max-width: 1400px;
            margin: 0 auto;
        }

        .dimensions-grid {
            display: flex;
            gap: 30px;
        }

        .dimension-box {
            flex: 1;
            padding: 20px;
            border: 1px solid var(--nird-green);
            border-radius: 8px;
            background: rgba(0, 200, 83, 0.05);
            position: relative;
        }

        .dimension-box h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            color: var(--nird-green);
            margin-bottom: 10px;
        }

        .dimension-box p {
            color: #ccc;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .dimension-box::before {
            content: attr(data-icon);
            position: absolute;
            top: -20px;
            left: 10px;
            font-size: 3rem;
            opacity: 0.2;
            color: var(--nird-violet);
        }

        /* --- FOOTER STYLES --- */
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

        /* Responsiveness */
        @media (max-width: 1024px) {

            .distro-pillars,
            .dimensions-grid {
                flex-direction: column;
            }

            .distro-card,
            .dimension-box {
                margin-bottom: 20px;
            }

            .nird-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .forge-logo {
                text-align: left;
            }
        }
    </style>

</head>

<body>
    <div class="absolute right-10 m-5 top-10">
        <nav>
            <div class="nav-right flex flex-col gap-3">
                <a href="/demarches" class="cta-header">Démarches</a>
                <a href="/simulateur" class="cta-header">Simulateur</a>
                <a href="/pilote" class="cta-header">Pilote</a>
            </div>
        </nav>
    </div>

    <div class="main-content">
        <h1>Les Fondations Logicielles NIRD</h1>
        <p class="section-intro">
            Nous proposons une base logicielle robuste et libre pour l'Éducation Nationale :
            <b>PrimTux</b> pour le primaire et <b> Linux NIRD</b> pour le secondaire.
        </p>

        <div class="distro-pillars">

            <div class="distro-card">
                <h2>Linux NIRD (Secondaire)</h2>
                <img src="linuxnird.png" alt="Capture d'écran de Linux NIRD" class="distro-image">

                <ul class="advantages-list">
                    <li>Créée, testée et maintenue par des enseignants (Lycée Carnot).</li>
                    <li>Légère (Linux Mint + Xfce) : idéale pour le reconditionnement de PC anciens.</li>
                    <li>Fonctionne sans connexion Internet (mode crise ou absence temporaire).</li>
                    <li>Suite complète de logiciels libres optimisée (y compris spécialité NSI).</li>
                    <li>Bootable sur clé USB (sans toucher au système en place).</li>
                    <li>Support de premier niveau via un forum Tchap dédié.</li>
                </ul>

                <div class="distro-cta">
                    <p>Image .ISO (Version 0.1) :</p>
                    <a href="lien direct" target="_blank">Télécharger l'Image NIRD</a>
                    <span>(Clé md5 disponible sur le forum)</span>
                </div>
            </div>

            <div class="distro-card">
                <h2>PrimTux (Primaire)</h2>
                <img src="nirdenfant.png" alt="Capture d'écran de PrimTux" class="distro-image">

                <ul class="advantages-list">
                    <li>Conçue par et pour des enseignants du primaire.</li>
                    <li>Multi-profils adaptés (Cycles 1, 2, 3) et profil enseignant.</li>
                    <li>Soutenue par la DNE et l'association PrimTux active.</li>
                    <li>Permet l'équipement durable via reconditionnement.</li>
                    <li>Les collèges et lycées NIRD peuvent contribuer à son reconditionnement.</li>
                </ul>

                <div class="distro-cta">
                    <p>Ressources Officielles :</p>
                    <a href="https://nird.forge.apps.education.fr/" target="_blank">Site Officiel PrimTux</a>
                    <a href="Clip de présentation de PrimTux (vidéo, 2 min)" target="_blank">Voir la Vidéo (2 min)</a>
                    <a href="Tester PrimTux 8 en ligne (sur Distrosea)" target="_blank">Tester en Ligne</a>
                </div>
            </div>

        </div>

        <div class="why-linux-section">
            <h2
                style="font-family: 'Space Grotesk', sans-serif; text-align: center; margin-bottom: 40px; color: var(--text-white);">
                Le Choix Structurant : N.I.R.D.
            </h2>

            <div class="dimensions-grid">

                <div class="dimension-box">
                    <h3>1. Inclusif : Accessible à Tous</h3>
                    <p>Libre (sans licence), il réduit les inégalités d'accès. Interfaces adaptées (PrimTux) pour
                        accompagner le rythme de chaque élève et les besoins spécifiques.</p>
                </div>

                <div class="dimension-box">
                    <h3>2. Responsable : Maîtrise & Éthique</h3>
                    <p>Respect des données (RGPD), pas de collecte à des fins commerciales. Favorise la souveraineté
                        éducative et forme des citoyens critiques face aux outils numériques.</p>
                </div>

                <div class="dimension-box">
                    <h3>3. Durable : Anti-déchets & Économique</h3>
                    <p>Optimise le matériel ancien, lutte contre l'obsolescence et réduit les déchets électroniques. Une
                        solution économique alignée avec les objectifs de développement durable.</p>
                </div>

            </div>
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
</body>

</html>