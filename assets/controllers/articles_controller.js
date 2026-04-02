import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['links', 'articleName']

    initialize() {
        // Called once when the controller is first instantiated (per element)

        // Here you can initialize variables, create scoped callables for event
        // listeners, instantiate external libraries, etc.
        // this._fooBar = this.fooBar.bind(this)
    }

    connect() {
        this.index = this.linksTarget.children.length;
    }

    addLink() {

    const prototype = this.linksTarget.dataset.prototype
    const index = this.linksTarget.children.length

    const html = prototype.replace(/__name__/g, index)

    this.linksTarget.insertAdjacentHTML("beforeend", html)

}

    generateSearchLinks() {
        const articleName = this.articleNameTarget.value.trim();

        if (!articleName) {
            alert('Veuillez d\'abord renseigner le nom de l\'article');
            return;
        }

        // Définition des plateformes avec leurs URLs de recherche
        const stores = [
            {
                name: 'Amazon',
                url: `https://www.amazon.fr/s?k=${encodeURIComponent(articleName)}`
            },
            {
                name: 'Aubert',
                url: `https://www.aubert.com/recherche?sfterm=${encodeURIComponent(articleName)}`
            },
            {
                name: 'Vertbaudet',
                url: `https://www.vertbaudet.fr/search=${encodeURIComponent(articleName)}.htm?`
            },
            {
                name: 'IKEA',
                url: `https://www.ikea.com/fr/fr/search?q=${encodeURIComponent(articleName)}`
            },
            {
                name: 'Cdiscount',
                url: `https://www.cdiscount.com/search/10/${encodeURIComponent(articleName)}.html`
            },
            {
                name: 'Bébé9',
                url: `https://www.bebe9.com/catalogsearch/result/?q=${encodeURIComponent(articleName)}`
            }
        ];

        // Ajouter les liens pour chaque plateforme
        stores.forEach(store => {
            console.log(`Adding link for ${store.name}: ${store.url}`);
            this.addLinkWithData(store.url, store.name);
        });
    }

    addLinkWithData(url, label) {
        const prototype = this.linksTarget.dataset.prototype;
        const index = this.linksTarget.children.length;

        const html = prototype.replace(/__name__/g, index);

        // Insérer le HTML dans le DOM
        this.linksTarget.insertAdjacentHTML("beforeend", html);

        // Récupérer les champs du dernier lien ajouté
        const newLink = this.linksTarget.lastElementChild;
        const urlInput = newLink.querySelector('[data-store-detector-target="url"]');
        const labelInput = newLink.querySelector('[data-store-detector-target="label"]');

        if (urlInput) {
            urlInput.value = url;
        }
        if (labelInput) {
            labelInput.value = label;
        }
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
    }
}
