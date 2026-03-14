import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ['item', 'btn', 'statusBtn']

    activeCategory = 'all'
    activeStatus = 'available'

    connect() {
        this.applyFilters()
    }
    // ===== Catégorie =====
    showAll() {
        this.itemTargets.forEach(el => el.classList.remove('hidden'))
        // On applique le style actif au bouton "Tous"
        this.btnTargets.forEach(btn => {
            if (btn.dataset.category === 'all') {
                this.setActive(btn)
            }
        })
    }

    filter(event) {
        const cat = event.currentTarget.dataset.category
        this.setActive(event.currentTarget)
        this.itemTargets.forEach(el => {
            if (el.dataset.category === cat) {
                el.classList.remove('hidden')

            } else {
                el.classList.add('hidden')

            }
        })
    }

    setActive(clickedBtn) {
        this.btnTargets.forEach(btn => {
            btn.classList.remove(
                'bg-indigo-500',
                'text-white',
                'shadow-md'
            )
        })

        clickedBtn.classList.add(
            'bg-indigo-500',
            'text-white',
            'shadow-md'
        )
    }

    // ===== Statut =====
    filterStatus(event) {
        this.activeStatus = event.currentTarget.dataset.status

        // UI active
        this.statusBtnTargets.forEach(b =>
            b.classList.remove('filter-chip-active')
        )
        event.currentTarget.classList.add('filter-chip-active')

        this.applyFilters()
    }

    // ===== Apply =====
    applyFilters() {
        this.itemTargets.forEach(item => {
            const cat = item.dataset.category
            const status = item.dataset.status

            const matchCategory = this.activeCategory === 'all' || cat === this.activeCategory
            const matchStatus = status === this.activeStatus

            item.classList.toggle('hidden', !(matchCategory && matchStatus))
        })
    }

    // ===== Prix =====
    sortAsc(event) {
        this.sortByPrice(true)
        this.setActive(event.currentTarget)
    }

    sortDesc(event) {
        this.sortByPrice(false)
        this.setActive(event.currentTarget)
    }

    sortByPrice(ascending = true) {
        const container = this.cardTargets[0].parentElement

        const cards = [...this.cardTargets]

        cards.sort((a, b) => {
            const pa = parseFloat(a.dataset.price)
            const pb = parseFloat(b.dataset.price)
            return ascending ? pa - pb : pb - pa
        })

        cards.forEach(card => container.appendChild(card))
    }

}