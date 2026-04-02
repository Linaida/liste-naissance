import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["modal", "content", "title"]

    openReservations(event) {
        const articleId = event.currentTarget.dataset.articleId
        const articleName = event.currentTarget.dataset.articleName
        
        this.titleTarget.textContent = `📋 Réservations - ${articleName}`
        
        fetch(`/articles/${articleId}/reservations`)
            .then(response => response.text())
            .then(html => {
                this.contentTarget.innerHTML = html
                this.modalTarget.classList.remove("hidden")
                this.modalTarget.classList.add("flex")
            })
            .catch(error => {
                console.error('Erreur:', error)
                this.contentTarget.innerHTML = '<p class="text-red-600">Erreur lors du chargement des réservations</p>'
            })
    }

    closeReservations() {
        this.modalTarget.classList.add("hidden")
        this.modalTarget.classList.remove("flex")
    }

    closeOnBackdrop(event) {
        if (event.target === this.modalTarget) {
            this.closeReservations()
        }
    }
}

