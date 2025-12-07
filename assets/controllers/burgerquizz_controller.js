// assets/scripts/controllers/burger_quizz_controller.js
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["burger", "toggleMenu", "mask"];
    
    connect() {
        // When component connects, we need to copy the nav content
        // This approach assumes you have a way to reference the nav content
        // this.copyNavContent();
        console.log("BurgerQuizzController connected");
        console.log(this.burgerTarget);
    }
    
    toggle() {
        console.log("BurgerQuizzController toggle called" + this.burgerTarget.classList);
        if (this.burgerTarget.classList.contains('closed')) {
            this.maskTarget.style.left = "0";
            this.burgerTarget.classList.remove('closed');
            this.burgerTarget.classList.add('open');
            console.log("BurgerQuizzController opened");
        }
        else {
           // this.maskTarget.close();
            this.maskTarget.style.left = "101vw";
            this.burgerTarget.classList.remove('open');
            this.burgerTarget.classList.add('closed');
            console.log("BurgerQuizzController closed");
        }
    }
}