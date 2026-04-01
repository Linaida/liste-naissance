import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["frame"]

    connect() {
        // Créer une référence de la fonction pour pouvoir la retirer plus tard
        this.refreshArticlesHandler = () => this.refreshArticles()
        // Écouter l'événement de rafraîchissement des articles
        document.addEventListener('articles:refresh', this.refreshArticlesHandler)
    }

    disconnect() {
        // Nettoyer l'écouteur d'événement avec la même référence
        document.removeEventListener('articles:refresh', this.refreshArticlesHandler)
    }

    refreshArticles() {
        // Recharger le contenu de la frame via fetch
        if (this.hasFrameTarget) {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    // Parser le HTML reçu
                    const parser = new DOMParser()
                    const newDoc = parser.parseFromString(html, 'text/html')
                    const newFrame = newDoc.querySelector('#articles-list')
                    
                    // Remplacer le contenu de la frame
                    if (newFrame) {
                        this.frameTarget.innerHTML = newFrame.innerHTML
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du rafraîchissement des articles:', error)
                    // Fallback en cas d'erreur
                    window.location.reload()
                })
        } else {
            // Fallback: recharger la page si pas de target
            window.location.reload()
        }
    }
}
