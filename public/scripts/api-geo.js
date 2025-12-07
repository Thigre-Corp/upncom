/***************** API GEO ******************************/
/*
    Récupération des villes en fonction du code postal
    mise en oeuvre de l'API GEO du gouvernement français
    https://geo.api.gouv.fr/decouvrir
*/


/***************** constantes ******************************/
const apiURL = 'https://geo.api.gouv.fr/communes?codePostal=';
const format = '&format=json';

const cp = document.getElementById("contact_cp");
const select = document.getElementById("contact_ville");
const container = document.querySelector(".ville");

/***************** Event Listener ******************************/
cp.addEventListener('input', (e) => {
    let inputCP = e.target.value;
    if (inputCP.length > 4 ) {
        let url = apiURL + inputCP + format;
        var length = select.options.length;
        /* purger la liste des options*/
        for (i = length-1; i >= 0;i--) {
        select.remove(i);
        }
        callAPI(url);
        container.classList.remove('inactive');
    }
});

/***************** CallBack ******************************/
function callAPI(url) {
    return fetch(url)
        .then(response => response.json())
        .then(data => {
            let ville = [];
            for (let i = 0; i < data.length; i++) {
                console.log('looping');
                var option = document.createElement('option');
                option.text = data[i].nom;
                ville.push(data[i].nom);
                select.add(option);
            }
            /*sendToBack(ville);*/
        })
        .catch(() => { console.error("fetch error !") })
}