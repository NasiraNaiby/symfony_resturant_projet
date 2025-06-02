// if (!sessionStorage.getItem('preloaderShown')) { 
//     const spinnerElement = document.querySelector('.spinner-container'); 
//     setTimeout(()=>{ spinnerElement.style.display = 'none'; 
//         sessionStorage.setItem('preloaderShown', 'true'); 
//     }, 3000); } 
   // else { // Hide the preloader immediately if it has been shown before 
        //document.querySelector('.spinner-container').style.display = 'none'; 
    //}

$('.carousel-one').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    navText: ['<span class="owl-prev">‹</span>', '<span class="owl-next">›</span>'],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:5
        }
    }
});
$('.carousel-two').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    navText: ['<span class="owl-prev">‹</span>', '<span class="owl-next">›</span>'],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:6
        }
    }
});
$('.carousel-three').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    navText: ['<span class="owl-prev">‹</span>', '<span class="owl-next">›</span>'],
    responsive:{
        0:{
            items:1
        },
        810:{
            items:2
        },
        1000:{
            items:6
        }
    }
});

// let cartCount = 0;
// document.querySelectorAll('.btn-add-to-cart').forEach(button => {
//     button.addEventListener('click', function() {
//         cartCount++;
//         let cartCountElement = document.getElementById('cart-count');
//         cartCountElement.textContent = cartCount;
//         cartCountElement.classList.add('bounce');
//         // Remove the animation
//         cartCountElement.addEventListener('animationend', () => {
//             cartCountElement.classList.remove('bounce');
//         });
//     });
// });

let cartCount = 0;
document.querySelectorAll('.btn-add-to-cart').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior

        let platId = event.target.getAttribute('href').split('/').pop(); // Get plat id from the URL
        fetch(`/add/${platId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            cartCount = data.cartCount; // Update cart count from the response
            let cartCountElement = document.getElementById('cart-count');
            cartCountElement.textContent = cartCount; // Update the cart count in the UI
            cartCountElement.classList.add('bounce');

            // Remove the bounce animation after it finishes
            cartCountElement.addEventListener('animationend', () => {
                cartCountElement.classList.remove('bounce');
            });
        })
        .catch(error => console.error('Error:', error));
    });
});




document.getElementById('contactForm').addEventListener('submit', function(event){
    event.preventDefault();

    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('téléphone').value.trim();
    const email = document.getElementById('emailAddress').value.trim();
    const message = document.getElementById('message').value.trim();

    if(name === '' || phone === '' || email === '' || message === ''){
        alert('Tous les champs sont obligatoires');
        return;
    }
    if (!validateName(name)) {
        alert('Veuillez entrer un nom valide.');
        return;
    }
    if(!validateEmail(email)){
        alert('Veuillez entrer une adresse email valide.');
        return;
    }
    if (!validatePhone(phone)) {
        alert('Veuillez entrer un numéro de téléphone valide.');
        return;
    }
    if (!validateMessage(message)) {
        alert('Veuillez entrer un message valide.');
        return;
    }

    document.getElementById('contactForm').submit();
});

function validateName(name) {
    const re = /^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžæÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/;
    return re.test(name);
}

function validateEmail(email) {
    const re = /^[a-z0-9.-]+@[a-z0-9.-]{2,}\.[a-z]{2,4}$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re =  /^0[1-9]([-. ]?[0-9]{2}){4}$/;
    return re.test(phone);
}

function validateMessage(message) {
    const re = /^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžæÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/;
    return re.test(message);
}


