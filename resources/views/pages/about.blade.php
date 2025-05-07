@extends('layouts.app')

@section('title', 'À propos | MemesFlow')
@section('content')
    <style>
        /* From Uiverse.io by Shakil-Babu */
        .btn {
            background-color: #00BFA6;
            padding: 14px 40px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            border-radius: 10px;
            border: 2px dashed #00BFA6;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
            transition: .4s;
        }

        .btn span:last-child {
            display: none;
        }

        .btn:hover {
            transition: .4s;
            border: 2px dashed #00BFA6;
            background-color: #fff;
            color: #00BFA6;
        }

        .btn:active {
            background-color: #87dbd0;
        }
    </style>
    <div class="container py-5">
        <h1>À propos de MemesFlow</h1>
        <p>
            MemesFlow est une plateforme en ligne intuitive et moderne dédiée à la création et au partage de mèmes.
            <br><br>

            Dans le cadre du mini-projet d’admission à SUPINFO, j’ai développé MemesFlow, une plateforme web intuitive de
            génération de mèmes. Ce projet reflète ma passion pour le développement web moderne, en mettant l’accent sur
            l’interactivité, l’accessibilité et l’originalité. <br>

            Objectif :
            Permettre à tout utilisateur, sans compétence technique préalable, de créer et partager des mèmes personnalisés
            en quelques clics, grâce à une interface simple et responsive. <br><br>

            Fonctionnalités développées :
        <ul>
            <li>Téléversement d’image depuis l’ordinateur</li>
            <li>Ajout de texte dynamique</li>
            <li>Glisser-déposer pour positionner librement chaque texte</li>
            <li>Aperçu en temps réel du rendu final</li>
            <li>Téléchargement immédiat en PNG</li>
            <li>Partage direct sur les réseaux sociaux</li>
            <li>Galerie disponible pour tous</li>
        </ul>

        Approche technique :
        Le projet utilise HTML5, CSS, JavaScript (Canvas API) pour le front-end, et PHP avec Laravel pour la gestion
        serveur. L’accent a été mis sur la fluidité de l’expérience utilisateur, la sécurité des données et la modularité du
        code. <br><br>

        Pourquoi SUPINFO ?
        Intégrer SUPINFO me permettrait d’approfondir mes compétences en développement full-stack, d’explorer l’IA
        (génération de texte automatique) et le cloud, afin de faire évoluer ce projet vers une plateforme collaborative
        intelligente.<br><br>

        Prêt à créer votre premier mème ? <br>
        <a href=" {{ route('memes') }} ">
            <span class="brand-text font-weight-light">➡️ Commencer maintenant</span>
        </a><br><br>
        </p>
    </div>
@endsection
