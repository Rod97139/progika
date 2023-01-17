window.onload = () => {
            
    let departement = document.querySelector("#lodging_departement")

    
    departement.addEventListener("change", function(){
        console.log(departement)
        let form = this.closest("form")
        console.log(form);
        let data = this.name + "=" + this.value
        
        fetch(form.action, {
            method: form.getAttribute("method"),
            body: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
            }
        })
        .then(response => response.text())
        .then(html => {
            let content = document.createElement("html")
            content.innerHTML = html
            let nouveauSelect = content.querySelector("#lodging_city")
            console.log(nouveauSelect)

            document.querySelector("#lodging_city").replaceWith(nouveauSelect)
        })

    })
}