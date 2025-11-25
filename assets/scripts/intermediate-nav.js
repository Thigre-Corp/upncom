// https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API


const sections = document.querySelectorAll('section');
const indexes = document.querySelectorAll('.index_navBar');
let firstTime = true;

const options = {
    root: null,
    rootMargin: "100px",
    scrollMargin: "0px",
    threshold: 0.5,
};

const intersectionCallback = (entries) => {

    entries.forEach((entry) => {

        if (entry.isIntersecting) {
            let elem = '#' + entry.target.id + '_navBar';
            if (entry.intersectionRatio >= 0.5) {
                if (!firstTime) {
                    indexes.forEach((element) => {
                        element.classList.remove("select");
                    });
                    document.querySelector(elem).classList.add("select");
                }
                firstTime = false;
            }
        }
    });
};

const observer = new IntersectionObserver(intersectionCallback, options);

sections.forEach((element) => {
    observer.observe(element);
});