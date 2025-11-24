   // https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API


    const sections = document.querySelectorAll('section');
    const indexes = document.querySelectorAll('.index_navBar');

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
                //console.log("go" + elem);
                if (entry.intersectionRatio >= 0.5) {
                    indexes.forEach((element) => {
                        element.classList.remove("select");
                    });

                    document.querySelector(elem).classList.add("select");
                }
            }
        });
    };

    const observer = new IntersectionObserver(intersectionCallback, options);

    sections.forEach((element) => {
        console.log(element.id);
        observer.observe(element);
    });