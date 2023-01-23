const FiltersForm = document.querySelector("#filters")

//boucle sur les inputs
document.querySelectorAll("#filters input").forEach(input => {
    input.addEventListener("change", () => {
        // on intercepte les clics
        // on récupère les datas
        const Form = new FormData(FiltersForm)
        Form.forEach((value, key) => {
            console.log(key, value);
        })
    })

})