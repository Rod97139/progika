             
    let region = document.querySelector("#lodging_region")
    region.addEventListener("change", function(){
            let form = this.closest("form")
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
            let newSelect = content.querySelector("#lodging_departement") 
            

            document.querySelector("#lodging_departement").replaceWith(newSelect)

            


                let departement = document.querySelector("#lodging_departement")
                    if (typeof departement !== 'undefined' && departement !== null ) {
                        
                    
                    departement.addEventListener("change", function(){
                        
                            data += '&' + this.name + "=" + this.value
                        fetch(form.action, {
                            method: form.getAttribute("method"),
                            body: data,
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            content = document.createElement("html")
                            content.innerHTML = html
                            let nouveauSelect = content.querySelector("#lodging_city")
                            document.querySelector("#lodging_city").replaceWith(nouveauSelect)
                        })
    
                    })
                }
                
        
        })
    })  
