import { Controller } from "@hotwired/stimulus"
import { Toast, dispatchEvent } from '../utils/toast.js'

export default class extends Controller {

    static targets = ["modal", "form", "nameInput", "emailInput", "messageInput", "cancelModal", "cancelForm", "cancelEmailInput"]

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
            Toast.warning('Veuillez entrer votre nom')
            return
        }
        
        if (!email) {
            Toast.warning('Veuillez entrer votre email')
            return
        }
        
        if (!this.validateEmail(email)) {
            Toast.warning('Veuillez entrer un email valide')
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
            Toast.success('Réservation effectuée avec succès !')
            console.log('Réservation créée :', data)
            
            // Dispatcher un événement pour recharger les articles
            dispatchEvent('articles:refresh')
        })
        .catch(error => {
            console.error('Erreur lors de la réservation :', error)
            Toast.error('Une erreur est survenue lors de la réservation')
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

    openCancelModal(event) {
        event.preventDefault()
        
        const articleId = event.target.dataset.articleId
        const articleName = event.target.dataset.articleName
        
        // Stocker l'ID de l'article
        this.articleId = articleId
        
        // Réinitialiser le formulaire
        this.cancelFormTarget.reset()
        this.cancelEmailInputTarget.focus()
        
        // Afficher la modal
        this.cancelModalTarget.classList.remove('hidden')
        this.cancelModalTarget.classList.add('flex')
    }

    closeCancelModal() {
        this.cancelModalTarget.classList.add('hidden')
        this.cancelModalTarget.classList.remove('flex')
    }

    submitCancelReservation(event) {
        event.preventDefault()
        
        const email = this.cancelEmailInputTarget.value.trim()
        
        if (!email) {
            Toast.warning('Veuillez entrer votre email')
            return
        }
        
        if (!this.validateEmail(email)) {
            Toast.warning('Veuillez entrer un email valide')
            return
        }
        
        // Données à envoyer
        const cancelData = {
            articleId: this.articleId,
            email: email
        }

        // Envoyer la demande d'annulation au serveur
        fetch('/api/reservation/cancel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(cancelData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }
            return response.json()
        })
        .then(data => {
            // Fermer la modal après succès
            this.closeCancelModal()
            Toast.success('Réservation annulée avec succès !')
            console.log('Réservation annulée :', data)
            
            // Dispatcher un événement pour recharger les articles
            dispatchEvent('articles:refresh')
        })
        .catch(error => {
            console.error('Erreur lors de l\'annulation de la réservation :', error)
            Toast.error('Une erreur est survenue lors de l\'annulation de la réservation')
        })
    }

    handleCancelBackdropClick(event) {
        // Fermer la modal si on clique sur le fond
        if (event.target === this.cancelModalTarget) {
            this.closeCancelModal()
        }
    }
}
