const form_ajax=document.querySelectorAll(".FormAjax");

form_ajax.forEach(form => {
    form.addEventListener("submit", function(e){
        e.preventDefault();

        Swal.fire({
            title: 'Estas Seguro?',
            text: "Quieres enviar el formulario?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Enviar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                
                let data = new FormData(this);
                let method = this.getAttribute("method");
                let action = this.getAttribute("action");

                let headers = new Headers();

                let config={
                    method:method,
                    headers:headers,
                    mode:'cors',
                    cache:'no-cache',
                    body: data
                };

                fetch(action,config)
                .then(response => response.json())
                .then(response =>{
                    return alerts_ajax(response);
                });
                
            }
        });
    });
});


function alerts_ajax(alert){

    if(alert.type == "single"){

        Swal.fire({
            icon: alert.icon,
            title: alert.title,
            text: alert.text,
            confirmButtonText: 'Aceptar'
        });

    }else if (alert.type == "reload"){

        Swal.fire({
            icon: alert.icon,
            title: alert.title,
            text: alert.text,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                location.reload();
            }
        });

    }else if (alert.type == "cleanup"){
        Swal.fire({
            icon: alert.icon,
            title: alert.title,
            text: alert.text,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                document.querySelector(".FormAjax").reset();
            }
        });
    }else if (alert.type == "redirect"){
        window.location.href=alert.url;
    }
}

let btn_exit = document.getElementById("btn_exit");
btn_exit.addEventListener("click", function(e){
    e.preventDefault();

    Swal.fire({
        title: 'Estas Seguro?',
        text: "Quieres Cerrar la sesión?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Cerrar',
        cancelButtonText: 'No, Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let url = btn_exit.getAttribute("href");
            window.location.href=url;
        }
    });
});