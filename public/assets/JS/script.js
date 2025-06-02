

        window.onload = function() {
            const ctx = document.getElementById('PlatsChart').getContext('2d');

            const PlatsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels, // Use the passed labels
                    datasets: [{
                        label: 'Sold Count',
                        data: data, // Use the passed data
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(50, 205, 50, 0.2)',
                            'rgba(255, 69, 0, 0.2)',
                            'rgba(138, 43, 226, 0.2)',
                            'rgba(255, 20, 147, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(50, 205, 50, 1)',
                            'rgba(255, 69, 0, 1)',
                            'rgba(138, 43, 226, 1)',
                            'rgba(255, 20, 147, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        };
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.querySelector("#toggle-button");
    if (toggleButton) {
        toggleButton.addEventListener('click', function(){
            document.querySelector("#sidebar").classList.toggle("expand");
        });
    }

    // admin user add clients button functionality
    const addClientButton = document.getElementById("addClientBtn");
    if (addClientButton) {
        addClientButton.addEventListener("click", function () {
            var form = document.getElementById("clientForm");
            if (form) {
                form.style.display = form.style.display === "none" ? "block" : "none";
            }
        });
    }

    // clients profile update link
    const editClientProfileButton = document.getElementById("editclientProfile");
    if (editClientProfileButton) {
        editClientProfileButton.addEventListener("click", function (event) {
            event.preventDefault();
            var form = document.getElementById("clientProfile");
            if (form) {
                form.style.display = form.style.display === "none" ? "block" : "none";
            }
        });
    }

     // clients password update link
     document.addEventListener("DOMContentLoaded", function () {
        const editClientpasswordButton = document.getElementById("clientchangePassword");
        if (editClientpasswordButton) {
            editClientpasswordButton.addEventListener("click", function (event) {
                event.preventDefault();
                var form = document.getElementById("changePassword");
                if (form) {
                    form.style.display = form.style.display === "none" ? "block" : "none";
                }
            });
        }
    });
    


    // clients commands section
    const ClientCommmandButton = document.getElementById("clientCommands");
    if (ClientCommmandButton) {
        ClientCommmandButton.addEventListener("click", function (event) {
            event.preventDefault();
            var form = document.getElementById("commandSection");
            if (form) {
                form.style.display = form.style.display === "none" ? "block" : "none";
            }
        });
    }

    // admin add plat button 
    const addPlatsButton = document.getElementById("addplatBtn");
    if (addPlatsButton) {
        addPlatsButton.addEventListener("click", function (event) {
            event.preventDefault();
            var form = document.getElementById("addPlatForm");
            if (form) {
                form.style.display = form.style.display === "none" ? "block" : "none";
            }
        });
    }
    
     // admin add Categorie button 
     const addcategorieButton = document.getElementById("addcategorieBtn");
     if (addcategorieButton) {
         addcategorieButton.addEventListener("click", function (event) {
             event.preventDefault();
             var form = document.getElementById("addcategorieForm");
             if (form) {
                 form.style.display = form.style.display === "none" ? "block" : "none";
             }
         });
     }

    //  add boissons button
    document.addEventListener("DOMContentLoaded", function () {
        var addBoissonsBtn = document.getElementById("addBoissonsBtn");
        var form = document.getElementById("addBoissonsForm");
        
        if (addBoissonsBtn && form) {
            addBoissonsBtn.addEventListener("click", function (event) {
                event.preventDefault();
                form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
            });
        }
    });
    
    

    const clientForm = document.getElementById('clientForm');
    if (clientForm) {
        clientForm.addEventListener('submit', function (event) {
            event.preventDefault();
            validateForm(this);
        });
    }

    const updateForm = document.getElementById('UpdateForm');
    if (updateForm) {
        updateForm.addEventListener('submit', function (event) {
            event.preventDefault();
            validateForm(this);
        });
    }

    function validateForm(form) {
        const nom = form.querySelector('#nom').value.trim();
        const prenom = form.querySelector('#prenom').value.trim();
        const addresse = form.querySelector('#addresse').value.trim();
        const codePostal = form.querySelector('#codePostal').value.trim();
        const ville = form.querySelector('#ville').value.trim();
        const tel = form.querySelector('#tel').value.trim();
        const mail = form.querySelector('#mail').value.trim();

        if (!nom || !prenom || !addresse || !codePostal || !ville || !tel || !mail) {
            alert('Tous les champs sont obligatoires');
            return;
        }

        const allValidations = [
            validateText(nom),
            validateText(prenom),
            validateText(addresse),
            validateText(ville),
            validateCodePostal(codePostal),
            validateTel(tel),
            validateMail(mail)
        ];
        
        if (!allValidations.every(Boolean)) {
            alert('Veuillez vérifier vos informations.');
            return;
        }

        form.submit();
    }

    function validateText(text) {
        const re = /^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžæÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/;
        return re.test(text);
    }

    function validateCodePostal(codePostal) {
        const re = /^[1-9][0-9]{4}$/;
        return re.test(codePostal);
    }

    function validateTel(tel) {
        const re = /^0[1-9]([-. ]?[0-9]{2}){4}$/;
        return re.test(tel);
    }

    function validateMail(mail) {
        const re = /^[a-z0-9.-]+@[a-z0-9.-]{2,}\.[a-z]{2,4}$/i;
        return re.test(mail);
    }
});
