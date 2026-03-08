import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['uploadBlock', 'urlBlock', 'preview']

    showUpload() {
        this.uploadBlockTarget.classList.remove('hidden')
        this.urlBlockTarget.classList.add('hidden')
        this.previewTarget.classList.add('hidden')
    }

    showUrl() {
        this.uploadBlockTarget.classList.add('hidden')
        this.urlBlockTarget.classList.remove('hidden')
        this.previewTarget.classList.add('hidden')
    }

    previewFromUrl(event) {
        const url = event.target.value
        if (!url) {
            this.previewTarget.classList.add('hidden')
            return
        }
        this.previewTarget.src = url
        this.previewTarget.classList.remove('hidden')
    }
    previewFromFile(event) {
        const file = event.target.files[0]
        if (!file) return

        const reader = new FileReader()
        reader.onload = e => {
            this.previewTarget.src = e.target.result
            this.previewTarget.classList.remove('hidden')
        }
        reader.readAsDataURL(file)
    }
}