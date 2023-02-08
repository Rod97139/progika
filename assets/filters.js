const FiltersForm = document.querySelector("#filters")

//boucle sur les inputs
document.querySelectorAll("#filters input").forEach(input => {
    input.addEventListener("change", () => {
        // on intercepte les clics
        // on récupère les datas
        const Form = new FormData(FiltersForm)

        // on fabrique l'url
        const Params = new URLSearchParams()
        
        Form.forEach((value, key) => {
            if (value != '') {
                Params.append(key, value)
            }
            
        })
        //On récupere l'url active
        const Url = new URL(window.location.href)
        
        // On lance la requete ajax
        fetch(Url.pathname + "?" + Params.toString() + "&ajax=1", {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then(response => 
            response.json()
        ).then(data => {
            // on va chercher la zone du contenu
            const content = document.querySelector("#content")

            // on remplace le contenu html
            content.innerHTML = data.content


            // on met a jour l'url
            history.pushState({}, null, Url.pathname + "?" + Params.toString())

        }).catch(e => alert(e))

    })

}) 