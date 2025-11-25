document.addEventListener('DOMContentLoaded', function () {
    const burger = document.getElementById('burger-quizz');
    const toggleMenu = document.getElementById('toggleMenu');
    const mask = document.getElementById('burgerMask');
    mask.innerHTML = toggleMenu.innerHTML;
    mask.ariaHidden = 'true';
    mask.classList.add('toggleMenu');

    burger.addEventListener('change', function () {
        if (this.checked) {
            mask.style.transform = 'translateX(0)';
        } else {
            mask.style.transform = 'translateX(101vw)';
        }
    });
});

