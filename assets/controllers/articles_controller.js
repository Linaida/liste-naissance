import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['links']

    initialize() {
        // Called once when the controller is first instantiated (per element)

        // Here you can initialize variables, create scoped callables for event
        // listeners, instantiate external libraries, etc.
        // this._fooBar = this.fooBar.bind(this)
    }

    connect() {
        this.index = this.linksTarget.children.length;
    }

    addLink(event) {
        event.preventDefault();
        
        const prototype = this.linksTarget.dataset.prototype;
        const newForm = prototype.replace(/__name__/g, this.index);
        const div = document.createElement('div');
        div.classList.add('link-item', 'p-4', 'bg-white/70', 'rounded-2xl', 'shadow-inner', 'space-y-4');
        div.innerHTML = newForm;
        this.linksTarget.appendChild(div);
        this.index++;
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
