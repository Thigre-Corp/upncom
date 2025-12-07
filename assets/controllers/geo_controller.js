import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["cp", "select", "container"];
  static values = {
    apiUrl: String,
    format: String
  };

  connect() {
    this.apiUrlValue = 'https://geo.api.gouv.fr/communes?codePostal=';
    this.formatValue = '&format=json';
    
    // Add event listener using Stimulus
    this.cpTarget.addEventListener('input', (e) => {
      this.handleInput(e);
    });
  }

  handleInput(event) {
    const inputCP = event.target.value;
    
    if (inputCP.length > 4) {
      const url = this.apiUrlValue + inputCP + this.formatValue;
      this.callAPI(url);
      this.containerTarget.classList.remove('inactive');
    } else {
      // Clear options when input is too short
      this.clearOptions();
      this.containerTarget.classList.add('inactive');
    }
  }

  callAPI(url) {
    fetch(url)
      .then(response => response.json())
      .then(data => {
        this.clearOptions();
        const ville = [];
        
        for (let i = 0; i < data.length; i++) {
          console.log('looping');
          const option = document.createElement('option');
          option.text = data[i].nom;
          option.value = data[i].nom; // Set the value to the city name
          ville.push(data[i].nom);
          this.selectTarget.add(option);
        }
      })
      .catch(() => {
        console.error("fetch error !");
      });
  }

  clearOptions() {
    // Clear existing options
    while (this.selectTarget.options.length > 0) {
      this.selectTarget.remove(0);
    }
  }
}