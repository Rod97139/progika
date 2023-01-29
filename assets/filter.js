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
            Params.append(key, value)
        })
        //On récupere l'url active
        const Url = new URL(window.location.href)
        
        // On lance la requete ajax
        fetch(Url.href + "?" + Params.toString() + "&ajax=1", {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then(response => 
            response.json()
        ).then(data => {
            const content = document.querySelector("#content")
            content.innerHTML = data.content
        }).catch(e => alert(e))

    })

})