import { Controller } from "@hotwired/stimulus"

export default class extends Controller {

    static targets = ["modal", "form", "nameInput", "emailInput", "messageInput"]

    openModal(event) {
        event.preventDefault()
        
        const articleId = event.target.dataset.articleId
        const articleName = event.target.dataset.articleName
        
        // Stocker l'ID de l'article
        this.articleId = articleId
        
        // Mettre à jour le titre de la modal
        const modalTitle = this.modalTarget.querySelector('[data-reservation-title]')
        if (modalTitle) {
            modalTitle.textContent = `Réserver : ${articleName}`
        }
        
        // Réinitialiser le formulaire
        this.formTarget.reset()
        this.nameInputTarget.focus()
        
        // Afficher la modal
        this.modalTarget.classList.remove('hidden')
        this.modalTarget.classList.add('flex')
    }

    closeModal() {
        this.modalTarget.classList.add('hidden')
        this.modalTarget.classList.remove('flex')
    }

    submitReservation(event) {
        event.preventDefault()
        
        const name = this.nameInputTarget.value.trim()
        const email = this.emailInputTarget.value.trim()
        const message = this.messageInputTarget.value.trim()
        
        if (!name) {
            alert('Veuillez entrer votre nom')
            return
        }
        
        if (!email) {
            alert('Veuillez entrer votre email')
            return
        }
        
        if (!this.validateEmail(email)) {
            alert('Veuillez entrer un email valide')
            return
        }
        
        // Données à envoyer
        const reservationData = {
            articleId: this.articleId,
            name: name,
            email: email,
            message: message || null
        }

        // Envoyer la réservation au serveur
        fetch('/api/reservation/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(reservationData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            return response.json()
        })
        .then(data => {
            // Fermer la modal après succès
            this.closeModal()
            alert('Réservation effectuée avec succès !')
            console.log('Réservation créée :', data)
            
            // Recharger la page pour mettre à jour la liste des articles
            window.location.reload()
        })
        .catch(error => {
            console.error('Erreur lors de la réservation :', error)
            alert('Une erreur est survenue lors de la réservation')
        })
    }

    handleBackdropClick(event) {
        // Fermer la modal si on clique sur le fond
        if (event.target === this.modalTarget) {
            this.closeModal()
        }
    }

    validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        return emailRegex.test(email)
    }
}
