/*à faire en controller stimulus
    
    // 1. Définition des constantes
    const parallaxElements = document.querySelectorAll('.parallax-image');
    // Le taux de défilement : 0.2 signifie que l'image bouge 5 fois plus lentement que la page.
    const scrollRate = 0.2; 

    // 2. Fonction principale de calcul
    function applyParallax() {
        // Optimisation : Utiliser requestAnimationFrame pour un défilement plus fluide
        window.requestAnimationFrame(() => {
            
            // Pour chaque élément ayant la classe .parallax-image
            parallaxElements.forEach(el => {
                
                // Récupère la position de l'élément dans le viewport
                const rect = el.getBoundingClientRect();
                
                // Calcule la distance de l'élément par rapport au haut du viewport
                // Si l'élément est au centre du viewport, value sera proche de 0
                // (hauteur_fenêtre / 2) - (position_haut + hauteur_élément / 2)
                const distanceCenter = (window.innerHeight / 2) - (rect.top + rect.height / 2);
                
                // Applique le taux de défilement pour un mouvement subtil
                const yPos = distanceCenter * scrollRate;
                
                // Applique le style pour ajuster la position verticale de l'arrière-plan
                el.style.backgroundPositionY = `calc(50% + ${yPos}px)`;
            });
        });
    }

    // 3. Écouteurs d'événements
    // Déclencher le calcul au chargement de la page et à chaque défilement
    window.addEventListener('scroll', applyParallax);
    window.addEventListener('resize', applyParallax); // Pour ajuster en cas de redimensionnement
    applyParallax(); // Exécuter une fois au chargement initial
    // 
    
    */